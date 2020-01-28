<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;

class SolVuePresetanciones extends Model
{
    //
	protected $table = 'cssp.siic_solicitudes_vue_detalle_presentaciones';
    protected $primaryKey = 'ID_DETALLE_PRE';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION'; 

	public function solicitud(){
		return $this->belongsTo('App\Models\Cssp\SolicitudesVue', 'ID_SOLICITUD', 'ID_SOLICITUD');
	}

	public function presentacion(){
		return $this->belongsTo('App\Models\Cssp\VwProductosPresentaciones', 'ID_PRESENTACION', 'ID_PRESENTACION');
	}
}
