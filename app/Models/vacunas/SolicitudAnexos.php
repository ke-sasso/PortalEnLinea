<?php

namespace App\Models\vacunas;

use Illuminate\Database\Eloquent\Model;

class SolicitudAnexos extends Model
{
    //
    protected $table = 'dnm_vacunas.solicitudes_anexos';
    protected $primaryKey = 'id';
	public $timestamps = false;
}
