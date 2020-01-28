<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

//tabla donde se relaciona el dictamen creado por la solicitud y los items
// se les asigana si cumplen o no cumplen los items
class DictamenPostItems extends Model
{
    //
    protected $table = 'sim.sim_dictamen_post_registro_items';
    protected $primaryKey = 'id_dictamen_item_post';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';
}
