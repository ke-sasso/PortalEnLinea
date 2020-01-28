<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;

class ActualizacioMonoInsert extends Model
{
    //
    protected $table = 'cssp.siic_sol_postregistro_actualizacion_mon_inserto';
    protected $primaryKey = 'ID_ACTUALIZACION';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION';
}
