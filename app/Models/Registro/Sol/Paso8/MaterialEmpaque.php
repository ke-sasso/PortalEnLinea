<?php

namespace App\Models\Registro\Sol\Paso8;

use Illuminate\Database\Eloquent\Model;

class MaterialEmpaque extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO8.materialEmpaque';
    protected $primaryKey='idMaterial';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }
}
