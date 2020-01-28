<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paises extends Model
{
    //
    protected $table = 'dnm_catalogos.cat_paises';
    protected $primaryKey = 'idPais';
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';
}
