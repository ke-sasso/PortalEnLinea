<?php 
namespace App\Models\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Rama extends Model {

	protected $table = 'dnm_catalogos.cat_ramas';
    protected $primaryKey = 'idRama';
	public $timestamps = false;
	//protected $connection = 'sqlsrv';

	public static function getList($idJunta)
	{
		return Rama::where('idJunta',$idJunta)->where("activo","A")->pluck('nombreRama','idRama');
	}
}
