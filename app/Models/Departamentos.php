<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Municipios;

class Departamentos extends Model {

	protected $table = 'dnm_catalogos.cat.departamento';
    protected $primaryKey = 'idDepartamento';
    public $timestamps = false;
	protected $connection = 'sqlsrv';

	public static function getList($idPais = 222)
	{
		// Si no recibe parametro, filtar por defecto El Salvador
		return Departamentos::where('idPais', $idPais)->pluck('nombreDepartamento','idDepartamento');
	}

	public static function getListByMunicipio($idMunicipio)
	{
		$municipio = Municipios::find($idMunicipio);
		$departamento = Departamentos::find($municipio->idDepartamento);
		return Departamentos::where('idPais', $departamento->idPais)->pluck('nombreDepartamento','idDepartamento');
	}

}
