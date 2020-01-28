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
use App\Models\Registro\Sol\SolicitudSeguimiento;
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
use App\Models\Registro\Sol\Paso2\VidaUtilEmpaque;
use App\Models\Registro\Sol\Paso2\EmpaquePresentacion;
use App\Models\Registro\Sol\Paso6\BpmAcondicionador;
use App\Models\Registro\Sol\Paso6\BpmAlterno;
use App\Models\Registro\Sol\Paso6\BpmPrincipal;
use App\Models\Registro\Sol\Paso6\BmpFabPrinActivo;
use App\Models\Registro\Sol\Paso6\BpmRelacionados;
use App\Models\Registro\Sol\Paso7\Distribuidor;
use App\Models\Registro\Sol\Paso8\MaterialEmpaque;
use App\Models\Registro\Sol\Paso9\Farmacologia;
use App\Models\Registro\Sol\Paso9\DetalleFarmacologia;
use App\Models\Ucc\UnificacionPortal;
use App\Models\Registro\Dic\Dictamen;
use App\Models\Registro\Dic\ValidacionDictamen;

class SubsanacionPreController extends Controller
{
    private $url=null;
    private $token=null;
    use DiaHabilTrait;
    public function __construct() {
        $this->url = Config::get('app.api');
        $this->token = Config::get('app.token');
    }

    public function storeStep1y2(Request $request){
        //dd($request->presentaciones);
        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();
        try{
            $arregloCampos=[];
            $idSolicitud=Crypt::decrypt($request->idSolicitud);

            $solicitud = Solicitud::findOrFail($idSolicitud);
            $solicitud->idUsuarioModifica=$nit.'@'.$request->ip();
            if($solicitud->estadoDictamen!=4){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
            }

            $solDetalle = $solicitud->solicitudesDetalle;
            $solDetalle->idUsuarioModifica=$nit.'@'.$request->ip();

            //Guardo el detalle con el id de la solicitud ingresada.
            if($request->has('nombreComercial2')){
             $a1=Crypt::decrypt($request->nombreComercial2);
                    if($a1=='nombreComercial2'){
                            if($solDetalle->nombreComercial!=$request->nom_prod && strlen($request->nom_prod)>0){
                                $solDetalle->nombreComercial=$request->nom_prod;
                                array_push($arregloCampos,'nombreComercial');
                            }
                    }
            }
            if($request->has('innovador2')){
             $a2=Crypt::decrypt($request->innovador2);
                    if($a2=='innovador2'){
                            if($solDetalle->innovador!=$request->innovador && strlen($request->innovador)>0){
                                $solDetalle->innovador= $request->innovador;
                                array_push($arregloCampos,'innovador');
                            }
                    }
            }
            if($request->has('origenProducto2')){
             $a3=Crypt::decrypt($request->origenProducto2);
                    if($a3=='origenProducto2'){
                            if($solDetalle->origenProducto!=$request->origen && strlen($request->origen)>0){
                                        $solDetalle->origenProducto= $request->origen;
                                if($request->origen==4){
                                        $solDetalle->idPaisReconocimiento=$request->paisReconocimiento;
                                        $solDetalle->numeroRegistroReconocimiento= $request->noregistrorecono;
                                }
                                array_push($arregloCampos,'origenProducto');
                            }
                    }
            }
            if($request->has('tipoMedicamento2')){
             $a4=Crypt::decrypt($request->tipoMedicamento2);
                    if($a4=='tipoMedicamento2'){
                            if($solDetalle->tipoMedicamento!=$request->tipoMedicamento && strlen($request->tipoMedicamento)>0){
                                $solDetalle->tipoMedicamento= $request->tipoMedicamento;
                                array_push($arregloCampos,'tipoMedicamento');
                            }
                    }
            }
            if($request->has('formaFarmaceutica2')){
             $a5=Crypt::decrypt($request->formaFarmaceutica2);
                    if($a5=='formaFarmaceutica2'){
                            if($solDetalle->formaFarmaceutica!=$request->formafarm && strlen($request->formafarm)>0){
                                $solDetalle->formaFarmaceutica= $request->formafarm;
                                array_push($arregloCampos,'formaFarmaceutica');
                            }
                    }
            }
            if($request->has('viaAdmon2')){
             $a6=Crypt::decrypt($request->viaAdmon2);
                    if($a6=='viaAdmon2'){
                            if($solDetalle->viaAdmon!=$request->viaAdmin && strlen($request->viaAdmin)>0){
                                $solDetalle->viaAdmon= $request->viaAdmin;
                                array_push($arregloCampos,'viaAdmon');
                            }
                    }
            }
             if($request->has('condicionesAlmacenaje2')){
             $a7=Crypt::decrypt($request->condicionesAlmacenaje2);
                    if($a7=='condicionesAlmacenaje2'){
                            if($solDetalle->condicionesAlmacenaje!=$request->condAlmacenamiento && strlen($request->condAlmacenamiento)>0){
                                $solDetalle->condicionesAlmacenaje= $request->condAlmacenamiento;
                                array_push($arregloCampos,'condicionesAlmacenaje');
                            }
                    }
            }
             if($request->has('vidaUtil2')){
             $a8=Crypt::decrypt($request->vidaUtil2);
                    if($a8=='vidaUtil2'){


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
                                            $utilvidaempaque->idSolicitud =  $solicitud->idSolicitud;
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
                                    array_push($arregloCampos,'vidaUtil');
                    }
            }
            if($request->has('excipientes2')){
             $a9=Crypt::decrypt($request->excipientes2);
                    if($a9=='excipientes2'){
                            if($solDetalle->excipientes!=$request->excipientes && strlen($request->excipientes)>0){
                                $solDetalle->excipientes= $request->excipientes;
                                array_push($arregloCampos,'excipientes');
                            }
                    }
            }
            if($request->has('unidadDosis2')){
             $a10=Crypt::decrypt($request->unidadDosis2);
                    if($a10=='unidadDosis2'){
                            if($solDetalle->unidadDosis!=$request->udosis && strlen($request->udosis)>0){
                                $solDetalle->unidadDosis= $request->udosis;
                                array_push($arregloCampos,'unidadDosis');
                            }
                    }
            }
            if($request->has('modalidadVenta2')){
             $a11=Crypt::decrypt($request->modalidadVenta2);
                    if($a11=='modalidadVenta2'){
                            if($solDetalle->modalidadVenta!=$request->modalidad && strlen($request->modalidad)>0){
                                $solDetalle->modalidadVenta= $request->modalidad;
                                array_push($arregloCampos,'modalidadVenta');
                            }
                    }
            }
            if($request->has('bioequivalente2')){
             $a12=Crypt::decrypt($request->bioequivalente2);
                    if($a12=='bioequivalente2'){
                            if($solDetalle->bioequivalente!=$request->bioequi && strlen($request->bioequi)>0){
                                $solDetalle->bioequivalente= $request->bioequi;
                                array_push($arregloCampos,'bioequivalente');
                            }
                    }
            }
            if($request->has('formula2')){
             $a13=Crypt::decrypt($request->formula2);
                    if($a13=='formula2'){
                            if($solDetalle->formula!=$request->formula && strlen($request->formula)>0){
                                $solDetalle->formula= $request->formula;
                                array_push($arregloCampos,'formula');
                            }
                    }
            }
            $solDetalle->save();


          if($request->has('principios2')){
                    $a14=Crypt::decrypt($request->principios2);
                    if($a14=='principios2'){
                                    if($request->has('patente')) $solDetalle->poseePatentes= $request->patente; $solDetalle->save();
                                    if(count($solicitud->principiosActivos)>0) $solicitud->principiosActivos()->delete();
                                    if($request->has('idMateriasP')){
                                        $nom = $request->nombreMateria; $unidad =$request->idUnidadesM; $concentracion=$request->concentracion;
                                        $nombreUni = $request->nombreUnidad;
                                        foreach($request->idMateriasP as $key => $value){
                                            $materiaSol = new PrincipioActivo();
                                            $materiaSol->idSolicitud =  $solicitud->idSolicitud;
                                            $materiaSol->idMateriaPrima=$value;
                                            $materiaSol->nombreMateriaPrima=$nom[$key];
                                            $materiaSol->concentracion = $concentracion[$key];
                                            $materiaSol->unidadMedida = $unidad[$key];
                                            $materiaSol->nombreUnidadMedida = $nombreUni[$key];
                                            $materiaSol->idUsuarioCreacion=$nit.'@'.$request->ip();
                                            $materiaSol->save();
                                        }

                                        array_push($arregloCampos,'principios');
                                    }
                    }
           }

           if($request->has('presentaciones2')){
                    $a15=Crypt::decrypt($request->presentaciones2);
                    if($a15=='presentaciones2'){
                            if(count($solicitud->empaquesPresentacion)>0) $solicitud->empaquesPresentacion()->delete();
                            if($request->has('presentaciones')){

                                foreach ($request->presentaciones as $presentacion) {
                                    $present = json_decode($presentacion);
                                    $solPresentacion = new EmpaquePresentacion();
                                    $solPresentacion->idSolicitud = $solicitud->idSolicitud;
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
                                 array_push($arregloCampos,'presentaciones');
                            }
               }
        }

        if(count($arregloCampos)>0) SubsanacionPreController::actualizarCamposDictamen($idSolicitud,$arregloCampos,$nit.'@'.$request->ip());

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
        return response()->json(['status' => 200,'data' => Crypt::encrypt($solicitud->idSolicitud) , 'message' => "¡Se ha guardado el paso 2 de la solicitud existosamente!", 'paso' => '1'],200);

    }


    public function storeStep3(Request $request){

        $arregloCampos=[];
        $nit = Session::get('user');

        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud2);

            $solicitud = Solicitud::findOrFail($idSolicitud);

            if($solicitud->estadoDictamen!=4){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
            }
            //---------------TITULAR--------------------
            if($request->has('idTitular2')){
                 $a1=Crypt::decrypt($request->idTitular2);
                if($a1=='idTitular2'){
                                if(!empty($solicitud->titular)){
                                    $titularSol = $solicitud->titular;
                                    $titularSol->idUsuarioModifica=$nit.'@'.$request->ip();
                                }
                                $titularSol->idTitular=$request->titular;
                                $titularSol->tipoTitular=$request->tipoTitular;
                                $titularSol->save();
                                array_push($arregloCampos,'idTitular');
                }
           }

            //---------------Representante lega---------

            if($request->has('poderRepresentante2')){

                 $a2=Crypt::decrypt($request->poderRepresentante2);
                if($a2=='poderRepresentante2'){

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
                            array_push($arregloCampos,'poderRepresentante');
            }
          }

            //---------------APODERADO---------

          if($request->has('poderApoderado2')){
                $a3=Crypt::decrypt($request->poderApoderado2);
                if($a3=='poderApoderado2'){
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
                            array_push($arregloCampos,'poderApoderado');
                }
            }

            //---------------PROFESIONAL RESPONSABLE---------

        if($request->has('poderProfesional2')){
             $a4=Crypt::decrypt($request->poderProfesional2);
              if($a4=='poderProfesional2'){
                                if(!empty($solicitud->profesional)){
                                    $profesionalSol = $solicitud->profesional;
                                    $profesionalSol->idUsuarioModifica=$nit.'@'.$request->ip();
                                }
                                $profesionalSol->idProfesional=$request->idProfesional;
                                $profesionalSol->nitProfesional=$request->nitProfesional;
                                $profesionalSol->poderProfesional=$request->poderProf;
                                $profesionalSol->save();
                                array_push($arregloCampos,'poderProfesional');
             }
        }

         if(count($arregloCampos)>0) SubsanacionPreController::actualizarCamposDictamen($idSolicitud,$arregloCampos,$nit.'@'.$request->ip());




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

    public function storeStep4(Request $request){


        $nit = Session::get('user');
        $arregloCampos=[];

        DB::connection('sqlsrv')->beginTransaction();

        try{
                $idSolicitud=Crypt::decrypt($request->idSolicitud3);

                $solicitud = Solicitud::findOrFail($idSolicitud);
                $idUsuarioModificacion=$nit.'@'.$request->ip();

               if($solicitud->estadoDictamen!=4){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                }

            //------------------FABRICANTE PRINCIPAL-----------------
            if($request->has('nombreFabricante2')){
                $a1=Crypt::decrypt($request->nombreFabricante2);
                if($a1=='nombreFabricante2'){
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
                        array_push($arregloCampos,'nombreFabricante');
                 }
            }

            //-----------------FABRICANTES ALTERNOS------------------
            if($request->has('nombreFabAlterno2')){
                $a2=Crypt::decrypt($request->nombreFabAlterno2);
                if($a2=='nombreFabAlterno2'){

                        if(count($solicitud->fabricantesAlternos)>0){ $solicitud->fabricantesAlternos()->delete(); }
                        if($request->has('fabricantesAlternos')){
                            $arregloFabAlternos=[]; $nomaquilaFabAlt=$request->noMaquilaFabAlterno;
                            if(in_array($solicitud->fabricantePrincipal->idFabricante,$request->fabricantesAlternos)){
                                 return response()->json(['status' => 422, 'errors' => "¡El fabricante principal no debe ser seleccionado como alterno!"],422);
                            }
                            foreach ($request->fabricantesAlternos as $key => $idFabAlterno){ array_push($arregloFabAlternos,['idFabAlterno' =>$idFabAlterno,'procedencia'=>1,'noPoderMaquila'=>$nomaquilaFabAlt[$key], 'idUsuarioCreacion'=> $nit.'@'.$request->ip()]); }
                            $solicitud->fabricantesAlternos()->createMany($arregloFabAlternos);
                        }
                        array_push($arregloCampos,'nombreFabAlterno');
                }
            }
             //------------------FABRICANTES ACONDICIONADORES---------------------
             if($request->has('nombreLabAcondicionador2')){
                $a3=Crypt::decrypt($request->nombreLabAcondicionador2);
                if($a3=='nombreLabAcondicionador2'){

                            if(count($solicitud->laboratorioAcondicionador)>0){ $solicitud->laboratorioAcondicionador()->delete(); }
                            if($request->has('laboratorioAcondicionador')){
                                $arregloLabAcon=[];
                                $tipo=$request->tipoLabAcondicionador;
                                $nomaquiAcon=$request->noMaquilaFabAcon;
                                foreach ($request->laboratorioAcondicionador as $key => $idLab){
                                 array_push($arregloLabAcon,['idLabAcondicionador' =>  $idLab,'procedencia'=>1,'tipo'=>$tipo[$key],'noPoderMaquila'=>$nomaquiAcon[$key],'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                                }
                                $solicitud->laboratorioAcondicionador()->createMany($arregloLabAcon);
                            }
                array_push($arregloCampos,'nombreLabAcondicionador');
                }
            }

            //------------------LABORATORIOS RELACIONAL---------------------
             if($request->has('nombreLabRelacionado2')){
                $a4=Crypt::decrypt($request->nombreLabRelacionado2);
                if($a4=='nombreLabRelacionado2'){

                        if(count($solicitud->laboratorioRelacional)>0){ $solicitud->laboratorioRelacional()->delete(); }
                        if($request->has('laboratorioRelacionado')){
                            $arregloLabRela=[];
                            foreach ($request->laboratorioRelacionado as $idLabRela){ array_push($arregloLabRela,['idFab' =>$idLabRela,'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);}
                            $solicitud->laboratorioRelacional()->createMany($arregloLabRela);
                        }
                        array_push($arregloCampos,'nombreLabRelacionado');
                }
            }
            //-----------------FABRICANTE PRINCIPIO ACTIVO----------------
              if($request->has('nombreFabPrincipio2')){
                $a4=Crypt::decrypt($request->nombreFabPrincipio2);
                if($a4=='nombreFabPrincipio2'){

                        if(count($solicitud->fabprincipioactivo)>0){ $solicitud->fabprincipioactivo()->delete(); }
                        if($request->has('fabPrincipioActivo') && $request->has('origenfabprincipio') && $request->has('idprincpio') && $request->has('nombreprincipio')){
                            $arreglofabprincipio=[];
                            $origen=$request->origenfabprincipio; $idpri=$request->idprincpio; $nombrepri=$request->nombreprincipio;
                            foreach($request->fabPrincipioActivo as $key => $principio){
                                array_push($arreglofabprincipio,['idFabricante'=>$principio,'procedencia'=>$origen[$key],'idPrincipio'=>$idpri[$key],'nombrePrincipio'=>$nombrepri[$key],'tipo'=>1,'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                            }
                            $solicitud->fabprincipioactivo()->createMany($arreglofabprincipio);
                        }
                        array_push($arregloCampos,'nombreFabPrincipio');
                }
            }
            //----------------------CONTRATO DE MAQUILA-------------------------------------------

            $detalle=$solicitud->solicitudesDetalle;
            $detalle->poderMaquila=$request->valContratoMaquila;
            $detalle->save();

            if($request->valContratoMaquila==1){
                if($request->has('poderFabPrincipal2')){
                $b1=Crypt::decrypt($request->poderFabPrincipal2);
                if($b1=='poderFabPrincipal2'){
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
                            array_push($arregloCampos,'poderFabPrincipal');
                  }
                }
                if($request->has('poderFabAlterno2')){
                       $b2=Crypt::decrypt($request->poderFabAlterno2);
                       if($b2=='poderFabAlterno2'){
                            if(count($solicitud->poderfabAlterno)){ $solicitud->poderfabAlterno()->delete(); }
                            if($request->has('poderFabAlterno')){
                                $arrayPoderFabalterno=[];
                                foreach($request->poderFabAlterno as $fabAlter){
                                     array_push($arrayPoderFabalterno,['idPoder'=>$fabAlter,'usuarioCreacion'=>$nit.'@'.$request->ip()]);
                                }
                                $solicitud->poderfabAlterno()->createMany($arrayPoderFabalterno);
                            }
                             array_push($arregloCampos,'poderFabAlterno');
                       }
                }
               if($request->has('poderFabAcondicionador2')){
                    $b3=Crypt::decrypt($request->poderFabAcondicionador2);
                    if($b3=='poderFabAcondicionador2'){
                        if(count($solicitud->poderfabAcondicionador)){ $solicitud->poderfabAcondicionador()->delete(); }
                        if($request->has('poderFabAcondicionador')){
                            $arrayPoderFabAcondicionador=[];
                            foreach($request->poderFabAcondicionador as $fabacon){
                                 array_push($arrayPoderFabAcondicionador,['idPoder'=>$fabacon,'usuarioCreacion'=>$nit.'@'.$request->ip()]);
                            }
                            $solicitud->poderfabAcondicionador()->createMany($arrayPoderFabAcondicionador);
                        }
                        array_push($arregloCampos,'poderFabAcondicionador');
                    }
                }
            }else{
                 if(count($solicitud->poderfabAlterno)){ $solicitud->poderfabAlterno()->delete(); }
                 if(count($solicitud->poderfabAcondicionador)){ $solicitud->poderfabAcondicionador()->delete(); }
                 if(!empty($solicitud->poderfabprincipal)){  $solicitud->poderfabprincipal()->delete(); }
            }




           if(count($arregloCampos)>0) SubsanacionPreController::actualizarCamposDictamen($idSolicitud,$arregloCampos,$nit.'@'.$request->ip());


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


    public function storeStep5(Request $request){


        $nit = Session::get('user');
        DB::connection('sqlsrv')->beginTransaction();
        $arregloCampos=[];

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud4);

            $solicitud = Solicitud::findOrFail($idSolicitud);
            $idUsuarioModificacion=$nit.'@'.$request->ip();

            if($solicitud->estadoDictamen!=4){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
            }

            if(!empty($solicitud->manufactura)){

                $manufactu = $solicitud->manufactura;
                $manufactu->idUsuarioModifica=$nit.'@'.$request->ip();

                if($request->has('autoridadEmisora2')){
                     $a1=Crypt::decrypt($request->autoridadEmisora2);
                            if($a1=='autoridadEmisora2'){
                                    if($manufactu->autoridadEmisora!=$request->certificadolv){
                                        $manufactu->autoridadEmisora = $request->certificadolv;
                                        array_push($arregloCampos,'autoridadEmisora');
                                    }
                            }
                }
                if($request->has('nombreProductoProcedencia2')){
                     $a2=Crypt::decrypt($request->nombreProductoProcedencia2);
                            if($a2=='nombreProductoProcedencia2'){
                                    if($manufactu->nombreProductoProcedencia!=$request->nomProdPais){
                                         $manufactu->nombreProductoProcedencia = $request->nomProdPais;
                                         array_push($arregloCampos,'nombreProductoProcedencia');

                                    }
                            }
                }
                if($request->has('titularProducto2')){
                     $a3=Crypt::decrypt($request->titularProducto2);
                            if($a3=='titularProducto2'){
                                    if($manufactu->titularProducto!=$request->titularProductoC){
                                         $manufactu->titularProducto=$request->titularProductoC;
                                         array_push($arregloCampos,'titularProducto');
                                    }
                            }
                }

                   //dd(date('d-m-Y',strtotime($manufactu->fechaEmision)));
                   // 28-07-2018
                   // dd($request->fechaEmision);
                 if($request->has('fechaEmision2')){
                     $a3=Crypt::decrypt($request->fechaEmision2);
                            if($a3=='fechaEmision2'){
                                    if(date('d-m-Y',strtotime($manufactu->fechaEmision))!=$request->fechaEmision){
                                         $manufactu->fechaEmision = $request->fechaEmision;
                                         array_push($arregloCampos,'fechaEmision');
                                    }
                            }
                }

                 if($request->has('fechaVencimiento2')){
                     $a3=Crypt::decrypt($request->fechaVencimiento2);
                            if($a3=='fechaVencimiento2'){
                                    if(date('d-m-Y',strtotime($manufactu->fechaVencimiento))!=$request->fechaVencimiento){
                                          $manufactu->fechaVencimiento = $request->fechaVencimiento;
                                         array_push($arregloCampos,'fechaVencimiento');
                                    }
                            }
                }
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

            if(count($arregloCampos)>0) SubsanacionPreController::actualizarCamposDictamen($idSolicitud,$arregloCampos,$nit.'@'.$request->ip());


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
        $arregloCampos=[];
        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud5);
            //dd($idSolicitud);
            $solicitud = Solicitud::findOrFail($idSolicitud);
            $idUsuarioModificacion=$nit.'@'.$request->ip();

           if($solicitud->estadoDictamen!=4){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
            }
            //------------------FABRICANTE PRINCIPAL-----------------

                                    if(!empty($solicitud->bpmPrincipal)){
                                        $fabricantePrin = $solicitud->bpmPrincipal;
                                        $fabricantePrin->idUsuarioModifica=$nit.'@'.$request->ip();
                                        $fabricantePrin->idFabricantePpal=$request->idcertificadobpm;
                                           if($request->has('nombreEmisor2')){
                                                   $a1=Crypt::decrypt($request->nombreEmisor2);
                                                 if($a1=='nombreEmisor2'){
                                                            if($fabricantePrin->nombreEmisor!=$request->certificadobpm){
                                                             $fabricantePrin->nombreEmisor=$request->certificadobpm;
                                                             array_push($arregloCampos,'nombreEmisor');
                                                            }
                                                        }
                                            }
                                            if($request->has('fechaEmisionM2')){
                                                   $a2=Crypt::decrypt($request->fechaEmisionM2);
                                                  if($a2=='fechaEmisionM2'){
                                                            if(date('d-m-Y',strtotime($fabricantePrin->fechaEmision))!=$request->fechaEmision){
                                                             $fabricantePrin->fechaEmision=$request->fechaEmision;
                                                             array_push($arregloCampos,'fechaEmisionM');
                                                            }
                                                        }
                                            }
                                            if($request->has('fechaVencimientoM2')){
                                                   $a3=Crypt::decrypt($request->fechaVencimientoM2);
                                                  if($a3=='fechaVencimientoM2'){
                                                            if(date('d-m-Y',strtotime($fabricantePrin->fechaVencimiento))!=$request->fechaVencimiento){
                                                             $fabricantePrin->fechaVencimiento=$request->fechaVencimiento;
                                                             array_push($arregloCampos,'fechaVencimientoM');
                                                            }
                                                        }
                                            }
                                    }else{
                                        $fabricantePrin = new BpmPrincipal();
                                        $fabricantePrin->idSolicitud=$idSolicitud;
                                        $fabricantePrin->idUsuarioCreacion=$nit.'@'.$request->ip();
                                        $fabricantePrin->idFabricantePpal=$request->idcertificadobpm;
                                        if($request->has('certificadobpm')) $fabricantePrin->nombreEmisor=$request->certificadobpm;
                                        if($request->has('fechaEmision')) $fabricantePrin->fechaEmision=$request->fechaEmision;
                                        if($request->has('fechaVencimiento')) $fabricantePrin->fechaVencimiento=$request->fechaVencimiento;
                                    }

                                    $fabricantePrin->save();



            //-----------------FABRICANTES ALTERNOS------------------

              if($request->has('bpmAlterno2')){
                     $a4=Crypt::decrypt($request->bpmAlterno2);
                     if($a4=='bpmAlterno2'){

                            if(count($solicitud->bpmAlternos)>0){ $solicitud->bpmAlternos()->delete(); }
                            if($request->has('practLabAlternos')){
                                $arregloFabAlternos=[];
                                $f1= 'fechaEmision-'; $f2='fechaVencimiento-'; $em1='emisorAlterno-';
                                foreach ($request->practLabAlternos as $idFabAlterno){
                                    $fe = $f1.$idFabAlterno; $fv = $f2.$idFabAlterno; $emfab=$em1.$idFabAlterno;
                                    array_push($arregloFabAlternos,['idAlterno' =>$idFabAlterno,'nombreEmisor'=>$request->$emfab,'fechaEmision'=>$request->$fe,'fechaVencimiento'=>$request->$fv, 'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                                }

                                $solicitud->bpmAlternos()->createMany($arregloFabAlternos);
                                array_push($arregloCampos,'bpmAlterno');
                            }
                    }
             }

            //------------------FABRICANTES ACONDICIONADORES---------------------
             if($request->has('bpmAcondicionador2')){
                 $a6=Crypt::decrypt($request->bpmAcondicionador2);
                if($a6=='bpmAcondicionador2'){
                        if(count($solicitud->bpmAcondicionadores)>0){ $solicitud->bpmAcondicionadores()->delete(); }
                        if($request->has('practLabAcondi')){
                            $arregloLabAcon=[];
                            $f3= 'fechaEmision-'; $f4='fechaVencimiento-'; $em2='emisorAcondicionador-';
                            foreach ($request->practLabAcondi as $idLab){
                                $fe2 = $f3.$idLab; $fv2 = $f4.$idLab; $emacon=$em2.$idLab;
                                array_push($arregloLabAcon,['idAcondicionador' =>  $idLab,'nombreEmisor'=>$request->$emacon,'fechaEmision'=>$request->$fe2,'fechaVencimiento'=>$request->$fv2,'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                            }
                            $solicitud->bpmAcondicionadores()->createMany($arregloLabAcon);
                            array_push($arregloCampos,'bpmAcondicionador');
                        }
                }
            }

                     //------------------FABRICANTES RELACIONADOS--------------------
           if($request->has('bpmRelacionado2')){
                $a4=Crypt::decrypt($request->bpmRelacionado2);
                if($a4=='bpmRelacionado2'){
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
                     array_push($arregloCampos,'bpmRelacionado');
              }
            }
             //------------------FABRICANTES PRINCIPIO ACTIVO-------------------
            if($request->has('bpmPrinActivo2')){
                $a4=Crypt::decrypt($request->bpmPrinActivo2);
                if($a4=='bpmPrinActivo2'){
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
                     array_push($arregloCampos,'bpmPrinActivo');
                }
            }

        if(count($arregloCampos)>0) SubsanacionPreController::actualizarCamposDictamen($idSolicitud,$arregloCampos,$nit.'@'.$request->ip());



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
        $arregloCampos=[];
        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud6);

            $solicitud = Solicitud::findOrFail($idSolicitud);
            $idUsuarioModificacion=$nit.'@'.$request->ip();

            if($solicitud->estadoDictamen!=4){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
            }

            if($request->has('distribuidores2')){
                 $a1=Crypt::decrypt($request->distribuidores2);
                 if($a1=='distribuidores2'){
                            if(count($solicitud->distribuidores)>0){ $solicitud->distribuidores()->delete(); }
                            if($request->has('dist')){
                                $arregloDist=[];

                                foreach ($request->dist as $idDistribuidor){
                                    array_push($arregloDist,['idDistribuidor' =>$idDistribuidor,'idUsuarioCreacion'=> $nit.'@'.$request->ip()]);
                                }

                                $solicitud->distribuidores()->createMany($arregloDist);
                            }
                            array_push($arregloCampos,'distribuidores');
                }
             }

            if(count($arregloCampos)>0) SubsanacionPreController::actualizarCamposDictamen($idSolicitud,$arregloCampos,$nit.'@'.$request->ip());
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
                if($solicitud->estadoDictamen!=0){
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
        $arregloCampos=[];
        DB::connection('sqlsrv')->beginTransaction();

        try{
            $idSolicitud=Crypt::decrypt($request->idSolicitud8);
            //dd($idSolicitud);

            $solicitud = Solicitud::findOrFail($idSolicitud);
            $idUsuarioModificacion=$nit.'@'.$request->ip();

            if($solicitud->estadoDictamen!=4){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
            }

            //dd($request->all());
            if(!empty($solicitud->farmacologia)){
                $farmaco = $solicitud->farmacologia->detalle;
                $farmaco->idUsuarioModifica=$nit.'@'.$request->ip();

                 if($request->has('farmacocinetica2')){
                         $a1=Crypt::decrypt($request->farmacocinetica2);
                         if($a1=='farmacocinetica2'){
                                     if($farmaco->farmacocinetica!= $request->farm){
                                            $farmaco->farmacocinetica = $request->farm;
                                             array_push($arregloCampos,'farmacocinetica');
                                      }
                        }
                 }
                  if($request->has('mecanismoAccion2')){
                         $a2=Crypt::decrypt($request->mecanismoAccion2);
                         if($a2=='mecanismoAccion2'){
                                     if($farmaco->mecanismoAccion!= $request->mecaaccion){
                                             $farmaco->mecanismoAccion= $request->mecaaccion;
                                              array_push($arregloCampos,'mecanismoAccion');
                                      }
                        }
                 }
                 if($request->has('indicacionesTerapeuticas2')){
                         $a3=Crypt::decrypt($request->indicacionesTerapeuticas2);
                         if($a3=='indicacionesTerapeuticas2'){
                                     if($farmaco->indicacionesTerapeuticas!= $request->indicacion){
                                           $farmaco->indicacionesTerapeuticas = $request->indicacion;
                                           array_push($arregloCampos,'indicacionesTerapeuticas');
                                      }
                        }
                 }
                  if($request->has('contraindicaciones2')){
                         $a4=Crypt::decrypt($request->contraindicaciones2);
                         if($a4=='contraindicaciones2'){
                                     if($farmaco->contraindicaciones!= $request->contrad){
                                            $farmaco->contraindicaciones = $request->contrad;
                                             array_push($arregloCampos,'contraindicaciones');
                                      }
                        }
                 }
                  if($request->has('regimenDosis2')){
                         $a5=Crypt::decrypt($request->regimenDosis2);
                         if($a5=='regimenDosis2'){
                                     if($farmaco->regimenDosis!=$request->dos){
                                           $farmaco->regimenDosis = $request->dos;
                                           array_push($arregloCampos,'regimenDosis');
                                      }
                        }
                 }
                 if($request->has('efectosAdversos2')){
                         $a6=Crypt::decrypt($request->efectosAdversos2);
                         if($a6=='efectosAdversos2'){
                                     if($farmaco->efectosAdversos!= $request->efectos){
                                          $farmaco->efectosAdversos = $request->efectos;
                                          array_push($arregloCampos,'efectosAdversos');
                                      }
                        }
                 }
                  if($request->has('precauciones2')){
                         $a7=Crypt::decrypt($request->precauciones2);
                         if($a7=='precauciones2'){
                                     if($farmaco->precauciones!= $request->adv){
                                           $farmaco->precauciones = $request->adv;
                                            array_push($arregloCampos,'precauciones');
                                      }
                        }
                 }
                 if($request->has('interacciones2')){
                         $a8=Crypt::decrypt($request->interacciones2);
                         if($a8=='interacciones2'){
                                     if($farmaco->interacciones!= $request->interaccion){
                                         $farmaco->interacciones = $request->interaccion;
                                          array_push($arregloCampos,'interacciones');
                                      }
                        }
                 }
                   if($request->has('categoriaTerapeutica2')){
                         $a9=Crypt::decrypt($request->categoriaTerapeutica2);
                         if($a9=='categoriaTerapeutica2'){
                                     if($farmaco->categoriaTerapeutica!= $request->codigoatc){
                                        $farmaco->categoriaTerapeutica = $request->codigoatc;
                                          array_push($arregloCampos,'categoriaTerapeutica');
                                      }
                        }
                 }

                $farmaco->idUsuarioModifica = $nit.'@'.$request->ip();
                $farmaco->save();

               if(count($arregloCampos)>0) SubsanacionPreController::actualizarCamposDictamen($idSolicitud,$arregloCampos,$nit.'@'.$request->ip());
            }else{
                $farmaco = new Farmacologia();
                $farmaco->idSolicitud=$idSolicitud;
                $farmaco->idUsuarioCreacion=$nit.'@'.$request->ip();
                $farmaco->save();
                $arreglo =[];
                $arreglo=[
                    'farmacocinetica' => $request->farm,
                    'mecanismoAccion'=> $request->mecaaccion,
                    'indicacionesTerapeuticas' => $request->indicacion,
                    'contraindicaciones' => $request->contrad,
                    'regimenDosis' => $request->dos,
                    'efectosAdversos' => $request->efectos,
                    'precauciones' => $request->adv,
                    'interacciones' => $request->interaccion,
                    'categoriaTerapeutica' => $request->codigoatc,
                    'idUsuarioCreacion' => $nit.'@'.$request->ip()
                ];
                $farmaco->detalle()->create($arreglo);
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
        $i = 0;
        $nit = Session::get('user');
        $ar= $request->file('file-es');


        try{
            $edicion=false;
            $urlsEliminar=[];
            $idSolicitud=Crypt::decrypt($request->idSolicitud9);

                $solicitud = Solicitud::findOrFail($idSolicitud);
                $usuarioCreacion=$nit.'@'.$request->ip();

                if($solicitud->estadoDictamen!=4){
                    return response()->json(['status' => 422, 'errors' => "¡No se puede guardar el paso porque la solicitud no tiene permiso para editar!"],422);
                 }

                /*if($request->has('docGuardado')){
                    $edicion=true;
                    foreach ($solicitud->documentos as $detalleDocumento)
                        if(!in_array($detalleDocumento->idDoc, $request->docGuardado)){
                            array_push($urlsEliminar,$detalleDocumento->urlArchivo);
                            //$detalleDocumento->delete();
                            $doc = PreDocumentos::find($detalleDocumento->idDoc);
                            $doc->delete();

                            DB::connection('sqlsrv')->commit();
                        }
                }*/


                    $saveDocs=$this->guardarDocumentos($solicitud->idSolicitud,$request->file('file-es'),$usuarioCreacion,null,$urlsEliminar,$edicion);


                if($saveDocs==0){
                    DB::rollback();
                    return response()->json(['status' => 422, 'errors' => "¡Error en el sistema: No se han podido guardar los documentos adjuntos, por favor vuelva intentarlo!"],422);
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
            $solicitud = Solicitud::findOrFail($idSolicitud);
            $idUsuarioModificacion=$nit.'@'.$request->ip();
            $camposPendientes=Dictamen::getCamposObservador($idSolicitud);
            $camposDocumentos=Dictamen::getDocumentosObsSubsanacion($idSolicitud);
            //dd(count($camposDocumentos)>0);
            //dd(count($camposDocumentos)>0);
            if(!empty($camposPendientes)){
                $aler ='¡Debe de verificar que ningun campo se encuentre observado para volver enviar la solicitud!';
                return response()->json(['status' => 422, 'errors' => $aler],422);
            }elseif(!empty($camposDocumentos)){
                $aler ='¡Debe de verificar que ningun documento se encuentre observado para volver enviar la solicitud!';
                return response()->json(['status' => 422, 'errors' => $aler],422);
            }else{
                    $now = Carbon::now('America/El_Salvador');
                    //$now =  Carbon::parse('2019-05-18 00:00:00');
                    $valDiaHabil = $this->esDiaHabil($now);
                    $fechaRecepcionSubsanacion=$now->format('Y-m-d  H:i:s');
                    if($valDiaHabil){
                             $fechaSubsanacion=$now->format('Y-m-d  H:i:s');
                             $msg='¡Se ha guardado la solicitud existosamente!';
                             $idEstado=11;
                    }else{
                            $fechaSubsanacion=$this->siguienteDiaHabil($now);
                            $msg='¡Se ha guardado la solicitud existosamente pero sera recibida en la siguiente fecha hábil '.$fechaSubsanacion.'!';
                            $idEstado=16;
                    }
                    $solicitud->fechaRecepcionSubsanacion=$fechaRecepcionSubsanacion;
                    $solicitud->fechaSubsanacion=$fechaSubsanacion;
                    $solicitud->estadoDictamen=$idEstado;
                    $solicitud->idUsuarioModifica=$idUsuarioModificacion;
                    $solicitud->save();
                    SolicitudSeguimiento::create(['idSolicitud'=>$solicitud->idSolicitud,'estadoSolicitud'=>11,'observaciones'=>'Solicitud subsanada','idUsuarioCreacion'=>$nit.'@'.$request->ip(),'fechaCreacion'=>Carbon::now('America/El_Salvador')]);

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
             $arregloCampos=[];
            $filesystem= new Filesystem();
            if($filesystem->exists($npath)) {
                if ($filesystem->isWritable($npath)) {
                    $newpath = $npath . $idSolicitudNew;
                    if (!empty($files)){
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

                            $valDoc = 'docrequisito'.$index;
                            array_push($arregloCampos, $valDoc);


                        }

                        $dicActualizar = Dictamen::where('idSolicitud',$idSolicitudNew)->where('estadoDictamen',4)->pluck('idDictamen')->toArray();
                        for($j=0;$j<count($dicActualizar);$j++){
                            for($k=0;$k<count($arregloCampos);$k++){
                               $val = ValidacionDictamen::where('idDictamen',$dicActualizar[$j])->where('campo',$arregloCampos[$k])->first();
                                   if(!empty($val)){
                                           $val->estadoCampo=3;
                                           $val->usuarioModificacion=$idUsuarioCrea;
                                           $val->save();
                                    }
                            }
                         }

                        $lab = UnificacionPortal::where('idSolicitud',$idSolicitudNew)->get()->first();
                           if(!empty($lab)){
                                if(count($lab->revisionMetodologica)>0){
                                    $revision = $lab->revisionMetodologica()->orderBy('fecha_creacion','desc')->pluck('id_revision_metodologica');
                                    for($j=0;$j<count($revision);$j++){
                                            for($k=0;$k<count($arregloCampos);$k++){
                                               $val = ValidacionDictamen::where('idDictamen',$revision[$j])->where('campo',$arregloCampos[$k])->where('esLab',1)->first();
                                                   if(!empty($val)){
                                                           $val->estadoCampo=3;
                                                           $val->usuarioModificacion=$idUsuarioCrea;
                                                           $val->save();
                                                    }
                                            }
                                         }
                                }
                          }

                        DB::commit();

                        return 1;
                    } else {
                        return 1;
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
     static function modificarDocCopia($idSolicitudNew,$index,$idUsuarioCrea,$url){

        $doc = PreDocumentos::where('idSolicitud',$idSolicitudNew)->where('idItemRequisitoDoc',$index)->first();
        if(!empty($doc)){
            $doc->urlArchivo=$url;
            $doc->idUsuarioModifica=$idUsuarioCrea;
            $doc->save();
        }

    }



    public function obtenerValidacionesCampo(Request $request){
    $id = Crypt::decrypt($request->idSolicitud);
    $solicitud = Solicitud::findOrFail($id);
    $dictamenes = Dictamen::where('idSolicitud', $id)->where('estadoDictamen',4)->pluck('idDictamen');
    $validaciones = ValidacionDictamen::whereIn('idDictamen',$dictamenes)->where('campo',$request->campo)->get();


            $lab = UnificacionPortal::where('idSolicitud',$id)->get()->first();
            if(!empty($lab)){
                if(count($lab->revisionMetodologica)>0){
                     $revision = $lab->revisionMetodologica()->orderBy('fecha_creacion','desc')->pluck('id_revision_metodologica');
                     //CONSULTAMOS LAS OBSERVACIONES DE LOS DOCUMENTOS COPIAS OBSERVADOS POR LABORATORIO
                     $campoDoc = $request->campo;
                     $validacionesUcc = ValidacionDictamen::whereIn('idDictamen',$revision)->where('esLab',1)->where('campo',$campoDoc)->get();

                     $arraymerge = $validaciones->merge($validacionesUcc);
                     $validaciones = $arraymerge;
                }
            }

    //dd($validaciones);
    return Datatables::of($validaciones)
          ->addColumn('dictamen',function($dt){
            if ($dt->dictamen) {
              if($dt->dictamen->tipoDictamen==1)
                return 'Médico';
              else
                return 'Químico';
             }else{
              return 'Laboratorio';
             }
          })
          ->make('true');
    //dd($request->idSolicitud);
  }

 public function actualizarCamposDictamen($idSolicitud,$campos,$usuario){

          $arregloCampos=$campos;
           $dicActualizar = Dictamen::where('idSolicitud',$idSolicitud)->where('estadoDictamen',4)->pluck('idDictamen')->toArray();
            for($j=0;$j<count($dicActualizar);$j++){
                for($i=0;$i<count($arregloCampos);$i++){
                   $val = ValidacionDictamen::where('idDictamen',$dicActualizar[$j])->where('campo',$arregloCampos[$i])->first();
                       if(!empty($val)){
                               $val->estadoCampo=3;
                               $val->usuarioModificacion=$usuario;
                               $val->save();
                        }
                }
             }
 }

     public function indexDocNumeracion($idSolicitud){

        $data = ['title'           => 'Documentos solicitud'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Ver Solicitud', 'url' => route('get.preregistrorv.index.subsanarSolicitud',['idSolicitud'=>$idSolicitud])],
                    ['nom'  =>  'Documentos solicitud', 'url' => '#']
                ]];
         try {

             $id = Crypt::decrypt($idSolicitud);

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
        return view('registro.nuevoregistro.subsanacion.numeraciondoc',$data);
    }

}
