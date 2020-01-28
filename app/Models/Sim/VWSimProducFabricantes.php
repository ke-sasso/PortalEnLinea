<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class VWSimProducFabricantes extends Model
{
    //
    protected $table = 'sim.vw_productos_insumos_fabricantes';
    protected $primaryKey = 'ID_PRODUCTO';
    public $timestamps = false;
}
