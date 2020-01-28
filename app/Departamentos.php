<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamentos extends Model
{
    //

    protected $table = 'dnm_catalogos.cat_departamentos';
    protected $primaryKey = 'idDepartamento';
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

	public function municipios(){
		return $this->hasMany('App\Models\Catalogos\Municipios', 'idDepartamento', 'idDepartamento');
	}

	public function pais(){
    	return $this->belongsTo('App\Models\Catalogos\Paises', 'idPais', 'codigoId');
    }

}
