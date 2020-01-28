<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;
use DB;
class SoliVueFabricantes extends Model
{
    //
	protected $table = 'cssp.siic_solicitudes_vue_fabricantes';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;

    public static function store($idSolicitud,$nregistro){

    	$fabricantes= DB::table('cssp.siic_productos_fabricantes as pf')
		                ->join('cssp.cssp_establecimientos as est','pf.id_fabricante', '=','est.id_establecimiento')
		                ->leftJoin('cssp.cssp_paises as pa', 'est.id_pais','=','pa.id_pais')
		                ->where('pf.id_producto',$nregistro)
		                ->select('pf.id_fabricante','est.nombre_comercial', 'pf.tipo','pf.contrato_maquila','pf.vigente_hasta', 'pf.ultimo_pago',DB::raw('ifnull(pa.nombre_pais,"N\A") as nombrepa'))
		                ->get();
		                
		foreach ($fabricantes as $fabs) {
			
		
		
			$solFabricante = new SoliVueFabricantes();

			$solFabricante->ID_SOLICITUD=$idSolicitud;
			$solFabricante->ID_FABRICANTE=$fabs->id_fabricante;
			$solFabricante->NOMBRE_FABRICANTE=$fabs->nombre_comercial;
			$solFabricante->CONTRATO_MAQUILA=$fabs->contrato_maquila;
			$solFabricante->VIGENTE_HASTA=$fabs->vigente_hasta;
			$solFabricante->ULTIMO_PAGO=$fabs->ultimo_pago;
			$solFabricante->TIPO=$fabs->tipo;

			$saved=$solFabricante->save();
			if(!$saved){
	            	return view('errors.500');
	        }
    	}
    }
}
