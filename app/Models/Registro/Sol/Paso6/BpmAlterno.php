<?php

namespace App\Models\Registro\Sol\Paso6;

use Illuminate\Database\Eloquent\Model;

class BpmAlterno extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO6.bpmAlternos';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

       protected $fillable = ['idAlterno','nombreEmisor','fechaEmision','fechaVencimiento','idUsuarioCreacion'];

    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }
}
