<?php

namespace App\Models\Cssp\Mandamientos;

use Illuminate\Database\Eloquent\Model;
use DB;
class Mandamiento extends Model
{
  protected $table = 'cssp.cssp_mandamientos';
  protected $primaryKey = 'ID_MANDAMIENTO';
  public $incrementing = false;
  const CREATED_AT = 'FECHA_CREACION';
  const UPDATED_AT = 'FECHA_MODIFICACION';

  public function detalle()
  {
    return $this->hasMany('App\Models\Cssp\Mandamientos\MandamientoDetalle','ID_MANDAMIENTO','ID_MANDAMIENTO');
  }
  public static function countGenenrados($idCliente,$idPagos){
  	     $data1=date('Y')."/01/01";
         $data2=date('Y')."/12/31";
         $result = DB::table('cssp.cssp_mandamientos as A')
         ->join('cssp.cssp_mandamientos_detalle as B','B.ID_MANDAMIENTO','=','A.id_mandamiento')
         ->whereIn('B.ID_TIPO_PAGO',$idPagos)
         ->where('B.id_cliente','=',$idCliente)
         ->where('A.FECHA','>=',$data1)
         ->where('A.FECHA','<=',$data2)
         ->count();
        return $result;
  }
}
