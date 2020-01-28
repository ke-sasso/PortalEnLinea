<?php

namespace App\Http\Controllers\Mandamientos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mumpo\FpdfBarcode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Auth;
use Crypt;
use DB;
use Log;
use Carbon\Carbon;
use Datatables;
use Session;
use Validator;
use App\fpdf\PDF_Code128;
use App\Models\Cssp\Mandamientos\Mandamiento;
use Config;
class PagosHigienicosController extends Controller
{
     private $url=null;

    public function __construct() {
        $this->url = Config::get('app.api');
    }

    public function index(){

      $data = ['title' 			=> 'Mandamientos'
      ,'subtitle'			=> 'Pago Inscripciones Higiénicos'
      ,'breadcrumb' 		=> [
        ['nom'	=>	'Mandamientos', 'url' => '#'],
        ['nom'	=>	'Pago Inscripciones Higiénicos', 'url' => '#']
      ]];
      if(!Session::has('msnErrorServicio')){
           try{
                   $client = new Client();
                   $res = $client->request('POST', $this->url.'mandamiento/consulta/pago',[
                        'headers' => [
                            'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                        ],
                            'form_params' =>[
                                'cod1'   =>'85803099076',
                                'cod2'   =>'85803099077',
                                'cod3'   =>'85803099078'
                    ]]);
            }catch(\GuzzleHttp\Exception\RequestException $e){
                            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                            Session::flash('msnError','<b>¡ESTIMADO USUARIO OCURRIO UN PROBLEMA AL CARGAR LA INFORMACIÓN, POR FAVOR ESPERE UN MOMENTO E INTENTA NUEVAMENTE!</b>');
                            Session::flash('msnErrorServicio','ERROR ODIN');
                            $this->emailErrorOdin('¡Problemas al consultar información de mandamiento de pago Higiénicos! - FECHA '.Carbon::now());
                            return redirect()->route('ver.pagos.higienicos');
            }
            $r = json_decode($res->getBody());
            if($r->status==200){
                $info=$r->data[0];
                $p1 = $info->p1;
                $p2 = $info->p2;
                $p3 = $info->p3;
            }else{
                $p1=[];$p2=[];$p3=[];
            }
        }else{
            $p1=[];$p2=[];$p3=[];
        }
        $data['p1']=$p1;
        $data['p2']=$p2;
        $data['p3']=$p3;
        $data['nombre']=Session::get('name').' '.Session::get('lastname');
        return view('recetas.mandamiento.pagoshigienicos.index',$data);
    }

    public function store(Request $rq)
    {
      //idPago 1.Nacional 2.Extranjero
        $v = Validator::make($rq->all(),[
            'idPago'        =>'numeric|not_in:0|between:1,2|required',
            'cuenta'        =>'sometimes|max:100',
            'comentario'    =>'sometimes|max:100'
          ]);
        $v->setAttributeNames([
            'idPago'    =>'codigo de mandamiento',
            'cuenta'    =>'por cuenta de',
            'comentario'=>'comentario'
        ]);
        if ($v->fails()){
          $msg = "<ul>";
          foreach ($v->messages()->all() as $err) {
            $msg .= "<li>$err</li>";
          }
          $msg .= "</ul>";
            return back()->withErrors($msg);
        }
       $idP=$rq->idPago;
       $cod1='85803099076';
       if($idP==1){
          $cod2='85803099077';
        }else{
          $cod2='85803099078';
        }
        try{
                $client = new Client();
                $res = $client->request('POST', $this->url.'mandamiento/consulta/pago',[
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                    ],
                        'form_params' =>[
                            'cod1'   =>$cod1,
                            'cod2'   =>$cod2
                  ]]);
         }catch(\GuzzleHttp\Exception\RequestException $e){
                  Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                  $this->emailErrorOdin('¡Problemas al consultar información de mandamiento de pago Higiénicos! - FECHA '.Carbon::now());
                  return redirect()->route('ver.pagos.higienicos');
        }
        $r = json_decode($res->getBody());
        if($r->status==200){
          $info=$r->data[0];
          $p1 =$info->p1;
          $p2= $info->p2;
        }else{
           Session::flash('msnError','¡Problemas al consultar información del mandamiento de pago!');
           return redirect()->route('doInicio');
        }

        $suma = $p1->VALOR + $p2->VALOR;
        $total = number_format((float)$suma, 2, '.', '');
        if($total>=9999){
            return redirect()->route('ver.pagos.higienicos')->withErrors("¡Estimado usuario, el sistema genera mandamientos de pagos por un monto inferior a $9,999.99!");
        }
        $last = Mandamiento::orderBy('ID_MANDAMIENTO', 'desc')->select('ID_MANDAMIENTO')->first();
        $last=$last->ID_MANDAMIENTO+1;
        $fechaVencimiento = Carbon::now()->addDays(60)->format('Y/m/d');
        $idProfesional = Session::get('user');
      try{
          DB::beginTransaction();

          $mandamiento = new Mandamiento;
          $mandamiento->ID_MANDAMIENTO = $last;
          $mandamiento->FECHA = date('Y/m/d');
          $mandamiento->HORA = date('H:i:s');
          $mandamiento->ID_CLIENTE = $idProfesional;
          $mandamiento->A_NOMBRE = Session::get('name').' '.Session::get('lastname');
          $mandamiento->FECHA_VENCIMIENTO = $fechaVencimiento;
          $mandamiento->ID_JUNTA = 'U15';
          $mandamiento->TOTAL = $total;
          $mandamiento->NOMBRE_CLIENTE = Session::get('name').' '.Session::get('lastname');
          $mandamiento->POR_CUENTA =  mb_strtoupper($rq->cuenta, 'UTF-8');
          $mandamiento->ID_USUARIO_CREACION = Session::get('user').'@'.$rq->ip();
          $mandamiento->save();
          //detalle
          $mandamiento->detalle()->create([
            'ID_CLIENTE'=>$idProfesional
            ,'ID_TIPO_PAGO'=>$p1->ID_TIPO_PAGO
            ,'VALOR'=>number_format((float)$p1->VALOR, 2, '.', '')
            ,'COMENTARIOS'=>$rq->comentario
            ,'NOMBRE_CLIENTE'=>Session::get('name').' '.Session::get('lastname')
            ,'COMENTARIOS_ANEXOS'=>''
          ]);
            $mandamiento->detalle()->create([
            'ID_CLIENTE'=>$idProfesional
            ,'ID_TIPO_PAGO'=>$p2->ID_TIPO_PAGO
            ,'VALOR'=>number_format((float)$p2->VALOR, 2, '.', '')
            ,'COMENTARIOS'=>$rq->comentario
            ,'NOMBRE_CLIENTE'=>Session::get('name').' '.Session::get('lastname')
            ,'COMENTARIOS_ANEXOS'=>''
          ]);

            $mandamiento=Mandamiento::find($last);

                    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                    define('FPDF_FONTPATH',app_path().'/fpdf/font/');
                    $pdf=new PDF_Code128('P','mm','Letter');
                    $pdf->AddPage();
                    $pdf->SetXY(5,9);
                    $pdf->SetTitle('MANDAMIENTO - '.$mandamiento->ID_MANDAMIENTO);
    				$pdf->SetFont('Arial','',12);
    				$pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
    				$pdf->SetFont('Arial','',10);
    				$pdf->Cell(0,5,'NIT 0614-020312-105-7',0,1,'C');
    				$pdf->SetFont('Times','',8);
    				$pdf->Cell(0,4,'MANDAMIENTO DE INGRESOS',0,1,'C');
    				$pdf->SetFont('Times','',7);
    				$pdf->Cell(0,4,'UNIDAD DE COSMETICOS  - PAGOS VARIOS WEB',0,1,'C');
    				$pdf->SetFont('Times','',7);
    				$x = $pdf->GetX();
    				$y = $pdf->GetY();
    				$pdf->Cell(150,4,'Cliente:  '.$mandamiento->ID_CLIENTE.' - '.utf8_decode($mandamiento->NOMBRE_CLIENTE).'',0,1,'J');
    				$pdf->SetXY($x + 135, $y);
    				$pdf->SetFont('Times','B',10);
    				$pdf->Cell(10,4,'Por: $'.$total.'                        No.: '.$mandamiento->ID_MANDAMIENTO.'',0,1,'J');
    				$pdf->SetFont('Times','',7);
    				$pdf->Cell(0,4,'Por Cuenta de: '.utf8_decode($mandamiento->POR_CUENTA).'                                                                                                                                                                                                 ',0,1,'J');
    				$pdf->Cell(0,4,'_____________________________________________________________________________________________________________________________________________________________ ',0,1,'J');

    				$pdf->Cell(15,3,$p1->CODIGO,0,'J',0);
    				$x = $pdf->GetX();
    				$y = $pdf->GetY();
    				$pdf->MultiCell(165,3,$p1->NOMBRE_TIPO_PAGO,0,'L',false);
    				$pdf->SetXY($x + 165, $y);
    				$pdf->MultiCell(20,3,'$ '.number_format((float)$p1->VALOR, 2, '.', ''),0,'L',false);
                    $pdf->Cell(15,3,$p2->CODIGO,0,'J',0);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(165,3,$p2->NOMBRE_TIPO_PAGO,0,'L',false);
                    $pdf->SetXY($x + 165, $y);
                    $pdf->MultiCell(20,3,'$ '.number_format((float)$p2->VALOR, 2, '.', ''),0,'L',false);
    				$pdf->Ln();
    				$pdf->SetX($x);
    				$pdf->MultiCell(165,3,utf8_decode($rq->comentario),0,'L',false);
    				$pdf->SetFont('Times','',7);
    				$pdf->SetXY(10,51);

    				$pdf->Write(5,'_____________________________________________________________________________________________________________________________________________________________',0,1,'J');
    				$pdf->SetFont('Times','B',7);
    				$pdf->SetXY(190,56);
    				$pdf->Write(5,'$ '.$total,0,1,'R',0);

    				//A set
    				$pdf->Image(url('img/escudo-new.jpg'),11,9,14.5);
                    $pdf->SetXY(190,9);
                    $pdf->Image(url('img/dnm-new.jpg'));
    				$pdf->SetXY(150,16);
    				$pdf->SetFont('Arial','',10);
    				$pdf->Write(5,'Decreto 417');
    				$pdf->SetFont('Times','',7);
    				$pdf->SetXY(90,55);
    				$pdf->Write(5,'NPE:'.$mandamiento->NPE.'');
    				$pdf->SetXY(10,60);
    				$pdf->Write(5,'Emitido:'.$mandamiento->FECHA.'');
    				$pdf->SetXY(10,65);
    				$pdf->SetFont('Times','B',10);
    				$pdf->Write(5,'Vencimiento:'.$mandamiento->FECHA_VENCIMIENTO.'');
    				$pdf->SetFont('Times','',7);
    				$pdf->Code128(70,60,$mandamiento->CODIGO_BARRA,80,6);
    				$pdf->SetXY(75,65);
    				$pdf->Write(5,$mandamiento->CODIGO_BARRA_TEXTO);
    				$pdf->SetXY(180,60);
    				$pdf->Write(5,'Copia: Banco');
    				$pdf->SetXY(170,65);
    				$pdf->Write(5,'Usuario: '.$mandamiento->ID_CLIENTE.'');
    				$pdf->SetXY(10,70);
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
    				$pdf->Cell(0,4,'UNIDAD DE COSMETICOS  - PAGOS VARIOS WEB',0,1,'C');
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
    				$pdf->Cell(15,3,$p1->CODIGO,0,'J',0);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(165,3,$p1->NOMBRE_TIPO_PAGO,0,'L',false);
                    $pdf->SetXY($x + 165, $y);
                    $pdf->MultiCell(20,3,'$ '.number_format((float)$p1->VALOR, 2, '.', ''),0,'L',false);
                    $pdf->Cell(15,3,$p2->CODIGO,0,'J',0);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(165,3,$p2->NOMBRE_TIPO_PAGO,0,'L',false);
                    $pdf->SetXY($x + 165, $y);
                    $pdf->MultiCell(20,3,'$ '.number_format((float)$p2->VALOR, 2, '.', ''),0,'L',false);
    				$pdf->Ln();
    				$pdf->SetX($x);
    				$pdf->MultiCell(165,3,utf8_decode($rq->comentario),0,'L',false);
    				$pdf->SetFont('Times','',7);
    				$pdf->SetXY(10,130);
    				$pdf->Write(5,'_____________________________________________________________________________________________________________________________________________________________',0,1,'J');
    				$pdf->SetFont('Times','B',7);
    				$pdf->SetXY(190,135);
    				$pdf->Write(5,'$ '.$total,0,1,'R',0);

    				//A set
    				$pdf->Image(url('img/escudo-new.jpg'),11,87,14.5);
                    $pdf->SetXY(190,85);
                    $pdf->Image(url('img/dnm-new.jpg'));
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
    				$pdf->Output('MANDAMIENTO-'.$mandamiento->ID_MANDAMIENTO.'.pdf','D');

            DB::commit();
      } catch (\Exception $e) {
          DB::rollback();
          Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
          Session::flash('msnError','¡Problemas al generar mandamiento de pago!');
          return redirect()->route('ver.pagos.higienicos');
      }

    }
}
