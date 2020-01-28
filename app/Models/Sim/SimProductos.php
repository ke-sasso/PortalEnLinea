<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class SimProductos extends Model
{
    //
    protected $table = 'sim.sim_productos';
    protected $primaryKey = 'CORRELATIVO';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION';

	
}
