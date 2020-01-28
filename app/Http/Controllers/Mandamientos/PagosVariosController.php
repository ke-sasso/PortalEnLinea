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
class PagosVariosController extends Controller
{
     private $url=null;

    public function __construct() {
        $this->url = Config::get('app.api');
    }

    public function index()
    {

      $data = ['title' 			=> 'Mandamientos'
      ,'subtitle'			=> 'Pagos varios'
      ,'breadcrumb' 		=> [
        ['nom'	=>	'Mandamientos', 'url' => '#'],
        ['nom'	=>	'Pagos varios', 'url' => '#']
      ]];

      //dd(Carbon::now()->addDays(60)->format('Y/m/d'));
      $data['nombre']=Session::get('name').' '.Session::get('lastname');
       return view('recetas.mandamiento.pagosvarios.index',$data);

    }
    public function lista(Request $request){
        try{
                $client = new Client();
                $res = $client->request('POST', $this->url.'mandamiento/pagosvarios/lista',[
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                    ]

                ]);
        }catch(\GuzzleHttp\Exception\RequestException $e){
                   $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                  Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                  $this->emailErrorOdin('¡Problemas al consultar información mandamiento pagos varios! - FECHA '.Carbon::now());
                  return json_encode($results);
        }
        $r = json_decode($res->getBody());

        if($r->status==200){
                $collection = collect($r->data[0]);
                return Datatables::of($collection)
                      ->addColumn('in', function ($dt) {
                    return '<input type="radio" class="form-control" name="idPago" id="idPago" value="'. Crypt::encrypt($dt->id_tipo_pago).'"  onclick="suma(\''.$dt->valor.'\');" >';
                })
                ->addColumn('nombre_tipo_pago', function ($dt) {
                    return $dt->codigo." - ".$dt->nombre_tipo_pago;
                })
                ->addColumn('valor', function ($dt) {
                    return '<h4><span class="label label-success"> $'.$dt->valor.'</span></h4>';
                })->make(true);
        }else{
                    $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                    return json_encode($results);
          }



    }

    public function store(Request $rq){
        $v = Validator::make($rq->all(),[
            'idPago'        =>'required',
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
        $idP=Crypt::decrypt($rq->idPago);
        try{
                $client = new Client();
                $res = $client->request('POST', $this->url.'mandamiento/pagosvarios/consulta',[
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                    ],
                    'form_params' =>[
                            'idPago'   =>$idP
                    ]
                ]);
        }catch(\GuzzleHttp\Exception\RequestException $e){
                    Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                    Session::flash('msnError','<b>¡ESTIMADO USUARIO OCURRIO UN PROBLEMA AL CARGAR LA INFORMACIÓN, POR FAVOR ESPERE UN MOMENTO E INTENTA NUEVAMENTE!</b>');
                    $this->emailErrorOdin('¡Problemas al consultar información mandamiento pagos varios! - FECHA '.Carbon::now());
                    return redirect()->route('ver.pagos.varios');
        }
        $r = json_decode($res->getBody());
        if($r->status==200){
           $info=$r->data[0];
        }else{
           Session::flash('msnError','¡Problemas al consultar información del mandamiento de pago!');
           return redirect()->route('ver.pagos.varios');
        }

       $total = $info->VALOR;
       $total=number_format((float)$total, 2, '.', '');
       if($total>=9999){
              return redirect()->route('ver.pagos.varios')->withErrors("¡Estimado usuario, el sistema genera mandamientos de pagos por un monto inferior a $9,999.99!");
       }
       $last = Mandamiento::orderBy('ID_MANDAMIENTO', 'desc')->select('ID_MANDAMIENTO')->first();
       $last=$last->ID_MANDAMIENTO+1;
       $fechaVencimiento = Carbon::now()->addDays(60)->format('Y/m/d');
       $idProfesional = Session::get('user');
      try {
            DB::beginTransaction();

            $mandamiento = new Mandamiento;
            $mandamiento->ID_MANDAMIENTO = $last;
            $mandamiento->FECHA = date('Y/m/d');
            $mandamiento->HORA = date('H:i:s');
            $mandamiento->ID_CLIENTE = $idProfesional;
            $mandamiento->A_NOMBRE = Session::get('name').' '.Session::get('lastname');
            $mandamiento->FECHA_VENCIMIENTO = $fechaVencimiento;
            $mandamiento->ID_JUNTA = $info->ID_JUNTA;
            $mandamiento->TOTAL = $total;
            $mandamiento->NOMBRE_CLIENTE = Session::get('name').' '.Session::get('lastname');
            $mandamiento->POR_CUENTA = mb_strtoupper($rq->cuenta, 'UTF-8');
            $mandamiento->ID_USUARIO_CREACION = Session::get('user').'@'.$rq->ip();
            $mandamiento->save();
            //detalle
            $mandamiento->detalle()->create([
              'ID_CLIENTE'=>$idProfesional
              ,'ID_TIPO_PAGO'=>$info->ID_TIPO_PAGO
              ,'VALOR'=>$total
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
    				$pdf->Cell(0,4,'UNIDAD DE '.$info->UNIDAD.' - PAGOS VARIOS WEB',0,1,'C');
    				$pdf->SetFont('Times','',7);
    				$x = $pdf->GetX();
    				$y = $pdf->GetY();
    				$pdf->Cell(150,4,'Cliente:  '.$mandamiento->ID_CLIENTE.' - '.utf8_decode($mandamiento->NOMBRE_CLIENTE).'',0,1,'J');
    				$pdf->SetXY($x + 135, $y);
    				$pdf->SetFont('Times','B',10);
    				$pdf->Cell(10,4,'Por: $'.$total.'                       No.: '.$mandamiento->ID_MANDAMIENTO.'',0,1,'J');
    				$pdf->SetFont('Times','',7);
    				$pdf->Cell(0,4,'Por Cuenta de: '.utf8_decode($mandamiento->POR_CUENTA).'                                                                                                                                                                                                 ',0,1,'J');
    				$pdf->Cell(0,4,'_____________________________________________________________________________________________________________________________________________________________ ',0,1,'J');

    				$pdf->Cell(15,3,$info->CODIGO,0,'J',0);
    				$x = $pdf->GetX();
    				$y = $pdf->GetY();
    				$pdf->MultiCell(165,3,$info->NOMBRE_TIPO_PAGO,0,'L',false);
    				$pdf->SetXY($x + 165, $y);
    				$pdf->MultiCell(20,3,'$ '.$total.'',0,'L',false);
    				$pdf->Ln();
    				$pdf->SetX($x);
    				$pdf->MultiCell(165,3,utf8_decode($rq->comentario),0,'L',false);
    				$pdf->SetFont('Times','',7);
    				$pdf->SetXY(10,51);
    				$pdf->Write(5,'_____________________________________________________________________________________________________________________________________________________________',0,1,'J');
    				$pdf->SetFont('Times','B',7);
    				$pdf->SetXY(190,56);
    				$pdf->Write(5,'$ '.$total.'',0,1,'R',0);

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
    				$pdf->Cell(0,4,'UNIDAD DE '.$info->UNIDAD.' - PAGOS VARIOS WEB',0,1,'C');
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
    				$pdf->Cell(15,3,$info->CODIGO,0,'J',0);
    				$x = $pdf->GetX();
    				$y = $pdf->GetY();
    				$pdf->MultiCell(165,3,utf8_decode($info->NOMBRE_TIPO_PAGO),0,'L',false);
    				$pdf->SetXY($x + 165, $y);
    				$pdf->MultiCell(20,3,'$ '.$total.'',0,'L',false);
    				$pdf->Ln();
    				$pdf->SetX($x);
    				$pdf->MultiCell(165,3,utf8_decode($rq->comentario),0,'L',false);
    				$pdf->SetFont('Times','',7);
    				$pdf->SetXY(10,130);
    				$pdf->Write(5,'_____________________________________________________________________________________________________________________________________________________________',0,1,'J');
    				$pdf->SetFont('Times','B',7);
    				$pdf->SetXY(190,135);
    				$pdf->Write(5,'$ '.$total.'',0,1,'R',0);

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

      }catch (\Exception $e){
          DB::rollback();
          Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
          Session::flash('msnError','¡Problemas al generar mandamiento de pago!');
          return redirect()->route('ver.pagos.varios');
      }

    }
}
