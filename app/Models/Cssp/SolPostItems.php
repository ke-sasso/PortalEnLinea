<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;

class SolPostItems extends Model
{
    //
    protected $table = 'cssp.si_urv_solicitudes_postregistro_lista_chequeo_items';
    protected $primaryKey = 'ID_ITEM';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION'; 
}
