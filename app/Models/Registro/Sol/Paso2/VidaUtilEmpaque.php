<?php

namespace App\Models\Registro\Sol\Paso2;

use Illuminate\Database\Eloquent\Model;

class VidaUtilEmpaque extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO2.vidautilEmpaque';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }
    protected $fillable = ['idSolicitud','empaquePrimario','idMaterial','vidaUtil','nombreMaterial','nombrePrimario','observacion','idUsuarioCreacion','idUsuarioModifica'];

    public function solicitud(){
        return $this->belongsTo('App\Models\Registro\Sol\Solicitud','idSolicitud','idSolicitud');
    }
    public function tipoperiodo(){
        return $this->hasOne('App\Models\Registro\Cat\PeriodoVidaUtil','idPeriodo','idPeriodo');
    }
}
