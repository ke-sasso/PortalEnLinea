<?php 
namespace App\Models\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model {

	protected $table = 'dnm_catalogos.dnm_inscripciones_portal';
    protected $primaryKey = 'idInscripcion';
	public $timestamps = false;
	//protected $connection = 'sqlsrv';

}
