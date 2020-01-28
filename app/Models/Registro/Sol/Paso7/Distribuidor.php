<?php

namespace App\Models\Registro\Sol\Paso7;

use Illuminate\Database\Eloquent\Model;

class Distribuidor extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO7.distribuidores';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    protected $fillable = ['idDistribuidor','idUsuarioCreacion'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }
}
