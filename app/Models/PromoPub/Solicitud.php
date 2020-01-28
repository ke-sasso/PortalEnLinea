<?php

namespace App\Models\PromoPub;

use Illuminate\Database\Eloquent\Model;
use DB;

class Solicitud extends Model
{
    protected $table = 'dnm_publicidad_si.pub_solicitudes';
    protected $primaryKey='idSolicitud';    
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public static function getSolicitudes($nit){
        return DB::table('dnm_usuarios_portal.vw_solicitudes_pub as sol')
            ->where('sol.nitSolicitante',$nit)
            ->orderBy('sol.idSolicitud','desc')
            ->select('sol.*');     
    }

}
