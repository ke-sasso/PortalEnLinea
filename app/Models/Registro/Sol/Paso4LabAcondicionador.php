<?php

namespace App\Models\Registro\Sol;

use Illuminate\Database\Eloquent\Model;

class Paso4LabAcondicionador extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO4.laboratoriosAcondicionadores';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }
    protected $fillable = ['idLabAcondicionador','procedencia','tipo','noPoderMaquila','idUsuarioCreacion'];

    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }

}
