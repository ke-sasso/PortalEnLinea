<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use GuzzleHttp\Client;
use Config;
class VwSolicitudes extends Model
{
    //
     protected $table = 'dnm_publicidad_si.vwsolicitudes';
    protected $primaryKey = 'idSolicitud';
    public $timestamps = false;



	/*
	public static function getDataRowsPublicidad(){
		
		$result1 = DB::table('dnm_publicidad_si.vwsolicitudes as sol')
					->leftJoin('dnm_publicidad_si.pub_solicitud_dictamen as soldic','sol.idSolicitud','=','soldic.idSolicitud')
					->leftJoin('dnm_publicidad_si.pub_cat_estado_solicitud as estado','soldic.idEstadoDictamen','=','estado.idEstado')
					->leftJoin('dnm_publicidad_si.pub_tipo_dictamen as tipo','soldic.tipoDictamen','=','tipo.idTipoDictamen')
					->where('soldic.tipoDictamen',3)
					->groupBy('soldic.idSolicitud')
					->orderBy('soldic.fechaCreacion', 'desc')
					->select('sol.*',DB::raw('group_concat("<td>","Fecha: ",soldic.fechaCreacion,"<br>","Estado:",estado.nombreEstado,"<br>","Dictamen: ",tipo.nombreDictamen,"<br>",soldic.observaciones,"<br>","</td>","<td>","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;","</td>") as dictamenes'));

		return $result1;
	}*/

	public static function solicitud($idSolicitud){
		$url = Config::get('app.api');
		if($idSolicitud){

			/*$solicitud = DB::table('dnm_publicidad_si.pub_solicitudes')->where('idSolicitud',$idSolicitud)->first();*/
			$client = new Client();
            $res = $client->request('POST', $url.'pyp/get/solicitud',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            
                ],
                'form_params' =>[
                    'solicitud'  =>$idSolicitud
                ]   
            ]);

            $r = json_decode($res->getBody());

            if($r->status == 200){
				return $r->data;
			}
			elseif($r->status == 400){
				return null;
			}

		}
	}
	// primero buscamos en la tabla solicitudes si el numeroregistro ya tiene otra solicitud como 
	//solicitud padre si ya la tiene retorne 1 sino 0
	public static function getSolicitudPadre($numeroSolicitud){
		if($numeroSolicitud){

			$solPadre = DB::table('dnm_publicidad_si.pub_solicitudes')->where('solicitudPadre',$numeroSolicitud)
							->first();
			if($solPadre!=null)
				return 1;
			else
			  return 0;
			/*
			$solitudPadre= DB::table('dnm_publicidad_si.vwsolicitudes')->where('numeroSolicitud',$numSolPadre->solicitudPadre)
							->first();*/
			//return $solitudPadre;
		}

	}
	public static function getSolicitudDocumentos($idSolicitud){

		return DB::table('dnm_publicidad_si.pub_solicitud_documentos')
			->where('idSolicitud',$idSolicitud)
			->get();

	}
}
