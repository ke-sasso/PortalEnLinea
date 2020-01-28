<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;
use DB;
class VueTramitesTipos extends Model
{
    //
    protected $table = 'cssp.siic_solicitudes_vue_tramites_tipos';
    protected $primaryKey = 'ID_TRAMITE';
    public $timestamps = false;

    public static function getTramiteByTipo($idTipoTramite){


    	return DB::table('cssp.siic_solicitudes_vue_tramites_tipos')->where('ACTIVO','A')
    			->where('ID_TIPO_TRA',$idTipoTramite)
    			->select('*',DB::raw('CASE WHEN ROL_USUARIO=6 THEN "ADMIN"
			  							   WHEN ROL_USUARIO=5 THEN "JURI"
              							   WHEN ROL_USUARIO=3 THEN "MEDI"
							               WHEN ROL_USUARIO=4 THEN "MEDI"
							               WHEN ROL_USUARIO=9 THEN "MEDI"
							               WHEN ROL_USUARIO=1 THEN "QUIM"
							               WHEN ROL_USUARIO=2 THEN "QUIM"
							               WHEN ROL_USUARIO=7 THEN "QUIM"
							               WHEN ROL_USUARIO=8 THEN "QUIM"
							               END tipo'))
    			->orderBy('NOMBRE_TRAMITE','asc')
                ->whereNotIn('ID_TRAMITE',[58,59,54,66,21,37,38,39,29,27,57,45,46,64,44])
                ->get();

    }

     public function tramite(){
        return $this->belongsTo('App\Models\Cssp\SolicitudesVueTramites');
    }
}
