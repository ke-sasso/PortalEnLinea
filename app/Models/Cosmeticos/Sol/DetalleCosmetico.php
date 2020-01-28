<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class DetalleCosmetico extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.detalleCosmetico';
    protected $primaryKey='id';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    protected $fillable = ['idDetalle', 'idClasificacion', 'idFormaCosmetica'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function solicitudesDetalle(){
        return $this->belongsTo('App\Models\Cosmeticos\Sol\SolicitudDetalle','idDetalle','idDetalle');
    }

    public function clasificacion()
    {
      return $this->belongsTo('App\Models\Cosmeticos\Cat\Clasificacion','idClasificacion','idClasificacion');
      // code...
    }
}
