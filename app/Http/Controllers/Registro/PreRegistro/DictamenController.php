<?php

namespace App\Http\Controllers\Registro\PreRegistro;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests\SolCosRequest;
use App\Http\Requests\Registro\NuevoRegistro\Step1y2Request;
use App\Http\Requests\Registro\NuevoRegistro\Step3Request;
use App\Http\Requests\Registro\NuevoRegistro\Step4Request;
use App\Http\Requests\Registro\NuevoRegistro\Step5Request;
use App\Http\Requests\Registro\NuevoRegistro\Step6Request;
use App\Http\Requests\Registro\NuevoRegistro\Step7Request;
use App\Http\Requests\Registro\NuevoRegistro\Step8Request;
use App\Http\Requests\Registro\NuevoRegistro\Step9Request;

use App\Http\Requests\Registro\NuevoRegistro\Step11Request;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use Crypt;
use Validator;
use Datatables;
use Log;
use File;
use Config;
use Session;
use DB;
use Response;
use Carbon\Carbon;


use App\Models\Registro\Sol\Solicitud;
use App\Models\Registro\Sol\RequisitosDocumento;
use App\Models\Registro\Sol\PreDocumentos;
use App\Models\Registro\Sol\Paso2ProductoGenerales;
use App\Models\Registro\Sol\Paso3Apoderado;
use App\Models\Registro\Sol\Paso3Profesional;
use App\Models\Registro\Sol\Paso3Representante;
use App\Models\Registro\Sol\Paso3Titular;
use App\Models\Registro\Sol\Paso4FabPrincipal;
use App\Models\Registro\Sol\Paso4FabAlternos;
use App\Models\Registro\Sol\Paso4LabAcondicionador;
use App\Models\Registro\Sol\Paso5CertManufactura;
use App\Models\Registro\Sol\Paso2\PrincipioActivo;
use App\Models\Registro\Sol\Paso2\EmpaquePresentacion;
use App\Models\Registro\Sol\Paso6\BpmAcondicionador;
use App\Models\Registro\Sol\Paso6\BpmAlterno;
use App\Models\Registro\Sol\Paso6\BpmPrincipal;
use App\Models\Registro\Sol\Paso7\Distribuidor;
use App\Models\Registro\Sol\Paso8\MaterialEmpaque;
use App\Models\Registro\Sol\Paso9\Farmacologia;
use App\Models\Registro\Sol\Paso9\DetalleFarmacologia;
use App\Models\Ucc\UnificacionPortal;
use App\Models\Registro\Dic\Dictamen;
use App\Models\Registro\Dic\ValidacionDictamen;

class DictamenController extends Controller
{
    private $url=null;
    private $token=null;

    public function __construct() {
        $this->url = Config::get('app.api');
        $this->token = Config::get('app.token');
    }

    public function getRowsDictamenRV(Request $request){
    $id = Crypt::decrypt($request->idSolicitud);
    $dictamenes = new Collection;
    $solicitud = Solicitud::find($id);

    //->where('estadoDictamen',4)
    //------------------------ DICTAMENTE MEDICO-------------------------------------------
    $dictamenMedico = Dictamen::where('idSolicitud', $id)->where('tipoDictamen',1)->orderBy('idDictamen','DESC')->first();
    //dd($dictamenMedico);
    if(!empty($dictamenMedico)){
                if($dictamenMedico->estadoDictamen==4){
                    //VALIDAMOS QUE LA SOLICITUD QUE LA SOLICITUD NO ESTE ASIGNADA
                    if($solicitud->estadoDictamen>2){
                    $opcionMed='<a title="Editar"  target="_blank" href="'.route('dictamen.medico.rv',['idsol'=>Crypt::encrypt($id),'idDictamen'=>Crypt::encrypt($dictamenMedico->idDictamen)]).'" class="btn btn-xs btn-primary  btn-perspective">DICTAMEN PDF<i class="fa fa-pencil"></i></a> ';
                     }else{
                        $opcionMed='';
                     }
                }else{
                    $opcionMed='';
                }
                 $dictamenes->push([
                            'dictamen' => "MÉDICO",
                            'estado' => '<span class="label label-primary">'.$dictamenMedico->estado->estado.'</span>',
                            'opcion' => $opcionMed
                 ]);
     }else{
                  $dictamenes->push([
                            'dictamen' => "MÉDICO",
                            'estado' => 'SIN DICTAMEN',
                            'opcion' => ''
                 ]);
     }
    //------------------------ DICTAMENTE QUIMICO-------------------------------------------

    $dictamenQuimico=Dictamen::where('idSolicitud', $id)->where('tipoDictamen',2)->orderBy('idDictamen','DESC')->first();
    //dd($dictamenQuimico);
     if(!empty($dictamenQuimico)){
             if($dictamenQuimico->estadoDictamen==4){
                //VALIDAMOS QUE LA SOLICITUD QUE LA SOLICITUD NO ESTE ASIGNADA
                if($solicitud->estadoDictamen>2){
                $opcionQuimico='<a title="Editar"  target="_blank" href="'.route('dictamen.medico.rv',['idsol'=>Crypt::encrypt($id),'idDictamen'=>Crypt::encrypt($dictamenQuimico->idDictamen)]).'" class="btn btn-xs btn-primary  btn-perspective">DICTAMEN PDF<i class="fa fa-pencil"></i></a> ';
                }else{
                     $opcionQuimico='';
                }
            }else{
                $opcionQuimico='';
            }

            $dictamenes->push([
                        'dictamen' => "QUIMICO",
                        'estado' => '<span class="label label-primary">'.$dictamenQuimico->estado->estado.'</span>',
                        'opcion' => $opcionQuimico
             ]);
    }else{
             $dictamenes->push([
                        'dictamen' => "QUIMICO",
                        'estado' => 'SIN DICTAMEN',
                        'opcion' => ''
             ]);
    }


    //------------------------ DICTAMENTE LABORATORIO-------------------------------------------
    $lab = UnificacionPortal::where('idSolicitud',$id)->get()->first();
    if(!empty($lab)){
        if(count($lab->revisionMetodologica)>0){
            $revision = $lab->revisionMetodologica()->orderBy('fecha_creacion','desc')->first();
            //dd($revision);
            $id_revision=$revision->id_revision_metodologica;
            $estadoLab= $revision->estado;
        }else{
                $id_revision='';
                $estadoLab='-1';
        }
    }else{
                $id_revision='';
                $estadoLab='-1';
    }
    if($estadoLab==0){ $nomestadoLab = 'EN PROCESO';}else if($estadoLab==1){$nomestadoLab = 'FAVORABLE';}else if($estadoLab==2){$nomestadoLab = 'OBSERVADO';
    }else{$nomestadoLab = 'SIN DICTAMEN';}
    if(!empty($id_revision)){
          //VALIDAMOS QUE LA SOLICITUD QUE LA SOLICITUD NO ESTE ASIGNADA
         if($solicitud->estadoDictamen>2){
        $opcionLab='<a title="Editar"  target="_blank" href="'.route('dictamen.laboratorio.rv',['idSolicitud'=>Crypt::encrypt($id_revision)]).'" class="btn btn-xs btn-primary  btn-perspective">DICTAMEN PDF<i class="fa fa-pencil"></i></a> ';
          }else{
              $opcionLab='';
          }
    }else{
                  $opcionLab='';
     }
    $dictamenes->push([
                'dictamen' => "LABORATORIO",
                'estado' => '<span class="label label-primary">'.$nomestadoLab.'</span>',
                'opcion' => $opcionLab
     ]);

    return Datatables::of($dictamenes)
          ->make('true');
    }
    
   public function reporteObservacionLaboratorio($id){
    $idRevision = Crypt::decrypt($id); 
    $client = new Client();
    $res = $client->request('GET',Config::get('app.apiUcc').'get-revision-observacion/'.$idRevision, [
                'headers' => [
                    'tk' => '2a2ab2ae20ef1df77360a1502b24cc7c',
                ],
                'form_params' => [
                    'id' => $idRevision
                ]
            ]);
     
     return Response::make($res->getBody(), 200, array('content-type' => 'application/pdf'));
  }
   public function reporteObservacionMedico($idsol,$idDictamen){
     $idsol = Crypt::decrypt($idsol); 
     $idDictamen = Crypt::decrypt($idDictamen);
    $client = new Client();
    $res = $client->request('GET',Config::get('app.apiSiEspecialidades').'reporteDictamenQuimico/'.$idsol.'/'.$idDictamen,[
                'headers' => [
                    'tk' => 'e1d7f219c95107d105070afe198b3098',
                ],
                'form_params' => [
                    'idSol' => $idsol,
                    'idDic' =>$idDictamen
                ]
            ]);
     
     return Response::make($res->getBody(), 200, array('content-type' => 'application/pdf'));
  }

  public function reporteRevisionUrvPre(Request $request){
    $idSol = Crypt::decrypt($request->idSolicitud);

    $solicitud = Solicitud::find($idSol);
    $revision = $solicitud->revisiones()->where('estado',4)->orderBy('idRevision','desc')->first(); //Consulto la ultima revisión
    $rutaArchivo = $revision->resolucionPdf;
    if (File::isFile($rutaArchivo)){
      $file = File::get($rutaArchivo);
      $response = Response::make($file, 200);
          // using this will allow you to do some checks on it (if pdf/docx/doc/xls/xlsx)
      $response->header('Content-Type', 'application/pdf');
      return $response;
    }
    
  }


}
