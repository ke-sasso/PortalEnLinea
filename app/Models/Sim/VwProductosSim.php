<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;
use DB;
class VwProductosSim extends Model
{
    //
    protected $table = 'dnm_usuarios_portal.vw_productossim';
    protected $primaryKey = 'CORRELATIVO';
    public $timestamps = false;


    public static function getDataRows(){
    	return DB::table('dnm_usuarios_portal.vw_productossim')->orderBy('CORRELATIVO','desc');
    }
}
