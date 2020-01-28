<?php

namespace App\Models\Registro\Cat;

use Illuminate\Database\Eloquent\Model;
use DB;
class CodigoAtc extends Model
{
  //
  protected $connection = 'sqlsrv';
  protected $table = 'dnm_urv_si.CAT.codigosAtc';
  protected $primaryKey='id';
  public $timestamps    = false;

    public function subAtc()
    {
        return $this->hasMany('App\Models\Registro\Cat\CodigoAtc', 'parentAtc', 'codigoAtc');
    }

    public static  function  getOptions(){


        $options = CodigoAtc::whereIn('nivelAtc',[3,4])->orderBy('nombre')->get()->map(function ($atc) {
            $subatcs = $atc->subAtc->map(function($subatc) use ($atc) {
                return  '<option value="'.$subatc->codigoAtc.'">'.trim($subatc->codigoAtc).' '.trim($subatc->nombre).'</option>';
            })->toArray();

            return ['id' => $atc->codigoAtc,'atc' => trim($atc->codigoAtc).' '.trim($atc->nombre), 'subatcs' => $subatcs];
        });
            /*->keyBy('id')->map(function($subpersona) {
            return $subpersona['subpersonas'];
        });*/

       return $options;
    }
    public static function getPaisesCA(){
        $pais=DB::connection('sqlsrv')->table('dnm_catalogos.cat.paises')
        ->select('idPais','nombre','codigoId')->where('activo','A')
        ->whereIn('codigoPais',['GT','NI','HN','CR','PA'])->get();
        return $pais;
    }

}
