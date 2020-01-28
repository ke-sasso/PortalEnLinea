<?php

namespace App\Models\Cosmeticos\Cos;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProfesionalCos extends Model
{
    //

    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.COS.profesionalesCosmeticos';
    protected $primaryKey='idProfesional';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';


    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }
      public function productos(){
        return $this->hasMany('App\Models\Cssp\Productos','ID_PRODUCTO','idCosmetico');
    }
    public static function productosByProfesional($idProfesional){
        $cosmeticos =  ProfesionalCos::where('idProfesional',$idProfesional)->pluck('idCosmetico');
        $productos = DB::connection('sqlsrv')->table('dnm_cosmeticos_si.COS.Cosmeticos')
                ->select('idCosmetico','nombreComercial','vigenciaHasta','renovacion','actualizado','fechaActualizado')
                ->whereIn('idCosmetico',$cosmeticos)->where('estado','A')->distinct();
        return $productos;
    }

}
