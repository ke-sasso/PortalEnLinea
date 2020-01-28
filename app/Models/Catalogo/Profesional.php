<?php 
namespace App\Models\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Profesional extends Model {

	protected $table = 'dnm_catalogos.dnm_profesionales';
    protected $primaryKey = 'idProfesional';
	public $timestamps = false;
	//protected $connection = 'sqlsrv';

}
