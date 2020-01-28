<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class vwSolicitudesSimPre extends Model
{
    //
    protected $table = 'sim.vw_solicitudes_sim_pre';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;

    public static function getSolicitudesPre($nit){

    	$perfiles=Session::get('perfiles');
        $poderProfesional=null;
        $poderApoderado=null;
        $poderApoderadoRep=null;
        $poderRepresentante=null;
        $propietario=null;


    	foreach ($perfiles as $perfil) {
            //if($perfil->UNIDAD==='SIM'){
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


                        if(strpos($perfil->idDNM,'AP')!==false){

                            $poderApoderadoRep=DB::table('cssp.vw_apoderados_poderes_todos')->where('ID_APODERADO',$perfil->idDNM)
                                                ->select('ID_PODER')
                                                ->pluck('ID_PODER');

                            $poderRepresentante = DB::table('cssp.siic_representantes_legales_poderes')->where('ID_REPRESENTANTE', $perfil->idDNM)
                                                    ->select('ID_PODER')
                                                    ->pluck('ID_PODER');
                        }
                        else //Para los casos que es representante de una personería jurídica nacional
                        {
                            $poderRepresentante = DB::table('cssp.siic_representantes_legales_poderes as pod')
                                ->join('cssp.cssp_propietarios as prop','prop.ID_PROPIETARIO','=','pod.ID_PROPIETARIO')
                                ->where('prop.NIT', $perfil->idDNM)
                                ->select('ID_PODER')
                                ->pluck('ID_PODER');
                        }

                }
                if($perfil->PERFIL=='PROP' && $perfil->UNIDAD=='SIM'){
                        $propietario=$perfil->idDNM;
                }

            //}
        }
        if($propietario==null && $poderApoderado==null && $poderApoderadoRep==null && $poderRepresentante==null && $poderProfesional==null){
            return  vwSolicitudesSimPre::where('ID_SOLICITUD',-1);
        }else{
            return vwSolicitudesSimPre::where(function($query) use ($poderProfesional) {
                                        if (!empty($poderProfesional)) {
                                            $query->whereIn('PODER_PROFESIONAL', $poderProfesional);
                                        }
                                    })
                                    ->orWhere(function ($query) use($poderApoderado) {
                                        if(!empty($poderApoderado)){;
                                             $query->where(function ($query) use ($poderApoderado) {
                                                 $query->whereIn('PODER_APODERADO_REPRESENTANTE',$poderApoderado);
                                             });
                                            $query->whereIn('PODER_APODERADO_REPRESENTANTE',$poderApoderado);
                                        }
                                    })
                                    ->orWhere(function ($query) use($poderApoderadoRep) {
                                        if(!empty($poderApoderadoRep)){
                                            $query->whereIn('PODER_APODERADO_REPRESENTANTE',$poderApoderadoRep);
                                        }
                                    })
                                    ->orWhere(function ($query) use($poderRepresentante) {
                                        if(!empty($poderRepresentante)){
                                            $query->whereIn('PODER_APODERADO_REPRESENTANTE',$poderRepresentante);
                                        }
                                    })
                                    ->orWhere(function ($query) use($propietario) {
                                        if(!empty($propietario)){
                                            $query->where('PROPIETARIO',$propietario);
                                        }
                                    })
                                    //->groupBy('ID_SOLICITUD')
                                    ->distinct()
                                    ->orderBy('ID_SOLICITUD','DESC');
        }

    }
}
