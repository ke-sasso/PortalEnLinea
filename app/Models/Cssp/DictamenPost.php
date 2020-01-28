<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;

class DictamenPost extends Model
{
    //
    protected $table = 'cssp.si_urv_solicitudes_postregistro_dictamenes';
    protected $primaryKey = 'ID_DICTAMEN';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION'; 



	public function dicRequisitos()
    {
        return $this->hasMany('App\Models\Cssp\DictamenPostReq','ID_DICTAMEN', 'ID_DICTAMEN');
    }

    public function solicitud(){
		return $this->belongsTo('App\Models\Cssp\SolicitudesVue', 'ID_SOLICITUD', 'ID_SOLICITUD');
	}
}
