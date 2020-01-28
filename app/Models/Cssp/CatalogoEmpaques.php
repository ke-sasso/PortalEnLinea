<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;
use DB;
class CatalogoEmpaques extends Model
{
    //
    	
    protected $table = 'cssp.si_urv_productos_empaques_presentaciones';
    protected $primaryKey = 'ID_PRESENTACION';
    const CREATED_AT = 'FECHA_CREACION';
	const UPDATED_AT = 'FECHA_MODIFICACION';


	public function detallePresentaciones()
    {
        return $this->hasMany('App\Models\Cssp\SolVuePresetanciones','ID_PRESENTACION', 'ID_PRESENTACION');
    }
	
	public static function getEmpaquesByProd($nregistro){

		$empaques1=DB::table('cssp.si_urv_productos_empaques_presentaciones')->where('ID_PRODUCTO',(string)$nregistro)
					->select('EMPAQUE_PRIMARIO')
					->distinct()
					->get();
		//dd(count($empaques1));
		$emp1=[];
		if(count($empaques1)>0){
			for($i=0;$i<count($empaques1);$i++) {
				$emp1[$i]=$empaques1[$i]->EMPAQUE_PRIMARIO;
			}
		}

		$empaques=DB::table('cssp.si_urv_productos_material_presentaciones')
					  ->where('ID_PRODUCTO',$nregistro)->distinct()->pluck('ID_EMPAQUE')
					  ->toArray();
		
		//dd($emp1);
		if(count($emp1)>0 || !empty($empaques)){
			$primarias= DB::table('cssp.si_urv_empaque_presentaciones')
					    ->whereIn('ID_EMPAQUE',$emp1)
					    ->orWhereIn('ID_EMPAQUE',$empaques)
					    ->get();

			return $primarias;
		}
		else{
			return DB::table('cssp.si_urv_empaque_presentaciones')->get();
		}
		
		/*
		if(count($emp2)>0){
			$secundarias= DB::table('cssp.si_urv_empaque_presentaciones')
						->whereIn('ID_EMPAQUE',$emp2)
						->union($primarias);
			}
		else{
			return $primarias->get();
		}
		
					
		
		if(count($emp3)>0){
			$terciarias= DB::table('si_urv_empaque_presentaciones')
					->whereIn('ID_EMPAQUE',$emp3)
					->union($secundarias)
					->get();
			return $terciarias;
		}
		else{
			//dd($emp2);
			return $secundarias->get();
		}

		*/
		
	}

	public static function getContenidoByProd($nregistro){
		
		$contenido1=DB::table('cssp.si_urv_productos_empaques_presentaciones')->where('ID_PRODUCTO',(string)$nregistro)
					->select('CONTENIDO_PRIMARIO')
					->distinct()
					->get();
		//dd(count($empaques1));
		$cont1=[];
		if(count($contenido1)>0){
			for($i=0;$i<count($contenido1);$i++) {
				$cont1[$i]=$contenido1[$i]->CONTENIDO_PRIMARIO;
			}
		}

		if(count($cont1)>0){
			$primcont= DB::table('cssp.si_urv_contenido_presentaciones')
					    ->whereIn('ID_CONTENIDO',$cont1)->get();

			return $primcont;
		}
		else{
			return DB::table('cssp.si_urv_contenido_presentaciones')->get();
		}
		

	}

	public static function getMaterialByProd($nregistro){
		$idMaterial=DB::table('cssp.si_urv_productos_empaques_presentaciones')
			->where('ID_PRODUCTO',$nregistro)
			->whereNotNull('ID_MATERIAL')
			->distinct()
			->pluck('ID_MATERIAL')
			->toArray();

		$idMateriales=DB::table('cssp.si_urv_productos_material_presentaciones')
					  ->where('ID_PRODUCTO',$nregistro)->distinct()->pluck('ID_MATERIAL')
					  ->toArray();

		
		if(!empty($idMaterial) || !empty($idMateriales)){
			
			$material=DB::table('cssp.si_urv_material_presentaciones')
				->orWhereIn('ID_MATERIAL',$idMateriales)
				->orWhereIn('ID_MATERIAL',$idMaterial)
				->select('ID_MATERIAL','NOMBRE_MATERIAL')
				->groupBy('ID_MATERIAL')
				->get()
				->toArray();
			return $material;
		}
		else{
			$material=null;
			return $material;
		}
	}

	public static function getColorByProd($nregistro){
		$idColor=DB::table('cssp.si_urv_productos_empaques_presentaciones')
			->where('ID_PRODUCTO',$nregistro)
			->distinct()
			->pluck('ID_COLOR')
			->toArray();
		
		$idColores=DB::table('cssp.si_urv_productos_material_presentaciones')
					  ->where('ID_PRODUCTO',$nregistro)->distinct()->pluck('ID_COLOR')
					  ->toArray();

		if(!empty($idColor) || !empty($idColores)){
			$color=DB::table('cssp.si_urv_color_presentaciones')
				->orWhereIn('ID_COLOR',$idColor)
				->orWhereIn('ID_COLOR',$idColores)
				->select('ID_COLOR','NOMBRE_COLOR')
				->get()
				->toArray();
				
			return $color;
		}
		else{
			$color=null;
			return $color;
		}
	}
}
