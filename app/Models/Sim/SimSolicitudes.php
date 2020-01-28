<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;
use DB;
class SimSolicitudes extends Model
{
    //
    protected $table = 'sim.sim_solicitudes';
    protected $primaryKey = 'ID_SOLICITUD';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION';

	public static function getSolicitudesCertificadas($nit){
		return DB::table('sim.vw_solicitudes_post_portalenlinea')
				->where('NIT_SOLICITANTE',$nit)
				->orderBy('ID_SOLICITUD','desc');
	}

	public  function soltramites(){
    	return $this->hasMany('App\Models\Sim\SolicitudTramitePost','solicitud_id','ID_SOLICITUD');    
    }

    public static function fabricantesResolucion($idProducto){
        return DB::select('call sim.sp_fabricantes_resolucion_post(?)',array($idProducto));
    }
}
