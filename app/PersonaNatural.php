<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class PersonaNatural extends Model
{
    //
    protected $table = 'dnm_catalogos.dnm_persona_natural';
    protected $primaryKey = 'nitNatural';
    const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

	public function tratamiento(){

    	return $this->belongsTo('App\Models\Catalogos\Tratamiento');
    }
    public function usuarioPortal(){
    	return $this->hasOne('App\UserPortal','idUsuario','nitNatural')->select('oirNotificaciones');
    }
     public function updateUsuarioPortal(){
    	return $this->hasOne('App\UserPortal','idUsuario','nitNatural');
    }

	public static function getTratamiento($nit){
		if($nit!=null){
		$tratamiento = DB::table('dnm_catalogos.cat_tratamiento as tra')
							->join('dnm_catalogos.dnm_persona_natural as per','tra.idTipoTratamiento','=','per.idTipoTratamiento')
							->where('per.nitNatural',$nit)
							->select('tra.nombreTratamiento')->first();
		return $tratamiento;
		}

	}
}
