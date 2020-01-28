<?php

namespace App\Models\Registro\Sol;

use Illuminate\Database\Eloquent\Model;

class PreDocumentos extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PRE.solicitudDocumentos';
    protected $primaryKey='idDoc';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }

}
