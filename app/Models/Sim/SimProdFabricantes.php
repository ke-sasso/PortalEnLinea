<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class SimProdFabricantes extends Model
{
    //sim_productos_fabricantes
    protected $table = 'sim.sim_productos_fabricantes';
    protected $primaryKey = 'id_producto_fabricante';
    public $timestamps = false;
}
