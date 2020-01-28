<?php

namespace App\Http\Controllers\Registro\PreRegistro;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Registro\Sol\SolicitudSeguimiento;
use Auth;
use Carbon\Carbon;
use Config;
use Crypt;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Log;
use Session;
use App\Models\Registro\Sol\DocumentoGenerales;
use App\PersonaNatural;
use App\Models\Registro\Sol\Solicitud;
use File;
use Response;
class FormularioController extends Controller
{
    public function __construct(){
        $this->url = Config::get('app.api');
        $this->urlArchivos='Y:\URV\PRE\\';
        $this->token = Config::get('app.token');
    }

    public function comprobanteIngresoRegistroVisado($idSolicitud){
        $idSoli = Crypt::decrypt($idSolicitud);
        try {
            //1.COMPROBANTE DE SOLICITUD
            $existeDoc=DocumentoGenerales::where('idSolicitud',$idSoli)->where('estado','A')->where('idTipoDoc',1)->first();
            if($existeDoc){
                     if (File::isFile($existeDoc->urlArchivo)){
                             try{
                                    $file = File::get($existeDoc->urlArchivo);
                                    $response = Response::make($file, 200);
                                    $response->header('Content-Type', 'application/pdf');
                                    return $response;
                             } catch (Exception $e) {
                                return Response::download($existeDoc->urlArchivo);
                             }
                    }else{
                                Session::flash('msnError','¡PROBLEMAS AL CONSULTAR PDF!');
                                return redirect()->action('Registro\PreRegistro\NuevoRegistroController@index');
                    }
            }else{
                    $client = new Client();
                    $res = $client->request('POST', $this->url . 'urvpre/solicitudpre/get-data', [
                        'headers' => [
                            'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                            ],
                            'form_params' =>[
                                 'idSolicitud' => trim($idSoli),//Las solicitudes actuales estan incompletas
                             ],
                            'allow_redirects'=> true
                    ]);

                    $r = json_decode($res->getBody());
                    if($r->status == 200) {
                            $solicitud = Solicitud::findOrFail(trim($idSoli));
                            $data['info']=$solicitud;
                            //Retornando datos
                            //dd($r->data);
                            $data['detalle'] = $r->data;
                            $date = Carbon::now();
                            $date->format('g:i A');
                            $data['date'] = $date;

                            $view =  \View::make('pdf.Rv.comprobantePreRv',$data)->render();
                            $pdf = \App::make('dompdf.wrapper');
                            $pdf->loadHTML($view);
                            $output = $pdf->output();
                            $nombreArchivo = "\COMPROBANTE DE SOLICITUD.pdf";
                            $rutaGuardado=$this->urlArchivos.$solicitud->idSolicitud;

                            if(file_exists($rutaGuardado)){
                                $docGen = new DocumentoGenerales();
                                $docGen->idSolicitud=$solicitud->idSolicitud;
                                $docGen->idTipoDoc=1;
                                $docGen->urlArchivo = $rutaGuardado.$nombreArchivo;
                                $docGen->tipoArchivo = 'application/pdf';
                                $docGen->save();
                                file_put_contents($rutaGuardado.$nombreArchivo, $output);
                                return $pdf->stream('COMPROBANTE DE SOLICITUD-'.$solicitud->numeroSolicitud.'.pdf');
                            }else{
                                Session::flash('msnError','¡PROBLEMAS AL GENERAR EL PDF, CONTACTAR CON INFORMÁTICA!');
                                return redirect()->action('Registro\PreRegistro\NuevoRegistroController@index');
                            }

                    }else if ($r->status == 400){
                            return response()->json(['status' => 400, 'message' => $r->message],200);
                    }else if ($r->status == 500) {
                          return response()->json(['status' => 400, 'message' => 'No se puso imprimir su pdf contacte a DNM informática'],200);
                    }

            }


        }
        catch (\Exception $e){
            //throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }


    public function imprimir($idSolicitud)
    {
      $idSoli = Crypt::decrypt($idSolicitud);
      $solicitud = Solicitud::findOrFail($idSoli);
      $nit = Session::get('user');
      $persona = PersonaNatural::find($nit);
      $data['persona']=$persona;
      $data['persona']->tels=json_decode($persona->telefonosContacto);

      try {

            $client = new Client();
            $res = $client->request('POST', $this->url . 'urvpre/solicitudpre/get-data', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' =>[
                     'idSolicitud' => trim($idSoli),//Las solicitudes actuales estan incompletas
                 ],
                'allow_redirects'=> true
            ]);
            $r = json_decode($res->getBody());
            if ($r->status == 200) {
                $data['info'] = $r->data;
                $data['solicitud'] = $solicitud;
                $nit=Session::get('user');
                $perfiles=DB::select('select * from dnm_usuarios_portal.vwperfilportal where NIT = "'.$nit.'" and UNIDAD = CONVERT( "RV" USING UTF8) COLLATE utf8_general_ci');
                 foreach($perfiles as $per){
                                if($per->PERFIL='PFR'){
                                   $data['perfil'] = 'Profesional Responsable';
                                }else if($per->PERFIL='APO'){
                                    $data['perfil'] = 'Apoderado';
                                }else if($per->PERFIL='PROP'){
                                    $data['perfil'] = 'Propietario';
                                }
                }

                $view =  \View::make('pdf.Rv.formularioRegistroUrv',$data)->render();

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view);
                return $pdf->download('Comprobante.pdf');
               // return $pdf->stream('Comprobante'.'.pdf');
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            //throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }
     public function pdfDesistirSol($idSolicitud)
    {

      $idSoli = Crypt::decrypt($idSolicitud);
      $solicitud = Solicitud::findOrFail($idSoli);
      $fecha = getdate();

        // dd($fecha["minutes"]." ". $fecha["hours"]. " ". $fecha["mday"]. " ". $fecha["mon"]. " ". $fecha["year"]);

      $hora=$this->numAletras($fecha["hours"]);
      $min=$this->numAletras($fecha["minutes"]);
      $dias=$this->numAletras($fecha["mday"]);
      $year=$this->numAletras($fecha["year"]);
      $meses = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
      $data['dia']="a las ".$hora.' horas '.$min." minutos del día ".$dias." de ".$meses[$fecha["mon"]-1]." del ".$year;

      try {
            $existeDoc=DocumentoGenerales::where('idSolicitud',$idSoli)->where('estado','A')->where('idTipoDoc',2)->first();
            if($existeDoc){
                     if (File::isFile($existeDoc->urlArchivo)){
                             try{
                                    $file = File::get($existeDoc->urlArchivo);
                                    $response = Response::make($file, 200);
                                    $response->header('Content-Type', 'application/pdf');
                                    return $response;
                             } catch (Exception $e) {
                                return Response::download($existeDoc->urlArchivo);
                             }
                    }else{
                                Session::flash('msnError','¡PROBLEMAS AL CONSULTAR PDF!');
                                return redirect()->action('Registro\PreRegistro\NuevoRegistroController@index');
                    }

            }else{

                        $client = new Client();
                        $res = $client->request('POST', $this->url . 'urvpre/solicitudpre/get-data', [
                            'headers' => [
                                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                            ],
                            'form_params' =>[
                                 'idSolicitud' => trim($idSoli),//Las solicitudes actuales estan incompletas
                             ],
                            'allow_redirects'=> true
                        ]);

                        $r = json_decode($res->getBody());
                        if ($r->status == 200) {
                            $data['info'] = $r->data;
                            $data['solicitud'] = $solicitud;


                             $y=date('Y',strtotime($solicitud->fechaEnvio));
                             $d=date('d',strtotime($solicitud->fechaEnvio));
                             $m=date('m',strtotime($solicitud->fechaEnvio));
                             $dias2=$this->numAletras($d);
                             $year2=$this->numAletras($y);

                            $nit=Session::get('user');
                            $perfiles=DB::select('select * from dnm_usuarios_portal.vwperfilportal where NIT = "'.$nit.'" and UNIDAD = CONVERT( "RV" USING UTF8) COLLATE utf8_general_ci');
                            foreach($perfiles as $per){
                                if($per->PERFIL='PFR'){
                                   $data['perfil'] = 'Profesional Responsable';
                                }else if($per->PERFIL='APO'){
                                    $data['perfil'] = 'Apoderado';
                                }else if($per->PERFIL='PROP'){
                                    $data['perfil'] = 'Propietario';
                                }
                            }

                            $data['fechaEnvio']=$dias2." de ".$meses[$m-1]." del ".$year2;
                            $persona = PersonaNatural::getTratamiento($nit);
                            $data['tratm']=$persona->nombreTratamiento;

                            $view =  \View::make('pdf.Rv.desistirSolicitudPreRv',$data)->render();
                            $pdf = \App::make('dompdf.wrapper');
                            $pdf->loadHTML($view);

                            //Guardalo en una variable
                            $output = $pdf->output();
                            $nombreArchivo = "\DESISTIR-SOLICITUD.pdf";
                            $rutaGuardado=$this->urlArchivos.$solicitud->idSolicitud;
                            if(file_exists($rutaGuardado)){
                                $solicitud->estadoDictamen=14;
                                $solicitud->save();

                                SolicitudSeguimiento::create(['idSolicitud'=>$solicitud->idSolicitud,'estadoSolicitud'=>14,'observaciones'=>'Solicitud desistida por usuario.','idUsuarioCreacion'=>$solicitud->nitSolicitante,'fechaCreacion'=>Carbon::now('America/El_Salvador')]);

                                $docGen = new DocumentoGenerales();
                                $docGen->idSolicitud=$solicitud->idSolicitud;
                                $docGen->idTipoDoc=2;
                                $docGen->urlArchivo = $rutaGuardado.$nombreArchivo;
                                $docGen->tipoArchivo = 'application/pdf';
                                $docGen->save();
                                file_put_contents($rutaGuardado.$nombreArchivo, $output);
                                return $pdf->stream('DESISTIR-SOLICITUD-'.$solicitud->numeroSolicitud.'.pdf');
                            }else{
                                Session::flash('msnError','¡PROBLEMAS AL GENERAR EL PDF, CONTACTAR CON INFORMÁTICA!');
                                return redirect()->action('Registro\PreRegistro\NuevoRegistroController@index');
                            }

                        }
                        else if ($r->status == 400){
                            return response()->json(['status' => 400, 'message' => $r->message],200);
                        }
             }
        }
        catch (\Exception $e){
            return $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function pdfComprobanteSubsanacion($idSolicitud)
    {

      $idSoli = Crypt::decrypt($idSolicitud);
      $solicitud = Solicitud::findOrFail($idSoli);
      try {
            $existeDoc=DocumentoGenerales::where('idSolicitud',$idSoli)->where('estado','A')->where('idTipoDoc',5)->get();
            foreach($existeDoc as $docex){
                $do = DocumentoGenerales::find($docex->idItem);
                $do->estado='I';
                $do->save();
            }


                        $client = new Client();
                        $res = $client->request('POST', $this->url . 'urvpre/solicitudpre/get-data', [
                            'headers' => [
                                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                            ],
                            'form_params' =>[
                                 'idSolicitud' => trim($idSoli),//Las solicitudes actuales estan incompletas
                             ],
                            'allow_redirects'=> true
                        ]);

                        $r = json_decode($res->getBody());
                        if ($r->status == 200) {

                            $data['info']=$solicitud;
                            //Retornando datos
                            //dd($r->data);
                            $data['detalle'] = $r->data;
                            $date = Carbon::now();
                            $date->format('g:i A');
                            $data['date'] = $date;

                            $view =  \View::make('pdf.Rv.comprobanteSubsanacionUrv',$data)->render();
                            $pdf = \App::make('dompdf.wrapper');
                            $pdf->loadHTML($view);

                            //Guardalo en una variable
                            $output = $pdf->output();
                            $nombreArchivo = "\COMPROBANTE-SUBSANACION.pdf";
                            $rutaGuardado=$this->urlArchivos.$solicitud->idSolicitud;
                            if(file_exists($rutaGuardado)){
                                $docGen = new DocumentoGenerales();
                                $docGen->idSolicitud=$solicitud->idSolicitud;
                                $docGen->idTipoDoc=5;
                                $docGen->urlArchivo = $rutaGuardado.$nombreArchivo;
                                $docGen->tipoArchivo = 'application/pdf';
                                $docGen->save();
                                file_put_contents($rutaGuardado.$nombreArchivo, $output);
                                return $pdf->stream('COMPROBANTE-SUBSANACION-'.$solicitud->numeroSolicitud.'.pdf');
                            }else{
                                Session::flash('msnError','¡PROBLEMAS AL GENERAR EL PDF, CONTACTAR CON INFORMÁTICA!');
                                return redirect()->action('Registro\PreRegistro\NuevoRegistroController@index');
                            }

                        }
                        else if ($r->status == 400){
                            return response()->json(['status' => 400, 'message' => $r->message],200);
                        }

        }
        catch (\Exception $e){
            return $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }



    public function imprimirDocNumeracion(Request $request)
    {
      $id = Crypt::decrypt($request->idSolicitud);

      $s=$request->all();
      try {


             $client = new Client();
            $res = $client->request('GET', $this->url . 'preregistrourv/get/expediente-documentos', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ]
            ]);

            $r = json_decode($res->getBody());
            if ($r->status == 200) {

                $data['expDoc'] = $r->data;
                $data['itemsDoc']=null;
                $solicitud= Solicitud::findOrFail($id);
                $data['soli']=$solicitud;
               // dd($data['expDoc']);
                $idItemDoc = [];
                if (count($solicitud->documentos) > 0){
                    for ($i = 0; $i < count($solicitud->documentos); $i++) {
                       $idItemDoc[$i] = $solicitud->documentos[$i]->idItemRequisitoDoc;
                     }
                       $data['itemsDoc']=$idItemDoc;
                }

                $data['info'] = $r->data;
                $data['solicitud'] = $solicitud;
                $data['num'] = $s;
               //dd($data['num']);
                $view =  \View::make('pdf.Rv.numeraciondocumentosUrv',$data)->render();

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view);

                return $pdf->stream('Indice-Documentos'.'.pdf');


            }
            else if ($r->status == 400){
                return new JsonResponse([
                    'status' => 400,
                    'message' => $r->message
                ],200);

            }
            else if ($r->status == 404){
                return new JsonResponse([
                    'status' => 404,
                    'message' => $r->message
                ],200);
            }



        }
        catch (\Exception $e){
            //throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }
     public static function numAletras($num, $fem = false, $dec = true) //$num es el numero que recibe cualquiera los otros parametros no se para que son
  {
     $matuni[2]  = "dos";
     $matuni[3]  = "tres";
     $matuni[4]  = "cuatro";
     $matuni[5]  = "cinco";
     $matuni[6]  = "seis";
     $matuni[7]  = "siete";
     $matuni[8]  = "ocho";
     $matuni[9]  = "nueve";
     $matuni[10] = "diez";
     $matuni[11] = "once";
     $matuni[12] = "doce";
     $matuni[13] = "trece";
     $matuni[14] = "catorce";
     $matuni[15] = "quince";
     $matuni[16] = "diecis&eacute;is";
     $matuni[17] = "diecisiete";
     $matuni[18] = "dieciocho";
     $matuni[19] = "diecinueve";
     $matuni[20] = "veinte";
     $matunisub[2] = "dos";
     $matunisub[3] = "tres";
     $matunisub[4] = "cuatro";
     $matunisub[5] = "quin";
     $matunisub[6] = "seis";
     $matunisub[7] = "sete";
     $matunisub[8] = "ocho";
     $matunisub[9] = "nove";

     $matdec[2] = "veint";
     $matdec[3] = "treinta";
     $matdec[4] = "cuarenta";
     $matdec[5] = "cincuenta";
     $matdec[6] = "sesenta";
     $matdec[7] = "setenta";
     $matdec[8] = "ochenta";
     $matdec[9] = "noventa";
     $matsub[3]  = 'mill';
     $matsub[5]  = 'bill';
     $matsub[7]  = 'mill';
     $matsub[9]  = 'trill';
     $matsub[11] = 'mill';
     $matsub[13] = 'bill';
     $matsub[15] = 'mill';
     $matmil[4]  = 'millones';
     $matmil[6]  = 'billones';
     $matmil[7]  = 'de billones';
     $matmil[8]  = 'millones de billones';
     $matmil[10] = 'trillones';
     $matmil[11] = 'de trillones';
     $matmil[12] = 'millones de trillones';
     $matmil[13] = 'de trillones';
     $matmil[14] = 'billones de trillones';
     $matmil[15] = 'de billones de trillones';
     $matmil[16] = 'millones de billones de trillones';

     //Zi hack
     $float=explode('.',$num);
     $num=$float[0];

     $num = trim((string)@$num);
     if ($num[0] == '-') {
      $neg = 'menos ';
      $num = substr($num, 1);
     }else
      $neg = '';
     while ($num[0] == '0') $num = substr($num, 1);
     if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
     $zeros = true;
     $punt = false;
     $ent = '';
     $fra = '';
     for ($c = 0; $c < strlen($num); $c++) {
      $n = $num[$c];
      if (! (strpos(".,'''", $n) === false)) {
       if ($punt) break;
       else{
        $punt = true;
        continue;
       }

      }elseif (! (strpos('0123456789', $n) === false)) {
       if ($punt) {
        if ($n != '0') $zeros = false;
        $fra .= $n;
       }else

        $ent .= $n;
      }else

       break;

     }
     $ent = '     ' . $ent;
     if ($dec and $fra and ! $zeros) {
      $fin = ' coma';
      for ($n = 0; $n < strlen($fra); $n++) {
       if (($s = $fra[$n]) == '0')
        $fin .= ' cero';
       elseif ($s == '1')
        $fin .= $fem ? ' una' : ' un';
       else
        $fin .= ' ' . $matuni[$s];
      }
     }else
      $fin = '';
     if ((int)$ent === 0) return 'Cero ' . $fin;
     $tex = '';
     $sub = 0;
     $mils = 0;
     $neutro = false;
     while ( ($num = substr($ent, -3)) != '   ') {
      $ent = substr($ent, 0, -3);
      if (++$sub < 3 and $fem) {
       $matuni[1] = 'una';
       $subcent = 'as';
      }else{
       $matuni[1] = $neutro ? 'un' : 'uno';
       $subcent = 'os';
      }
      $t = '';
      $n2 = substr($num, 1);
      if ($n2 == '00') {
      }elseif ($n2 < 21)
       $t = ' ' . $matuni[(int)$n2];
      elseif ($n2 < 30) {
       $n3 = $num[2];
       if ($n3 != 0) $t = 'i' . $matuni[$n3];
       $n2 = $num[1];
       $t = ' ' . $matdec[$n2] . $t;
      }else{
       $n3 = $num[2];
       if ($n3 != 0) $t = ' y ' . $matuni[$n3];
       $n2 = $num[1];
       $t = ' ' . $matdec[$n2] . $t;
      }
      $n = $num[0];
      if ($n == 1) {
       $t = ' ciento' . $t;
      }elseif ($n == 5){
       $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
      }elseif ($n != 0){
       $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
      }
      if ($sub == 1) {
      }elseif (! isset($matsub[$sub])) {
       if ($num == 1) {
        $t = ' mil';
       }elseif ($num > 1){
        $t .= ' mil';
       }
      }elseif ($num == 1) {
       $t .= ' ' . $matsub[$sub] . '?n';
      }elseif ($num > 1){
       $t .= ' ' . $matsub[$sub] . 'ones';
      }
      if ($num == '000') $mils ++;
      elseif ($mils != 0) {
       if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
       $mils = 0;
      }
      $neutro = true;
      $tex = $t . $tex;
     }
     $tex = $neg . substr($tex, 1) . $fin;
     //Zi hack --> return ucfirst($tex);
     //$end_num=ucfirst($tex).' '.$float[1].'/100';
    // return ucfirst($tex); ucfirst es la primera letra en mayuscula
    return $tex;
  }

  public function pdfComprobanteArchivado(Request $request){
    $idSolicitud = Crypt::decrypt($request->idSolicitud);
    //idTipoDoc = 6 : Documento de Resolucion de Archivado
    $documentoGeneral = DocumentoGenerales::where('idSolicitud',$idSolicitud)->where('idTipoDoc',6)->where('estado','A')->first();

    if (File::isFile($documentoGeneral->urlArchivo)){
      return response()->file($documentoGeneral->urlArchivo);
    }

    return 'No se pudo mostrar el PDF de la resolución de archivado.';
  }

  public function pdfComprobanteIngresoUrv(Request $request){
    $idSolicitud = Crypt::decrypt($request->idSolicitud);
    //idTipoDoc = 6 : Documento de Resolucion de Archivado
    $documentoGeneral = DocumentoGenerales::where('idSolicitud',$idSolicitud)->where('idTipoDoc',8)->where('estado','A')->first();

    if (File::isFile($documentoGeneral->urlArchivo)){
      return response()->file($documentoGeneral->urlArchivo);
    }

    return 'No se pudo mostrar el PDF de la resolución de archivado.';
  }
}
