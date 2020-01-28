<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;

class SolVueDocs extends Model
{
    //si_urv_postregistro_documentos
    protected $table = 'cssp.si_urv_postregistro_documentos';
    protected $primaryKey = 'ID_SOL_DOC';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION'; 
}
