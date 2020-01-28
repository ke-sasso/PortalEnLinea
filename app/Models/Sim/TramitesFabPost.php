<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class TramitesFabPost extends Model
{
    //
    protected $table = 'sim.sim_tramites_fabricante_post';
    protected $primaryKey = 'id_tramfab';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';
}
