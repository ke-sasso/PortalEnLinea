<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class SolCodigoModelo extends Model
{
    //
    protected $table = 'sim.sim_solicitud_codigos_modelos';
    protected $primaryKey = 'id_solicitud_codmod';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';
}
