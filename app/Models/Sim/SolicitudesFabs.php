<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;
use DB;
class SolicitudesFabs extends Model
{
    //
    protected $table = 'sim.sim_solicitudes_fabricantes';
    protected $primaryKey = 'CORRELATIVO';
    public $timestamps = false;


    public static function getFabricantesResol($idSolicitud){
    	/*
    	$fabricantes=DB::table('sim.sim_solicitudes_fabricantes as sfab')
			    	->join('sim.sim_fabricantes as fab','sfab.ID_FABRICANTE','=','fab.ID_FABRICANTE')
			    	->leftJoin('cssp.cssp_paises as pa','fab.PAIS','=','pa.ID_PAIS')
			    	->where('sfab.ID_SOLICITUD',$idSolicitud)
			    	->select(DB::raw('group_concat(fab.NOMBRE_FABRICANTE SEPARATOR "; ") as fabricantes, group_concat(ifnull(pa.NOMBRE_PAIS,"N/A") SEPARATOR ", ") as paises'))
			    	->first();
		//dd($fabricantes);
		if($fabricantes->fabricantes!=null && $fabricantes->paises!=null){
			$fabs=explode(';',$fabricantes->fabricantes);
			$pas=explode(',',$fabricantes->paises);
			
			$concatfab='';
			$concatpa='';
			for($i=0;$i<count($fabs);$i++) {
				if($i!=count($fabs)-2){
				  $concatfab.=(string)$fabs[$i].',';	
				  $concatpa.=(string)$pas[$i].',';
				}
				else{
				  $concatfab.=(string)$fabs[$i].' y por ';	
				  $concatpa.=(string)$pas[$i].' y';
				}
			}
			$concatfab=substr_replace($concatfab, "", -1);
			$concatpa=substr_replace($concatpa, "", -1);
			
			$data['concatpa']=$concatpa;
			$data['concatfab']=$concatfab;

			return $data;
		}
		else{
			return null;
		}*/
		return DB::select('call sim.sp_fabricantes_actualizacion_arte(?)',array($idSolicitud));
    }
    
}
