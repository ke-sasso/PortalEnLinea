<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use GuzzleHttp\Client;
use Config;
class EstablecimientoPortal extends Model
{
    //

    protected $table = 'dnm_establecimientos_si.est_informacion_portal';
    protected $primaryKey = 'id';
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';
}
