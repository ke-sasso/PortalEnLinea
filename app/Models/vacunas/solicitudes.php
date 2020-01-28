<?php

namespace App\Models\vacunas;

use Illuminate\Database\Eloquent\Model;
use DB;
use GuzzleHttp\Client;
use Config;
class solicitudes extends Model
{
    //

    protected $table = 'dnm_vacunas.solicitudes';
    protected $primaryKey = 'id';
	const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'ult_fecha_edicion';

  
}
