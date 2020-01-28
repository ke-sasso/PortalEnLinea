<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\DnmUsuariosPortal\vwPerfilPortal;

class vwSolicitudesRvUsuario extends Model
{
    //
    protected $table = 'dnm_usuarios_portal.vw_solicitudes_rv_ptl';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;


    public static function getAllSolicitudesRv($nit){

    	
    	$pfr=DB::select('select idDNM from dnm_usuarios_portal.vwperfilportal where NIT = "'.$nit.'" and UNIDAD = CONVERT( "RV" USING UTF8) COLLATE utf8_general_ci and PERFIL = CONVERT( "PFR" USING UTF8) COLLATE utf8_general_ci');

    	$apo=DB::select('select idDNM from dnm_usuarios_portal.vwperfilportal where NIT = "'.$nit.'" and UNIDAD = CONVERT( "RV" USING UTF8) COLLATE utf8_general_ci and PERFIL = CONVERT( "APO" USING UTF8) COLLATE utf8_general_ci');
    	
    	return DB::table('dnm_usuarios_portal.vw_solicitudes_rv_ptl')
    			->where(function($query) use ($nit,$pfr,$apo){
                    $query->whereIn('NO_REGISTRO',function($query) use ($nit){
                            $query->select('idProducto')
                                  ->from('dnm_usuarios_portal.vwproductos')
                                  ->where('idPersonaNatural',$nit);
                           })
                          ->orWhere(function ($query) use ($pfr,$apo) {
                    
                                if(!empty($pfr) && !empty($apo)){
                                    if($apo[0]->idDNM!='AP00000'){
                                        $query->where('ID_PROFESIONAL',$pfr[0]->idDNM)
                                              ->where('ID_APODERADO',$apo[0]->idDNM);     
                                    }
                                }
                                else if(!empty($pfr)){
                                    $query->where('ID_PROFESIONAL',$pfr[0]->idDNM); 
                                }
                                else if(!empty($apo)){
                                    if($apo[0]->idDNM!='AP00000'){
                                        $query->where('ID_APODERADO',$apo[0]->idDNM);       
                                    }
                                }
                            })
                          ->orWhere(function ($query) use ($nit){
                                $query->where('NIT_SOLICITANTE',$nit);
                           });
                });

                
    			 /*->orWhere(function ($query) use ($pfr,$apo) {
    			 	
                    if(!empty($pfr) && !empty($apo)){
                        if($apo[0]->idDNM!='AP00000'){
                            $query->where('ID_PROFESIONAL',$pfr[0]->idDNM)
                                  ->where('ID_APODERADO',$apo[0]->idDNM);     
                        }
                    }
                    else if(!empty($pfr)){
    			 		$query->where('ID_PROFESIONAL',$pfr[0]->idDNM);	
    			 	}
    			 	else if(!empty($apo)){
    			 		if($apo[0]->idDNM!='AP00000'){
    			 			$query->where('ID_APODERADO',$apo[0]->idDNM);		
    			 		}
    			 	}
	            });*/
    			

    }
}
