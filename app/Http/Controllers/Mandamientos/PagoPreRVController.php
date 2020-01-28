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
use File;
use Response;
use Carbon\Carbon;
use Log;
use Datatables;
use Session;
use App\fpdf\PDF_Code128;
use App\Models\Cssp\Mandamientos\Mandamiento;
use App\Models\Registro\Sol\SolicitudMandamientos;
use Config;
use App\Models\Registro\Sol\Solicitud;
use App\Models\Registro\Sol\DocumentoGenerales;
class PagoPreRVController extends Controller
{
     private $url=null;

    public function __construct() {
        $this->url = Config::get('app.api');
        $this->urlArchivos='Y:\URV\PRE\\';
    }
    public function store(Request $rq){

        if($rq->idSolicitud==''){
          Session::flash('msnError','¡Problemas al generar el mandamiento!');
          return back();
        }
         $id = Crypt::decrypt($rq->idSolicitud);
         $solicitud = Solicitud::find($id);
          if($solicitud->flagMandamiento==0){
            Session::flash('msnError','¡No tiene permisos para generar mandamiento!');
            return back();
          }
          $existeDoc=DocumentoGenerales::where('idSolicitud',$solicitud->idSolicitud)->where('estado','A')->where('idTipoDoc',10)->first();
          if($existeDoc){
                    $genmandamientos=SolicitudMandamientos::where('idSolicitud',$solicitud->idSolicitud)->where('estado','A')->first();
                    if(date('Y-m-d') > $genmandamientos->fechaVencimiento){
                              return $this->generarMandamiento($solicitud,$rq);
                     }
                     if (File::isFile($existeDoc->urlArchivo)){
                             try{
                                //dd($existeDoc->urlArchivo);
                                    $file = File::get($existeDoc->urlArchivo);
                                    $response = Response::make($file, 200);
                                    $response->header('Content-Type', 'application/pdf');
                                    return $response;
                             } catch (Exception $e) {
                                return Response::download($existeDoc->urlArchivo);
                             }
                    }else{
                                Session::flash('msnError','¡PROBLEMAS AL CONSULTAR PDF DE MANDAMIENTO!');
                                return redirect()->action('Registro\PreRegistro\NuevoRegistroController@index');
                    }
          }else{
                return $this->generarMandamiento($solicitud,$rq);

          }


    }

    public function generarMandamiento($solicitud,$rq){
                     //CONSULTAMOS INFORMACIÓN DE LA SOLICITUD
                $client = new Client();
                $res = $client->request('POST', $this->url . 'urvpre/solicitudpre/get-data', [
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                    ],
                    'form_params' =>[
                        'idSolicitud' => trim($solicitud->idSolicitud),
                    ]
                ]);
                $r = json_decode($res->getBody());
                if ($r->status == 200) {
                      $soldata=$r->data;
                }else{
                  Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$r->message,'msg'=>$r->message]);
                  Session()->flash('msnError', '¡Problemas al consultar información de la solicitud!');
                  return back();
                }

              //CONSULTAMOS EL TIPO DE PAGO DEL MANDAMIENTO
                $mandamiento = Crypt::decrypt($rq->idMandamiento);
                 $res2 = $client->request('POST', $this->url . 'urvpre/solicitudpre/consultar-tipopago/mandamiento', [
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                    ],
                    'form_params' =>[
                        'numMandamiento' => trim($mandamiento),
                    ]
                ]);

                $r2 = json_decode($res2->getBody());
                if ($r2->status == 200){
                      if($r2->tipopago==3571){
                            //EXTRANJERO
                            $cod1='85803099003';
                            $cod2='85803099005';
                      }else{
                          //NACIONAL
                           $cod1='85803099002';
                           $cod2='85803099004';

                      }
                }else{
                  Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$r->message,'msg'=>$r->message]);
                  Session()->flash('msnError', '-¡Problemas al consultar información de la solicitud!');
                  return back();
                }


              //CONSULTAMOS INFORMACIÓN DEL TIPO DE MANDAMIENTO
              $res = $client->request('POST', $this->url.'mandamiento/consulta/pago',[
                  'headers' => [
                      'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                  ],
                      'form_params' =>[
                          'cod1'   =>$cod1,
                          'cod2'   =>$cod2
                ]

              ]);

            $r = json_decode($res->getBody());
            $info=$r->data[0];
            $p1 =$info->p1;
            $p2= $info->p2;
            $suma = $p1->VALOR + $p2->VALOR;
            $total = number_format((float)$suma, 2, '.', '');
            $last = Mandamiento::orderBy('ID_MANDAMIENTO', 'desc')->select('ID_MANDAMIENTO')->first();
            $last=$last->ID_MANDAMIENTO+1;
            $fechaVencimiento = Carbon::now()->addDays(60)->format('Y/m/d');
            $idProfesional =Session::get('prof');
            try {
                DB::beginTransaction();
                $mandamiento = new Mandamiento;
                $mandamiento->ID_MANDAMIENTO = $last;
                $mandamiento->FECHA = date('Y/m/d');
                $mandamiento->HORA = date('H:i:s');
                $mandamiento->ID_CLIENTE = $idProfesional;
                $mandamiento->A_NOMBRE = Session::get('name').' '.Session::get('lastname');
                $mandamiento->FECHA_VENCIMIENTO = $fechaVencimiento;
                $mandamiento->ID_JUNTA = 'U26';
                $mandamiento->TOTAL = $total;
                $mandamiento->NOMBRE_CLIENTE = Session::get('name').' '.Session::get('lastname');
                $mandamiento->POR_CUENTA =  $soldata->titular->nombre;
                $mandamiento->ID_USUARIO_CREACION = Session::get('user').'@'.$rq->ip();
                $mandamiento->save();
                //detalle
                $mandamiento->detalle()->create([
                  'ID_CLIENTE'=>$idProfesional
                  ,'ID_TIPO_PAGO'=>$p1->ID_TIPO_PAGO
                  ,'VALOR'=>number_format((float)$p1->VALOR, 2, '.', '')
                  ,'NOMBRE_CLIENTE'=>Session::get('name').' '.Session::get('lastname')
                  ,'COMENTARIOS_ANEXOS'=>''
                ]);
                 $mandamiento->detalle()->create([
                  'ID_CLIENTE'=>$idProfesional
                  ,'ID_TIPO_PAGO'=>$p2->ID_TIPO_PAGO
                  ,'VALOR'=>number_format((float)$p2->VALOR, 2, '.', '')
                  ,'NOMBRE_CLIENTE'=>Session::get('name').' '.Session::get('lastname')
                  ,'COMENTARIOS_ANEXOS'=>''
                ]);

                //CONSULTAMOS SI YA A GENERADO UN MANDAMIENTO Y CAMBIAMOS DE ESTADO SI EXITEN REGISTRO
                $genmandamientos=SolicitudMandamientos::where('idSolicitud',$solicitud->idSolicitud)->get();
                if(count($genmandamientos)>0){
                   foreach($genmandamientos as $gen){
                     $iditem = SolicitudMandamientos::find($gen->idItem);
                     $iditem->estado='I';
                     $iditem->save();
                   }
                }
                SolicitudMandamientos::create([
                  'idSolicitud'=> $solicitud->idSolicitud,
                  'fechaVencimiento'=>date("Y-m-d",strtotime($fechaVencimiento)),
                  'idMandamiento'=>$mandamiento->ID_MANDAMIENTO,
                  'usuarioCreacion'=>Session::get('user').'@'.$rq->ip(),
                ]);
                $mandamiento=Mandamiento::find($last);


                error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                define('FPDF_FONTPATH',app_path().'/fpdf/font/');
                  $pdf=new PDF_Code128('P','mm','Letter');
                  $pdf->AddPage();
                  $pdf->SetXY(5,9);
                  $pdf->SetFont('Arial','',12);
                  $pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
                  $pdf->SetFont('Arial','',10);
                  $pdf->Cell(0,5,'NIT 0614-020312-105-7',0,1,'C');
                  $pdf->SetFont('Times','',8);
                  $pdf->Cell(0,4,'MANDAMIENTO DE INGRESOS',0,1,'C');
                  $pdf->SetFont('Times','',7);
                  $pdf->Cell(0,4,'UNIDAD DE REGISTRO PRE',0,1,'C');
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
                  $pdf->MultiCell(165,3,$p2->NOMBRE_TIPO_PAGO ." (".$solicitud->solicitudesDetalle->nombreComercial.")",0,'L',false);
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
                  $pdf->SetXY(180,65);
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
                  $pdf->Cell(0,4,'UNIDAD DE REGISTRO PRE  - PAGOS VARIOS WEB',0,1,'C');
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
                  $pdf->MultiCell(165,3,$p2->NOMBRE_TIPO_PAGO." (".$solicitud->solicitudesDetalle->nombreComercial.")",0,'L',false);
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
                  $pdf->SetXY(180,145);
                  $pdf->Write(5,'Usuario: '.$mandamiento->ID_CLIENTE.'');
                  $pdf->SetXY(10,150);
                  $pdf->Write(4,utf8_decode('Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podrá ser pagado en la red de las Agencias del Banco Agrícola, S.A.'),0,0,'J');
                  $output = $pdf->Output("","S");
                  $nombreArchivo = '\MANDAMIENTO_'.$mandamiento->ID_MANDAMIENTO.".pdf";
                  $rutaGuardado=$this->urlArchivos.$solicitud->idSolicitud;
                  if(file_exists($rutaGuardado)){
                                $docGen = new DocumentoGenerales();
                                $docGen->idSolicitud=$solicitud->idSolicitud;
                                $docGen->idTipoDoc=10;
                                $docGen->urlArchivo = $rutaGuardado.$nombreArchivo;
                                $docGen->tipoArchivo = 'application/pdf';
                                $docGen->save();
                                file_put_contents($rutaGuardado.$nombreArchivo, $output);
                                $pdf->Output('MANDAMIENTO.pdf','I');
                  }else{
                      Session::flash('msnError','¡PROBLEMAS AL GENERAR EL PDF, CONTACTAR CON INFORMÁTICA!');
                      return redirect()->action('Registro\PreRegistro\NuevoRegistroController@index');
                 }
                 DB::commit();
            } catch (\Exception $e) {
                    DB::rollback();
                    Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                    Session::flash('msnError','¡Problemas al generar mandamiento de pago!');
                    return back();
            }
    }

}
