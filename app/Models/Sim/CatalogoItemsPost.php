<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

//catalogo de los items para el dictamen de una solicitud
class CatalogoItemsPost extends Model
{
    //
	protected $table = 'sim.sim_catalogo_items_post';
    protected $primaryKey = 'id_item';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';

}
