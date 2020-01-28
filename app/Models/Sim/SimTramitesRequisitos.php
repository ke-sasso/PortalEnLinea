<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;
use DB;

class SimTramitesRequisitos extends Model
{
    //
    protected $table = 'sim.sim_tramites_tipos_requisitos';
    protected $primaryKey = 'idTramiteRequisito';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';

	public static function getDocumentosByRequisito(){

		return DB::table('sim.sim_tramite_post_requisitos as re')
				->join('sim.sim_tramite_post_requisito_documento as red','re.idRequisito','=','red.requisitoId')
				->join('sim.sim_tramite_post_documento as doc','red.documentoTramiteId','=','doc.idTramiteDocumento')
				->select('red.idRequisitoDocumento','red.documentoTramiteId', 'doc.descripcionDocumento')
				->get();
	}

	public static function getRequisitosByTramite(){
		
		return DB::table('sim.sim_tramites_tipos_requisitos as tr')
				->join('sim.sim_tramite_post_requisitos as re','tr.requisitoId','=','re.idRequisito')
				//->join('sim.sim_tramite_post_requisito_documento as red','re.idRequisito','=','red.requisitoId')
				//'red.idRequisitoDocumento'
				->select('tramiteTipoId','tr.requisitoId','nombreRequisito')
				->get();
	}

	public static function getDocumentosByRequisitoByTramite(){
		return DB::table('sim.sim_tramites_tipos_requisitos as tr')
			->join('sim.sim_tramite_post_requisitos as re','tr.requisitoId','=','re.idRequisito')
			->join('sim.sim_tramite_post_requisito_documento as red','re.idRequisito','=','red.requisitoId')
			->join('sim.sim_tramite_post_documento as doc','red.documentoTramiteId','=','doc.idTramiteDocumento')
			->select('tr.idTramiteRequisito','tr.tramiteTipoId','re.idRequisito','re.nombreRequisito','red.idRequisitoDocumento','red.documentoTramiteId', 'doc.descripcionDocumento')
			//->groupBy('idTramiteRequisito')
			->get();
	}

    public static function validarRequisitos($indexs,$idTramite){

        $requiRequeridos= SimTramitesRequisitos::where('tramiteTipoId',$idTramite)->where('requerido',1)->orderBy('requisitoId')->pluck('requisitoId');

        $requiSubidos=RequisitoDocumento::whereIn('idRequisitoDocumento',$indexs)->distinct()->orderBy('requisitoId')->pluck('requisitoId');

        $validados=false;

        if($requiRequeridos==$requiSubidos){
            $validados=true;
        }

        return $validados;


    }
}
