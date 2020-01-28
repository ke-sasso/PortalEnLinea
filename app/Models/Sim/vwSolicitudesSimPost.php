<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class vwSolicitudesSimPost extends Model
{
    //
    protected $table = 'sim.vw_solicitudes_sim_post';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;

    public static function getSolicitudesPost($nit){

    	$perfiles=Session::get('perfiles');
        $poderProfesional=null;
        $poderApoderado=null;
        $poderApoderadoRep=null;
        $propietario=null;

    	foreach ($perfiles as $perfil) {
            if($perfil->UNIDAD==='SIM'){
                if($perfil->PERFIL=='PFR'){
                    $poderProfesional=DB::table('cssp.siic_profesionales_poderes')->where('ID_PROFESIONAL',$perfil->idDNM)
                              ->select('ID_PODER')
                              ->pluck('ID_PODER');
                }

                if($perfil->PERFIL=='APO'){
                    if($perfil->idDNM!='AP00000'){
                        $poderApoderado=DB::table('cssp.vw_apoderados_poderes_todos')->where('ID_APODERADO',$perfil->idDNM)
                                        ->select('ID_PODER')
                                        ->pluck('ID_PODER');
                    }
                }

                if($perfil->PERFIL=='REPR'){
                        $poderApoderadoRep=DB::table('cssp.vw_apoderados_poderes_todos')->where('ID_APODERADO',$perfil->idDNM)
                                        ->select('ID_PODER')
                                        ->pluck('ID_PODER');
                }

                if($perfil->PERFIL=='PROP'){
                        $propietario=$perfil->idDNM;
                }

            }
        }





    	return vwSolicitudesSimPost::where(function($query) use ($nit,$poderApoderado,$poderProfesional,$poderApoderadoRep,$propietario){
                    $query->whereIn('IM',function($query) use ($nit){
                            $query->select('ID_PRODUCTO')
                                  ->from('dnm_usuarios_portal.vw_productossim')
                                  ->where('idPersonaNatural',$nit);
                           })
                          ->orWhere(function ($query) use ($poderApoderado,$poderProfesional,$poderApoderadoRep,$propietario) {

                                if(!empty($poderProfesional)){
                                    $query->whereIn('PODER_PROFESIONAL',$poderProfesional);
                                }
                                if(!empty($poderApoderado)){
                                    $query->whereIn('PODER_APODERADO_REPRESENTANTE',$poderApoderado);
                                }
                                if(!empty($poderApoderadoRep)){
                                    $query->whereIn('PODER_APODERADO_REPRESENTANTE',$poderApoderadoRep);
                                }
                                if(!empty($propietario)){
                                    $query->where('PROPIETARIO',$propietario);
                                }
                            })
                          ->orWhere(function ($query) use ($nit){
                                $query->where('NIT_SOLICITANTE',$nit);
                           })
                          ->orWhere(function ($query) use ($nit){
                                $query->where('NIT_SOLICITANTE',$nit);
                           });
                })
                //->groupBy('ID_SOLICITUD')
                ->orderBy('ID_SOLICITUD','DESC');
    }
}
