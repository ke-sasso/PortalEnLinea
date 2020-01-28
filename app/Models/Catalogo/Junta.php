<?php 
namespace App\Models\Catalogo;

use Illuminate\Database\Eloquent\Model;

class Junta extends Model {

	protected $table = 'dnm_catalogos.cat_juntas';
    protected $primaryKey = 'idJunta';
	public $timestamps = false;
	//protected $connection = 'sqlsrv';

	public static function getList()
	{
		return Junta::where('esJunta','Si')->whereIn("idJunta",["P01","P06","P07"])->pluck('nombreJunta','idJunta');
	}
}
