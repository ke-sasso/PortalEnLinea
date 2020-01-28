<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class SimFabricantes extends Model
{
    //sim_fabricantes
    protected $table = 'sim.sim_fabricantes';
    protected $primaryKey = 'ID_FABRICANTE';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION';
}
