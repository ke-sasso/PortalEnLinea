<?php

namespace App\Models\DnmUsuariosPortal;

use Illuminate\Database\Eloquent\Model;
use DB;
class vwSolicitudesPreRvPtl extends Model
{
    //
    protected $table = 'dnm_usuarios_portal.tbl_solicitudes_pre_rv';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;

    public static function getSolicitudesPreRv($nit){


    	$pfr=DB::select('select idDNM from dnm_usuarios_portal.vwperfilportal where NIT = "'.$nit.'" and UNIDAD = CONVERT( "RV" USING UTF8) COLLATE utf8_general_ci and PERFIL = CONVERT( "PFR" USING UTF8) COLLATE utf8_general_ci');

    	$apo=DB::select('select idDNM from dnm_usuarios_portal.vwperfilportal where NIT = "'.$nit.'" and UNIDAD = CONVERT( "RV" USING UTF8) COLLATE utf8_general_ci and PERFIL = CONVERT( "APO" USING UTF8) COLLATE utf8_general_ci');

        if(empty($pfr) && empty($apo)){
             return DB::table('dnm_usuarios_portal.tbl_solicitudes_pre_rv')->where('ID_SOLICITUD',-1);
        }else{
            return DB::table('dnm_usuarios_portal.tbl_solicitudes_pre_rv')
                ->where(function($query) use ($nit,$pfr,$apo){
                   /* $query->whereIn('NO_REGISTRO',function($query) use ($nit){
                            $query->select('idProducto')
                                  ->from('dnm_usuarios_portal.vwproductos')
                                  ->where('idPersonaNatural',$nit);
                           })*/
                           $query->Where(function ($query) use ($pfr,$apo) {

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
                            });
                })
                //->groupBy('ID_SOLICITUD')
                ->orderBy('ID_SOLICITUD','DESC');
        }




    }
}
