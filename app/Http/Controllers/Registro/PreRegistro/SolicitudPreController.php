<?php

namespace App\Http\Controllers\Registro\PreRegistro;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Filesystem\Filesystem;


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

use App\Traits\DiaHabilTrait;
use App\Models\Registro\Sol\Solicitud;
use App\Models\Registro\Sol\RequisitosDocumento;
use App\Models\Registro\Sol\PreDocumentos;
use App\Models\Registro\Sol\Paso2ProductoGenerales;
use App\Models\Registro\Sol\Paso3Apoderado;
use App\Models\Registro\Sol\Paso3Profesional;
use App\Models\Registro\Sol\Paso3Representante;
use App\Models\Registro\Sol\Paso3Titular;
use App\Models\Registro\Sol\Paso3Interesado;
use App\Models\Registro\Sol\Paso4FabPrincipal;
use App\Models\Registro\Sol\Paso4FabAlternos;
use App\Models\Registro\Sol\Paso4LabAcondicionador;
use App\Models\Registro\Sol\Paso4AcondicionadorPoderMaquila;
use App\Models\Registro\Sol\Paso4AlternoPoderMaquila;
use App\Models\Registro\Sol\Paso4PrincipalPoderMaquila;
use App\Models\Registro\Sol\Paso5CertManufactura;
use App\Models\Registro\Sol\Paso2\PrincipioActivo;
use App\Models\Registro\Sol\Paso2\EmpaquePresentacion;
use App\Models\Registro\Sol\Paso2\VidaUtilEmpaque;
use App\Models\Registro\Sol\Paso6\BpmAcondicionador;
use App\Models\Registro\Sol\Paso6\BpmAlterno;
use App\Models\Registro\Sol\Paso6\BpmPrincipal;
use App\Models\Registro\Sol\Paso6\BmpFabPrinActivo;
use App\Models\Registro\Sol\Paso6\BpmRelacionados;
use App\Models\Registro\Sol\Paso7\Distribuidor;
use App\Models\Registro\Sol\Paso8\MaterialEmpaque;
use App\Models\Registro\Sol\Paso9\Farmacologia;
use App\Models\Registro\Sol\Paso9\DetalleFarmacologia;
use App\Models\Registro\Sol\SolicitudSeguimiento;


class SolicitudPreController extends Controller
{
    private $url=null;
    private $token=null;
    use DiaHabilTrait;
    public function __construct() {
        $this->url = Config::get('app.api');
        $this->token = Config::get('app.token');
    }

    //listado de solicitudes
    /*
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
    }*/


    public function storeStep1y2(Step1y2Request $request){
        //dd($request->presentaciones);
        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();
        try{

            $idSolicitud=Crypt::decrypt($request->idSolicitud);

            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $solicitud->idUsuarioModifica=$nit.'@'.$request->ip();
                if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }
            }
            else{
                $solicitud = new Solicitud();
                $solicitud->idUsuarioCreacion= $nit.'@'.$request->ip();
                $solicitud->estadoDictamen = 0;
            }
            $solicitud->fechaSolicitud = date('Y-m-d');
            $solicitud->nitSolicitante=Session::get('user');
            $solicitud->mandamiento=$request->mandamiento;
            $solicitud->save();

            if($idSolicitud!=0){
                 $idSol2= $solicitud->idSolicitud;
            }else{
                 $idSol2= $solicitud::all()->last()->idSolicitud;
            }
            if(!empty($solicitud->solicitudesDetalle)){
                //return JsonResponse::create(['data' => empty($solicitud->solicitudesDetalle)],200);
                $solDetalle = $solicitud->solicitudesDetalle;
                $solDetalle->idUsuarioModifica=$nit.'@'.$request->ip();
            }else{
                $solDetalle = new Paso2ProductoGenerales();
                $solDetalle->idSolicitud = $idSol2;
                $solDetalle->idUsuarioCreacion=$nit.'@'.$request->ip();
            }
            //Guardo el detalle con el id de la solicitud ingresada.
            $solDetalle->nombreComercial=$request->nom_prod;
            if($request->has('innovador')) $solDetalle->innovador= $request->innovador;
            if($request->has('origen')){
                $solDetalle->origenProducto= $request->origen;
                if($request->origen==4){
                    $solDetalle->idPaisReconocimiento=$request->paisReconocimiento;
                    $solDetalle->numeroRegistroReconocimiento= $request->noregistrorecono;
                }
            }
            if($request->has('tipoMedicamento')) $solDetalle->tipoMedicamento= $request->tipoMedicamento;
            if($request->has('formafarm')) $solDetalle->formaFarmaceutica= $request->formafarm;
            if($request->has('viaAdmin')) $solDetalle->viaAdmon= $request->viaAdmin;
            if($request->has('condAlmacenamiento')) $solDetalle->condicionesAlmacenaje= $request->condAlmacenamiento;
            //if($request->has('vidaUtil')) $solDetalle->vidaUtil= $request->vidaUtil;
            if($request->has('excipientes')) $solDetalle->excipientes= $request->excipientes;
            if($request->has('udosis')) $solDetalle->unidadDosis= $request->udosis;
            if($request->has('modalidad')) $solDetalle->modalidadVenta= $request->modalidad;
            if($request->has('bioequi')) $solDetalle->bioequivalente= $request->bioequi;
            if($request->has('formula')) $solDetalle->formula= $request->formula;
            if($request->has('patente')) $solDetalle->poseePatentes= $request->patente;
            $solDetalle->save();

            if(count($solicitud->principiosActivos)>0) $solicitud->principiosActivos()->delete();
            if($request->has('idMateriasP')){
                $nom = $request->nombreMateria; $unidad =$request->idUnidadesM; $concentracion=$request->concentracion;
                $nombreUni = $request->nombreUnidad;
                foreach($request->idMateriasP as $key => $value){
                    $materiaSol = new PrincipioActivo();
                    $materiaSol->idSolicitud =  $idSol2;
                    $materiaSol->idMateriaPrima=$value;
                    $materiaSol->nombreMateriaPrima=$nom[$key];
                    $materiaSol->concentracion = $concentracion[$key];
                    $materiaSol->unidadMedida = $unidad[$key];
                    $materiaSol->nombreUnidadMedida = $nombreUni[$key];
                    $materiaSol->idUsuarioCreacion=$nit.'@'.$request->ip();
                    $materiaSol->save();
                }
            }

            if(count($solicitud->empaquesPresentacion)>0) $solicitud->empaquesPresentacion()->delete();
            if($request->has('presentaciones')){

                foreach ($request->presentaciones as $presentacion) {
                    $present = json_decode($presentacion);
                    $solPresentacion = new EmpaquePresentacion();
                    $solPresentacion->idSolicitud = $idSol2;
                    $solPresentacion->tipoPresentacion=$present->tipoP;
                    $solPresentacion->accesorio=$present->accesorios;
                    $solPresentacion->idMateria=$present->material;
                    $solPresentacion->idColor=$present->color;
                    $solPresentacion->textoPresentacion=$present->textPres;
                    $solPresentacion->presentacionEspecial=$present->presentacionespecial;
                    $solPresentacion->nombreMaterial = $present->nombreMaterial;
                    $solPresentacion->nombreColor =$present->nombreColor;

                    $solPresentacion->empaquePrimario=$present->empa1;
                    $solPresentacion->cantidadPrimaria=$present->por1;
                    $solPresentacion->contenidoPrimario=$present->mat1;

                    if($present->checkempsec==1){
                        $solPresentacion->empaqueSecunadrio=$present->empa2;
                        $solPresentacion->cantidadSecundaria=$present->por2;
                        $solPresentacion->contenidoSecundario=$present->empa1;
                    }

                    if($present->checkempter==1){
                        $solPresentacion->empaqueTerciario=$present->empa3;
                        $solPresentacion->cantidadTerciaria=$present->por3;
                        $solPresentacion->contenidoTerciario=$present->empa2;
                    }

                    $solPresentacion->idUsuarioCreacion = $nit . '@' . $request->ip();
                    $solPresentacion->save();
                }

            }

            if(count($solicitud->vidaUtil)>0) $solicitud->vidaUtil()->delete();
            if($request->has('idEmpaquevida') && $request->has('materialVidaId')){
                $idempaque = $request->idEmpaquevida; $nombreempaque =$request->nombreEmpaquevida;
                $idmate = $request->materialVidaId; $nombremate=$request->materialnombreVidaId;
                $utilvida=$request->utilvida;
                $observacionvida=$request->observacionvida;
                $idperid=$request->idperiodo;
                $idcolor = $request->idcolorvida;
                $nombreColor = $request->nombrecolorvida;
                foreach($request->idEmpaquevida as $key => $value){
                    $utilvidaempaque = new VidaUtilEmpaque();
                    $utilvidaempaque->idSolicitud =  $idSol2;
                    $utilvidaempaque->empaquePrimario=$value;
                    $utilvidaempaque->idMaterial=$idmate[$key];
                    $utilvidaempaque->vidaUtil = $utilvida[$key];
                    $utilvidaempaque->nombreMaterial = $nombremate[$key];
                    $utilvidaempaque->nombrePrimario = $nombreempaque[$key];
                    $utilvidaempaque->observacion = $observacionvida[$key];
                    $utilvidaempaque->idPeriodo = $idperid[$key];
                    $utilvidaempaque->idColor = $idcolor[$key];
                    $utilvidaempaque->nombreColor = $nombreColor[$key];
                    $utilvidaempaque->idUsuarioCreacion=$nit.'@'.$request->ip();
                    $utilvidaempaque->save();
                }
            }

        DB::connection('sqlsrv')->commit();

        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 2 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 2 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 2 de la solicitud!'
            ],500);
        }
        return response()->json(['status' => 200,'data' => Crypt::encrypt($idSol2) , 'message' => "¡Se ha guardado el paso 2 de la solicitud existosamente!", 'paso' => '1'],200);

    }


    public function storeStep3(Step3Request $request){


        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud2);

            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }
            }else{
                return response()->json(['status' => 422, 'errors' => "No se puede guardar el paso 3 de la solicitud sin antes guardar el paso 2!"],422);
            }

            //---------------TITULAR--------------------
            if(!empty($solicitud->titular)){
                $titularSol = $solicitud->titular;
                $titularSol->idUsuarioModifica=$nit.'@'.$request->ip();
            }else{
                $titularSol = new Paso3Titular();
                $titularSol->idSolicitud=$idSolicitud;
                $titularSol->idUsuarioCreacion=$nit.'@'.$request->ip();
            }
            $titularSol->idTitular=$request->titular;
            $titularSol->tipoTitular=$request->tipoTitular;
            $titularSol->save();

            //---------------Representante lega---------
            if(!empty($solicitud->representante)){
                if($request->has('idPresentanteLegal')){
                    $represeSol = $solicitud->representante;
                    $represeSol->idUsuarioModifica=$nit.'@'.$request->ip();
                    $represeSol->idRepresentante=$request->idPresentanteLegal;
                    $represeSol->poderRepresentante=$request->poderRL;
                    $represeSol->save();
                }else{
                    $solicitud->representante->delete();
                }
            }else{
                if($request->has('poderRL')){
                    $represeSol = new Paso3Representante();
                    $represeSol->idSolicitud=$idSolicitud;
                    $represeSol->idUsuarioCreacion=$nit.'@'.$request->ip();
                    $represeSol->idRepresentante=$request->idPresentanteLegal;
                    $represeSol->poderRepresentante=$request->poderRL;
                    $represeSol->save();
                }
            }

            //---------------APODERADO---------
            if(!empty($solicitud->apoderado)){
                if($request->has('idApoderado')){
                    $apoderadoSol = $solicitud->apoderado;
                    $apoderadoSol->idUsuarioModifica=$nit.'@'.$request->ip();
                    $apoderadoSol->idApoderado=$request->idApoderado;
                    $apoderadoSol->poderApoderado=$request->poderApo;
                    $apoderadoSol->save();
                }else{
                    $solicitud->apoderado->delete();
                }
            }else{
                if($request->has('poderApo')){
                    $apoderadoSol = new Paso3Apoderado();
                    $apoderadoSol->idSolicitud=$idSolicitud;
                    $apoderadoSol->idUsuarioCreacion=$nit.'@'.$request->ip();
                    $apoderadoSol->idApoderado=$request->idApoderado;
                    $apoderadoSol->poderApoderado=$request->poderApo;
                    $apoderadoSol->save();
                }
            }


            //---------------PROFESIONAL RESPONSABLE---------
            if(!empty($solicitud->profesional)){
                $profesionalSol = $solicitud->profesional;
                $profesionalSol->idUsuarioModifica=$nit.'@'.$request->ip();
            }else{
                $profesionalSol = new Paso3Profesional();
                $profesionalSol->idSolicitud=$idSolicitud;
                $profesionalSol->idUsuarioCreacion=$nit.'@'.$request->ip();
            }
            $profesionalSol->idProfesional=$request->idProfesional;
            $profesionalSol->nitProfesional=$request->nitProfesional;
            $profesionalSol->poderProfesional=$request->poderProf;
            $profesionalSol->save();

            //---------------OÍR NOTIFICACIONES--------------
            $detalle=$solicitud->solicitudesDetalle;
            if($request->has('oirNotificaciones')){
                $detalle->idOirNotificaciones=$request->oirNotificaciones;
                $detalle->save();
            }


            //---------------TERCERAS PERSONAS----------------
            if($request->has('valTerceraPersona')){
                    $detalle->tercerInteresado=$request->valTerceraPersona; $detalle->save();
                    if(!empty($solicitud->tercero)){
                        if($request->valTerceraPersona==1){
                        $datpersona = $solicitud->tercero;
                        $datpersona->nombres=$request->nominteresado;
                        $datpersona->direccion=$request->direccioninteresado;
                        $datpersona->email=$request->correointeresado;
                        $datpersona->telefono1=$request->tel1interesado;
                        $datpersona->telefono2=$request->tel2interesado;
                        $datpersona->idUsuarioModifica = $nit.'@'.$request->ip();
                        $datpersona->save();
                        }
                    }else{
                        if($request->valTerceraPersona==1){
                                $datpersona = new Paso3Interesado();
                                $datpersona->idSolicitud=$idSolicitud;
                                $datpersona->nombres=$request->nominteresado;
                                if($request->has('direccioninteresado')) $datpersona->direccion=$request->direccioninteresado;
                                if($request->has('correointeresado')) $datpersona->email=$request->correointeresado;
                                if($request->has('tel1interesado')) $datpersona->telefono1=$request->tel1interesado;
                                if($request->has('tel2interesado')) $datpersona->telefono2=$request->tel2interesado;
                                $datpersona->idUsuarioCreacion = $nit.'@'.$request->ip();
                                $datpersona->save();
                        }
                    }
            }





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

        return response()->json(['status' => 200, 'message' => "¡Se ha guardado el paso 3 de la solicitud existosamente!"],200);
    }

    public function storeStep4(Step4Request $request){


        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud3);
            //dd($idSolicitud);
            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $idUsuarioModificacion=$nit.'@'.$request->ip();
                if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }
            }else{
                return response()->json(['status' => 422, 'errors' => "No se puede guardar el paso 4 de la solicitud sin antes guardar el paso 2!"],422);
            }
            //------------------FABRICANTE PRINCIPAL-----------------

            if(!empty($solicitud->fabricantePrincipal)){
                $fabricantePrin = $solicitud->fabricantePrincipal;
                $fabricantePrin->idUsuarioModifica=$nit.'@'.$request->ip();
            }else{
                $fabricantePrin = new Paso4FabPrincipal();
                $fabricantePrin->idSolicitud=$idSolicitud;
                $fabricantePrin->idUsuarioCreacion=$nit.'@'.$request->ip();
            }
            $fabricantePrin->idFabricante=$request->idFabricantePri;
            if($request->origenFab=='E04,E55,E57'){
                $fabricantePrin->procedencia='N';
            }else{
                $fabricantePrin->procedencia='E';
            }
            $fabricantePrin->noPoderMaquila=$request->nomaquilaFabPrincipal;
            $fabricantePrin->save();

            //-----------------FABRICANTES ALTERNOS------------------
            if(count($solicitud->fabricantesAlternos)>0){ $solicitud->fabricantesAlternos()->delete(); }
            if($request->has('fabricantesAlternos')){
                $arregloFabAlternos=[]; $nomaquilaFabAlt=$request->noMaquilaFabAlterno;
                if(in_array($request->idFabricantePri,$request->fabricantesAlternos)){
                   return response()->json(['status' => 422, 'errors' => "¡El fabricante principal no debe ser seleccionado como alterno!"],422);
                }
                foreach ($request->fabricantesAlternos as $key => $idFabAlterno){ array_push($arregloFabAlternos,['idFabAlterno' =>$idFabAlterno,'procedencia'=>1,'noPoderMaquila'=>$nomaquilaFabAlt[$key], 'idUsuarioCreacion'=> $nit.'@'.$request->ip()]); }
                $solicitud->fabricantesAlternos()->createMany($arregloFabAlternos);
            }
             //------------------FABRICANTES ACONDICIONADORES---------------------
            if(count($solicitud->laboratorioAcondicionador)>0){ $solicitud->laboratorioAcondicionador()->delete(); }
            if($request->has('laboratorioAcondicionador')){
                $arregloLabAcon=[];
                $tipo=$request->tipoLabAcondicionador; $nomaquiAcon=$request->noMaquilaFabAcon;
                foreach ($request->laboratorioAcondicionador as $key => $idLab){
                 array_push($arregloLabAcon,['idLabAcondicionador' =>  $idLab,'procedencia'=>1,'tipo'=>$tipo[$key],'noPoderMaquila'=>$nomaquiAcon[$key],'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                }
                $solicitud->laboratorioAcondicionador()->createMany($arregloLabAcon);
            }

            //------------------LABORATORIOS RELACIONAL---------------------
            if(count($solicitud->laboratorioRelacional)>0){ $solicitud->laboratorioRelacional()->delete(); }
            if($request->has('laboratorioRelacionado')){
                $arregloLabRela=[];
                foreach ($request->laboratorioRelacionado as $idLabRela){ array_push($arregloLabRela,['idFab' =>$idLabRela,'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);}
                $solicitud->laboratorioRelacional()->createMany($arregloLabRela);
            }
            //------------------FABRICANTE PRINCIPIO ACTIVO-----------------
            if(count($solicitud->fabprincipioactivo)>0){ $solicitud->fabprincipioactivo()->delete(); }
            if($request->has('fabPrincipioActivo') && $request->has('origenfabprincipio') && $request->has('idprincpio') && $request->has('nombreprincipio')){
                $arreglofabprincipio=[];
                $origen=$request->origenfabprincipio; $idpri=$request->idprincpio; $nombrepri=$request->nombreprincipio;
                foreach($request->fabPrincipioActivo as $key => $principio){
                    array_push($arreglofabprincipio,['idFabricante'=>$principio,'procedencia'=>$origen[$key],'idPrincipio'=>$idpri[$key],'nombrePrincipio'=>$nombrepri[$key],'tipo'=>1,'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                }
                $solicitud->fabprincipioactivo()->createMany($arreglofabprincipio);
            }
            //----------------------CONTRATO DE MAQUILA-------------------------------------------
            /*$detalle=$solicitud->solicitudesDetalle;
            $detalle->poderMaquila=$request->valContratoMaquila;
            $detalle->save();

            if($request->valContratoMaquila==1){
                   if(!empty($solicitud->poderfabprincipal)){
                        if($request->has('poderFabPrincipal')){
                               $poderprin = $solicitud->poderfabprincipal;
                               $poderprin->idPoder=$request->poderFabPrincipal;
                               $poderprin->idUsuarioModifica=$nit.'@'.$request->ip();
                               $poderprin->save();
                        }else{
                          $solicitud->poderfabprincipal()->delete();
                        }
                    }else{
                                $poderprin = new Paso4PrincipalPoderMaquila();
                                $poderprin->idSolicitud=$idSolicitud;
                                $poderprin->idPoder=$request->poderFabPrincipal;
                                $poderprin->usuarioCreacion=$nit.'@'.$request->ip();
                                $poderprin->save();
                    }

                if(count($solicitud->poderfabAlterno)){ $solicitud->poderfabAlterno()->delete(); }
                if($request->has('poderFabAlterno')){
                    $arrayPoderFabalterno=[];
                    foreach($request->poderFabAlterno as $fabAlter){
                         array_push($arrayPoderFabalterno,['idPoder'=>$fabAlter,'usuarioCreacion'=>$nit.'@'.$request->ip()]);
                    }
                    $solicitud->poderfabAlterno()->createMany($arrayPoderFabalterno);
                }
                if(count($solicitud->poderfabAcondicionador)){ $solicitud->poderfabAcondicionador()->delete(); }
                if($request->has('poderFabAcondicionador')){
                    $arrayPoderFabAcondicionador=[];
                    foreach($request->poderFabAcondicionador as $fabacon){
                         array_push($arrayPoderFabAcondicionador,['idPoder'=>$fabacon,'usuarioCreacion'=>$nit.'@'.$request->ip()]);
                    }
                    $solicitud->poderfabAcondicionador()->createMany($arrayPoderFabAcondicionador);
                }
            }else{
                 if(count($solicitud->poderfabAlterno)){ $solicitud->poderfabAlterno()->delete(); }
                 if(count($solicitud->poderfabAcondicionador)){ $solicitud->poderfabAcondicionador()->delete(); }
                 if(!empty($solicitud->poderfabprincipal)){  $solicitud->poderfabprincipal()->delete(); }
            }*/



        }
        catch (\Illuminate\Database\QueryException $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 4 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 4 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 4 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "¡Se ha guardado el paso 4 de la solicitud existosamente!"],200);
    }


    public function storeStep5(Step5Request $request){


        $nit = Session::get('user');
        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud4);
            //dd($idSolicitud);
            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $idUsuarioModificacion=$nit.'@'.$request->ip();
                if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }
            }else{
                return response()->json(['status' => 422, 'errors' => "No se puede guardar el paso 5 de la solicitud sin antes guardar el paso 2!"],422);
            }

            if(!empty($solicitud->manufactura)){
                $manufactu = $solicitud->manufactura;
                $manufactu->idUsuarioModifica=$nit.'@'.$request->ip();

                $manufactu->autoridadEmisora = $request->certificadolv;
                $manufactu->nombreProductoProcedencia = $request->nomProdPais;
                $manufactu->titularProducto=$request->titularProductoC;
                $manufactu->fechaEmision = $request->fechaEmision;
                $manufactu->fechaVencimiento = $request->fechaVencimiento;
                $manufactu->save();
            }else{
                $manufactu = new Paso5CertManufactura();
                $manufactu->idSolicitud=$idSolicitud;
                $manufactu->idUsuarioCreacion=$nit.'@'.$request->ip();

                if($request->has('certificadolv')) $manufactu->autoridadEmisora = $request->certificadolv;
                if($request->has('nomProdPais'))  $manufactu->nombreProductoProcedencia = $request->nomProdPais;
                $manufactu->titularProducto=$request->titularProductoC;
                if($request->has('fechaEmision')) $manufactu->fechaEmision = $request->fechaEmision;
                if($request->has('fechaVencimiento')) $manufactu->fechaVencimiento = $request->fechaVencimiento;
                $manufactu->save();
            }


        }
        catch (\Illuminate\Database\QueryException $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 5 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 5 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 5 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "¡Se ha guardado el paso 5 de la solicitud existosamente!"],200);
    }


    public function storeStep6(Step6Request $request){


        $nit = Session::get('user');
        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud5);
            //dd($idSolicitud);
            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $idUsuarioModificacion=$nit.'@'.$request->ip();
                if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }
            }else{
                return response()->json(['status' => 422, 'errors' => "No se puede guardar el paso 6 de la solicitud sin antes guardar el paso 2!"],422);
            }
            //------------------FABRICANTE PRINCIPAL-----------------

            if(!empty($solicitud->bpmPrincipal)){
                $fabricantePrin = $solicitud->bpmPrincipal;
                $fabricantePrin->idUsuarioModifica=$nit.'@'.$request->ip();
            }else{
                $fabricantePrin = new BpmPrincipal();
                $fabricantePrin->idSolicitud=$idSolicitud;
                $fabricantePrin->idUsuarioCreacion=$nit.'@'.$request->ip();
            }
            $fabricantePrin->idFabricantePpal=$request->idcertificadobpm;
            $fabricantePrin->nombreEmisor=$request->certificadobpm;
            $fabricantePrin->fechaEmision=$request->fechaEmision;
            $fabricantePrin->fechaVencimiento=$request->fechaVencimiento;
            $fabricantePrin->save();


            //-----------------FABRICANTES ALTERNOS------------------
            if(count($solicitud->bpmAlternos)>0){ $solicitud->bpmAlternos()->delete(); }
            if($request->has('practLabAlternos')){
                $arregloFabAlternos=[];
                $f1= 'fechaEmision-'; $f2='fechaVencimiento-'; $em1='emisorAlterno-';
                foreach ($request->practLabAlternos as $idFabAlterno){
                    $fe = $f1.$idFabAlterno; $fv = $f2.$idFabAlterno; $emfab=$em1.$idFabAlterno;
                    array_push($arregloFabAlternos,['idAlterno' =>$idFabAlterno,'nombreEmisor'=>$request->$emfab,'fechaEmision'=>$request->$fe,'fechaVencimiento'=>$request->$fv, 'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                }

                $solicitud->bpmAlternos()->createMany($arregloFabAlternos);
            }

            //------------------FABRICANTES ACONDICIONADORES---------------------
            if(count($solicitud->bpmAcondicionadores)>0){ $solicitud->bpmAcondicionadores()->delete(); }
            if($request->has('practLabAcondi')){
                $arregloLabAcon=[];
                $f3= 'fechaEmision-'; $f4='fechaVencimiento-'; $em2='emisorAcondicionador-';
                foreach ($request->practLabAcondi as $idLab){
                    $fe2 = $f3.$idLab; $fv2 = $f4.$idLab; $emacon=$em2.$idLab;
                    array_push($arregloLabAcon,['idAcondicionador' =>  $idLab,'nombreEmisor'=>$request->$emacon,'fechaEmision'=>$request->$fe2,'fechaVencimiento'=>$request->$fv2,'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                }
                $solicitud->bpmAcondicionadores()->createMany($arregloLabAcon);
            }
             //------------------FABRICANTES RELACIONADOS--------------------
            if(count($solicitud->bpmRelacionados)>0){ $solicitud->bpmRelacionados()->delete(); }
            if($request->has('practLabRelacionado')){
                $arregloLabRelacionado=[];
                $f5= 'fechaEmisionRel-'; $f6='fechaVencimientoRel-'; $em3='emisorRelacionado-';
                foreach ($request->practLabRelacionado as $idRel){
                    $fe3 = $f5.$idRel; $fv3 = $f6.$idRel; $emrel=$em3.$idRel;
                    array_push($arregloLabRelacionado,['idRelacionado' =>  $idRel,'nombreEmisor'=>$request->$emrel,'fechaEmision'=>$request->$fe3,'fechaVencimiento'=>$request->$fv3,'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                }
                $solicitud->bpmRelacionados()->createMany($arregloLabRelacionado);
            }
             //------------------FABRICANTES PRINCIPIO ACTIVO-------------------
            if(count($solicitud->bpmFabPrinActivo)>0){ $solicitud->bpmFabPrinActivo()->delete(); }
            if($request->has('practLabPrinActivo')){
                $arregloFabPrinAcivo=[];
                $f7= 'fechaEmisionAct-'; $f8='fechaVencimientoAct-'; $em4='emisorPrinActivo-';
                foreach ($request->practLabPrinActivo as $idfabprin){
                    $fe4 = $f7.$idfabprin; $fv4 = $f8.$idfabprin; $emfabactivo=$em4.$idfabprin;
                    array_push($arregloFabPrinAcivo,['idFabActivo' =>  $idfabprin,'nombreEmisor'=>$request->$emfabactivo,'fechaEmision'=>$request->$fe4,'fechaVencimiento'=>$request->$fv4,'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                }
                $solicitud->bpmFabPrinActivo()->createMany($arregloFabPrinAcivo);
            }


        }
        catch (\Illuminate\Database\QueryException $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 6 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 6 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 6 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "¡Se ha guardado el paso 6 de la solicitud existosamente!"],200);
    }

    public function storeStep7(Step7Request $request){

        // dd($request->all());
        $nit = Session::get('user');
        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud6);
            //dd($idSolicitud);
            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $idUsuarioModificacion=$nit.'@'.$request->ip();
                if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }
            }else{
                return response()->json(['status' => 422, 'errors' => "No se puede guardar el paso 7 de la solicitud sin antes guardar el paso 2!"],422);
            }



            if(count($solicitud->distribuidores)>0){ $solicitud->distribuidores()->delete(); }
            if($request->has('dist')){
                $arregloDist=[];

                foreach ($request->dist as $idDistribuidor){
                    array_push($arregloDist,['idDistribuidor' =>$idDistribuidor,'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                }

                $solicitud->distribuidores()->createMany($arregloDist);
            }

        }
        catch (\Illuminate\Database\QueryException $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 7 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 7 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 7 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "¡Se ha guardado el paso 7 de la solicitud existosamente!"],200);
    }

    public function storeStep8(Step8Request $request){


        $nit = Session::get('user');
        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud7);
            //dd($idSolicitud);
            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $idUsuarioModificacion=$nit.'@'.$request->ip();
                if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }
            }else{
                return response()->json(['status' => 422, 'errors' => "No se puede guardar el paso 8 de la solicitud sin antes guardar el paso 2!"],422);
            }


            if(!empty($solicitud->materialEmpaque)){
                $martEmpaque = $solicitud->materialEmpaque;
                $martEmpaque->idUsuarioModifica=$nit.'@'.$request->ip();
            }else{
                $martEmpaque = new MaterialEmpaque();
                $martEmpaque->idSolicitud=$idSolicitud;
                $martEmpaque->idUsuarioCreacion=$nit.'@'.$request->ip();
            }
            $martEmpaque->idAcondicionador=$request->listLabAcondicionador;
            $martEmpaque->save();


        }
        catch (\Illuminate\Database\QueryException $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 8 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 8 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 8 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "¡Se ha guardado el paso 8 de la solicitud existosamente!"],200);
    }

    public function storeStep9(Step9Request $request){


        $nit = Session::get('user');
        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud8);
            //dd($idSolicitud);
            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $idUsuarioModificacion=$nit.'@'.$request->ip();
                if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }
            }else{
                return response()->json(['status' => 422, 'errors' => "No se puede guardar el paso 9 de la solicitud sin antes guardar el paso 2!"],422);
            }

            //dd($request->all());
            if(!empty($solicitud->farmacologia)){
                $farmaco = $solicitud->farmacologia->detalle;
                $farmaco->idUsuarioModifica=$nit.'@'.$request->ip();
                $farmaco->farmacocinetica = $request->farm;
                $farmaco->mecanismoAccion= $request->mecaaccion;
                $farmaco->indicacionesTerapeuticas = $request->indicacion;
                $farmaco->contraindicaciones = $request->contrad;
                $farmaco->regimenDosis = $request->dos;
                $farmaco->efectosAdversos = $request->efectos;
                $farmaco->precauciones = $request->adv;
                $farmaco->interacciones = $request->interaccion;
                $farmaco->categoriaTerapeutica = $request->codigoatc;
                $farmaco->idUsuarioModifica = $nit.'@'.$request->ip();
                $farmaco->save();
            }else{
                    $farmaco = new Farmacologia();
                    $farmaco->idSolicitud=$idSolicitud;
                    $farmaco->idUsuarioCreacion=$nit.'@'.$request->ip();
                    $farmaco->save();
                    $arreglo =[];

                    $idCorre=Farmacologia::where('idSolicitud',$idSolicitud)->first();

                    $detalle = new DetalleFarmacologia();
                    $detalle->idFichaTecnica=$idCorre->idFichaTecnica;
                    $detalle->farmacocinetica=$request->farm;
                    $detalle->mecanismoAccion=$request->mecaaccion;
                    $detalle->indicacionesTerapeuticas= $request->indicacion;
                    $detalle->contraindicaciones=$request->contrad;
                    $detalle->regimenDosis= $request->dos;
                    $detalle->efectosAdversos= $request->efectos;
                    $detalle->precauciones=$request->adv;
                    $detalle->interacciones=$request->interaccion;
                    $detalle->categoriaTerapeutica= $request->codigoatc;
                    $detalle->idUsuarioCreacion=$nit.'@'.$request->ip();
                    $detalle->save();


            }



        }
        catch (\Illuminate\Database\QueryException $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 9 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 9 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 9 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => "¡Se ha guardado el paso 9 de la solicitud existosamente!"],200);
    }

    public function storeStep10(Request $request){

        //dd($request->all());
        $nit = Session::get('user');
        $ar= $request->file('file-es');


        try{
            $edicion=false;
            $urlsEliminar=[];
            $idSolicitud=Crypt::decrypt($request->idSolicitud9);
            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $usuarioCreacion=$nit.'@'.$request->ip();
                if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }

                if(count($solicitud->documentos)==0){
                    if(count($ar)==0){  return response()->json(['status' => 422, 'errors' => "¡Seleccionar uno o más archivos!"],422);
                        DB::connection('sqlsrv')->beginTransaction(); }
                }

                //if(count($solicitud->documentos)>0)  $solicitud->documentos()->delete();
                // dd($request->all());
                /*if($request->has('docGuardado')){
                    $edicion=true;
                    foreach ($solicitud->documentos as $detalleDocumento)
                        if(!in_array($detalleDocumento->idDoc, $request->docGuardado)){
                            array_push($urlsEliminar,$detalleDocumento->urlArchivo);
                            $detalleDocumento->delete();
                            DB::connection('sqlsrv')->commit();
                        }
                }else{
                    $solicitud->documentos()->delete();
                }*/


                $saveDocs=$this->guardarDocumentos($solicitud->idSolicitud,$request->file('file-es'),$usuarioCreacion,null,$urlsEliminar,$edicion);
                if($saveDocs==0){
                    DB::rollback();
                    return response()->json(['status' => 422, 'errors' => "¡Error en el sistema: No se han podido guardar los documentos adjuntos, por favor vuelva intentarlo!"],422);
                }
            }
            else{
                return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso 10 de la solicitud sin antes guardar el paso 2!"],422);
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 10 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 10 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 10 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();
        Session::put('idSolCosPre',$idSolicitud);
        return response()->json(['status' => 200, 'message' => "¡Se ha guardado el paso 10 de la solicitud existosamente!"],200);
    }

    public function storeStep11(Step11Request $request){


        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud10);
            $ban = true;
            //dd($idSolicitud);
            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $idUsuarioModificacion=$nit.'@'.$request->ip();
                if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }
            }else{
                return response()->json(['status' => 422, 'errors' => "¡No se puede finalizar la solicitud sin antes guardar el paso 2!"],422);
            }
            //------------------FABRICANTE PRINCIPAL-----------------
            $aler = '<ul class="text-warning">';
            if(empty($solicitud->solicitudesDetalle)){  $aler.='<li>No existe detalle de solicitud, que a guardado verificar paso 2</li>'; $ban=false;}
            if(count($solicitud->principiosActivos)==0){$aler.='<li>No existe ningún principio activo, que a guardado verificar paso 2</li>'; $ban=false; }
            if(count($solicitud->empaquesPresentacion)==0){ $aler.='<li>No existe ninguna presentación, que a guardado verificar paso 2</li>'; $ban=false;}
            if(empty($solicitud->titular)){
                $ban=false;
                $aler.='<li>No existe ningún  titular del producto, verificar que a guardado paso 3</li>';
            }else{
                $tit=$solicitud->titular;
                if($tit->tipoTitular!=1){
                    $tt=0;
                    if(!empty($solicitud->representante)){ $tt=$tt+1;}
                    if(!empty($solicitud->apoderado)){  $tt=$tt+1;}
                    if($tt==0){$aler.='<li>Debe de ingresar un representante legal o un apoderado, verificar paso 3</li>'; $ban=false;}
                }

            }
            if(empty($solicitud->profesional)){$aler.='<li>No existe ningún  profesional responsable, verificar que a guardado paso 3</li>'; $ban=false;}
            if(empty($solicitud->fabricantePrincipal)){$aler.='<li>No existe ningún  fabricante principal, verificar que a guardado paso 4</li>'; $ban=false;}
            if(count($solicitud->documentos)==0){
                $aler.='<li>No existe ningún  documento, verificar que a guardado paso 10</li>'; $ban=false;
            }else{
                $a=0;

                $tipoMedicamento = $solicitud->solicitudesDetalle->tipoMedicamento;
                $innova = $solicitud->solicitudesDetalle->innovador;
                $origen = $solicitud->solicitudesDetalle->origenProducto;

                if($origen==4){
                        $idItemDoc = [];
                        //CONSULTAMOS LOS DOCUMENTOS REGISTRADOS DE LA SOLICITUD
                        if (count($solicitud->documentos) > 0){
                            for ($i = 0; $i < count($solicitud->documentos); $i++) {
                                $idItemDoc[$i] = $solicitud->documentos[$i]->idItemRequisitoDoc;
                            }
                        }
                        //VALIDAMOS SI EL FORMULARIO
                        if(!in_array(1,$idItemDoc)){
                                $ban=false;
                                $a=1;
                        }

                }else{
                        if($tipoMedicamento==6 || $tipoMedicamento==7 || $tipoMedicamento==8){
                            if($origen==4){
                                  //consultamos todos los documentos obligatorios menos los documentos 10 y 21 ya que no aplican para Naturales, Homeopáticos y Suplementos Nutricionales y además 5,7 y 18 de procedencia mutua centroamericana
                                    $requisitos = RequisitosDocumento::where('requerido',1)->where('idItem','<>','10')->where('idItem','<>','21')->where('idItem','<>','5')->where('idItem','<>','7')->where('idItem','<>','18')->get();
                            }else{
                                    //consultamos todos los documentos obligatorios menos los documentos 10 y 21 ya que no aplican para Naturales, Homeopáticos y Suplementos Nutricionales
                                   $requisitos = RequisitosDocumento::where('requerido',1)->where('idItem','<>','10')->where('idItem','<>','21')->get();
                            }
                        }else if($tipoMedicamento==12){
                            if($origen==4){
                            //consultamos todos los documentos obligatorios menos el documento 10 ya que no aplican para Gases medicinales y además 5,7 y 18 de procedencia mutua centroamericana
                             $requisitos = RequisitosDocumento::where('requerido',1)->where('idItem','<>','10')->where('idItem','<>','5')->where('idItem','<>','7')->where('idItem','<>','18')->get();
                            }else{
                            //consultamos todos los documentos obligatorios menos el documento 10 ya que no aplican para Gases medicinales.
                             $requisitos = RequisitosDocumento::where('requerido',1)->where('idItem','<>','10')->get();
                            }
                        }else if($tipoMedicamento==4){
                             if($origen==4){
                                 //consultamos todos los documentos obligatorios menos los documentos 7,8 y 10 ya que no aplican para vacunas y además 5,7 y 18 de procedencia mutua centroamericana
                                $requisitos = RequisitosDocumento::where('requerido',1) ->where('idItem','<>','21')->where('idItem','<>','10')->where('idItem','<>','7')->where('idItem','<>','8')->where('idItem','<>','5')->where('idItem','<>','18')->get();
                             }else{
                                 //consultamos todos los documentos obligatorios menos los documentos 7,8 y 10 ya que no aplican para vacunas.
                                $requisitos = RequisitosDocumento::where('requerido',1) ->where('idItem','<>','21')->where('idItem','<>','10')->where('idItem','<>','7')->where('idItem','<>','8')->where('idItem','<>','18')->get();
                             }
                        }else{
                             if($origen==4){
                                 //consultamos todos los documentos que no son requeridos para reconocimiento mutuo centroamericano
                                    $requisitos = RequisitosDocumento::where('requerido',1)->where('idItem','<>','7')->where('idItem','<>','5')->where('idItem','<>','18')->get();
                             }else{
                                    //consultamos todos los documentos que son requeridos sin importar el tipo de medicamento
                                    $requisitos = RequisitosDocumento::where('requerido',1)->get();
                             }
                        }
                         //dd($requisitos);
                        if($tipoMedicamento==1){
                            //sintesis
                            if($innova==1){
                            $requisitoTipoMec = RequisitosDocumento::where('sintesis',1)->get();
                            }
                        }else if($tipoMedicamento==2){
                            //biologico
                            $requisitoTipoMec = RequisitosDocumento::where('biologico',1)->get();
                        }else if($tipoMedicamento==3){
                            // biotecnologico
                            $requisitoTipoMec = RequisitosDocumento::where('biotecnologico',1)->get();
                        }else if($tipoMedicamento==4){
                            // vacunas
                            $requisitoTipoMec = RequisitosDocumento::where('vacuna',1)->get();
                        }else if($tipoMedicamento==6){
                            //suplementos
                            $requisitoTipoMec = RequisitosDocumento::where('suplementos',1)->get();
                        }else if($tipoMedicamento==7){
                            //naturales
                            $requisitoTipoMec = RequisitosDocumento::where('naturales',1)->get();
                        }else if($tipoMedicamento==8){
                            //homeopatico
                            $requisitoTipoMec = RequisitosDocumento::where('homeopatico',1)->get();
                        }else if($tipoMedicamento==11){
                            //radiofarmaco
                            $requisitoTipoMec = RequisitosDocumento::where('radiofarmaco',1)->get();
                        }else if($tipoMedicamento==12){
                            //gasesmedicinales
                            $requisitoTipoMec = RequisitosDocumento::where('gasesmedicinales',1)->get();
                        }else if($tipoMedicamento==9){
                            //generico
                            $requisitoTipoMec = RequisitosDocumento::where('generico',1)->get();
                        }else if($tipoMedicamento==13){
                            //probiotico
                            $requisitoTipoMec = RequisitosDocumento::where('probiotico',1)->get();
                        }

                        $idItemDoc = [];
                        //CONSULTAMOS LOS DOCUMENTOS REGISTRADOS DE LA SOLICITUD
                        if (count($solicitud->documentos) > 0){
                            for ($i = 0; $i < count($solicitud->documentos); $i++) {
                                $idItemDoc[$i] = $solicitud->documentos[$i]->idItemRequisitoDoc;
                            }
                        }

                        //VALIDAMOS SI EXISTEN TODOS LOS DOCUMENTOS REQUERIDOS
                        foreach($requisitos as $req){
                            if(!in_array($req->idItem,$idItemDoc)){
                                $ban=false;
                                $a=1;
                            }
                        }

                        //VALIDAMOS SI EXISTEN LOS DOCUMENTOS REQUERIDOS POR TIPO DE MEDICAMENTO
                        if(!empty($requisitoTipoMec)){
                                foreach($requisitoTipoMec as $req2){
                                    if(!in_array($req2->idItem,$idItemDoc)){
                                        $ban=false;
                                        $a=1;
                                    }
                                }
                        }
                        //consultamos los documentos queridos de reconocimiento extranjero
                        if($origen==3){
                            if($tipoMedicamento==4){
                                 //consultamos todos los documentos obligatorios de reconocimiento-extranjero menos el documento 9 ya que no aplican para vacunas
                                $requisitoExtra = RequisitosDocumento::where('recoextranjero',1)->where('idItem','<>','9')->get();
                            }else{
                                $requisitoExtra = RequisitosDocumento::where('recoextranjero',1)->get();
                            }

                            foreach($requisitoExtra as $req3){
                                if(!in_array($req3->idItem,$idItemDoc)){
                                    $ban=false;
                                    $a=1;
                                }
                            }
                        }
                }//cierre de if de origen==4

                if($a==1)  $aler.='<li>Verificar que ya guardo los documentos que son requeridos y  aquellos que aplique de acuerdo al tipo de medicamento seleccionado en el paso 2</li>';
            }//cierre de count($solicitud->documentos)==0


            if($ban==false){
                $aler.='</ul>';
                return response()->json(['status' => 422, 'errors' => $aler],422);
            }else{
                $now = Carbon::now('America/El_Salvador');
                //$now =  Carbon::parse('2019-05-17 17:35:50');
                $valDiaHabil = $this->esDiaHabil($now);
                $fechaRecepcion=$now->format('Y-m-d  H:i:s');
                if($valDiaHabil){
                   $fechaEnvio=$now->format('Y-m-d  H:i:s');
                   $msg='¡Se ha guardado la solicitud existosamente!';
                   $idEstado=17;
                }else{
                   $fechaEnvio=$this->siguienteDiaHabil($now);
                   $msg='¡Se ha guardado la solicitud existosamente pero sera recibida en la siguiente fecha hábil '.$fechaEnvio.'!';
                   $idEstado=16;
                }
                $solicitud->fechaRecepcion= $fechaRecepcion;
                $solicitud->fechaEnvio =$fechaEnvio;
                $estadoAnterior = $solicitud->estadoDictamen;
                $solicitud->estadoDictamen=$idEstado;

                if($estadoAnterior==18){
                    $solicitud->fechaCorreccion = $now;
                    $msg.=' ¡Solicitud Corregida!';
                }
                $solicitud->save();
                SolicitudSeguimiento::create(['idSolicitud'=>$solicitud->idSolicitud,'estadoSolicitud'=>$idEstado,'observaciones'=>$msg,'idUsuarioCreacion'=>$idUsuarioModificacion,'fechaCreacion'=>Carbon::now('America/El_Salvador')]);


            }




        }
        catch (\Illuminate\Database\QueryException $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 10 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 10 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 10 de la solicitud!'
            ],500);
        }

        DB::connection('sqlsrv')->commit();

        return response()->json(['status' => 200, 'message' => $msg, 'final' => "paso12"],200);
    }

    public static function guardarDocumentos($idSolicitudNew,$files,$idUsuarioCrea,$img_val,$urls,$edit){

        DB::beginTransaction();

        try {
            //dd($files);

             //$npath='Z:\URV\PRE\\';
             $npath='Y:\URV\PRE\\';

            $filesystem= new Filesystem();
            if($filesystem->exists($npath)) {
                if ($filesystem->isWritable($npath)) {
                    $newpath = $npath . $idSolicitudNew;
                    if(!$filesystem->isDirectory($newpath)){
                          File::makeDirectory($newpath, 0777, true, true);
                    }

                    if (!empty($files)) {
                        $indexs=array_keys($files);
                        for ($i = 0; $i < count($indexs); $i++) {
                            $index = $indexs[$i];
                            //$item = Item::findOrFail($index);
                            $name = 'item-'.$index.'.'.$files[$index]->getClientOriginalExtension();
                            $type = $files[$index]->getClientMimeType();
                            $files[$index]->move($newpath, $name);

                            $detalleDoc=PreDocumentos::where('idItemRequisitoDoc',$index)->where('idSolicitud',$idSolicitudNew)->first();
                            if(empty($detalleDoc)){
                                 $detalleDoc = new PreDocumentos();
                                 $detalleDoc->idUsuarioCreacion = $idUsuarioCrea;
                            }else{
                                $detalleDoc->idUsuarioModifica= $idUsuarioCrea;
                            }
                            $detalleDoc->urlArchivo = $newpath.'\\'.$name;
                            $detalleDoc->tipoArchivo = $type;
                            $detalleDoc->idSolicitud=$idSolicitudNew;
                            $detalleDoc->idItemRequisitoDoc=$index;
                            $detalleDoc->save();

                            /* $detalleDoc->documentosSol()->create([
                                 'idSolicitud' => $idSolicitudNew,
                                 'idItemDoc' => $item->idItem
                             ]);*/
                        }

                        /*$img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img_val));
                        $filepath = $newpath."/confirmacion-solicitud".$idSolicitudNew.".png";*/
                        // Save the image in a defined path
                        // file_put_contents($filepath,$img);

                        DB::commit();
                        return 1;
                    } else {
                        return 1;
                        /* DB::rollback();
                         throw new Exception("Error: Documentos adjuntos no han podido ser guardado junto ha la solicitud, vuelva a intentar a realizar el tramite", 1);
                         return 0;*/
                    }
                } else {
                    DB::rollback();
                    throw new Exception("Error: No se ha podido guardar los documentos adjuntos, vuelva a intentar a realizar el tramite", 1);
                    return 0;

                }
            }
        }
        catch (Throwable $e) {
            DB::rollback();
            throw $e;
            return $e;
        }
        catch(Exception $e){
            DB::rollback();
            throw $e;
            return $e;
            Session::flash('msnError', $e->getMessage());
        }


    }

    public function showDocByRequisito($idDocumento){

        $idDoc=Crypt::decrypt($idDocumento);
        //return 'no llega aqui';
        $archivo = PreDocumentos::findOrFail($idDoc);
        //return response()->download($archivo->urlArchivo);

        //dd($archivo);

        if($archivo!=null){
            if($archivo->tipoArchivo==='application/pdf'){
                //parte nueva
                //$archivo->tipoArchivo='application/pdf';
                if (File::isFile($archivo->urlArchivo))
                {
                     try{
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
                     } catch (Exception $e) {

                        return Response::download($archivo->urlArchivo);
                     }
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

    public function eliminarSol($idSolicitud){

        $idSoli = Crypt::decrypt($idSolicitud);
        //dd($idSolicitud.' '.$idSoli);

        $solicitud = Solicitud::findOrFail($idSoli);
        //dd($solicitud);
        $solicitud->estadoDictamen = (int)9;
        $fecha = Carbon::now();
        //dd($fecha);
        $solicitud->fechaEnvio = Carbon::now();
        //dd($solicitud);
        $solicitud->save();
    }
    public function desistirSol($idSolicitud){

        $idSoli = Crypt::decrypt($idSolicitud);
        $solicitud = Solicitud::findOrFail($idSoli);
        $solicitud->estadoDictamen = (int)14;
        $solicitud->save();
        SolicitudSeguimiento::create(['idSolicitud'=>$solicitud->idSolicitud,'estadoSolicitud'=>14,'observaciones'=>'Solicitud desistida por usuario.','idUsuarioCreacion'=>$solicitud->nitSolicitante,'fechaCreacion'=>Carbon::now('America/El_Salvador')]);

        Session::put('idSolPdfURV',$idSoli);
    }
    public function deleteDocumento(Request $request){
        $nit = Session::get('user');


        $idSolicitud=Crypt::decrypt($request->idSolicitud);
        $idDocumento=substr($request->idDocumento,4);
        //dd($idDocumento);
        try{
            $filesystem= new Filesystem();
            if($idSolicitud!=0){
                $solicitud = Solicitud::findOrFail($idSolicitud);
                $usuarioCreacion=$nit.'@'.$request->ip();
                /*if($solicitud->estadoDictamen!=0&&$solicitud->estadoDictamen!=18){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }*/

                if(count($solicitud->documentos)==0){
                    if(count($ar)==0){  return response()->json(['status' => 422, 'errors' => "¡Seleccionar uno o más archivos!"],422);
                        DB::connection('sqlsrv')->beginTransaction(); }
                }

                if(count($solicitud->documentos)>0)
                foreach ($solicitud->documentos as $detalleDocumento){
                         if($detalleDocumento->idItemRequisitoDoc==$idDocumento){
                            $urlArchivo=$detalleDocumento->urlArchivo;
                            $detalleDocumento->delete();
                            DB::connection('sqlsrv')->commit();

                            $filesystem->delete($urlArchivo);
                            //dd($urlArchivo);
                        }
                }
                 //DB::connection('sqlsrv')->commit();
                 return response()->json(['status' => 200, 'message' => "¡Se eliminaron con exito los documentos!"],200);
            }else{
                return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso 10 de la solicitud sin antes guardar el paso 2!"],422);
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 10 de la solicitud!'
            ],500);

        }
        catch (Throwable $e) {
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 10 de la solicitud!'
            ],500);
        }
        catch (\Exception $e){
            //throw $e;
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error en el sistema: No se han podido guardar el paso 10 de la solicitud!'
            ],500);
        }
    }


}
