<?php

namespace App\Models\Cssp\Siic;

use Illuminate\Database\Eloquent\Model;

class ProductoFabricante extends Model
{
    //
    protected $table = 'cssp.siic_productos_fabricantes';
    protected $primaryKey = 'ID_FABRICANTE';
    public $incrementing = false;
    const CREATED_AT = 'FECHA_CREACION';
    const UPDATED_AT = 'FECHA_MODIFICACION';
    protected $fillable = ['ID_ESTABLECIMIENTO','NOMBRE_COMERCIAL'];

    protected $with = ['establecimiento'];

    public function establecimiento()
    {
        return $this->hasOne('App\Models\Cssp\CsspEstablecimiento', 'ID_ESTABLECIMIENTO', 'ID_FABRICANTE');
    }



}
