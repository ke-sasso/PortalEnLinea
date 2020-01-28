<?php

namespace App\Models\Registro\Sol;

use Illuminate\Database\Eloquent\Model;

class Paso3Interesado extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO3.interesados';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    protected $fillable = ['idSolicitud','nombres','apellidos','direccion','email','telefono1','telefono2','idUsuarioCreacion','idUsuarioModifica'];


    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }

}
