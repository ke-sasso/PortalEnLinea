<?php

namespace App\Models\Ucc;

use Illuminate\Database\Eloquent\Model;

class RevisionMetodologicaPortal extends Model
{
  protected $table='ucc.si_ucc_revision_metodologica_portal';
	protected $primaryKey='id_revision_metodologica';
	const CREATED_AT ='fecha_creacion';
	const UPDATED_AT ='fecha_modificacion';

	public function unificacion()
	{
		return $this->belongsTo('App\Models\Ucc\UnificacionPortal','id_unificacion_revision','id_unificacion');
	}

	public function campos()
	{
		 return $this->hasMany('App\Models\Registro\Dic\ValidacionDictamen','id_revision_metodologica','idDictamen');
	}


}