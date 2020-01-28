<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class DesistimientoSol extends Model
{
    //
    protected $table = 'sim.sim_desistimiento_solicitud';
    protected $primaryKey = 'id_desistimiento';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';

}
