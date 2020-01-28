<?php

namespace App\Models\vacunas;

use Illuminate\Database\Eloquent\Model;
use DB;
class SolPrincipiosActi extends Model
{
    //
    protected $table = 'dnm_vacunas.solicitudes_principios_activos';
    protected $primaryKey = 'idCorrelativo';
	public $timestamps = false;



	public static function getPrincpioActivoByProd($idPrincipio,$idProducto){
		return	DB::table('cssp.siic_productos_formula as a')
			->join('cssp.siic_materias_primas as b','a.id_principio_activo','=','b.id_materia_prima')
			->join('cssp.cssp_unidades_medida as c','a.id_unidad_medida','=','c.id_unidad_medida')
			->where('b.activo','A')->where('ID_PRINCIPIO_ACTIVO',$idPrincipio)->where('a.id_producto',$idProducto)
			->select('a.ID_PRODUCTO','b.nombre_materia_prima','a.id_principio_activo','a.id_unidad_medida','c.nombre_unidad_medida','a.CONCENTRACION','a.PORCENTAJE_1','a.PORCENTAJE_2')
			->first();
		
	}

}
