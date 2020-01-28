<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class CertificacionPost extends Model
{
    //
    protected $table = 'sim.sim_certificacion_post_registro';
    protected $primaryKey = 'id_certificacion_post';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';
}
