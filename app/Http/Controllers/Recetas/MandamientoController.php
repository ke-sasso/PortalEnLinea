<?php

namespace App\Http\Controllers\Recetas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mumpo\FpdfBarcode;
use Config;
use Auth;
use Crypt;
use DB;
use Log;
use Carbon\Carbon;
use Session;
use App\fpdf\PDF_Code128;
use Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use App\Models\Cssp\Mandamientos\Mandamiento;

class MandamientoController extends Controller
{
    private $url=null;
    public function __construct() {
        $this->url = Config::get('app.api');
    }
    public function index()
    {
      $data = ['title' 			=> 'Recetas'
      ,'subtitle'			=> 'Mandamiento Talonario'
      ,'breadcrumb' 		=> [
        ['nom'	=>	'Recetas', 'url' => '#'],
        ['nom'	=>	'Mandamiento-Talonario', 'url' => '#']
      ]];

       return view('recetas.mandamiento.talonario.index',$data);

    }

    public function store(Request $rq){
       $v = Validator::make($rq->all(),[
            'txtCantidad'  =>'numeric|not_in:0|between:1,8|required',
            'txtPorCuenta' =>'sometimes',
            'txtComentario'=>'sometimes'
          ]);
        $v->setAttributeNames([
            'txtCantidad'  =>'cantidad de talonario',
            'txtPorCuenta' =>'por cuenta de',
            'txtComentario'=>'comentario'
        ]);
        if ($v->fails()){
          $msg = "<ul>";
          foreach ($v->messages()->all() as $err) {
            $msg .= "<li>$err</li>";
          }
          $msg .= "</ul>";
            return back()->withErrors($msg);
        }
        //--------------CONSULTAMOS LA INFORMACIÓN DEL PAGO------------------
        $client = new Client();
        $res = $client->request('POST', $this->url.'mandamiento/pagosvarios/consulta',[
              'headers' => [
              'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
        ],
            'form_params' =>[
            'idPago'   =>3730
        ]]);
        $r = json_decode($res->getBody());
        if($r->status==200){
            $info=$r->data[0];
            $precio =$info->VALOR;
        }else{
            Session::flash('msnError','¡Problemas al consultar información del mandamiento de pago!');
            return redirect()->route('doInicio');
        }
        $cantidad = $rq->txtCantidad;
        $cantRecetas = 25 * $cantidad;
        $operacion = $precio * $cantidad;
        $total = number_format((float)$operacion, 2, '.', '');
        $last = DB::select(DB::raw('SELECT MAX(ID_MANDAMIENTO)+1  AS LAST_ID FROM cssp.cssp_mandamientos'));
      //$npe  = DB::select(DB::raw('SELECT cssp.COD_NPE("'.$total.'.00'.'") AS COD_NPE'));
      //$codBarra = DB::select(DB::raw('SELECT cssp.COD_BARRA("'.$total.'.00'.'") AS COD_BARRA'));
      //$codBarraTexto = DB::select(DB::raw('SELECT cssp.COD_BARRA_TEXTO("'.$total.'.00'.'") AS COD_BARRA_TEXTO'));
        $fechaVencimiento = DB::select(DB::raw("SELECT REPLACE(CURDATE() + INTERVAL 60 DAY,'-','/') AS VENCIMIENTO"));
        $idProfesional = Session::get('user');
      try {
          DB::beginTransaction();

          $mandamiento = new Mandamiento;
          $mandamiento->ID_MANDAMIENTO = $last[0]->LAST_ID;
          //$mandamiento->CODIGO_BARRA = $codBarra[0]->COD_BARRA;
          //$mandamiento->CODIGO_BARRA_TEXTO = $codBarraTexto[0]->COD_BARRA_TEXTO;
          //$mandamiento->NPE = $npe[0]->COD_NPE;
          $mandamiento->FECHA = date('Y/m/d');
          $mandamiento->HORA = date('H:i:s');
          $mandamiento->ID_CLIENTE = $idProfesional;
          $mandamiento->A_NOMBRE = Session::get('name').' '.Session::get('lastname');
          $mandamiento->FECHA_VENCIMIENTO = $fechaVencimiento[0]->VENCIMIENTO;
          $mandamiento->ID_JUNTA = 'U21';
          $mandamiento->TOTAL = $total;
          $mandamiento->NOMBRE_CLIENTE = Session::get('name').' '.Session::get('lastname');
          $mandamiento->POR_CUENTA = $rq->txtPorCuenta;
          $mandamiento->ID_USUARIO_CREACION = Session::get('user').'@'.$rq->ip();
          $mandamiento->save();
          //detalle
          $mandamiento->detalle()->create([
            'ID_CLIENTE'=>$idProfesional
            ,'ID_TIPO_PAGO'=>3730
            ,'VALOR'=>$total
            ,'COMENTARIOS'=>$rq->txtComentario
            ,'NOMBRE_CLIENTE'=>Session::get('name').' '.Session::get('lastname')
            ,'COMENTARIOS_ANEXOS'=>'Mandamiento por '.$cantidad.' talonario, equivalente a '.$cantRecetas.' por un valor de $'.$total
          ]);
          $mandamiento=Mandamiento::find($last[0]->LAST_ID);

            error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
            define('FPDF_FONTPATH',app_path().'/fpdf/font/');
            $pdf=new PDF_Code128('P','mm','legal');
            $pdf->AddPage();
            $pdf->SetXY(5,2);
    				$pdf->SetFont('Arial','',12);
    				$pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
    				$pdf->SetFont('Arial','',10);
    				$pdf->Cell(0,5,'NIT 0614-020312-105-7',0,1,'C');
    				$pdf->SetFont('Times','',8);
    				$pdf->Cell(0,4,'MANDAMIENTO DE INGRESOS',0,1,'C');
    				$pdf->SetFont('Times','',7);
    				$pdf->Cell(0,4,'UNIDAD DE ESTUPEFACIENTES - PAGOS VARIOS WEB',0,1,'C');
    				$pdf->SetFont('Times','',7);
    				$x = $pdf->GetX();
    				$y = $pdf->GetY();
    				$pdf->Cell(150,4,'Cliente:  '.$mandamiento->ID_CLIENTE.' - '.utf8_decode($mandamiento->NOMBRE_CLIENTE).'',0,1,'J');
    				$pdf->SetXY($x + 135, $y);
    				$pdf->SetFont('Times','B',10);
    				$pdf->Cell(10,4,'Por: $'.$total.'                        No.: '.$mandamiento->ID_MANDAMIENTO.'',0,1,'J');
    				$pdf->SetFont('Times','',7);
    				$pdf->Cell(0,4,'Por Cuenta de: '.$rq->txtPorCuenta.'                                                                                                                                                                                                 ',0,1,'J');
    				$pdf->Cell(0,4,'_____________________________________________________________________________________________________________________________________________________________ ',0,1,'J');

    				$pdf->Cell(15,5,$info->CODIGO,0,'J',0);
    				$x = $pdf->GetX();
    				$y = $pdf->GetY();
    				$pdf->MultiCell(165,3,$info->NOMBRE_TIPO_PAGO.' ( '.$cantidad.' TALONARIO/S DE 25 RECETAS EQUIVALENTA A '.$cantRecetas.' RECETAS)',0,'L',false);
    				$pdf->SetXY($x + 165, $y);
    				$pdf->MultiCell(20,3,'$ '.$total,0,'L',false);
    				$pdf->Ln();
    				$pdf->SetX($x);
    				$pdf->MultiCell(165,3,utf8_decode($rq->txtComentario),0,'L',false);
    				$pdf->SetFont('Times','',7);
    				$pdf->SetXY(10,45);
    				$pdf->Write(5,'_____________________________________________________________________________________________________________________________________________________________',0,1,'J');
    				$pdf->SetFont('Times','B',7);
    				$pdf->SetXY(190,50);
    				$pdf->Write(5,'$ '.$total,0,1,'R',0);

    				//A set
    				$pdf->SetXY(9,2);
    				$pdf->Image(url('img/escudo.jpg'));
    				$pdf->SetXY(190,2);
    				$pdf->Image(url('img/dnm.jpg'));
    				$pdf->SetXY(160,10);
    				$pdf->SetFont('Arial','',10);
    				$pdf->Write(5,'Decreto 417');
    				$pdf->SetFont('Times','',7);
    				$pdf->SetXY(90,50);
    				$pdf->Write(5,'NPE:'.$mandamiento->NPE.'');
    				$pdf->SetXY(10,55);
    				$pdf->Write(5,'Emitido:'.$mandamiento->FECHA.'');
    				$pdf->SetXY(10,60);
    				$pdf->SetFont('Times','B',10);
    				$pdf->Write(5,'Vencimiento:'.$mandamiento->FECHA_VENCIMIENTO.'');
    				$pdf->SetFont('Times','',7);
    				$pdf->Code128(70,55,$mandamiento->CODIGO_BARRA,80,6);
    				$pdf->SetXY(75,60);
    				$pdf->Write(5,$mandamiento->CODIGO_BARRA_TEXTO);
    				$pdf->SetXY(180,55);
    				$pdf->Write(5,'Copia: Banco');
    				$pdf->SetXY(170,60);
    				$pdf->Write(5,'Usuario: '.$mandamiento->ID_CLIENTE.'');
    				$pdf->SetXY(10,65);
    				$pdf->Write(4,utf8_decode('Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podrá ser pagado en la red de las Agencias del Banco Agrícola, S.A.'),0,0,'J');
    				//segunda copia
    				$pdf->SetXY(10,87);
    				$pdf->SetFont('Arial','',12);
    				$pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
    				$pdf->SetFont('Arial','',10);
    				$pdf->Cell(0,5,'NIT 0614-020312-105-7',0,1,'C');
    				$pdf->SetFont('Times','',8);
    				$pdf->Cell(0,4,'MANDAMIENTO DE INGRESOS',0,1,'C');
    				$pdf->SetFont('Times','',7);
    				$pdf->Cell(0,4,'UNIDAD DE ESTUPEFACIENTES - PAGOS VARIOS WEB',0,1,'C');
    				$pdf->SetFont('Times','',7);
    				$x = $pdf->GetX();
    				$y = $pdf->GetY();
    				$pdf->Cell(150,4,'Cliente:  '.$mandamiento->ID_CLIENTE.' - '.utf8_decode($mandamiento->NOMBRE_CLIENTE).'',0,1,'J');
    				$pdf->SetXY($x + 135, $y);
    				$pdf->SetFont('Times','',10);
    				$pdf->Cell(10,4,'Por: $'.$total.'                        No.: '.$mandamiento->ID_MANDAMIENTO.'',0,1,'J');
    				$pdf->SetFont('Times','',7);
    				$pdf->Cell(0,4,'Por Cuenta de: '.utf8_decode($mandamiento->POR_CUENTA).'',0,1,'J');
    				$pdf->Cell(0,4,'_____________________________________________________________________________________________________________________________________________________________ ',0,1,'J');
    				$pdf->Cell(15,5,$info->CODIGO,0,'J',0);
    				$x = $pdf->GetX();
    				$y = $pdf->GetY();
    				$pdf->MultiCell(165,3,$info->NOMBRE_TIPO_PAGO.' ( '.$cantidad.' TALONARIO/S DE 25 RECETAS EQUIVALENTA A '.$cantRecetas.' RECETAS)',0,'L',false);
    				$pdf->SetXY($x + 165, $y);
    				$pdf->MultiCell(20,3,'$ '.$total,0,'L',false);
    				$pdf->Ln();
    				$pdf->SetX($x);
    				$pdf->MultiCell(165,3,utf8_decode($rq->txtComentario),0,'L',false);
    				$pdf->SetFont('Times','',7);
    				$pdf->SetXY(10,130);
    				$pdf->Write(5,'_____________________________________________________________________________________________________________________________________________________________',0,1,'J');
    				$pdf->SetFont('Times','B',7);
    				$pdf->SetXY(190,135);
    				$pdf->Write(5,'$ '.$total,0,1,'R',0);

    				//A set
    				$pdf->SetXY(9,85);
    				$pdf->Image(url('img/escudo.jpg'));
    				$pdf->SetXY(190,85);
    				$pdf->Image(url('img/dnm.jpg'));
    				$pdf->SetXY(150,95);
    				$pdf->SetFont('Arial','',10);
    				$pdf->Write(5,'Decreto 417');
    				$pdf->SetFont('Times','',7);
    				$pdf->SetXY(90,135);
    				$pdf->Write(5,'NPE:'.$mandamiento->NPE.'');
    				$pdf->SetXY(10,140);
    				$pdf->Write(5,'Emitido:'.$mandamiento->FECHA.'');
    				$pdf->SetXY(10,145);
    				$pdf->SetFont('Times','B',10);
    				$pdf->Write(5,'Vencimiento:'.$mandamiento->FECHA_VENCIMIENTO.'');
    				$pdf->SetFont('Times','',7);
    				$pdf->Code128(70,140,$mandamiento->CODIGO_BARRA,80,6);
    				$pdf->SetXY(75,145);
    				$pdf->Write(5,$mandamiento->CODIGO_BARRA_TEXTO);
    				$pdf->SetXY(180,140);
    				$pdf->Write(5,'Copia: Cliente');
    				$pdf->SetXY(170,145);
    				$pdf->Write(5,'Usuario: '.$mandamiento->ID_CLIENTE.'');
    				$pdf->SetXY(10,150);
    				$pdf->Write(4,utf8_decode('Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podrá ser pagado en la red de las Agencias del Banco Agrícola, S.A.'),0,0,'J');
    				$pdf->Output('mandamiento_'.$mandamiento->ID_MANDAMIENTO.'.pdf','I');
           DB::commit();

      } catch (\Exception $e) {
          DB::rollback();
          Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
          Session::flash('msnError','¡Problemas al generar mandamiento de pago!');
          return back();
      }

    }
}
