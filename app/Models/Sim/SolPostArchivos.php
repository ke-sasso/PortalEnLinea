<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class SolPostArchivos extends Model
{
    //
    protected $table = 'sim.sim_solicitudes_post_archivos';
    protected $primaryKey = 'id_sol_archivo';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';
}
