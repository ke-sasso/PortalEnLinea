<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    //
    protected $table = 'dnm_catalogos.cat_tratamiento';
    protected $primaryKey = 'idTipoTratamiento';
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

	public function personas()
    {
        return $this->hasMany('App\Models\Catalogos\PersonaNatural');
    }
}
