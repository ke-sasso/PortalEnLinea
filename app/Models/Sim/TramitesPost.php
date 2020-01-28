<?php

namespace App\Models\Sim;
use Illuminate\Database\Eloquent\Model;

class TramitesPost extends Model
{
    //
    protected $table = 'sim.sim_tramites_post_tipos';
    protected $primaryKey = 'id';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';
}
