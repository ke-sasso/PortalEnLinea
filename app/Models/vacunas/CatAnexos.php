<?php

namespace App\Models\vacunas;

use Illuminate\Database\Eloquent\Model;

class CatAnexos extends Model
{
    //
     protected $table = 'dnm_vacunas.cat_anexos';
    protected $primaryKey = 'id_anexo';
	const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';
}
