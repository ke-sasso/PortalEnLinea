<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipios extends Model
{
    //

    protected $table = 'dnm_catalogos.cat_municipios';
    protected $primaryKey = 'idMunicipio';
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

	public function departamento(){
    	return $this->belongsTo('App\Models\Catalogos\Departamentos', 'idDepartamento', 'idDepartamento');
    }
}
