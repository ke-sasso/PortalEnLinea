<?php

namespace App\Http\Controllers\Registro;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cssp\SolicitudesVue;
use App\Models\Cssp\Productos;
use App\Models\Cssp\SolVueDocs;
use Crypt;
use DB;

class SubsanacionController extends Controller
{
    //

    public function getSolicitudObservada($idSolicitud,$idTramite){

      $data = ['title'           => 'Solicitudes Post-Registro Observada'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Solicitudes Post-Registro Observada', 'url' => '#']
                ]]; 

      $idSol= Crypt::decrypt($idSolicitud);

      $solicitud=SolicitudesVue::find($idSol);
      $producto= Productos::getDataRows()->where('idProducto',trim($solicitud->NO_REGISTRO))->first();
      
      //dd($producto);
      
      $data['solicitud']=$solicitud;
      $data['producto']=$producto;
      $tramite=DB::table('cssp.siic_solicitudes_vue_tramites_tipos')
      			->select('ID_TRAMITE','NOMBRE_TRAMITE')
      			->where('ID_TRAMITE',$idTramite)->first();
      $data['tramite']=$tramite;
      $requisitos=DB::table('cssp.si_urv_solicitudes_postregistro_requisitos_lista_chequeo')
                  ->where('ID_SOLICITUD',$idSol)
                  ->where('USUARIO_CREACION','like',"%portalenlinea%")
                  ->select('ID_REQUISITO','ID_ITEM')
                  ->get()->toArray();

      //dd($requisitos);
      for($i=0;$i<count($requisitos);$i++){
        $idRequi[$i]=$requisitos[$i]->ID_REQUISITO;
      }
      $documentos=DB::table('cssp.si_urv_solicitudes_postregistro_lista_chequeo_items')->get();
      //dd($idRequi);
      $archivos=SolVueDocs::whereIn('ID_REQUISITO',$idRequi)->get();
      foreach ($requisitos as $requi) {
          foreach ($archivos as $arch) {
            if($arch->ID_REQUISITO==$requi->ID_REQUISITO){
              foreach ($documentos as $doc) {
                  if($requi->ID_ITEM==$doc->ID_ITEM){
                    $arch->nomDoc=$doc->NOMBRE_ITEM;
                  }
              }
            }
          }
      }
      $data['archivos']=$archivos;

      $data['dictamen']=DB::table('cssp.si_urv_solicitudes_postregistro_dictamenes')->where('ID_SOLICITUD',$idSol)->orderBy('ID_SOLICITUD','DESC')->limit(1)->first();
      //$file = File::get($archivos[0]->URL_ARCHIVO);
      //dd($file);
      //dd($data);
      return view('registro.subsanacion.subsanacion',$data);
    }

    public function subsanarSolicitud(Request $request){
    	//dd($request->all());
    	$solicitud=SolicitudesVue::find($request->idSolicitud);

    	if($request->idTramite==45){
    		$solicitud->id_poder_apoderado=$request->numPoderA;
    	}
    	else if($request->idTramite==46){
    		$solicitud->id_poder_profesional=$request->numPoder;
    	}

    	$solicitud->ID_ESTADO=2;
    	$solicitud->USUARIO_MODIFICA="portalenlinea@".$request->ip();
    	$solicitud->save();

    	return redirect()->route('ver.solicitudes.rv');

    }
}
