<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class SimDictamenPost extends Model
{
    //
    protected $table = 'sim.sim_dictamen_post_registro';
    protected $primaryKey = 'id_dictamen_post';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';
}
