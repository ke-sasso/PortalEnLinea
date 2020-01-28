<?php

namespace App\Models\Registro\Sol\Paso6;

use Illuminate\Database\Eloquent\Model;

class BpmPrincipal extends Model
{
    //

    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO6.bpmPrincipal';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }
}
