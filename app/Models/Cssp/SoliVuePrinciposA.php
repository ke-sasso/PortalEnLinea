<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;
use DB;
class SoliVuePrinciposA extends Model
{
    // GUARDAR EL PRINCIPIO ACTIVO DEL PRODUCTO DE LA SOLICITUD.
    protected $table = 'cssp.siic_solicitudes_vue_principios_activos';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;

    public static function store($idSolicitud,$nregistro){
    	if($nregistro){

            $principiosA= DB::table('cssp.siic_productos_formula as pf')
                          ->join('cssp.siic_materias_primas as mp','pf.ID_PRINCIPIO_ACTIVO','=','mp.ID_MATERIA_PRIMA')
                          ->join('cssp.cssp_unidades_medida as unm','pf.ID_UNIDAD_MEDIDA','=','unm.ID_UNIDAD_MEDIDA')
                          ->where('pf.id_producto',$nregistro)
                          ->select('pf.ID_PRINCIPIO_ACTIVO','mp.NOMBRE_MATERIA_PRIMA','pf.ID_UNIDAD_MEDIDA','unm.NOMBRE_UNIDAD_MEDIDA','pf.CONCENTRACION','pf.PORCENTAJE_1','pf.PORCENTAJE_2')
                          ->first();
            
            if($principiosA!=null){
              //dd($principiosA);
              $solPrincipiosA = new SoliVuePrinciposA();

              $solPrincipiosA->ID_SOLICITUD=$idSolicitud;
              $solPrincipiosA->ID_MATERIA_PRIMA=$principiosA->ID_PRINCIPIO_ACTIVO;
              $solPrincipiosA->CONCENTRACION=$principiosA->CONCENTRACION;
              $solPrincipiosA->ID_UNIDAD_MEDIDA=$principiosA->ID_UNIDAD_MEDIDA;

              $saved=$solPrincipiosA->save();
              if(!$saved){
                return view('errors.500');
              }
            }
            
      }
      else{
        
      }

    }
}
