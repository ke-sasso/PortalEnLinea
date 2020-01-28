<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipios extends Model {

	protected $table = 'dnm_catalogos.cat.municipios';
    protected $primaryKey = 'idMunicipio';
    public $timestamps = false;
	protected $connection = 'sqlsrv';

	public static function getList($idDepartamento)
	{
		return Municipios::where('idDepartamento',$idDepartamento)->pluck('nombreMunicipio','idMunicipio');
	}

	public function departamento()
    {
        return $this->belongsTo('App\Models\Departamentos','idDepartamento','idDepartamento');
    }

	public static function getIdDepartamento($idMunicipio)
	{
		$municipio = Municipios::find($idMunicipio);
		return $municipio->idDepartamento;
	}

}
