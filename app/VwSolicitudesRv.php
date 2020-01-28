<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class VwSolicitudesRv extends Model
{
    //
    protected $table = 'dnm_usuarios_portal.vw_solicitudesrv';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;


    public static function getSolicitudesRv($nit){
    	return DB::table('dnm_usuarios_portal.vw_solicitudesrv')
    			->whereIn('NO_REGISTRO',function($query) use ($nit){
		        		$query->select('idProducto')
		        			  ->from('dnm_usuarios_portal.vwproductos')
		        			  ->where('idPersonaNatural',$nit);
		        	})
    			->orderBy('ID_SOLICITUD','DESC')->distinct();

    }
}
