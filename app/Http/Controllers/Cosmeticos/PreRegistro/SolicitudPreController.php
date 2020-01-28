<?php
/**
 * Created by PhpStorm.
 * User: steven.mena
 * Date: 1/3/2018
 * Time: 9:32 AM
 */
namespace App\Http\Controllers\Cosmeticos\PreRegistro;




use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Filesystem\Filesystem;


use App\Http\Requests\SolCosRequest;
use App\Http\Requests\Cosmeticos\NuevoRegistro\Step1y2Request;
use App\Http\Requests\Cosmeticos\NuevoRegistro\Step3Request;
use App\Http\Requests\Cosmeticos\NuevoRegistro\Step4Request;

use App\Http\Controllers\Controller;


use GuzzleHttp\Client;
use Crypt;
use Validator;
use Response;
use Datatables;
use Log;
use File;
use Config;
use Session;
use DB;
use Carbon\Carbon;


use App\Models\Cosmeticos\VwSolicitudesPortal;

use App\Models\Cosmeticos\Sol\Solicitud;
use App\Models\Cosmeticos\Sol\SolicitudDetalle;
use App\Models\Cosmeticos\Sol\Presentacion;
use App\Models\Cosmeticos\Sol\Item;
use App\Models\Cosmeticos\Sol\DetalleDocumento;
use App\Models\Cosmeticos\Sol\Fabricante;
use App\Models\Cosmeticos\Sol\Importador;
use App\Models\Cosmeticos\Sol\DocumentoSol;

use App\Models\Cosmeticos\Sol\DetalleCosmetico;
use App\Models\Cosmeticos\Sol\DetalleHigienico;
use App\Models\Cosmeticos\Sol\Fragancia;
use App\Models\Cosmeticos\Sol\Tono;
use App\Models\Cosmeticos\Sol\SeguimientoSolPre;



class SolicitudPreController extends Controller
{
    private $url=null;
    private $token=null;
    private $pathfiles=null;

    public function __construct() {
        $this->url = Config::get('app.api');
        $this->token = Config::get('app.token');
        $this->pathfiles= Config::get('app.mapeo_files_cos');
    }

    //listado de solicitudes
    public function index(){

        $data = ['title'           => 'Solicitudes Nuevo Registro'
            ,'subtitle'         => ''
            ,'breadcrumb'       => [
                ['nom'  =>  'Cosmeticos', 'url' => '#'],
                ['nom'  =>  'Solicitudes nuevo registro', 'url' => '#']
            ]];

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/estados', [
                'headers' => [
                    'tk' => $this->token,
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $estados = $r->data;
            }
            else if ($r->status == 400){
                $estados = null;
            }

            $res1 = $client->request('POST', $this->url . 'pelcos/get/tiposTramites', [
                'headers' => [
                    'tk' => $this->token,
                ]
            ]);

            $r1 = json_decode($res1->getBody());

            if ($r1->status == 200) {
                $tramites = $r1->data;
            }
            else if ($r1->status == 400){
                $tramites = null;
            }
        }
        catch (\Exception $e){
            // throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
        }

        $data['estados'] =$estados;
        $data['tramites'] =$tramites;

        return view('cosmeticos.nuevoregistro.index',$data);
    }


    public function storeStep1y2(Step1y2Request $request){
        //dd($request->all());

        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();

        try{


            $idSolicitud=Crypt::decrypt($request->idSolicitud);

            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $solicitud->idUsuarioModificacion=$nit.'@'.$request->ip();
                if($solicitud->estado!=4) $solicitud->estado=0;
            }
            else{
                $solicitud = new Solicitud();
                $solicitud->estado=0;
                $solicitud->idUsuarioCrea= $nit.'@'.$request->ip();
            }


            $solicitud->tipoSolicitud=$request->tipoTramite;
            $solicitud->nitSolicitante=Session::get('user');
            $solicitud->idMandamiento=$request->mandamiento;

            if($request->has('coempaque')) {
                $solicitud->poseeCoempaque=$request->coempaque;
                if ($request->coempaque == 1) {

                    if($request->has('detcoempaque'))
                        $solicitud->descripcionCoempaque = $request->detcoempaque;
                }
            }
            $solicitud->solicitudPortal=1;
            $solicitud->save();

            if($idSolicitud!=0) {
                $idSol=$solicitud->idSolicitud;
            }
            else{
                $idSol=$solicitud::all()->last()->idSolicitud;
            }

            if(!empty($solicitud->solicitudesDetalle)){
                //return JsonResponse::create(['data' => empty($solicitud->solicitudesDetalle)],200);
                $solDetalle = $solicitud->solicitudesDetalle;
                $solDetalle->idUsuarioModificacion=$nit.'@'.$request->ip();
            }
            else{

                $solDetalle = new SolicitudDetalle();
                $solDetalle->idSolicitud = $idSol;
                $solDetalle->idUsuarioCrea=$nit.'@'.$request->ip();
            }
            //Guardo el detalle con el id de la solicitud ingresada.
            $solDetalle->nombreComercial=$request->nomProd;
            if($request->has('marca')) $solDetalle->idMarca = $request->marca;
            if($request->tipoTramite==2 || $request->tipoTramite==4){
                $solDetalle->idPais=222;
            }
            else if($request->tipoTramite==3 || $request->tipoTramite==5){
                if($request->has('paisOrigen')) $solDetalle->idPais=$request->paisOrigen;
                if($request->has('numRegistro')) $solDetalle->numeroRegistroExtr=$request->numRegistro;
                if($request->has('fechaVen')) $solDetalle->fechaVencimiento=date('Y-m-d',strtotime($request->fechaVen));
            }

            $solDetalle->save();

            if($idSolicitud==0) {
                $idSolDet=$solDetalle::all()->last()->idDetalle;
            }


            //Guardo detalle de Cosmetico o higienico
            if($request->tipoTramite==2 || $request->tipoTramite==3){
                if(!empty($solDetalle->detallesCosmetico)){
                    //return JsonResponse::create(['data' => empty($solicitud->solicitudesDetalle)],200);
                    $detalleCos = $solDetalle->detallesCosmetico;
                    if($request->has('clasificacion')) $detalleCos->idClasificacion = $request->clasificacion;
                    if($request->has('formacos')) $detalleCos->idFormaCosmetica = $request->formacos;
                    $detalleCos->idUsuarioModifica=$nit.'@'.$request->ip();
                    $detalleCos->save();
                }
                else{
                    $detalleCos = new DetalleCosmetico();
                    $detalleCos->idDetalle=$idSolDet;
                    if($request->has('clasificacion')) $detalleCos->idClasificacion = $request->clasificacion;
                    if($request->has('formacos')) $detalleCos->idFormaCosmetica = $request->formacos;
                    $detalleCos->idUsuarioCreacion = $nit.'@'.$request->ip();
                    $detalleCos->save();
                }

            }
            else if($request->tipoTramite==4 || $request->tipoTramite==5){

                if(!empty($solDetalle->detallesHigienicos)){
                    //return JsonResponse::create(['data' => empty($solicitud->solicitudesDetalle)],200);
                    $detalleCos = $solDetalle->detallesHigienicos;
                    if($request->has('clasificacion')) $detalleCos->idClasificacion = $request->clasificacion;
                    if($request->has('uso')) $detalleCos->uso = $request->uso;
                    $detalleCos->idUsuarioModifica=$nit.'@'.$request->ip();
                    $detalleCos->save();
                }
                else {
                    $detalleCos = new DetalleHigienico();
                    $detalleCos->idDetalle=$idSolDet;
                    if($request->has('clasificacion')) $detalleCos->idClasificacion = $request->clasificacion;
                    if($request->has('uso')) $detalleCos->uso = $request->uso;
                    $detalleCos->idUsuarioCreacion=$nit.'@'.$request->ip();
                    $detalleCos->save();

                }
            }

            /* Si no vienen presentanciones borrarlas */
            if(count($solicitud->presentaciones)>0) $solicitud->presentaciones()->delete();

            if($request->has('presentaciones')) {
                foreach ($request->presentaciones as $presentacion) {
                    $present = json_decode($presentacion);
                    $solPresentacion = new Presentacion();

                    if(isset($present->idSolicitud)){
                        $solPresentacion::create(json_decode($presentacion,true));
                    }
                    else {
                        $solPresentacion->idSolicitud = $idSol;
                        $solPresentacion->idEnvasePrimario = $present->emppri;
                        $solPresentacion->idMaterialPrimario = $present->matpri;
                        $solPresentacion->contenido = $present->contpri;
                        $solPresentacion->idUnidad = $present->unidadmedidapri;
                        if ($request->unidadmedidapri == 11) {
                            $solPresentacion->peso = $present->idMedida;
                            $solPresentacion->idMedida = $present->medida;
                        }

                        if ($present->checkempsec == 1) {
                            $solPresentacion->idEnvaseSecundario = $present->empsec;
                            $solPresentacion->idMaterialSecundario = $present->matsec;
                            $solPresentacion->contenidoSecundario = $present->contsec;
                        }

                        $solPresentacion->nombrePresentacion = $present->nombrePres;
                        $solPresentacion->textoPresentacion = $present->textPres;
                        $solPresentacion->idUsuarioCrea = $nit . '@' . $request->ip();
                        $solPresentacion->save();
                    }
                }
            }


            if($request->has('tonos')) {
                if(count($solicitud->tonos)>0) $solicitud->tonos()->delete();
                if ($request->tonos[0] != "") {
                    //$tonos=[];
                    foreach($request->tonos as $tn) {
                        if ($tn != "") {
                            $tono = new Tono();
                            $tono->idSolicitud = $idSol;
                            $tono->tono = $tn;
                            $tono->idUsuarioCrea = $nit . '@' . $request->ip();
                            $tono->save();
                            //array_push($tonos,['tono' => $tn,'idUsuarioCrea'=> $nit.'@'.$request->ip()]);
                        }
                        //$solicitud->tonos()->createMany($tonos);
                    }
                }
            }

            if($request->has('fragancias')) {
                if(count($solicitud->fragancias)>0) $solicitud->fragancias()->delete();
                if ($request->fragancias[0] != "") {
                    //$fragancias=[];
                    foreach($request->fragancias as $fragancia){
                        if($fragancia!=""){
                            $frag= new Fragancia();
                            $frag->idSolicitud=$idSol;
                            $frag->fragancia=$fragancia;
                            $frag->idUsuarioCrea=$nit.'@'.$request->ip();
                            $frag->save();
                        }
                        //array_push($fragancias,['fragancia' => $fragancia,'idUsuarioCrea'=> $nit.'@'.$request->ip()]);
                    }
                    //$solicitud->fragancias()->createMany($fragancias);
                }
            }

            DB::connection('sqlsrv')->commit();
        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'Error en el sistema: No se han podido guardar el paso 2 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'Error en el sistema: No se han podido guardar el paso 2 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'Error en el sistema: No se han podido guardar el paso 2 de la solicitud!'
            ],500);
        }

        return response()->json(['status' => 200,'data' => Crypt::encrypt($idSol) , 'message' => "Se ha guardado el paso 2 de la solicitud existosamente!"],200);

    }

    public function storeStep3(Step3Request $request){


        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud2);

            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $solDetalle = $solicitud->solicitudesDetalle;
                $solDetalle->idUsuarioModificacion=$nit.'@'.$request->ip();
            }
            else{
                return response()->json(['status' => 404, 'errors' => "No se puede guardar el paso 3 de la solicitud sin antes guardar el paso 2!"],404);
            }

            if($request->has('titular')) $solDetalle->idTitular = $request->titular;
            if($request->has('tipoTitular')) $solDetalle->tipoTitular = $request->tipoTitular;
            if($request->has('idProfesional')){
                $solDetalle->idProfesional= $request->idProfesional;
                $solDetalle->idPoderProfesional= $request->poderProf;
            }

            $solDetalle->save();

        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 3 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 3 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 3 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "Se ha guardado el paso 3 de la solicitud existosamente!"],200);
    }

    public function storeStep4(Step4Request $request){
        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud3);

            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $fabricantes = $solicitud->fabricantes;
                $importadores = $solicitud->importadores;
                $usuarioCreacion=$nit.'@'.$request->ip();
            }
            else{
                return response()->json(['status' => 404, 'errors' => "No se puede guardar el paso 4 de la solicitud sin antes guardar el paso 2!"],404);
            }

            //dd(in_array('E36',explode(',',$request->origenFab)));

            if(in_array('E36',explode(',',$request->origenFab))) $tipoFab=2;
            else $tipoFab=1;

            if(count($fabricantes)>0) $res= $solicitud->fabricantes()->delete();
            if($request->has('fabricantes')){
                $fabs=[];
                foreach ($request->fabricantes as $idFab) array_push($fabs,['idFabricante' =>  $idFab,'tipoFabricante'=>$tipoFab, 'idUsuarioCreacion'=> $usuarioCreacion]);
                $solicitud->fabricantes()->createMany($fabs);
            }

            if(count($importadores)>0) $res1= $solicitud->importadores()->delete();
            if(in_array('E36',explode(',',$request->origenFab))) {

                if ($request->has('importadores')) {
                    //si no esta vacio revisar que no sean los mismo fabricantes lo que vienen para guardar!
                    //$array_diff2 = array_diff($request->importadores,$importadores->pluck('idImportador')->toArray());
                    $imps = [];
                    foreach ($request->importadores as $idImp) array_push($imps, ['idImportador' => $idImp, 'idUsuarioCreacion' => $usuarioCreacion]);
                    $solicitud->importadores()->createMany($imps);
                }
            }

        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 4 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 4 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 4 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "Se ha guardado el paso 4 de la solicitud existosamente!"],200);
    }

    public function storeStep5(Request $request){
        //dd($request->all());

        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud4);

            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $distribuidores = $solicitud->distribuidores;
                $usuarioCreacion=$nit.'@'.$request->ip();
            }
            else{
                return response()->json(['status' => 404, 'errors' => "No se puede guardar el paso 5 de la solicitud sin antes guardar el paso 2!"],404);
            }

            if(count($distribuidores)>0) $res= $solicitud->distribuidores()->delete();
            if($request->distTitu==1){
                $solicitud->distribuidorTitular=1;
                $solicitud->idUsuarioModificacion=$nit.'@'.$request->ip();
                $solicitud->save();
            }
            else{
                $solicitud->distribuidorTitular=0;
                $solicitud->idUsuarioModificacion=$nit.'@'.$request->ip();
                $solicitud->save();

                if ($request->has('dist')) {
                    $dist = [];
                    foreach ($request->dist as $idDist) array_push($dist, ['idDistribuidor' => explode(',', $idDist)[0], 'idPoderDistribuidor' => explode(',', $idDist)[1], 'idUsuarioCreacion' => $usuarioCreacion]);
                    $solicitud->distribuidores()->createMany($dist);
                }
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 5 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 5 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 5 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "Se ha guardado el paso 5 de la solicitud existosamente!"],200);
    }

    public function storeStep6(Request $request){

        $rules=[
            'file-es'    => 'sometimes|required|min:1',
        ];
        $messages=[
            'file-es.sometimes'       => 'Debe adjuntar al menos un documento, para poder guardar el paso 6!',
        ];

        $v = Validator::make($request->all(),$rules,$messages);

        if ($v->fails()){
            $msg = "<ul class='text-warning'>";
            foreach ($v->messages()->all() as $err) {
                $msg .= "<li>$err</li>";
            }
            $msg .= "</ul>";
            return response()->json(['errors'=>$msg],400);
        }

        //dd($request->all());
        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();

        try{
            $edicion=false;
            $urlsEliminar=[];
            $idSolicitud=Crypt::decrypt($request->idSolicitud5);

            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $usuarioCreacion=$nit.'@'.$request->ip();
                if($request->has('docGuardado')){
                    $edicion=true;
                    foreach ($solicitud->detalleDocumentos as $detalleDocumento)
                        if(!in_array($detalleDocumento->idDoc, $request->docGuardado)){
                            array_push($urlsEliminar,$detalleDocumento->urlArchivo);
                            $detalleDocumento->delete();
                            DB::connection('sqlsrv')->commit();
                        }
                }
                else{
                    $solicitud->detalleDocumentos()->detach();
                }

                //dd(count($urlsEliminar));

                $saveDocs=$this->guardarDocumentos($solicitud->idSolicitud,$request->file('file-es'),$usuarioCreacion,null,$urlsEliminar,$edicion);
                if($saveDocs==0){
                    DB::rollback();
                    Session::flash('msnError','Error en el sistema: No se han podido guardar los documentos adjuntos, por favor vuelva intentarlo!');
                    return redirect()->route('get.cospreregistro.nuevasolicitud');
                }
            }
            else{
                return response()->json(['status' => 404, 'errors' => "No se ha podido guardar el paso 6 de la solicitud!"],404);
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'Error en el sistema: No se han podido guardar el paso 6 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'Error en el sistema: No se han podido guardar el paso 6 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'Error en el sistema: No se han podido guardar el paso 6 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();
        Session::put('idSolCosPre',$idSolicitud);
        return response()->json(['status' => 200, 'message' => "Se ha guardado el paso 6 de la solicitud existosamente!"],200);
    }

    public function deleteFab(Request $request){

        $rules=[
            'idSolicitud3' => 'required',
            'idFab'       => 'required',
            'fabOrImp'     => 'required',
        ];

        $v = Validator::make($request->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        DB::connection('sqlsrv')->beginTransaction();

        try{

            $varString = ($request->fabOrImp=='fab')?'fabricante':'importador';

            $idSolicitud=Crypt::decrypt($request->idSolicitud3);

            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                if($request->fabOrImp=='fab'){
                    $fabricantes = $solicitud->fabricantes->where('idFabricante', $request->idFab)->first();
                    if (!empty($fabricantes)) Fabricante::deleteFab($idSolicitud,$request->idFab);
                    else return response()->json(['status' => 404, 'errors' => "No se ha eliminado el ". $varString." porque la solicitud no se ha encontrado!"],404);
                }
                if($request->fabOrImp=='imp'){
                    $importadores = $solicitud->importadores->where('idImportador', $request->idFab)->first();
                    if (!empty($importadores)) Importador::deleteFab($idSolicitud,$request->idFab);
                    else return response()->json(['status' => 404, 'errors' => "No se ha eliminado el ". $varString." porque la solicitud no se ha encontrado!"],404);
                }
            }
            else{
                return response()->json(['status' => 404, 'errors' => "No se ha eliminado el ". $varString." porque la solicitud no se ha encontrado!"],404);
            }
        }
        catch (ModelNotFoundException $e) {
            Log::error('Error model Solicitud not found', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'No se ha eliminado el fabricante de la solicitud porque la solicitud no se ha encontrado!'
            ], 400);
        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 4 de la solicitud!'
            ],500);

        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 4 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "Se ha eliminado el ".$varString." de la solicitud exitosamente!"],200);
    }

    public function deleteDist(Request $request){

        $rules=[
            'idSolicitud4'   => 'required',
            'idDist'         => 'required',
        ];

        $v = Validator::make($request->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud4);

            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $distribuidor = $solicitud->distribuidores->where('idDistribuidor', explode(',',$request->idDist)[0])->where('idPoderDistribuidor',explode(',',$request->idDist)[1])->first();
                if (!empty($distribuidor)) $distribuidor->delete();
                else return response()->json(['status' => 404, 'errors' => "No se ha eliminado el distribuidor porque la solicitud no se ha encontrado!"],404);
            }
            else{
                return response()->json(['status' => 404, 'errors' => "No se ha eliminado el distribuidor porque la solicitud no se ha encontrado!"],404);
            }
        }
        catch (ModelNotFoundException $e) {
            Log::error('Error model Solicitud not found', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'errors' => 'No se ha eliminado el distribuidor de la solicitud porque la solicitud no se ha encontrado!'
            ], 400);
        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'No se ha eliminado el distribuidor de la solicitud porque la solicitud no se ha encontrado'
            ],500);

        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'No se ha eliminado el distribuidor de la solicitud porque la solicitud no se ha encontrado'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "Se ha eliminado el distribuidor de la solicitud exitosamente!"],200);
    }

    public function storeMain(Request $request){

        //dd($request->all());
        $nit = Session::get('user');
        $validado = true;

        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Session::get('idSolCosPre');
            $usuarioModificacion=$nit.'@'.$request->ip();

            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                if(empty($solicitud->solicitudesDetalle)) $validado=false;
                elseif(is_null($solicitud->solicitudesDetalle->idMarca) || $solicitud->solicitudesDetalle->idMarca=='') $validado=false;


                if($solicitud->tipoSolicitud==2 || $solicitud->tipoSolicitud==3){
                    if(empty($solicitud->solicitudesDetalle->detallesCosmetico)) $validado=false;
                    elseif(is_null($solicitud->solicitudesDetalle->detallesCosmetico->idClasificacion)
                           || $solicitud->solicitudesDetalle->detallesCosmetico->idClasificacion=='')
                           $validado=false;
                    elseif(is_null($solicitud->solicitudesDetalle->detallesCosmetico->idFormaCosmetica)
                           || $solicitud->solicitudesDetalle->detallesCosmetico->idFormaCosmetica=='')
                           $validado=false;

                    if(count($solicitud->presentaciones)==0)  $validado=false;
                }
                elseif($solicitud->tipoSolicitud==4 || $solicitud->tipoSolicitud==5){
                    if(empty($solicitud->solicitudesDetalle->detallesHigienicos))
                        $validado=false;
                }
                if(count($solicitud->fabricantes)==0) $validado=false;
                if($solicitud->tipoSolicitud==5 || $solicitud->tipoSolicitud==3) {
                    if (count($solicitud->importadores) == 0) $validado = false;
                }
                if($solicitud->distribuidorTitular==0){
                    if(count($solicitud->distribuidores)==0) $validado=false;
                }

                if(count($solicitud->detalleDocumentos)==0) $validado=false;

                if($validado){
                    if($solicitud->estado==4){
                        $solicitud->estado=11;
                        $solicitud->fechaSubsanacion = date('Y-m-d H:i:s');

                            $seg = new SeguimientoSolPre();
                            $seg->idSolicitud=$idSolicitud;
                            $seg->idEstado=11;
                            $seg->seguimiento='Solicitud subsanada por usuario';
                            $seg->usuarioCreacion=$usuarioModificacion;
                            $seg->save();

                    }
                    else{
                        $solicitud->estado=1;
                        $solicitud->fechaEnvio = date('Y-m-d H:i:s');

                            $seg = new SeguimientoSolPre();
                            $seg->idSolicitud=$idSolicitud;
                            $seg->idEstado=1;
                            $seg->seguimiento='Solicitud enviada por usuario';
                            $seg->usuarioCreacion=$usuarioModificacion;
                            $seg->save();

                    }
                    $solicitud->idUsuarioModificacion=$usuarioModificacion;
                    $solicitud->save();
                }
                else{
                    //dd($solicitud);
                    return response()->json(['status' => 400, 'errors' => "No se han podido enviar la solicitud porque uno de los pasos tiene información incompleta o no ha sido guardado!"],400);
                }
            }
            else{
                return response()->json(['status' => 404, 'errors' => "No se han podido enviar la solicitud porque la solicitud no se ha encontrado!"],404);
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'Error en el sistema: No se han podido enviar la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'Error en el sistema: No se han podido enviar la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'errors'=>'Error en el sistema: No se han podido enviar la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();


        if($solicitud->estado==1) Session::put('msnExito','Su solicitud fue recibida e ingresada existosamente!');
        else Session::put('msnExito','Su solicitud fue recibida y subsanada existosamente!');
        //Session::forget('idSolCosPre',$idSolicitud);
        //return redirect()->route('cospresolicitud.lista-sol');
        return response()->json(['status' => 200, 'message' => "Su solicitud fue recibida e ingresada existosamente!",'route'=>'cospresolicitud.lista-sol'],200);
    }


    public function store(Request $request){

       //dd($request->all());

        $rules=[
            'tipoTramite'    => 'required',
            'mandamiento'    => 'required',
            'nomProd'        => 'required',
            'marca'          => 'required',
            'areaApli'       => 'sometimes',
            'clasificacion'  => 'required',
            'formacos'       => 'sometimes',
            'presentaciones' => 'required|array|min:1',
            'idDenomiacion'  => 'required|array|min:1',
            'porcentaje'     => 'required|array|min:1',
            'tonos'          => 'sometimes|array|min:1',
            'fragancias'     => 'sometimes|array|min:1',
            'tipoTitular'    => 'required',
            'titular'        => 'required',
            'poderProf'      => 'required',
            'idProfesional'  => 'required',
            'fabricantes'    => 'required|array|min:1',
            'importadores'   => 'required_if:tipoTramite,4|required_if:tipoTramite,5|array|min:1',
            'dist'           => 'required|array|min:1'
        ];

        $v = Validator::make($request->all(),$this->rules());

        if ($v->fails()){
            return redirect()->route('get.cospreregistro.nuevasolicitud')->with(['errors' => $v->errors()]);
        }


        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();

        try{

            //Guardo Solictud
            $solicitud = new Solicitud();
            $solicitud->tipoSolicitud=$request->tipoTramite;
            $solicitud->nitSolicitante=Session::get('user');
            $solicitud->idMandamiento=$request->mandamiento;
            $solicitud->idUsuarioCrea= $nit.'@'.$request->ip();
            if($request->coempaque==1){
                $solicitud->descripcionCoempaque = $request->detcoempaque;
            }
            $solicitud->solicitudPortal=1;
            $solicitud->save();

            //Guardo el detalle con el id de la solicitud ingresada.
            $solDetalle = new SolicitudDetalle();
            $solDetalle->idSolicitud = $solicitud->idSolicitud;
            $solDetalle->nombreComercial=$request->nomProd;
            $solDetalle->idMarca = $request->marca;
            $solDetalle->idTitular = $request->titular;
            $solDetalle->tipoTitular = $request->tipoTitular;
            $solDetalle->idProfesional= $request->idProfesional;
            $solDetalle->idPoderProfesional= $request->poderProf;
            if($request->tipoTramite==2 || $request->tipoTramite==4){
                $solDetalle->idPais=2;
            }
            else if($request->tipoTramite==3 || $request->tipoTramite==5){
                $solDetalle->idPais=$request->paisOrigen;
                $solDetalle->numeroRegistroExtr=$request->numRegistro;
                $solDetalle->fechaVencimiento=date('Y-m-d',strtotime($request->fechaVen));
            }
            $solDetalle->idUsuarioCrea=$nit.'@'.$request->ip();
            $solDetalle->save();

            //Guardo detalle de Cosmetico o higienico
            if($request->tipoTramite==2 || $request->tipoTramite==3){
                $solDetalle->detallesCosmetico()->create([
                    'idClasificacion' => $request->clasificacion,
                    'idFormaCosmetica' => $request->formacos
                ]);

                $formCos=[];
                for($i=0; $i< count($request->idDenomiacion); $i++) array_push($formCos,['idDenominacion' => $request->idDenomiacion[$i],'porcentaje' => (float) $request->porcentaje[$i], 'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                $solicitud->formulasCosmetico()->createMany($formCos);
            }
            else if($request->tipoTramite==4 || $request->tipoTramite==5){
                $solDetalle->detallesHigienicos()->create([
                    'idClasificacion' => $request->clasificacion,
                    'uso' => $request->uso
                ]);

                $formHig=[];
                for($i=0; $i< count($request->idDenomiacion); $i++) array_push($formHig,['idDenominacion' => $request->idDenomiacion[$i],'porcentaje' => (float) $request->porcentaje[$i], 'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                $solicitud->formulasHigienicos()->createMany($formHig);
            }

            //fabricantes
            $fabs=[];
            foreach ($request->fabricantes as $idFab) array_push($fabs,['idFabricante' =>  $idFab, 'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
            $solicitud->fabricantes()->createMany($fabs);

            if($request->has('importadores')) {
                $imps = [];
                foreach ($request->importadores as $idImp) array_push($imps, ['idImportador' => $idImp, 'idUsuarioCreacion' => $nit . '@' . $request->ip()]);
                $solicitud->importadores()->createMany($imps);
            }

            $dist=[];
            foreach ($request->dist as $idDist) array_push($dist,['idDistribuidor' => explode(',',$idDist)[0] ,'idPoderDistribuidor' => explode(',',$idDist)[1], 'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
            $solicitud->distribuidores()->createMany($dist);


            if($request->has('tonos')) {
                if ($request->tonos[0] != "") {
                    $tonos=[];
                    foreach($request->tonos as $tn) array_push($tonos,['tono' => $tn,'idUsuarioCrea'=> $nit.'@'.$request->ip()]);
                    $solicitud->tonos()->createMany($tonos);
                }
            }

            if($request->has('fragancias')) {
                if ($request->fragancias[0] != "") {
                    $fragancias=[];
                    foreach($request->fragancias as $fragancia) array_push($fragancias,['fragancia' => $fragancia,'idUsuarioCrea'=> $nit.'@'.$request->ip()]);
                    $solicitud->fragancias()->createMany($fragancias);
                }
            }

            foreach ($request->presentaciones as $presentacion){
                $present = json_decode($presentacion);
                $solPresentacion = new Presentacion();
                $solPresentacion->idSolicitud       =$solicitud->idSolicitud;
                $solPresentacion->idEnvasePrimario  =$present->emppri;
                $solPresentacion->idMaterialPrimario= $present->matpri;
                $solPresentacion->contenido         = $present->contpri;
                $solPresentacion->idUnidad          = $present->unidadmedidapri;
                if($request->unidadmedidapri==11){
                    $solPresentacion->peso = $present->idMedida;
                    $solPresentacion->idMedida = $present->medida;
                }

                if($present->checkempsec==1) {
                    $solPresentacion->idEnvaseSecundario   = $present->empsec;
                    $solPresentacion->idMaterialSecundario = $present->matsec;
                    $solPresentacion->contenidoSecundario  = $present->contsec;
                }

                $solPresentacion->nombrePresentacion = $present->nombrePres;
                $solPresentacion->textoPresentacion  = $present->textPres;
                $solPresentacion->idUsuarioCrea      = $nit.'@'.$request->ip();
                $solPresentacion->save();
            }


            $saveDocs=$this->guardarDocumentos($solicitud->idSolicitud,$request->file('file-es'),$nit.'@'.$request->ip(),$request->img_val);
            if($saveDocs==0){
                DB::rollback();
                Session::flash('msnError','Error en el sistema: No se han podido guardar los documentos adjuntos, por favor vuelva intentarlo!');
                return redirect()->route('get.cospreregistro.nuevasolicitud');
            }

        }

        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            Session::flash('msnError','Error en el sistema: No se han podido guardar la solicitud !');
            return redirect()->route('get.cospreregistro.nuevasolicitud');
        }
        catch (Throwable $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            Session::flash('msnError','Error en el sistema: No se han podido guardar la solicitud !');
            return redirect()->route('get.cospreregistro.nuevasolicitud');
        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            Session::flash('msnError','Error en el sistema: No se han podido guardar la solicitud !');
            return redirect()->route('get.cospreregistro.nuevasolicitud');
        }

        DB::connection('sqlsrv')->commit();
        Session::put('msnExito','Su solicitud fue recibida e ingresada existosamente!');
        //dd(Session::get('msnExito'));
        return redirect()->route('cospresolicitud.lista-sol');


    }

    public function guardarDocumentos($idSolicitudNew,$files,$idUsuarioCrea,$img_val,$urls,$edit){
        /* FUNCION PARA GUARDAR LOS ARCHIVOS */
        DB::connection('sqlsrv')->beginTransaction();

        try {

            $npath=$this->pathfiles;

            $filesystem= new Filesystem();
            if($filesystem->exists($npath)) {
                if ($filesystem->isWritable($npath)) {
                    $newpath = $npath . $idSolicitudNew;
                    if($filesystem->isDirectory($newpath)){
                        if(count($urls)>0){
                            foreach ($urls as $url){
                                $filesystem->delete($url);
                            }
                        }
                        else{
                            if($edit==false) $filesystem->cleanDirectory($newpath);
                        }

                    }
                    else{
                        File::makeDirectory($newpath, 0777, true, true);
                    }

                    if (!empty($files)) {
                        $indexs=array_keys($files);
                        for ($i = 0; $i < count($indexs); $i++) {
                            $index = $indexs[$i];
                            $item = Item::findOrFail($index);
                            $name = mb_strtoupper($item->nombreItem, 'UTF-8'). '.' . $files[$index]->getClientOriginalExtension();
                            $type = $files[$index]->getClientMimeType();
                            $files[$index]->move($newpath, $name);

                            $detalleDoc = new DetalleDocumento();
                            $detalleDoc->urlArchivo = $newpath.'\\'.$name;
                            $detalleDoc->tipoArchivo = $type;
                            $detalleDoc->idUsuarioCrea = $idUsuarioCrea;
                            $detalleDoc->save();

                            $detalleDoc->documentosSol()->create([
                                'idSolicitud' => $idSolicitudNew,
                                'idItemDoc' => $item->idItem
                            ]);
                        }

                        //$img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img_val));
                        //$filepath = $newpath."/confirmacion-solicitud".$idSolicitudNew.".png";

                        // Save the image in a defined path
                        //file_put_contents($filepath,$img);

                        DB::connection('sqlsrv')->commit();
                        return 1;
                    } else {
                        return 1;
                        //DB::connection('sqlsrv')->rollback();
                        //throw new Exception("Error: Documentos adjuntos no han podido ser guardado junto ha la solicitud, vuelva a intentar a realizar el tramite", 1);
                        //return 0;
                    }
                } else {
                    DB::connection('sqlsrv')->rollback();
                    //throw new Exception("Error: No se ha podido guardar los documentos adjuntos, vuelva a intentar a realizar el tramite", 1);
                    return 0;

                }
            }
        }
        catch (Throwable $e) {
            DB::connection('sqlsrv')->rollback();
            //throw $e;
            return 0;
        }
        catch(Exception $e){
            DB::connection('sqlsrv')->rollback();
            //throw $e;
            return 0;
            Session::flash('msnError', $e->getMessage());
        }


    }/* /FIN DE LA FUNCION DE GUARDAR LOS ARCHIVOS*/

    public function getSolicitudesByUsuario(Request $request){
        //
        $nit=Session::get('user');
        $solicitudes=VwSolicitudesPortal::getSolicitudes($nit);

        return Datatables::of($solicitudes)
            ->addColumn('resolucion', function ($dt) {
                $r1='<a href="'.route('comprobante.cospre',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-info btn-xs" target="_blank" title="Comprobante de Solicitud"><i class="fa fa-print"></i></a>';
                $r2='<a href="'.route('cospresolicitud.edit',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-primary btn-xs" title="Editar"><i class="fa fa-edit"></i></a>';
                $r3='<a href="'.route('cospresolicitud.edit',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-warning btn-xs" title="Subsanar"><i class="fa fa-edit"></i></a>';

                if($dt->idEstado!=0){
                    if($dt->idEstado==4) return $r1.' '.$r3; else return $r1;
                }elseif ($dt->idEstado==0) { //Agregar boton para Desistir del tramite tipo borrador
                    return $r2.' '.'<button value="'.Crypt::encrypt($dt->idSolicitud).'" class="btn btn-xs btn-danger btnEliminarSolCos" title="Eliminar Solicitud");"><i class="fa fa-trash"></i></button>';
                }
            })
            ->addColumn('linkresol',function ($dt){
                   if($dt->countObs>0)   return '<a href="'.route('imprimir.resolob.cos',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-info btn-circle btn-xs" title="Resoluciòn Observada"><i class="fa fa-paste "></i></a>';
                   else return '';
            })
            ->filter(function($query) use ($request){

                if($request->has('nsolicitud')){
                    $query->where('numeroSolicitud','=',$request->get('nsolicitud'));

                }

                if($request->has('nregistro')){
                    $query->where('idProducto','=',$request->get('nregistro'));
                }
                elseif($request->has('nomComercial')){
                    $query->where('nombreComercial','like','%'.$request->get('nomComercial').'%');
                }

                if($request->has('idTra')){
                    if($request->get('idTra')!="0"){
                        $query->where('idTramite','=',$request->get('idTra'));
                    }
                }
                if($request->has('estado')){
                    if($request->get('estado')!="sinseleccion")
                        $query->where('idEstado','=',$request->get('estado'));
                }
                $query->where('idEstado','<>','10'); //Evitando que se listen solicitudes desistidas

            })
            ->make(true);
    }

    public function edit($idSolicitud)
    {

        //$data=[];

        $solicitud = Solicitud::findOrFail(Crypt::decrypt($idSolicitud));
        $title='';
        $observacionesdic=null;

        if($solicitud->estado==4){
            $title='Subsanación de solicitud ' . $solicitud->idSolicitud . ' - ' . $solicitud->solicitudesDetalle->nombreComercial;
            $observacionesdic=Solicitud::getLastObservacion($solicitud->idSolicitud);
            //dd($observacionesdic);

        }
        else $title='Edición de solicitud ' . $solicitud->idSolicitud . ' - ' . $solicitud->solicitudesDetalle->nombreComercial;

        $data = ['title' => $title,
            'subtitle' => '',
            'breadcrumb' => [
                ['nom' => 'Cosmeticos', 'url' => '#'],
                ['nom' => 'Solicitudes nuevo registro', 'url' => '#']
            ]];



        try {

            $solicitud = Solicitud::findOrFail(Crypt::decrypt($idSolicitud));
            $data['solicitud'] = $solicitud;
            $data['itemsDoc']=null;
            $data['observacionesdic']=$observacionesdic;



            $client = new Client();
            $res = $client->request('POST', $this->url . 'cospre/solicitudpre/get-data', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' => [
                    'idSolicitud' => trim($solicitud->idSolicitud),
                ]
            ]);



            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $data['soldata'] = $r->data;
            } else if ($r->status == 400 || $r->status == 500) {
                Session::flash('msnError','Error en el sistema: No se mostrar la solicitud '.$solicitud->numeroSolicitud.', por favor vuelva intentarlo!');
                return back();
            }

            $client1 = new Client();
            $res1 = $client1->request('POST', $this->url . 'cospre/solicitudpre/items-by-tramite', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'form_params' => [
                    'idTramite' => $solicitud->tipoSolicitud
                ]
            ]);

            $r1 = json_decode($res1->getBody());

            //dd($r1);

            if ($r1->status == 200) {
                $data['documentos'] = $r1->data;
            } else {
                $data['documentos'] = null;
            }



        } catch (\Exception $e) {
            //throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
        }
        Session::put('idSolCosPre', $idSolicitud);

        $idItemDoc = [];
        if (count($solicitud->detalleDocumentos) > 0){
           for ($i = 0; $i < count($solicitud->detalleDocumentos); $i++) {
                $idItemDoc[$i] = $solicitud->detalleDocumentos[$i]->pivot->idItemDoc;
            }
            $data['itemsDoc']=$idItemDoc;
        }

      //dd($data);
      return view('cosmeticos.nuevoregistro.edit',$data);
    }

    public function desistirSol($idSolicitud){

        $idSoli = Crypt::decrypt($idSolicitud);
        //dd($idSolicitud.' '.$idSoli);

        $solicitud = Solicitud::findOrFail($idSoli);
        //dd($solicitud);
        $solicitud->estado = (int)10;
        $fecha = Carbon::now();
        //dd($fecha);
        $solicitud->fechaEnvio = Carbon::now();
        //dd($solicitud);
        $solicitud->save();

        $seg = new SeguimientoSolPre();
        $seg->idSolicitud=$idSoli;
        $seg->idEstado=10;
        $seg->seguimiento='Solicitud desistida por usuario';
        $seg->usuarioCreacion=$solicitud->nitSolicitante;
        $seg->save();
    }

    public function showDocByRequisito($idSolicitud,$idItemDoc){

        $docSol=DocumentoSol::where('idSolicitud',Crypt::decrypt($idSolicitud))->where('idItemDoc',Crypt::decrypt($idItemDoc))->first();

        $archivo = $docSol->detalleDocumento;

        if($archivo!=null){
            if($archivo->tipoArchivo==='application/pdf'){
                //parte nueva
                //$archivo->tipoArchivo='application/pdf';
                if (File::isFile($archivo->urlArchivo))
                {
                    $file = File::get($archivo->urlArchivo);
                    $response = Response::make($file, 200);
                    // using this will allow you to do some checks on it (if pdf/docx/doc/xls/xlsx)
                    $response->header('Content-Type', 'application/pdf');
                    /*
                    $response->header([
                      'Content-Type'=> 'application/pdf',
                      'Content-Disposition' => 'inline; filename="Arte#"'
                      ]);*/
                    return $response;
                }
            }
            // Or to download
            else{
                if (File::isFile($archivo->urlArchivo))
                {

                    return Response::download($archivo->urlArchivo);
                }
            }
        }

    }
}
