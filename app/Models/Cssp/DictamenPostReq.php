<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;

class DictamenPostReq extends Model
{
    //
    protected $table = 'cssp.si_urv_solicitudes_postregistro_dictamenes_requisitos';
    protected $primaryKey = 'ID_DICTAMEN';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION'; 



	public function dictamen(){
		return $this->belongsTo('App\Models\Cssp\DictamenPost', 'ID_DICTAMEN', 'ID_DICTAMEN');
	}
}
