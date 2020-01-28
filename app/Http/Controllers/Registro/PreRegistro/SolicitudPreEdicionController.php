<?php

namespace App\Http\Controllers\Registro\PreRegistro;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use App\Http\Requests;


use App\Http\Requests\SolCosRequest;
use App\Http\Requests\Registro\NuevoRegistro\Step1y2Request;
use App\Http\Requests\Registro\NuevoRegistro\Step3Request;
use App\Http\Requests\Registro\NuevoRegistro\Step4Request;
use App\Http\Requests\Registro\NuevoRegistro\Step5Request;
use App\Http\Requests\Registro\NuevoRegistro\Step6Request;
use App\Http\Requests\Registro\NuevoRegistro\Step7Request;
use App\Http\Requests\Registro\NuevoRegistro\Step8Request;
use App\Http\Requests\Registro\NuevoRegistro\Step9Request;

use GuzzleHttp\Client;
use Crypt;
use Validator;
use Datatables;
use Log;
use File;
use Config;
use Session;
use DB;
use Carbon\Carbon;

use App\Models\Registro\Sol\Solicitud;
use App\Models\Registro\Sol\Paso2ProductoGenerales;
use App\Models\Registro\Sol\Paso3Apoderado;
use App\Models\Registro\Sol\Paso3Profesional;
use App\Models\Registro\Sol\Paso3Representante;
use App\Models\Registro\Sol\Paso3Titular;
use App\Models\Registro\Sol\Paso4FabPrincipal;
use App\Models\Registro\Sol\Paso4FabAlternos;
use App\Models\Registro\Sol\Paso4LabAcondicionador;
use App\Models\Registro\Sol\Paso5CertManufactura;

use App\Models\Registro\Sol\Paso6\BpmAcondicionador;
use App\Models\Registro\Sol\Paso6\BpmAlterno;
use App\Models\Registro\Sol\Paso6\BpmPrincipal;
use App\Models\Registro\Sol\Paso7\Distribuidor;
use App\Models\Registro\Sol\Paso8\MaterialEmpaque;
use App\Models\Registro\Sol\Paso9\Farmacologia;
use App\Models\Registro\Sol\Paso9\DetalleFarmacologia;

use App\Models\Registro\Dic\Dictamen;
use App\Models\Registro\Dic\ValidacionDictamen;

class SolicitudPreEdicionController extends Controller
{
    private $url=null;
    private $token=null;

    public function __construct() {
        $this->url = Config::get('app.api');
        $this->token = Config::get('app.token');
    }

    public function getDataRows(Request $request)
    {
      $nit=Session::get('user');
   // dd(Solicitud::where('nitSolicitante',$nit)->where('estadoDictamen','!=','10')->join('sol.estadosSolicitudes','idEstado','estadoDictamen')->get());
      $sol = Solicitud::getSolicitudes($nit);
      return Datatables::of($sol)
        ->addColumn('fechaCreacion',function($dt){
          return date('d-m-Y',strtotime($dt->fechaCreacion));
        })
         ->addColumn('fechaEnvio',function($dt){
          $fec1 = date('d-m-Y',strtotime($dt->fechaEnvio));
              if($fec1=='31-12-1969'){
                  return '';
              }else{
                   return date('d-m-Y',strtotime($dt->fechaEnvio));
              }
        })
        ->addColumn('fechaSubsanacion',function($dt){
           $fec2 = date('d-m-Y',strtotime($dt->fechaSubsanacion));
          if($fec2=='31-12-1969'){
                  return '';
              }else{
                   return date('d-m-Y',strtotime($dt->fechaSubsanacion));
              }
        })
        ->addColumn('estadoPortal',function($dt){
          if($dt->estadoDictamen==18)
            return '<span class="label label-primary">'.$dt->estadoPortal.'</span><br><br><span class="label label-primary"> Días Transcurridos: '.$dt->plazo.'/10</span>';

          return '<span class="label label-primary">'.$dt->estadoPortal.'</span>';
        })
        ->addColumn('opciones',function($dt){

           if($dt->estadoDictamen==1){
                    return '<a href="'.route('pdf.comprobante.ingreso.sol.rv',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-info btn-xs btn-perspective" target="_blank" title="Comprobante de Ingreso"><i class="fa fa-print"></i></a> <button id="btnCancelarFactura" class="btn btn-danger btn-xs btn-perspective btnDesistirSol" data-id="'.Crypt::encrypt($dt->idSolicitud).'" title="Desistir Solicitud"><i class="fa fa-times"></i></button>';
           }else if($dt->estadoDictamen==17){
                    //COMPROBANTE DE SOLICITUD - EN REVISIÓN
                    return '<a href="'.route('comprobante.ingreso.rv.pre',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-info btn-xs btn-perspective" target="_blank" title="Comprobante de Solicitud"><i class="fa fa-print"></i></a> <button id="btnCancelarFactura" class="btn btn-danger btn-xs btn-perspective btnDesistirSol" data-id="'.Crypt::encrypt($dt->idSolicitud).'" title="Desistir Solicitud"><i class="fa fa-times"></i></button>';
           }elseif ($dt->estadoDictamen==0) { //Agregar boton para Desistir del tramite tipo borrador
                    return '<a title="Editar" href="'.route('get.preregistrorv.index.editarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-primary btn-perspective"><i class="fa fa-pencil"></i></a>'.' '.'<button value="'.Crypt::encrypt($dt->idSolicitud).'" class="btn btn-xs btn-danger btn-perspective btnEliminarSolCos" title="Eliminar Solicitud");"><i class="fa fa-trash"></i></button> ';
            }elseif ($dt->estadoDictamen==2) { //Agregar boton para Desistir del tramite tipo borrador
                    return '<a title="VER" href="'.route('get.preregistrorv.index.subsanarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-primary  btn-perspective">VER<i class="fa fa-pencil"></i></a> <button id="btnCancelarFactura" class="btn btn-danger btn-xs btn-perspective btnDesistirSol" data-id="'.Crypt::encrypt($dt->idSolicitud).'" title="Desistir Solicitud"><i class="fa fa-times"></i></button>';
            }elseif ($dt->estadoDictamen==3||$dt->estadoDictamen==12||$dt->estadoDictamen==13) { //FAVORABLE || DOCUMENTOS RECIBIDOS || DOCUMENTOS REVISADOS
                    return '<a title="VER" href="'.route('get.preregistrorv.index.subsanarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-primary  btn-perspective">VER<i class="fa fa-pencil"></i></a> <button id="btnCancelarFactura" class="btn btn-danger btn-xs btn-perspective btnDesistirSol" data-id="'.Crypt::encrypt($dt->idSolicitud).'" title="Desistir Solicitud"><i class="fa fa-times"></i></button>';
            }elseif ($dt->estadoDictamen==4){
                 return '<a title="SUBSANAR" href="'.route('get.preregistrorv.index.subsanarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-primary btn-perspective">SUBSANAR<i class="fa fa-pencil"></i></a> <a  class="btn btn-xs btn-primary  btn-perspective" target="_blank" title="Estatus dictamenes"  onclick="verDictamenesModal(\''.Crypt::encrypt($dt->idSolicitud).'\');">DICTAMEN<i class="fa fa-copy"></i></a> <button id="btnCancelarFactura" class="btn btn-danger btn-xs btn-perspective btnDesistirSol" data-id="'.Crypt::encrypt($dt->idSolicitud).'" title="Desistir Solicitud"><i class="fa fa-times"></i></button>';
                  // <button id="btnCancelarFactura" class="btn btn-danger btn-xs btn-perspective btnDesistirSol" data-id="'.Crypt::encrypt($dt->idSolicitud).'" title="Desistir Solicitud"><i class="fa fa-times"></i></button>
            }elseif($dt->estadoDictamen==5){
                 return '<a title="VER" href="'.route('get.preregistrorv.index.subsanarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-primary  btn-perspective">VER<i class="fa fa-pencil"></i></a> <a  class="btn btn-xs btn-primary  btn-perspective" target="_blank" title="Estatus dictamenes"  onclick="verDictamenesModal(\''.Crypt::encrypt($dt->idSolicitud).'\');">DICTAMEN<i class="fa fa-copy"></i></a>';
            }elseif($dt->estadoDictamen==11){
                 return '<a title="VER" href="'.route('get.preregistrorv.index.subsanarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-primary  btn-perspective">VER<i class="fa fa-pencil"></i></a> <a href="'.route('pdf.comprobante.subsanacion.sol.rv',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-info  btn-perspective" target="_blank" title="Comprobante de subsanación"><i class="fa fa-print"></i></a>';
            }elseif($dt->estadoDictamen==15){ //Archivado 1 - Se archivo en el proceso de revisión previa
                return '<a title="VER" href="'.route('get.preregistrorv.index.subsanarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-primary  btn-perspective">VER<i class="fa fa-pencil"></i></a> <a title="Ver resolucion" href="'.route('pdf.comprobante.archivado.sol.rv',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-warning btn-perspective" target="_blank"><i class="fa fa-file"></i></a>';

            }elseif ($dt->estadoDictamen==18) { //Agregado boton editar para estado EN ESPERA DE CORRECCION
                    return '<a title="Editar" href="'.route('get.preregistrorv.index.editarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-primary btn-perspective"><i class="fa fa-pencil"></i></a> <a title="Ver resolucion" href="'.route('resolucion.nuevoregistro.rv',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-warning btn-perspective" target="_blank"><i class="fa fa-file"></i></a>';
            }else{
                return '<a title="VER" href="'.route('get.preregistrorv.index.subsanarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-primary  btn-perspective">VER<i class="fa fa-pencil"></i></a>';

            }
        })
         ->filter(function($query) use ($request){
               if($request->has('nsolicitud')){
                    $query->where('T1.numeroSolicitud','=',$request->get('nsolicitud'));
                }
                if($request->has('nomComercial')){

                    $query->where('T3.nombreComercial','like','%'.$request->get('nomComercial').'%');
                }
                if($request->has('estado')){
                        $query->where('T1.estadoDictamen','=',$request->get('estado'));
                }
                if($request->has('fecha')){
                    $query->where('T1.fechaCreacion','like',"%". date('Y-m-d',strtotime($request->fecha))."%");
                }
                if($request->has('fechaRecep')){
                    $query->where('T1.fechaEnvio','like',"%". date('Y-m-d',strtotime($request->fechaRecep))."%");
                }
                 if($request->has('fechaSubsanacion')){
                    $query->where('T1.fechaSubsanacion','like',"%". date('Y-m-d',strtotime($request->fechaSubsanacion))."%");
                }

            })

        ->make(true);
    }

    public function edit($idSolicitud)
    {
      $data = ['title'           => 'Solicitudes Nuevo Registro'
                 ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Solicitudes nuevo registro', 'url' => route('get.preregistrorv.index')],
                    ['nom'  =>  'Editar Solicitud', 'url' => '#']
                ]
              ,'solicitud'=>Solicitud::findOrFail(Crypt::decrypt($idSolicitud))];
      try {
          $solicitud = Solicitud::findOrFail(Crypt::decrypt($idSolicitud));
          $data['infoSolicitud']=$solicitud;
          //dd($solicitud);
          //$detafar = $solicitud->farmacologia;
         // dd(Crypt::decrypt($idSolicitud));
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
          //dd($r);
          if ($r->status == 200) {
                $data['soldata']=$r->data;
          }
          else{
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$r->message,'msg'=>$r->message]);
            Session()->flash('msnError', '¡Problemas al consultar información de la solicitud!');
            return back();
          }

          //CONSULTAMOS EL TIPO DE PAGO DEL MANDAMIENTO
          $client2 = new Client();
           $res2 = $client2->request('POST', $this->url . 'urvpre/solicitudpre/consultar-tipopago/mandamiento', [
              'headers' => [
                  'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

              ],
              'form_params' =>[
                  'numMandamiento' => trim($solicitud->mandamiento),
              ]
          ]);
          $r2 = json_decode($res2->getBody());
          if ($r2->status == 200){
                if($r2->tipopago==3571){
                      //EXTRANJERO
                      $data['tipomandamiento']=2;
                }else{
                    //NACIONAL
                     $data['tipomandamiento']=1;
                }
          }else{
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$r->message,'msg'=>$r->message]);
            Session()->flash('msnError', '-¡Problemas al consultar información de la solicitud!');
            return back();
          }


      }
      catch (\Exception $e){
           //throw $e;
          Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
          Session()->flash('msnError', '¡Problemas al consultar información de la solicitud!');
          return back();
      }

     return view('registro.nuevoregistro.edit',$data);

    }


     public function subsanacion($idSolicitud)
    {
      $data = ['title'           => 'Solicitudes Nuevo Registro'
                 ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Solicitudes nuevo registro', 'url' => route('get.preregistrorv.index')],
                    ['nom'  =>  'Subsanar Solicitud', 'url' => '#']
                ]
              ,'solicitud'=>Solicitud::findOrFail(Crypt::decrypt($idSolicitud))];
      try {
          $solicitud = Solicitud::findOrFail(Crypt::decrypt($idSolicitud));
          $data['infoSolicitud']=$solicitud;
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
                $data['soldata']=$r->data;
          }
          else{
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$r->message,'msg'=>$r->message]);
            Session()->flash('msnError', '¡Problemas al consultar información de la solicitud!');
            return back();
          }

          //CONSULTAMOS EL TIPO DE PAGO DEL MANDAMIENTO
          $client2 = new Client();
           $res2 = $client2->request('POST', $this->url . 'urvpre/solicitudpre/consultar-tipopago/mandamiento', [
              'headers' => [
                  'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

              ],
              'form_params' =>[
                  'numMandamiento' => trim($solicitud->mandamiento),
              ]
          ]);
          $r2 = json_decode($res2->getBody());
          if ($r2->status == 200){
                if($r2->tipopago==3571){
                      //EXTRANJERO
                      $data['tipomandamiento']=2;
                }else{
                    //NACIONAL
                     $data['tipomandamiento']=1;
                }
          }else{
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$r->message,'msg'=>$r->message]);
            Session()->flash('msnError', '-¡Problemas al consultar información de la solicitud!');
            return back();
          }

        if($solicitud->estadoDictamen==4){
         //Consutamos los campos que estan observados por dictamente medico y quimico
         $data['campos']= Dictamen::getCamposObservador(Crypt::decrypt($idSolicitud));
          //Consutamos las tablas que estan observados por dictamente medico y quimico
         $data['tablas']= Dictamen::getTablasObservadas(Crypt::decrypt($idSolicitud));
         //dd($data['campos']);
         }else{
              $data['campos']=[];
              $data['tablas']=[];
         }

      }
      catch (\Exception $e){
           //throw $e;
          Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
          Session()->flash('msnError', '¡Problemas al consultar información de la solicitud!');
          return back();
      }

     return view('registro.nuevoregistro.subsanacion.edit',$data);

    }

      public function getExpDocumentosEdit($idSolicitud,$vista){

        try {

            $client = new Client();
            $res = $client->request('GET', $this->url . 'preregistrourv/get/expediente-documentos', [
                'headers' => [
                    'tk' => $this->token,

                ]
            ]);

            $r = json_decode($res->getBody());
            if ($r->status == 200) {

                $data['expDoc'] = $r->data;
                $data['itemsDoc']=null;
                $solicitud= Solicitud::findOrFail(Crypt::decrypt($idSolicitud));
                $data['soli']=$solicitud;
               // dd($data['expDoc']);
                $idItemDoc = [];
                if (count($solicitud->documentos) > 0){
                    for ($i = 0; $i < count($solicitud->documentos); $i++) {
                       $idItemDoc[$i] = $solicitud->documentos[$i]->idItemRequisitoDoc;
                  }
                       $data['itemsDoc']=$idItemDoc;
                }
                //vista 1. para editar 2.para subsanar
                if($vista==1){
                   return view('registro.nuevoregistro.pasosEdit.paso10.expedientetabla',$data);
                }else{
                   if($solicitud->estadoDictamen==4){
                     //Consutamos los documentos que estan observados por dictamente medico y quimico
                     $data['docObservados']=Dictamen::getDocumentosObservados(Crypt::decrypt($idSolicitud));
                   }else{
                     $data['docObservados']=[];
                   }

                   return view('registro.nuevoregistro.subsanacion.pasosEdit.paso10.expedientetabla',$data);
                }

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

      public function serachNombreSolicitud(Request $request){
          try {
              $q=$request->search;
              $nit=Session::get('user');
              $solicitud=Solicitud::searchAuotoSolicitud($nit,$q);
              return $solicitud;
          } catch (Exception $e) {
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
          }
      }

}
