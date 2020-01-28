<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamientos extends Model {

	protected $table = 'dnm_catalogos.cat_tratamiento';
    protected $primaryKey = 'idTipoTratamiento';
	public $timestamps = false;
	//protected $connection = 'sqlsrv';

}
