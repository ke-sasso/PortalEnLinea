<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;
use DB;
class SolicitudesVueTramites extends Model
{
    //TABLA PARA EMPAREJAR CADA SOLICITUD CON UN DETERMINADO TRAMITE 
    protected $table = 'cssp.siic_solicitudes_vue_tramites';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;

    public static function store($idSolicitud,$idTramite){
        /*las columnas junta medica, y orden se guadar por defecto, 4 y 0 respectivamente*/
    	$solTramite = new SolicitudesVueTramites();
    	$solTramite->ID_SOLICITUD=$idSolicitud;
    	$solTramite->ID_TRAMITE=$idTramite;
    	$saved=$solTramite->save();
        if(!$saved){
            return view('errors.500');
        }

    }

    public function SolicitudesVue(){
        return $this->hasMany('App\Models\Cssp\SolicitudesVue', 'ID_SOLICITUD', 'ID_SOLICITUD');
    }

    public function SolTramiteTipos(){
        return $this->hasMany('App\Models\Cssp\VueTramitesTipos', 'ID_TRAMITE', 'ID_TRAMITE');
    }

    public function SolPostTraArea(){
        return $this->belongsTo('App\Models\Cssp\SiUrv\SolPostTraArea', 'ID_TRAMITE', 'ID_TRAMITE');
    }
}
