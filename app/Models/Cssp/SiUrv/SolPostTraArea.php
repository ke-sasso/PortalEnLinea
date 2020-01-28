<?php

namespace App\Models\Cssp\SiUrv;

use Illuminate\Database\Eloquent\Model;

class SolPostTraArea extends Model
{
    //
    protected $table = 'cssp.si_urv_solicitudes_postregistro_tramites_areas';
    protected $primaryKey = 'ID_TRAMITE';
    public $incrementing= false;
    const CREATED_AT = 'FECHA_CREACION';
    const UPDATED_AT = 'FECHA_MODIFICACION';


    public function SolVueTramite(){
        return $this->hasMany('App\Models\Cssp\SolicitudesVueTramites', 'ID_TRAMITE', 'ID_TRAMITE');
    }

}
