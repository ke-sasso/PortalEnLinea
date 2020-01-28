<?php

namespace App\Http\Controllers\Registro;

use App\Models\Cssp\Propietarios;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Config;
use App\Models\Cssp\Productos;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

use Log;
use Carbon\Carbon;
use Exception;

use App\Models\Cssp\UnidadMedida;
use App\Models\Cssp\SiUrv\TipoMedicamento;
use App\Models\Cssp\Siic\ViaAdministracion;
use App\Models\Cssp\Siic\FormaFarmaceutica;
use App\Models\Cssp\Siic\MateriaPrima;

class ToolsRvController extends Controller
{
    //
	private $url=null;
    
    public function __construct() { 
        $this->url = Config::get('app.api');
    }


    public function validatePoderProf(Request $request){

    	$client = new Client();
		$res = $client->request('POST', $this->url.'pel/poder/profesional',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'numPoder' => $request->numPoder
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			//dd($r);
			return response()->json(['status' => 200, 'data' => $r->data],200);
			//$data['poderProf']=$r->data[0];	
		}
		
		
    }

    public function validatePoderApo(Request $request){

    	$client = new Client();
		$res = $client->request('POST', $this->url.'pel/poder/apoderado',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'numPoder' => $request->numPoder
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			//dd($r);
			return response()->json(['status' => 200, 'data' => $r->data],200);
			//$data['poderProf']=$r->data[0];	
		}
		
		
    }

    public function desistimientoSolicitud(Request $request){
    	
    	//dd($idSolicitud);
    	DB::beginTransaction();
    	
    	try{
	    	$solicitud=SolicitudesVue::find($request->idSolicitud);
	    	$solicitud->ACTIVO='S';
	    	$solicitud->ID_ESTADO=7;
	    	$solicitud->USUARIO_MODIFICA="portalenlinea";
	    	$solicitud->save();

	    	$solicitud->dictamenes->first()->dicRequisitos->first()->delete();
	    	$solicitud->dictamenes()->delete();

	    	DB::commit();

	    	return back();

	    }
	    catch (Throwable $e) {
	            DB::rollback();
	            throw $e;
	            return $e;
	    }
	    catch(Exception $e){
	        DB::rollback();
	        throw $e;
	        Session::flash('msnError', $e->getMessage());
	        return $e;
	    }
    }

    public function getTiposMedicamentos(Request $request){
        try {
            if($request->has('q')){
                $tiposMedicamentos= TipoMedicamento::scopeNombreMed($request->q)->get()->toJson();
            }
            else{
                $tiposMedicamentos= TipoMedicamento::scopeNombreMed()->get()->toJson();
            }

            return $tiposMedicamentos;
        }catch (ModelNotFoundException $e){
            Log::error('Error model TipoMedicamento not found',['time'=>Carbon::now(),'code'=>$e->getCode,'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, no se puedo realizar al consulta de tipos de medicamentos'
            ],400);
        } catch (Exception $e) {
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode,'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getViasAdministracion(Request $request){
        try {
            if($request->has('q')){
                $viasAdministracion= ViaAdministracion::scopeNomViaAdmin($request->q)->get()->toJson();
            }
            else{
                $viasAdministracion= ViaAdministracion::scopeNomViaAdmin()->get()->toJson();
            }

            return $viasAdministracion;
        }catch (ModelNotFoundException $e){
            Log::error('Error model ViaAdministracion not found',['time'=>Carbon::now(),'code'=>$e->getCode,'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, no se puedo realizar al consulta de vias de administración'
            ],400);
        } catch (Exception $e) {
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode,'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getFormasFarmaceuticas(Request $request){
        try {
            if($request->has('q')){
                $viasAdministracion= FormaFarmaceutica::scopeNomForma($request->q)->get()->toJson();
            }
            else{
                $viasAdministracion= FormaFarmaceutica::scopeNomForma()->get()->toJson();
            }

            return $viasAdministracion;
        }catch (ModelNotFoundException $e){
            Log::error('Error model FormaFarmaceutica not found',['time'=>Carbon::now(),'code'=>$e->getCode,'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, no se puedo realizar al consulta de formas farmaceuticas'
            ],400);
        } catch (Exception $e) {
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode,'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getMateriasPrimas(Request $request){
        try {
            if($request->has('q')){
                $materiasPrimas= MateriaPrima::scopeNomMatPrima($request->q)->get()->toJson();
            }
            else{
                $materiasPrimas= MateriaPrima::scopeNomMatPrima()->get()->toJson();
            }

            return $materiasPrimas;
        }catch (ModelNotFoundException $e){
            Log::error('Error model MateriaPrima not found',['time'=>Carbon::now(),'code'=>$e->getCode,'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, no se puedo realizar al consulta de materias primas'
            ],400);
        } catch (Exception $e) {
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode,'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getUnidadesMedida(Request $request){
        try {
            if($request->has('q')){
                $unidadesMedida= UnidadMedida::scopeUnidadMed($request->q)->get()->toJson();
            }
            else{
                $unidadesMedida= UnidadMedida::scopeUnidadMed()->get()->toJson();
            }

            return $unidadesMedida;
        }catch (ModelNotFoundException $e){
            Log::error('Error model MateriaPrima not found',['time'=>Carbon::now(),'code'=>$e->getCode,'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, no se puedo realizar al consulta de materias primas'
            ],400);
        } catch (Exception $e) {
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode,'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }



}
