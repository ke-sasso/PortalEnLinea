<?php namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;

class Propietarios extends Model {

	protected $table = 'cssp.cssp_propietarios';
    protected $primaryKey = 'ID_PROPIETARIO';
    public $timestamps = false;
    public $incrementing = false;


    public static function findPropSelectize($query){
		return Propietarios::where('NOMBRE_PROPIETARIO','LIKE','%'.$query.'%')
			->orWhere('NIT','LIKE','%'.$query.'%')
			->select('ID_PROPIETARIO','NIT','NOMBRE_PROPIETARIO')->take(50)->get();
	}

    public function productos(){
        return $this->hasMany('App\Models\Cssp\Productos', 'ID_PROPIETARIO', 'ID_PROPIETARIO');
    }
}
