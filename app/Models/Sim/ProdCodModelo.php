<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;
use DB;
class ProdCodModelo extends Model
{
    //
    protected $table = 'sim.sim_producto_codigos_modelos';
    protected $primaryKey = 'id_producto_codmod';
    const CREATED_AT = 'fecha_creacion';
	const UPDATED_AT = 'fecha_modificacion';

	public static function getModelosByIm($idproducto){
		return	DB::table('sim.sim_producto_codigos_modelos')
					->where('modelos','<>','')
					->where('producto_id',$idproducto)
					->groupBy('modelos')
					->get();
		
	}

	
}
