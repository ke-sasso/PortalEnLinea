<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Http\JsonResponse;
use DB;
use Crypt;
use Log;
use Session;
use GuzzleHttp\Client;
use Config;
use App\Models\Cosmeticos\Sol\Solicitud;
use App\Models\Cssp\SolicitudesVueTramites;
use App\Models\Cssp\SolicitudesVue;
use App\Models\Cssp\Productos;
use App\Models\Cssp\VueTramitesTipos;
use App\Models\Sim\SimSolicitudes;
use App\Models\Sim\SimDictamenPost;
use App\Models\Sim\SolicitudesFabs;
use App\Models\Sim\TramitesPost;
use App\Models\Sim\SolCodigoModelo;
use App\Models\Sim\SimProductos;
use App\Models\Sim\DesistimientoSol;
use App\Models\Sim\CertificacionPost;
use App\PersonaNatural;
use App\Models\Registro\Sol\Solicitud as SolicitudRegistro;
use Carbon\Carbon;

class PdfController extends Controller
{

    //
    private $url=null;

    public function __construct() {
        $this->url = Config::get('app.api');
    }
    //funcion que permitir generar un pdf con los detalles de la solicitud ingresada.
     public function invoice($idSolicitud)
    {

        $longitud= strlen($idSolicitud);
        if($longitud>100){
            $idSol =Crypt::decrypt($idSolicitud);
            Session::forget('idSolicitud');
            if($idSol!=null){
                $client = new Client();
                $res = $client->request('POST', $this->url.'pyp/get/solicitudes/i',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'solicitud'  =>$idSol
                ]
                ]);

                $r = json_decode($res->getBody());

                if($r->status == 200){
                  $solicitud=$r->data[0];

                  //dd($solicitud);
                if(strpos($solicitud->productos,";"))
                {
                  $productos = explode(";", $solicitud->productos);
                  //dd($productos);
                  //dd(count(explode(", ", $productos[1])));
                  $data=[];
                  for($i=0;$i<count($productos);$i++){
                    list($p1,$p2,$p3) = explode(", ", $productos[$i]);
                    //dd($p1);

                      $data['productos'][$i]['num']=$p1;
                      $data['productos'][$i]['nombre']=$p2;
                      $data['productos'][$i]['mod']=$p3;

                  }
                }
                else{
                              //$productos =
                   list($p1,$p2,$p3)=explode(", ", $solicitud->productos);
                   $data['productos'][0]['num']=$p1;
                   $data['productos'][0]['nombre']=$p2;
                   $data['productos'][0]['mod']=$p3;

                }


                 $data['solicitud']=$solicitud;
                 $data['solicitud']->tels=json_decode($solicitud->telefonosContacto);
                 $data['solicitud']->establecimientos=implode(", ",json_decode($solicitud->nombreEstablecimiento));

                 $view =  \View::make('pdf.invoice',$data)->render();
                 $pdf = \App::make('dompdf.wrapper');
                 $pdf->loadHTML($view);
                 return $pdf->stream('invoice');
              }
              elseif($r->status==400){
                  return view('errors.generic')->with('error','Error del Sistema, no se pudo imprimir la solicitud');
              }
              elseif($r->status==404){
                    return view('errors.generic')->with('error','Error del Sistema, no se pudo imprimir la solicitud');
              }
            }
            else{
              return view('errors.generic')->with('error','Error del Sistema, no se pudo imprimir la solicitud');
            }
        }
        else{
          return view('errors.generic')->with('error','Error del Sistema, no se pudo imprimir la solicitud');
        }
    //return $pdf->download('invoice');
    }

    public function ResolucionRV($idSolicitud,$idTramite)
    {         $idTramite = (int)str_replace("P","",$idTramite);
              $data['idTramite']=$idTramite;

               if($idSolicitud!=null and $idTramite!=null){

                  if($idTramite==48){
                      $data['idSolicitud']=$idSolicitud;
                      $head=SolicitudesVue::select('TEXTO_AUTO','NO_REGISTRO','FECHA_CREACION')->find($idSolicitud);
                      $producto=SolicitudesVue::getFichasProductoByProd($head->NO_REGISTRO);
                      //dd($producto);
                      $fabricante=Productos::getFabricanteByProdCssp($head->NO_REGISTRO);
                      //dd($fabricante->where('tipo','Principal'));
                      $data['fabricante']=$fabricante;
                      $acondicionador=Productos::getLabsAcondiByProdCssp($head->NO_REGISTRO);
                      //dd($acondicionador);
                      $data['acondicionador']=$acondicionador;
                      //dd($acondicionador);
                      $formafarm=Productos::getFormaFarmByProd($head->NO_REGISTRO);
                      if(count($formafarm)>0){
                        $data['formafarm']=$formafarm[0];
                      }
                      else{
                        $data['formafarm']=null;
                      }
                      $titular=Productos::getPropietarioByProd($head->NO_REGISTRO);
                      $data['titular']=$titular;
                      $presentaciones=Productos::getPresentacionConcat($head->NO_REGISTRO);
                      $data['presentaciones']=$presentaciones;
                      $formula=DB::select('select cssp.convertirSubindices("'.$producto->FORMULA.'") as formula;')[0]->formula;
                      $modV=DB::table('cssp.cssp_productos_modalidades_venta')->where('ID_MODALIDAD_VENTA',$producto->RECETA)
                              ->select('NOMBRE_MODALIDAD_VENTA')->first();
                      //dd($modV);
                      $data['modV']=$modV;
                      //$excipiente=DB::select('select cssp.convertirSubindices("'.$producto->EXCIPIENTES.'") as excipiente;')[0]->excipiente;
                      $concentracion='Cada '.$producto->UNIDAD_DE_DOSIS.' contiene: '.$formula.'.';
                      //dd($concentracion);
                      $data['concentracion']=$concentracion;
                      $data['producto']=$producto;

                      $cuerpo=SolicitudesVueTramites::find($idSolicitud);
                      $codHerramienta=$cuerpo->SolTramiteTipos[0]->CODIGO_HERRAMIENTA_USADA;
                      $data['codHerramienta']=$codHerramienta;

                      $hora=$this->numAletras(date('H',strtotime($head->FECHA_CREACION)));
                      $min=$this->numAletras(date('i',strtotime($head->FECHA_CREACION)));
                      $dias=$this->numAletras(date('d',strtotime($head->FECHA_CREACION)));
                      $year=$this->numAletras(date('Y',strtotime($head->FECHA_CREACION)));

                      $meses = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");

                      $data['dia']="a las ".$hora.' horas '.$min." minutos del día ".$dias." de ".$meses[date('n',strtotime($head->FECHA_CREACION))-1]." del ".$year;
                      //dd($data);
                      $data['fechaCreacion']=$head->FECHA_CREACION;
                      $view =  \View::make('pdf.constanciaprod',$data)->render();
                      //dd($data);
                      $pdf = \App::make('dompdf.wrapper');
                      $pdf->loadHTML($view);
                    //return $pdf->stream('invoice');
                    return $pdf->stream('Constancia'.$head->NO_REGISTRO.'.pdf');
                  }
                  else{
                      //$sp=VueTramitesTipos::select('PROCEDIMIENTO')->find(54);
                      //dd(str_replace('"','',$sp->PROCEDIMIENTO));
                      //dd($sp->PROCEDIMIENTO);
                      $data['idSolicitud']=$idSolicitud;
                      $head=SolicitudesVue::select('ID_SOLICITUD','TEXTO_AUTO','NO_REGISTRO','FECHA_MODIFICACION','ID_ESTADO')->find($idSolicitud);
                      $producto=Productos::find($head->NO_REGISTRO);
                      $observacion=DB::select('select cssp.fn_get_soli_post_num_obs(?) as observacion',array($head->ID_SOLICITUD));

                      //$data['head']=$head->TEXTO_AUTO;
                      //$data['head1']='Profesional Responsable del producto denominado '.'<b>'.$producto->NOMBRE.'</b>';
                      //dd($data);
                      if(strpos($head,$producto->NOMBRE_COMERCIAL)==false){

                        $data['head']=$head->TEXTO_AUTO.'del producto denominado <b>'.$producto->NOMBRE_COMERCIAL.'.</b>';
                      }
                      else{
                        $data['head']=$head->TEXTO_AUTO;
                      }
                      $data['fechaSolicitud']=$head->FECHA_MODIFICACION;
                      //$cuerpo=SolicitudesVueTramites::select('TEXTO_IMPRIMIR_PORTAL')->find($idSolicitud)->TEXTO_IMPRIMIR_PORTAL;
                      //$data['cuerpo']=$cuerpo;
                      $cuerpo=SolicitudesVueTramites::find($idSolicitud);
                      $codHerramienta=$cuerpo->SolTramiteTipos[0]->CODIGO_HERRAMIENTA_USADA;
                      $data['codHerramienta']=$codHerramienta;
                      $data['cuerpo']=$cuerpo->TEXTO_IMPRIMIR_PORTAL;
                      //dd($data);

                      $foot=DB::select('select cssp.convertirTextoNumeroIndividual("'.$head->NO_REGISTRO.'") as letrasprod;');
                      $data['foot']=$foot[0]->letrasprod.' ('.$head->NO_REGISTRO.')';

                      $hora=$this->numAletras(date('H',strtotime($head->FECHA_MODIFICACION)));
                      $min=$this->numAletras(date('i',strtotime($head->FECHA_MODIFICACION)));
                      $dias=$this->numAletras(date('d',strtotime($head->FECHA_MODIFICACION)));
                      $year=$this->numAletras(date('Y',strtotime($head->FECHA_MODIFICACION)));

                      $meses = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");

                      $data['dia']="a las ".$hora.' horas '.$min." minutos del día ".$dias." de ".$meses[date('n',strtotime($head->FECHA_MODIFICACION))-1]." del ".$year;

                      if($idTramite==66){

                        $data['detallePresentaciones']=$head->detallePresentaciones;

                        $view =  \View::make('pdf.Rv.resolagotamiento',$data)->render();
                      }
                      else if($head->ID_ESTADO==4){
                        $dictamen=DB::select("select cssp.dictamen_desfavorable_portal(".$head->ID_SOLICITUD.") as dictamen");
                        //dd();
                        $data['dictamen']=$dictamen[0]->dictamen;
                        //unidad

                        if($head->SolicitudVueTramite->SolPostTraArea()->first()->ID_AREA==2){
                            $data['unidad']='UNIDAD JURIDICA';
                        }
                        else{
                            $data['unidad']='UNIDAD DE REGISTRO Y VISADO';
                        }


                        $view =  \View::make('pdf.Rv.resoldesfavorable',$data)->render();
                      }
                      //CUANDO EL AREA SEA IGUAL A 2 ES PORQUE ES UN TRAMITE JURIDICO
                      else if(count($observacion)>=1 && $head->SolicitudVueTramite->SolPostTraArea()->first()->ID_AREA==2 && $head->ID_ESTADO!=10){

                          $data['codHerramienta']='C02-RS-03-UJ.HER08';
                          $dictamen=DB::select("select cssp.dictamen_observado_portal(".$head->ID_SOLICITUD.") as dictamen");
                          //    dd($dictamen);
                          $data['dictamen']=$dictamen[0]->dictamen;
                          $view =  \View::make('pdf.Rv.resolobservadapostuj',$data)->render();
                          $pdf = \App::make('dompdf.wrapper');
                          $pdf->loadHTML($view);
                          // return $pdf->stream('invoice');
                          return $pdf->stream('Solicitud'.$idSolicitud.'.pdf');
                      }
                      else{
                          if($head->SolicitudVueTramite->SolPostTraArea()->first()->ID_AREA==2){
                              $data['unidad']='UNIDAD JURIDICA';
                          }
                          else{
                              $data['unidad']='UNIDAD DE REGISTRO Y VISADO';
                          }
                          $view =  \View::make('pdf.resolucionrv',$data)->render();
                      }


                      //dd($data);
                      $pdf = \App::make('dompdf.wrapper');
                      $pdf->loadHTML($view);
                    // return $pdf->stream('invoice');
                     return $pdf->stream('Solicitud'.$idSolicitud.'.pdf');
                }
              }
              elseif($r->status==400){
                  return view('errors.generic')->with('error','Error del Sistema, no se pudo imprimir la solicitud');
              }

            else{
             return view('errors.generic')->with('error','Error del Sistema, no se pudo imprimir la solicitud');
            }
    }

    //return $pdf->download('invoice');
    //return $pdf->download('invoice');
    public function resolucionSim($idSolicitud,$idTramite){

      $idSolicitud=Crypt::decrypt($idSolicitud);
      $idTramite=Crypt::decrypt($idTramite);
      $solicitud = SimSolicitudes::find($idSolicitud);

      $tramite = TramitesPost::find($idTramite);
      $producto= DB::table('sim.vw_productos_insumos_general')->where('ID_PRODUCTO',$solicitud->IM)->first();
      $solicitante= PersonaNatural::find($solicitud->NIT_SOLICITANTE);


      if(date("Y-m-d",strtotime($solicitud->FECHA_CREACION)) >= "2018-03-14"){
          $fabricante= SimSolicitudes::fabricantesResolucion($solicitud->IM);
      }
      else{
          $fabricante= DB::table('sim.sim_fabricantes as fb')
                      ->join('sim.sim_productos_fabricantes as pf','fb.ID_FABRICANTE','=','pf.id_fabricante')
                      ->join('cssp.cssp_paises as pa','fb.PAIS','=','pa.ID_PAIS')
                      ->where('pf.id_producto',$solicitud->IM)
                      ->select('fb.NOMBRE_FABRICANTE','fb.DIRECCION','pf.id_producto','pa.NOMBRE_PAIS')
                      ->first();
      }

      $data['solicitante']=$solicitante;
      $data['producto']=$producto;
      $data['solicitud']=$solicitud;
      $data['tramite']=$tramite;
      $data['fabricante']=$fabricante;

      //dd($data);

      if($idTramite==3){
        $data['modelos']=DB::table('sim.vw_productos_insumos_codigos_modelos')->where('ID_PRODUCTO',$solicitud->IM)->get();

        $h=date('H',strtotime($solicitud->FECHA_CREACION));
        $mi=date('i',strtotime($solicitud->FECHA_CREACION));
        $y=date('Y',strtotime($solicitud->FECHA_CREACION));
        $d=date('d',strtotime($solicitud->FECHA_CREACION));
        $m=date('m',strtotime($solicitud->FECHA_CREACION));

        $hora=$this->numAletras($h);
        $min=$this->numAletras($mi);
        $dias=$this->numAletras($d);
        $year=$this->numAletras($y);

        $meses = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");

        $data['dia']="a las ".$hora.' horas '.$min." minutos del día ".$dias." de ".$meses[$m-1]." del ".$year;
        //$codmod=DB::table('sim.sim_solicitud_codigos_modelos')->where('solicitud_id','=',211)->get();
        //$data['codmod']=$codmod;
        $fabricante= DB::table('sim.sim_fabricantes as fb')
              ->join('sim.sim_productos_fabricantes as pf','fb.ID_FABRICANTE','=','pf.id_fabricante')
              ->join('cssp.cssp_paises as pa','fb.PAIS','=','pa.ID_PAIS')
              ->where('pf.id_producto',$solicitud->IM)
              ->select('fb.NOMBRE_FABRICANTE','fb.DIRECCION','pf.id_producto','pa.NOMBRE_PAIS')
              ->first();
        $data['fabricante']=$fabricante;


        $view =  \View::make('pdf.constancia',$data)->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
      }
      else if($idTramite==5){
        $solcodmod=SolCodigoModelo::where('solicitud_id',$idSolicitud)->get();
        $data['solcodmod']=$solcodmod;
        $resolucion= CertificacionPost::where('solicitud_id',$idSolicitud)->first();
        $data['resolucion']=$resolucion;
        //dd($data);
        $view =  \View::make('pdf.Sim.adiccioncodigo',$data)->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
      }
      elseif($solicitud->ESTADO_SOLICITUD==2){
         if($idTramite==15 || $idTramite==18 || $idTramite==17){
           return $this->desistimientoSolicitud($idSolicitud,$idTramite);
        }
        else{
          $resolucion= CertificacionPost::where('solicitud_id',$idSolicitud)->first();
          $data['resolucion']=$resolucion;
          if($idTramite==1){
              if(date("Y-m-d",strtotime($solicitud->FECHA_CREACION)) <= "2018-07-18"){
                  $fabricantes=SolicitudesFabs::getFabricantesResol($idSolicitud);
                  $data['fabricantes']=$fabricantes;
              }
              else{
                  $data['fabricantes']=null;
              }
          }
          //dd($data);
          $view =  \View::make('pdf.resolucionsim',$data)->render();

          $pdf = \App::make('dompdf.wrapper');
          $pdf->loadHTML($view);
        }

      }
      elseif($idTramite==28 && $solicitud->IM==null && $solicitud->ESTADO_SOLICITUD==3){
        return $this->desistimientoSolicitud($idSolicitud,$idTramite);
      }
      elseif($solicitud->ESTADO_SOLICITUD==3){
        $dictamen= SimDictamenPost::where('solicitud_id',$idSolicitud)->orderBy('solicitud_id','desc')->first();
        if($dictamen->estado==3){
            $data['dictamen']=$dictamen;
            //dd($data);
            $view =  \View::make('pdf.Sim.resoluobservadasim',$data)->render();

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
        }
        else{
            $this->comprobanteIngresoSim(Crypt::encrypt($idSolicitud));
        }

      }
     return $pdf->stream('Solicitud'.$idSolicitud.'.pdf');
    }

    public function  desistimientoSolicitud($idSolicitud,$idTramite){

      $solicitud = SimSolicitudes::find($idSolicitud);
      $data['solicitud']=$solicitud;
      $tramite = TramitesPost::find($idTramite);
      $data['tramite']=$tramite;
      if($idTramite==17 || $idTramite==18 || $idTramite==15){
        $resolucion= CertificacionPost::where('solicitud_id',$idSolicitud)->first();
        $data['resolucion']=$resolucion;
      }
      else{
        $desistimiento=DesistimientoSol::where('pim',$solicitud->PIM)->first();
        $data['desistimiento']=$desistimiento;
      }
      $data['idTramite']=$idTramite;
      //dd($data);
      $view =  \View::make('pdf.desistimiento',$data)->render();
      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);

     return $pdf->stream($tramite->nombre.$idSolicitud.'.pdf');
    }


    public function comprobanteIngresoSim($idSolicitud){

        $idSol=Crypt::decrypt($idSolicitud);
        $data=[];
        $solicitud=SimSolicitudes::find($idSol);
        $data['solicitud']=$solicitud;
        //$data['perfil']=$perfil;
        $data['titular']=$propietario=SolicitudesVue::getPropietarioByProd($solicitud->PROPIETARIO);
        $tramite=DB::table('sim.sim_solicitud_tramite_post as sotp')
        ->join('sim.sim_tramites_post_tipos as trpt','sotp.tramite_id','=','trpt.id')
        ->where('sotp.solicitud_id',$idSol)->select('nombre as nomtramite')->first();
        $data['tramite']=$tramite;

        //dd($data);
        //
        $view =  \View::make('pdf.comprobanteIngresoSim',$data)->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream('Comprobante'.'.pdf');
    }

    public function comprobanteIngresoRv($idSolicitud,$idTramite){

        $solicitud=SolicitudesVue::select('ID_SOLICITUD','NO_REGISTRO','MANDAMIENTO','PROPIETARIOS_FABRICANTES','TITULO','PERSONA','FECHA_CREACION')
        ->find($idSolicitud);
         $data['solicitud']=$solicitud;
         $data['producto']=Productos::select('NOMBRE_COMERCIAL')->find($solicitud->NO_REGISTRO);
         $data['tramite']=VueTramitesTipos::select('NOMBRE_TRAMITE')->find(str_replace("P","",$idTramite));
         $view =  \View::make('pdf.Rv.comprobanteIngresoRv',$data)->render();
         //dd($data);
         $pdf = \App::make('dompdf.wrapper');
         $pdf->loadHTML($view);
         //return $pdf->stream('invoice');
        return $pdf->stream('Comprobante de Ingreso'.$solicitud->ID_SOLICITUD.'.pdf');
    }

    public function declaracionJuradaRv($idSolicitud,$idTramite){
       $solicitud=SolicitudesVue::select('NO_REGISTRO','MANDAMIENTO','PROPIETARIOS_FABRICANTES','TITULO','PERSONA','FECHA_CREACION')
        ->find($idSolicitud);
         $data['solicitud']=$solicitud;
       $data['tramite']=VueTramitesTipos::select('NOMBRE_TRAMITE')->find($idTramite);
       $view =  \View::make('pdf.Rv.declaracionJurada',$data)->render();
       //dd($data);
       $pdf = \App::make('dompdf.wrapper');
       $pdf->loadHTML($view);
       return $pdf->stream('Declaracion Jurada'.$solicitud->ID_SOLICITUD.'.pdf');
    }

    public function comprobanteIngresoCosPre($idSolicitud){
        $idSoli = Crypt::decrypt($idSolicitud);

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'cospre/solicitudpre/data-comprobante-ingreso', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                     'idSolicitud' => trim($idSoli),//Las solicitudes actuales estan incompletas
                 ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                //Retornando datos
                //dd($r->data);
                $data['info'] = $r->data;
                $date = Carbon::now();
                $date->format('g:i A');
                $data['date'] = $date;
                //dd($data);
                $view =  \View::make('pdf.Cos.comprobanteIngresoCosPre',$data)->render();

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view);

                return $pdf->stream('Comprobante'.'.pdf');
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
            else if ($r->status == 500) {
              return response()->json(['status' => 400, 'message' => 'No se puso imprimir su pdf contacte a DNM informática'],200);
            }
        }
        catch (\Exception $e){
            //throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function formularioRegSanitarioProdCosmEHig(Request $request){
      //dd('dentro de la funcion');

      $idSoli = Crypt::decrypt($request->idSolicitud);
      $solicitud = Solicitud::findOrFail($idSoli);
      $nit = Session::get('user');
      $persona = PersonaNatural::find($nit);
      $data['persona']=$persona;
      $data['persona']->tels=json_decode($persona->telefonosContacto);

      try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'cospre/solicitudpre/get-data', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                     'idSolicitud' => trim($idSoli),//Las solicitudes actuales estan incompletas
                 ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $data['info'] = $r->data;
                $data['solicitud'] = $solicitud;
                //dd($solicitud);
                if ($solicitud->tipoSolicitud == 2 || $solicitud->tipoSolicitud == 3) {
                  $view =  \View::make('pdf.Cos.formularioRegSanitarioProdCosm',$data)->render();
                }else{
                  $view =  \View::make('pdf.Cos.formularioRegSanitarioProdHig',$data)->render();
                }

                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view);

                return $pdf->stream('Comprobante'.'.pdf');
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            //throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public static function numAletras($num, $fem = false, $dec = true) //$num es el numero que recibe cualquiera los otros parametros no se para que son
   {
     $matuni[2]  = "dos";
     $matuni[3]  = "tres";
     $matuni[4]  = "cuatro";
     $matuni[5]  = "cinco";
     $matuni[6]  = "seis";
     $matuni[7]  = "siete";
     $matuni[8]  = "ocho";
     $matuni[9]  = "nueve";
     $matuni[10] = "diez";
     $matuni[11] = "once";
     $matuni[12] = "doce";
     $matuni[13] = "trece";
     $matuni[14] = "catorce";
     $matuni[15] = "quince";
     $matuni[16] = "diecis&eacute;is";
     $matuni[17] = "diecisiete";
     $matuni[18] = "dieciocho";
     $matuni[19] = "diecinueve";
     $matuni[20] = "veinte";
     $matunisub[2] = "dos";
     $matunisub[3] = "tres";
     $matunisub[4] = "cuatro";
     $matunisub[5] = "quin";
     $matunisub[6] = "seis";
     $matunisub[7] = "sete";
     $matunisub[8] = "ocho";
     $matunisub[9] = "nove";

     $matdec[2] = "veint";
     $matdec[3] = "treinta";
     $matdec[4] = "cuarenta";
     $matdec[5] = "cincuenta";
     $matdec[6] = "sesenta";
     $matdec[7] = "setenta";
     $matdec[8] = "ochenta";
     $matdec[9] = "noventa";
     $matsub[3]  = 'mill';
     $matsub[5]  = 'bill';
     $matsub[7]  = 'mill';
     $matsub[9]  = 'trill';
     $matsub[11] = 'mill';
     $matsub[13] = 'bill';
     $matsub[15] = 'mill';
     $matmil[4]  = 'millones';
     $matmil[6]  = 'billones';
     $matmil[7]  = 'de billones';
     $matmil[8]  = 'millones de billones';
     $matmil[10] = 'trillones';
     $matmil[11] = 'de trillones';
     $matmil[12] = 'millones de trillones';
     $matmil[13] = 'de trillones';
     $matmil[14] = 'billones de trillones';
     $matmil[15] = 'de billones de trillones';
     $matmil[16] = 'millones de billones de trillones';

     //Zi hack
     $float=explode('.',$num);
     $num=$float[0];

     $num = trim((string)@$num);
     if ($num[0] == '-') {
      $neg = 'menos ';
      $num = substr($num, 1);
     }else
      $neg = '';
     while ($num[0] == '0') $num = substr($num, 1);
     if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
     $zeros = true;
     $punt = false;
     $ent = '';
     $fra = '';
     for ($c = 0; $c < strlen($num); $c++) {
      $n = $num[$c];
      if (! (strpos(".,'''", $n) === false)) {
       if ($punt) break;
       else{
        $punt = true;
        continue;
       }

      }elseif (! (strpos('0123456789', $n) === false)) {
       if ($punt) {
        if ($n != '0') $zeros = false;
        $fra .= $n;
       }else

        $ent .= $n;
      }else

       break;

     }
     $ent = '     ' . $ent;
     if ($dec and $fra and ! $zeros) {
      $fin = ' coma';
      for ($n = 0; $n < strlen($fra); $n++) {
       if (($s = $fra[$n]) == '0')
        $fin .= ' cero';
       elseif ($s == '1')
        $fin .= $fem ? ' una' : ' un';
       else
        $fin .= ' ' . $matuni[$s];
      }
     }else
      $fin = '';
     if ((int)$ent === 0) return 'Cero ' . $fin;
     $tex = '';
     $sub = 0;
     $mils = 0;
     $neutro = false;
     while ( ($num = substr($ent, -3)) != '   ') {
      $ent = substr($ent, 0, -3);
      if (++$sub < 3 and $fem) {
       $matuni[1] = 'una';
       $subcent = 'as';
      }else{
       $matuni[1] = $neutro ? 'un' : 'uno';
       $subcent = 'os';
      }
      $t = '';
      $n2 = substr($num, 1);
      if ($n2 == '00') {
      }elseif ($n2 < 21)
       $t = ' ' . $matuni[(int)$n2];
      elseif ($n2 < 30) {
       $n3 = $num[2];
       if ($n3 != 0) $t = 'i' . $matuni[$n3];
       $n2 = $num[1];
       $t = ' ' . $matdec[$n2] . $t;
      }else{
       $n3 = $num[2];
       if ($n3 != 0) $t = ' y ' . $matuni[$n3];
       $n2 = $num[1];
       $t = ' ' . $matdec[$n2] . $t;
      }
      $n = $num[0];
      if ($n == 1) {
       $t = ' ciento' . $t;
      }elseif ($n == 5){
       $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
      }elseif ($n != 0){
       $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
      }
      if ($sub == 1) {
      }elseif (! isset($matsub[$sub])) {
       if ($num == 1) {
        $t = ' mil';
       }elseif ($num > 1){
        $t .= ' mil';
       }
      }elseif ($num == 1) {
       $t .= ' ' . $matsub[$sub] . '?n';
      }elseif ($num > 1){
       $t .= ' ' . $matsub[$sub] . 'ones';
      }
      if ($num == '000') $mils ++;
      elseif ($mils != 0) {
       if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
       $mils = 0;
      }
      $neutro = true;
      $tex = $t . $tex;
     }
     $tex = $neg . substr($tex, 1) . $fin;
     //Zi hack --> return ucfirst($tex);
     //$end_num=ucfirst($tex).' '.$float[1].'/100';
    // return ucfirst($tex); ucfirst es la primera letra en mayuscula
    return $tex;
  }



}
