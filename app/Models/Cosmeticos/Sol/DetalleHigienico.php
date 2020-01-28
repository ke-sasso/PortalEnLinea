<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class DetalleHigienico extends Model
{
    //

    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.detalleHigienico';
    protected $primaryKey='id';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    protected $fillable = ['idDetalle', 'idClasificacion', 'uso'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function clasificacion()
    {
        return $this->belongsTo('App\Models\Cosmeticos\Cat\ClasificacionHig','idClasificacion','idClasificacion');
        // code...
    }
}
