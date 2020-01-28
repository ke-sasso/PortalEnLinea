<?php

namespace App\Models\Registro\Sol;

use Illuminate\Database\Eloquent\Model;

class SolicitudMandamientos extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.SOL.solicitudMandamientos';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    protected $fillable = ['idSolicitud','idMandamiento','fechaVencimiento','usuarioCreacion'];

    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }


}
