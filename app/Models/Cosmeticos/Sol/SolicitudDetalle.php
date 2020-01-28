<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class SolicitudDetalle extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.solicitudesDetalle';
    public    $incrementing=false;
    protected $primaryKey='idDetalle';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function solicitud(){
        return $this->belongsTo('App\Models\Cosmeticos\Sol\Solicitud','idSolicitud','idSolicitud');
    }

    public function detallesCosmetico(){
        return $this->hasOne('App\Models\Cosmeticos\Sol\DetalleCosmetico','idDetalle','idDetalle');
    }

    public function detallesHigienicos(){
        return $this->hasOne('App\Models\Cosmeticos\Sol\DetalleHigienico','idDetalle','idDetalle');
    }
}
