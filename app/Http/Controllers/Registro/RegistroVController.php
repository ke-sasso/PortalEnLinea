<?php

namespace App\Http\Controllers\Registro;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Cssp\VueTramitesTipos;
use App\Models\Cssp\SolicitudesVue;
use App\Models\Cssp\SolicitudesVueHistorial;
use App\Models\Cssp\SolicitudesVueTramites;
use App\Models\Cssp\SolicitudEmpPresent;
use App\Models\Cssp\SoliPostListaChequeo;
use App\Models\Cssp\SoliVuePrinciposA;
use App\Models\Cssp\SolVuePresetanciones;
use App\Models\Cssp\ActualizacioMonoInsert;
use App\Models\Cssp\CatalogoEmpaques;
use App\Models\Cssp\SoliVueFabricantes;
use App\Models\Cssp\Productos;
use App\Models\Cssp\SolVueDocs;
use App\Models\Cssp\SolPostItems;
use App\Models\DnmUsuariosPortal\vwSolicitudesPreRvPtl;
use App\VwSolicitudesRv;
use App\vwSolicitudesRvUsuario;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PersonaNatural;
use App\Models\Cssp\ProdNomExportacion;
use Session;
use Datatables;
use DateTime;
use DB;
use File;
use Crypt;
use Illuminate\Filesystem\Filesystem;
use Response;
use Config;
use Validator;
use GuzzleHttp\Client;
use Exception;

class RegistroVController extends Controller
{

    private $url=null;

    public function __construct() {
        $this->url = Config::get('app.api');
    }

    public function index(){

	    $data = ['title'           => 'Nueva Solicitud'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Nueva Solicitud', 'url' => '#']
                ]];
	    $nit=Session::get('user');

	    //1 son los de ventilla express
	   	$data['tramites']=VueTramitesTipos::getTramiteByTipo(1);
      $data['documentos']=DB::table('cssp.si_urv_solicitudes_postregistro_lista_chequeo_items')->get();
      $data['tramitesDoc']=DB::table('cssp.si_urv_solicitudes_postregistro_lista_chequeo_tramites')->get();
      //$data['perfiles']=Session::get('perfiles');
      $data['perfiles']=DB::select('select * from dnm_usuarios_portal.vwperfilportal where NIT = "'.$nit.'" and UNIDAD = CONVERT( "RV" USING UTF8) COLLATE utf8_general_ci');

      //dd($data);
	    return view('registro.index',$data);

    }


    public function getDataRowsProductos(Request $request){
    	$nit=Session::get('user');
    	//$productos = Productos::getDataRows()->where('idPersonaNatural',$nit);
      if($request->idTramite==52){
        $productos = Productos::getDataRows()->where('idPersonaNatural',$nit)
        ->whereIn('ID_TIPO_PRODUCTO',['C04','C18'])->distinct();
      }
      else{
        $productos = Productos::getDataRows()->where('idPersonaNatural',$nit)->distinct();
      }



      if($request->idTramite==44){

        $productos = Productos::getDataRows()->where('idPersonaNatural',$nit)
                     ->whereIn('vwprod.ACTIVO',['I','A'])->distinct();

        return Datatables::of($productos)
            ->editColumn('nombreComercial','{{htmlentities(trim($nombreComercial))}}')
            ->make(true);
      }
      else if($request->idTramite==45 || $request->idTramite==46){
          $productos = Productos::getDataRows()
                       ->whereIn('vwprod.ACTIVO',['A'])->distinct();

          return Datatables::of($productos)
            ->editColumn('nombreComercial','{{htmlentities(trim($nombreComercial))}}')
            ->make(true);
      }
      else{

        $productos = Productos::getDataRows()->where('idPersonaNatural',$nit)
                     ->where('vwprod.ACTIVO','A')->distinct();

        return Datatables::of($productos)
              ->editColumn('nombreComercial','{{htmlentities(trim($nombreComercial))}}')
              ->make(true);
      }


    }

    public function getPaises(){
      $paises= DB::table('dnm_catalogos.cat_paises')
                  ->where('activo','A')
                  ->select('idPais','nombre','codigoId')
                  ->orderBy('nombre','asc')
                  ->get();
      if($paises!=null){
        return response()->json(['status' => 200, 'data' => $paises]);
      }
    }

    public function getFamiliasFormas(){
      $familias=DB::table('cssp.siic_formas_farmaceuticas_familias')
                ->where('activo','A')
                ->select('ID_FORMA_FARMACEUTICA_FAMILIA','NOMBRE_FORMA_FARMACEUTICA_FAMILIA')
                ->orderBy('NOMBRE_FORMA_FARMACEUTICA_FAMILIA','asc')
                ->get();
      if($familias!=null){
        return response()->json(['status' => 200, 'data' => $familias]);
      }
    }

    public function getFormas(Request $request){

      //dd($idFamilia);
      if($request->has('idProducto')){
        $formasfar=DB::table('cssp.siic_formas_farmaceuticas')
                ->where('activo','A')
                ->where('ID_FORMA_FARMACEUTICA_FAMILIA',$request->idFamilia)
                ->orWhere('ID_FORMA_FARMACEUTICA','FF1253')
                ->select('ID_FORMA_FARMACEUTICA','NOMBRE_FORMA_FARMACEUTICA')
                ->orderBy('NOMBRE_FORMA_FARMACEUTICA','asc')
                ->get();

      }
      else{
        $formasfar=DB::table('cssp.siic_formas_farmaceuticas')
                ->where('activo','A')
                ->where('ID_FORMA_FARMACEUTICA_FAMILIA',$request->idFamilia)
                ->select('ID_FORMA_FARMACEUTICA','NOMBRE_FORMA_FARMACEUTICA')
                ->orderBy('NOMBRE_FORMA_FARMACEUTICA','asc')
                ->get();
      }
      if($formasfar!=null){
        return response()->json(['status' => 200, 'data' => $formasfar]);
      }
    }

     public function getForma(Request $request){
      if($request->idProducto!=null){
          $formas=Productos::getFormaFarmByProd($request->idProducto);
          if($formas!=null){

             return response()->json(['status' => 200,'message' => 'Resultado con exito', 'data' => $formas]);
          }
          else{
            return response()->json(['status' => 400,'message' => 'Error en la Consulta de Fabricantes', 'data' => []]);
          }
      }
      else{
        return response()->json(['status' => 404,'message' => 'Error: Debe Seleccionar un producto', 'data' => []]);
      }

    }

    public function getFabricantes(Request $request){
      if($request->idProducto!=null){
          $productos=Productos::getFabricanteByProdCssp($request->idProducto);
          if($productos!=null){

             return response()->json(['status' => 200,'message' => 'Resultado con exito', 'data' => $productos]);
          }
          else{
            return response()->json(['status' => 400,'message' => 'Error en la Consulta de Fabricantes', 'data' => []]);
          }
      }
      else{
        return response()->json(['status' => 404,'message' => 'Error: Debe Seleccionar un producto', 'data' => []]);
      }

    }

    public function getDataRowsExport(Request $request){
        if($request->idProducto!=null){
          $export = Productos::getNomExportacionByProd($request->idProducto);
          if($export!=null){
            return response()->json(['status' => 200,'message' => 'Resultado con exito', 'data' => $export]);
           }
          else{
            return response()->json(['status' => 400,'message' => 'Error en la Consulta de Exportaciones', 'data' => []]);
          }
        }
        else{
          return response()->json(['status' => 404,'message' => 'Error: Debe Seleccionar un producto', 'data' => []]);
        }
      //return Datatables::of($export)->make(true);


    }

    public function getAllEmpaques(){
        $empaques = DB::table('cssp.si_urv_empaque_presentaciones')->where('ACTIVO','A')->get();
          if($empaques!=null){
            return response()->json(['status' => 200,'message' => 'Resultado con exito', 'data' => $empaques]);
          }
          else{
            return response()->json(['status' => 400,'message' => 'Error: en la consulta', 'data' => []]);
          }
    }
    public function getEmpaques(Request $request){
          $nregistro=(string)$request->idProducto;

          $empaques=CatalogoEmpaques::getEmpaquesByProd($nregistro);
          $material=CatalogoEmpaques::getMaterialByProd($nregistro);
          $color=CatalogoEmpaques::getColorByProd($nregistro);
          //return $empaques;
          //$empaques = DB::table('cssp.si_urv_empaque_presentaciones')->where('ACTIVO','A')->get();
          if($empaques!=null){
            return response()->json(['status' => 200,'message' => 'Resultado con exito', 'data' => $empaques, 'mat' => $material,'color'=> $color]);
          }
          else{
            return response()->json(['status' => 400,'message' => 'Error: en la consulta', 'data' => []]);
          }
    }

    public function getContenidosByProd(Request $request){
          $nregistro=(string)$request->idProducto;

          $contenidos=CatalogoEmpaques::getContenidoByProd($nregistro);
          //return $empaques;
          //$empaques = DB::table('cssp.si_urv_empaque_presentaciones')->where('ACTIVO','A')->get();
          if($contenidos!=null){
            return response()->json(['status' => 200,'message' => 'Resultado con exito', 'data' => $contenidos]);
          }
          else{
            return response()->json(['status' => 400,'message' => 'Error: en la consulta', 'data' => []]);
          }
    }

    public function getContenidos(){

          $contenidos = DB::table('cssp.si_urv_contenido_presentaciones')->where('ACTIVO','A')->get();
          if($contenidos!=null){
            return response()->json(['status' => 200,'message' => 'Resultado con exito', 'data' => $contenidos]);
          }
          else{
            return response()->json(['status' => 400,'message' => 'Error: en la consulta', 'data' => []]);
          }
    }

    public function getDataRowsPresentaciones(Request $request){
      if($request->idProducto!=null){
          $presentaciones = Productos::getPresentacionByProd($request->idProducto);
          if($presentaciones!=null){
            return response()->json(['status' => 200,'message' => 'Resultado con exito', 'data' => $presentaciones]);
          }
          else{
            return response()->json(['status' => 400,'message' => 'Error: en la consulta', 'data' => []]);
          }
      }
      else{
        return response()->json(['status' => 404,'message' => 'Error: Debe Seleccionar un producto', 'data' => []]);
      }

    }

    public function getDataRowsFabricante(Request $request){

      $fabricante = Productos::getFabricantesByProd($request->get('idproducto'));

      return Datatables::of($fabricante)->make(true);


    }

    public function getDataRowsLabs(Request $request){
      if($request->idProducto!=null){
          $laboratorios = Productos::getLabsAcondiByProdCssp($request->idProducto);
          if($laboratorios!=null){
            return response()->json(['status' => 200,'message' => 'Resultado con exito', 'data' => $laboratorios]);
          }
          else{
            return response()->json(['status' => 400,'message' => 'Error: en la consulta', 'data' => []]);
          }

      }
      else{
        return response()->json(['status' => 404,'message' => 'Error: Debe Seleccionar un producto', 'data' => []]);
      }

    }

    public function verificarMandamiento(Request $request){
        //dd($request->all());

        try {
        if ($request->mandamiento!=null) {

          $verificacion  = DB::select("Select m.id_mandamiento, mr.* from cssp.cssp_mandamientos m inner join cssp.cssp_mandamientos_detalle md on md.id_mandamiento = m.id_mandamiento
                      inner join cssp.cssp_mandamientos_recibos mr on mr.id_mandamiento = m.id_mandamiento
                      where m.id_mandamiento not in
                      (
                          Select MANDAMIENTO from cssp.siic_solicitudes_vue
                          where MANDAMIENTO = m.id_mandamiento and ID_ESTADO in (1,2,3,10)
                      ) and m.id_mandamiento=".$request->mandamiento."");

          /*
          $verificacion  = DB::table('cssp.cssp_mandamientos as m')
                                ->join('cssp.cssp_mandamientos_detalle as md','md.id_mandamiento','=','m.id_mandamiento')
                                ->join('cssp.cssp_mandamientos_recibos as mr','mr.id_mandamiento','=','m.id_mandamiento')
                                ->whereNotIn('m.id_mandamiento',function($query){
                                      $query->select('MANDAMIENTO')
                                      ->from('cssp.siic_solicitudes_vue')
                                      ->where('MANDAMIENTO','m.id_mandamiento')
                                      ->whereIn('ID_ESTADO', ['9', '2']);
                                  })
                                ->where('m.id_mandamiento',$request->mandamiento)->first();*/
          //dd($verificacion);
          if($verificacion!=null){
            return response()->json(['status' => 200, 'data' => $verificacion],200);
          }
        else{
          return response()->json(['status' => 400, 'message' => "Mandamiento no es valido o ya ha sido utilizado"],200);
        }
      }
      }
      catch (Exception $e) {
          return response()->json(['status' => 404, 'message' => "Error, favor contacte a DNM informática"],200);
      }

    }

    public function confirmacion(Request $request){
      //dd($request->all());

       if(!(isset($request->txtregistro))){
        Session::flash('msnError',' Es Obligatorio que seleccione un producto para realizar el tramite');
        return back()->withInput();
      }else if($request->idTramite==''){
         Session::flash('msnError',' Es Obligatorio que seleccione un tipo de trámite');
         return back()->withInput();
      }
      if($request->idTramite=='36' || $request->idTramite=='48'){
            $verificacion  = DB::select("Select m.id_mandamiento, mr.* from cssp.cssp_mandamientos m inner join cssp.cssp_mandamientos_detalle md on md.id_mandamiento = m.id_mandamiento
                            inner join cssp.cssp_mandamientos_recibos mr on mr.id_mandamiento = m.id_mandamiento
                            where m.id_mandamiento not in
                            (
                                Select MANDAMIENTO from cssp.siic_solicitudes_vue
                                where MANDAMIENTO = m.id_mandamiento and ID_ESTADO in (1,2,3,10)
                            ) and m.id_mandamiento=".$request->num_mandamiento."");
            if(empty($verificacion)){
               Session::flash('msnError',' Mandamiento no es valido o ya ha sido utilizado');
               return back()->withInput();
            }
      }
      $numArchivos=DB::table('cssp.si_urv_solicitudes_postregistro_lista_chequeo_tramites')
                    ->where('ID_TRAMITE',$request->idTramite)
                    ->where('ITEM_OBLIGATORIO',1)
                    ->pluck('ID_ITEM')
                    ->toArray();

      /*$countArch=[];
       for($i=0;$i<count($numArchivos);$i++){
          $countArch[$i]=$numArchivos[$i]->ID_ITEM;
        }*/
       $countArch=$numArchivos;

       $count=count($countArch);
       if(in_array(1,$countArch,true)){
         $count--;
       }

       if($request->idTramite==21){
           if($request->emp1[0]==="9"){
           }
           elseif ($request->emp1[0]==="10"){
           }
           else{
             $count--;
           }

          if(count($request->file('files'))!=$count){
            Session::flash('msnError',' Es obligatorio que suba todos los archivos adjuntos según documentos');
            return back()->withInput();
          }
       }
       else if($request->idTramite==38 || $request->idTramite==37){
          $empaques1=DB::table('cssp.si_urv_productos_empaques_presentaciones')->where('ID_PRODUCTO',$request->txtregistro)
          ->select('EMPAQUE_PRIMARIO')
          ->distinct()
          ->get();
          //dd($empaques1);
          $emp1=[];
          //dd('Archivos iniciales'.$count);
          if(count($empaques1)>0){
            for($i=0;$i<count($empaques1);$i++) {
              $emp1[$i]=$empaques1[$i]->EMPAQUE_PRIMARIO;
            }
          }
          if(in_array(10,$emp1,true)){
          }
          elseif(in_array(9,$emp1,true)){
          }
          else{
            //dd($count);
              $count--;
            // dd('Si resta archivos'.$count);
          }

          if(count($request->file('files'))!=$count){
            Session::flash('msnError',' Es obligatorio que suba todos los archivos adjuntos según documentos');
            return back()->withInput();
          }

      }
      else if(count($request->file('files')) == $count || count($request->file('files')) > $count){
          $indexs=array_keys($request->file('files'));
          unset($countArch[0]);
          //dd($countArch);
          $requisitosReq= array_diff($countArch,$indexs);

          //dd($requisitosReq);
          if(!empty($requisitosReq)){
            foreach ($requisitosReq as $reqR) {

              $solPostIt=SolPostItems::whereIn('ID_ITEM',$requisitosReq)
              ->select(DB::raw('group_CONCAT(NOMBRE_ITEM separator ", ") as reqs'))->first();

            }
            $tramites=VueTramitesTipos::find($request->idTramite);
            //dd($tramites);
            Session::flash('msnError',' El requisito: '.strtoupper($solPostIt->reqs).' son documentos requerido para realizar el trámite: '.$tramites->NOMBRE_TRAMITE);
            return back()->withInput();

          }

      }
      else if(count($request->file('files')) < $count){
          //dd('entro');
          Session::flash('msnError',' Es obligatorio que suba todos los archivos adjuntos según documentos');
          return back()->withInput();
      }


      /*if($request->idTramite==57){
          $count--;

          if(count($request->file('files')) < $count){

            Session::flash('msnError',' Es obligatorio que suba todos los archivos adjuntos según documentos');
            return back()->withInput();
          }
      }
      else{
        if(count($request->file('files'))!=$count){
            Session::flash('msnError',' Es obligatorio que suba todos los archivos adjuntos según documentos');
            return back()->withInput();
          }
      }*/



       /*if(count($request->file('files'))!=$count){
          Session::flash('msnError',' Es obligatorio que suba todos los archivos adjuntos según documentos');
          return back()->withInput();
       }*/
       if($request->file('files')==null){
        Session::flash('msnError',' Es Obligatorio que suba los archivos adjuntos según documentos');
        return back()->withInput();
      }


       $data = ['title'           => 'Verificacion de la Solicitud'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Nueva Solicitud', 'url' => '#']
                ]];
       $data['idTramite']=$request->idTramite;
       $data['idArea']=$request->idArea;
       $data['perfil']=$request->perfil;
       $data['txtregistro']=$request->txtregistro;
       $data['txttipo']=$request->txttipo;
       $data['txtnombreprod']=$request->txtnombreprod;
       $data['txtrenovacion']=$request->txtrenovacion;
       $data['txtvigencia']=$request->txtvigencia;


       if($request->idTramite==64 || $request->idTramite==54){
          $data['txtobservaciones']=$request->observaciones;

       }
       elseif($request->idTramite==36){
          $data['nomexport']=$request->nomexport;
          $data['pais']=DB::table('dnm_catalogos.cat_paises')
                  ->where('activo','A')
                  ->whereIn('idPais',$request->pais)
                  ->select('idPais','nombre','codigoId')
                  ->get();
          //dd($data);

       }
       elseif($request->idTramite==48){
          $producto=SolicitudesVue::getFichasProductoByProd($request->txtregistro);
          $fabricante=Productos::getFabricanteByProdCssp($request->txtregistro);
          $data['fabricante']=$fabricante;
          $acondicionador=Productos::getLabsAcondiByProdCssp($request->txtregistro);
          $data['acondicionador']=$acondicionador;
          $formafarm=Productos::getFormaFarmByProd($request->txtregistro);
          if(count($formafarm)>0){
            $data['formafarm']=$formafarm[0];
          }
          else{
            $data['formafarm']=null;
          }
          $titular=Productos::getPropietarioByProd($request->txtregistro);
          $data['titular']=$titular;
          $presentaciones=Productos::getPresentacionConcat($request->txtregistro);
          $data['presentaciones']=$presentaciones;
          $formula=DB::select('select cssp.convertirSubindices("'.$producto->FORMULA.'") as formula;')[0]->formula;
          $modV=DB::table('cssp.cssp_productos_modalidades_venta')->where('ID_MODALIDAD_VENTA',$producto->RECETA)
                  ->select('NOMBRE_MODALIDAD_VENTA')->first();
          $data['modV']=$modV;
          $concentracion='Cada '.$producto->UNIDAD_DE_DOSIS.' contiene: '.$formula.'.';
          $data['concentracion']=$concentracion;
          $data['producto']=$producto;
          //dd($data);
       }
       elseif($request->idTramite==67){
         $laboratorios=Productos::getLabsAcondiByProdCssp($request->txtregistro);
         $labsDes=$request->idLabs;
         foreach ($laboratorios as $labs) {
          for($i=0;$i<count($labsDes);$i++){
            if($labs->ID_LABORATORIO===$labsDes[$i]){
                $labs->descontinuar=1;
            }
            else{
                $labs->descontinuar=0;
            }
          }
         }
         $data['laboratorios']=$laboratorios;
         //$data['idLabs']=$request->idLabs;

       }
       elseif($request->idTramite==29 || $request->idTramite==27){
          $data['version']=$request->version;
          $data['fecha']=$request->fecha;
       }
       elseif($request->idTramite==61){
         $fabricantes=Productos::getFabricanteByProdCssp($request->txtregistro);
         //dd($fabricantes);
         $idFabs=$request->idFab;
         if(count($fabricantes)==count($request->idFab)){
            Session::flash('msnError','El producto no puede quedar sin ningun Fabricante');
            return back()->withInput();
         }

          for($j=0;$j<count($fabricantes);$j++) {
            for($i=0;$i<count($idFabs);$i++){
            if($fabricantes[$j]->id_fabricante===$idFabs[$i]){
                if($fabricantes[$j]->tipo==='Principal'){
                  $fabricantes[$j]->descontinuar=1;
                  $fabricantes[$j]->nuevotipo='A DESCONTINUAR';
                  $fabricantes[$j+1]->nuevotipo='Principal';
                }
                else if($fabricantes[$j]->tipo==='Alterno'){
                  $fabricantes[$j]->descontinuar=1;
                  $fabricantes[$j]->nuevotipo='A DESCONTINUAR';
                }
            }
            else{
                $fabricantes[$j]->descontinuar=0;
            }
          }
         }
         //$data['idFab']=$request->idFab;
         $data['fabricantes']=$fabricantes;

       }
       elseif($request->idTramite==66){
        $rules = [
            'lote'           =>  'required',
            'unidades'       =>  'required',
            'fecha1'         =>  'required',
            'presentaciones' =>  'required'
        ];

        $messages =  array( 'lote.required' => 'Es necesario que digite el lote',
                            'unidades.required' => 'Es necesario que digite la cantidad de unidades',
                            'fecha1.required' => 'Es necesario que digite la fecha de vencimiento',
                            'presentaciones.required' => 'Es necesario que seleccione una presentacion por lote'
                      );

        $v = Validator::make($request->all(),$rules,$messages);

        if ($v->fails()){
            //dd($v->messages());
            return back()->withErrors($v->messages());
        }

        $data['lote']=$request->lote;
        $data['unidades']=$request->unidades;
        $data['fecha']=$request->fecha1;
        $presentaciones=Productos::getPresentacionByProd($request->txtregistro);
        $arraypresent=[];
       /* foreach($presentaciones as $present) {
          if($present->ID_PRESENTACION==$request->presentaciones){
              $data['presentacion']=$present;
          }
        }*/
        for($i=0;$i<count($presentaciones);$i++){
          for($j=0;$j<count($request->presentaciones);$j++){
            if((string)$presentaciones[$i]->ID_PRESENTACION==$request->presentaciones[$j]){
              $arraypresent[$j]['idpresentacion']=$presentaciones[$i]->ID_PRESENTACION;
              $arraypresent[$j]['nompresentacion']=$presentaciones[$i]->PRESENTACION_COMPLETA;
            }
          }

        }

        $data['presentaciones']=$arraypresent;

       }
       elseif($request->idTramite==21){
          //dd($request->all());

        $rules = [
            'emp1'      =>  'required',
            'cont1'     =>  'required',
            'cant1'     =>  'required'
        ];

        $messages =  array( 'emp1.required' => 'Es necesario que seleccione el empaque primario',
                            'cont1.required' => 'Es necesario que digite la cantidad de la presentacion',
                            'cant1.required' => 'Es necesario que seleccione el contenido de la presentacion'
                      );

        $v = Validator::make($request->all(),$rules,$messages);

        if ($v->fails()){
            //dd($v->messages());
            return back()->withErrors($v->messages());
        }


        if($v->passes()) {

         $data['emp1']=$request->emp1;
         $data['cont1']=$request->cont1;
         $data['cant1']=$request->cant1;
         if($request->has('emp2')){
            $data['emp2']=$request->emp2;
         }
         else{
            $data['emp2']=null;
         }

         if($request->has('cont2')){
            $data['cont2']=$request->cont2;
         }
         else{
            $data['cont2']=null;
         }
         if($request->has('cant2')){
            $data['cant2']=$request->cant2;
         }
         else{
          $data['cant2']=null;
         }

         if($request->has('emp3')){
            $data['emp3']=$request->emp3;
         }
         else{
            $data['emp3']=null;
         }

         if($request->has('cont3')){
            $data['cont3']=$request->cont3;
         }
         else{
            $data['cont3']=null;
         }
         if($request->has('cant3')){
            $data['cant3']=$request->cant3;
         }
         else{
          $data['cant3']=null;
         }

         $data['idMaterial']=$request->idmat;
         $data['nomMaterial']=$request->nom_mat;
         $data['idColor']=$request->idcolor;
         $data['nomColor']=$request->nom_color;
         $data['present']=$request->present;
         $data['tipo']=$request->tipo;
         $data['tipos']=$request->tipos[0];
         $data['accesorios']=$request->accesorios;
         $presentaciones=Productos::getPresentacionByProd($request->txtregistro);
         $data['presentaciones']=$presentaciones;
        }
        else{

            return back()->withErrors($v->messages());
        }
       }
       elseif($request->idTramite==51){
        $idPresentaciones=$request->idPresentacion;
        //dd($idPresentaciones);
        $presentaciones=Productos::getPresentacionByProd($request->txtregistro);
        //dd($presentaciones);
        if(count($presentaciones)==count($idPresentaciones)){
          Session::flash('msnError','El producto no puede quedar sin ninguna presentación');
            return back()->withInput();
        }
        else{
         foreach ($presentaciones as $pre) {
            if(in_array((string)$pre->ID_PRESENTACION,$idPresentaciones,true)){
                $pre->descontinuar=1;
            }
            else{
                $pre->descontinuar=0;
            }

         }

          //dd($presentaciones);
          $data['presentaciones']=$presentaciones;
        }
       }
       else if($request->idTramite==37 || $request->idTramite==38 || $request->idTramite==39){
          if(empty($request->idPresentacion)){
            Session::flash('msnError',' Debe seleccionar al menos una presentación para realizar el trámite!');
            return back()->withInput();
          }

          if($request->has('titular')){
            $data['titular']=$request->titular;
          }
          else{
           $data['titular']=0;
          }
          if($request->has('fabricante')){
            $data['fabricante']=$request->fabricante;
          }
          else{
            $data['fabricante']=0;
          }
          if($request->has('nomprod')){
              $data['nomprod']=$request->nomprod;
          }
          else{
             $data['nomprod']=0;
          }
          //if($request->idTramite==38 || $request->idTramite==39){
            if($request->has('condiciones')){
              $data['condiciones']=$request->condiciones;
            }
            else{
               $data['condiciones']=0;
            }
           if($request->has('acondicionador')){
              $data['acondicionador']=$request->acondicionador;
           }
           else{
              $data['acondicionador']=0;
           }

          //}

          $idPresentaciones=$request->idPresentacion;
          //dd($idPresentacion);
          $presentaciones=Productos::getPresentacionByProd($request->txtregistro);
          //dd($presentaciones);
          for($i=0;$i<count($presentaciones);$i++){
              if(in_array((string)$presentaciones[$i]->ID_PRESENTACION,$idPresentaciones,true)){
                 $presentaciones[$i]->seleccionado=1;
              }
              else{
                $presentaciones[$i]->seleccionado=0;
              }
            }

          $data['presentaciones']=$presentaciones;
        }

        else if($request->idTramite==57){
            $data['idForma']=$request->idForma;
            $data['nomForma']=$request->nomForma;

            $formasfar=DB::table('cssp.siic_formas_farmaceuticas')
                ->where('activo','A')
                ->where('ID_FORMA_FARMACEUTICA',$request->formas)
                ->select('ID_FORMA_FARMACEUTICA','NOMBRE_FORMA_FARMACEUTICA')
                ->first();
            $data['formanueva']=$formasfar;
        }
        //dd($presentaciones);
        else if($request->idTramite==46){
          $data['numPoder']=$request->numPoder;
        }
        else if($request->idTramite==45){
          $data['numPoder']=$request->numPoderA;
        }


       if($request->has('num_mandamiento')){
         $mandamiento=$request->num_mandamiento;
       }
       else{
          $mandamiento=-1;
       }
       $data['mandamiento']=$mandamiento;
       $tipoDocumento=$request->tipoDocumento;

       $indexs=array_keys($request->file('files'));
       //dd($indexs);
       $file=[];
       $path='C:\xampp\htdocs\PortalEnLinea\public\solicitudes';
       //$path='C:\xampp\htdocs\PortalEnLinea\public\solicitudes';
        $carpeta=trim($request->txtregistro).rand();
        Session::put('carpeta',$carpeta);
        //si hay archivos crear la ruta con registro
        $newpath=$path.'\\'.$carpeta;
        File::makeDirectory($newpath, 0777, true, true);
       for($j=0;$j<count($indexs);$j++){
           if($request->file('files')[$indexs[$j]]->getClientMimeType()!='application/pdf'){
               Session::flash('msnError',' No se pueden subir documentos con formato distinto a PDF!');
               return back()->withInput();
           }

          $file[$j]['idDoc']=$indexs[$j];
          $file[$j]['doc']=DB::table('cssp.si_urv_solicitudes_postregistro_lista_chequeo_items')->where('ID_ITEM',$indexs[$j])->first()->NOMBRE_ITEM;
          $file[$j]['uploadfile']=$request->file('files')[$indexs[$j]]->getClientOriginalName();
          /* FUNCION PARA GUARDAR LOS ARCHIVOS */


          $files= $request->file('files');

          if(!empty($files)){
              $index=$indexs[$j];
              //$name= $files[$i]->getClientOriginalName();
              $name=$index.'.'.$files[$index]->getClientOriginalExtension();
              $type= $files[$index]->getClientMimeType();
              $files[$index]->move($newpath,$name);
          }

       }

       $data['files']=$file;

       $data['upload']=$request->file('files');
       $data['documentos']=DB::table('cssp.si_urv_solicitudes_postregistro_lista_chequeo_items')->get();

      //dd($data);
      return view('registro.showsolicitud',$data);

    }

    public function store (Request $request){



      DB::beginTransaction();
      try {
                 $nit=Session::get('user');
                 $tratamiento= PersonaNatural::getTratamiento($nit);
                 $solicitante= PersonaNatural::find($nit);
                 $idSolicitud= SolicitudesVue::getMaxIdSolicitud();
                 $numexpediente=SolicitudesVue::getUltimoExp();
                 $idTipoSolicitud=2;
                 $usuarioAsignado="portalenlinea";



                /* SI EL TRAMITE ES 44 ENTONCES CANCELACION DE REGISTRO Y ENTRA A SESION*/
                 if($request->idTramite==44){
                    $sesion="Si";
                    $estado=2;
                    $usuarioAsignado="portalenlinea";
                 }
                 else if($request->idTramite==33 || $request->idTramite==35 || $request->idTramite==52 || $request->idTramite==45 || $request->idTramite==46){
                    $sesion="No";
                    $estado=1;
                    $usuarioAsignado="";
                }
                else{
                    $sesion="No";
                    $estado=1;
                    $usuarioAsignado="portalenlinea";
                 }

                 /* LAS OBSERVACIONES SON EL TOMAR NOTA QUE EXISTEN EN DIFERENTES TRAMITES*/
                 $observaciones=$request->observaciones;
                 $horaAuto=date('H:i:s');
                 $fechaAuto=date('Y/m/d');
                 $idFormaFarm= SolicitudesVue::getIdFormaFarmByProd($request->txtregistro);
                 /*fichas informacion general del producto la cual se guarda en la tabla solicitudes_vue*/
                 $fichasproductos=SolicitudesVue::getFichasProductoByProd($request->txtregistro);
                 //dd($fichasproductos);
                 $propietario=SolicitudesVue::getPropietarioByProd($fichasproductos->ID_PROPIETARIO);

                 $profesional = Productos::getProfesionalByProducto($request->txtregistro);
                // dd($profesional);
                 $apoderado= SolicitudesVue::getApoderadosByProd($request->txtregistro);
                 //dd($profesional->id_profesional);
                 /* SE CREA UNA NUEVA CLASE PARA GUARDAR LA SOLICITUD EN LA TABLA SIIC_SOLICITUDES_VUE*/
                 $solicitudesVue= new SolicitudesVue();

                 $solicitudesVue->ID_SOLICITUD=$idSolicitud;
                 /*TIPO DE SOLICITU ES 2 QUE ES POSTREGISTRO, 1 ES PRE Y 3 ES RECONOCIMIENTO*/
                 $solicitudesVue->ID_TIPO_SOLICITUD=$idTipoSolicitud;
                 $solicitudesVue->NO_EXPEDIENTE=$numexpediente;
                 $solicitudesVue->NO_REGISTRO=$request->txtregistro;
                 $solicitudesVue->ID_USUARIO_ASIGNADO=$usuarioAsignado;
                 $solicitudesVue->SESION=$sesion;
                 $solicitudesVue->FECHA_AUTO=date('Y/m/d');
                 $solicitudesVue->HORA_AUTO=date('H:i:s');
                 $solicitudesVue->HORA_FECHA_AUTO_TEXTO="";
                 $solicitudesVue->FECHA=date('Y/m/d');
                 //$solicitudesVue->ID_FORMA_FARMACEUTICA=$idFormaFarm;
                if($idFormaFarm!=null){
                  $solicitudesVue->ID_FORMA_FARMACEUTICA=$idFormaFarm;
                 }
                 else{
                   $solicitudesVue->ID_FORMA_FARMACEUTICA='';
                 }
                 $solicitudesVue->ID_TIPO_PRODUCTO=$fichasproductos->id_tipo_producto;
                 $solicitudesVue->NOMBRE=$request->txtnombreprod;
                 $solicitudesVue->PROPIETARIOS_FABRICANTES=$propietario->NOMBRE_PROPIETARIO;
                 $solicitudesVue->ID_PROPIETARIO=$fichasproductos->ID_PROPIETARIO;
                 $solicitudesVue->ID_VIA_ADMINISTRACION=$fichasproductos->id_via_administracion;
                 $solicitudesVue->VIDA_UTIL=$fichasproductos->VIDA_UTIL;
                 if($request->num_mandamiento==-1){
                  $solicitudesVue->MANDAMIENTO=0;
                 }
                 else{
                  $solicitudesVue->MANDAMIENTO=$request->num_mandamiento;
                 }
                //dd($profesional);
                 $titulo= DB::table('cssp.cssp_profesionales as pro')
                          ->join('cssp.cssp_ramas as ra','pro.id_rama','=','ra.id_rama')
                          //->where('pro.activo','A')
                          ->where('ID_PROFESIONAL',$profesional->id_profesional)->first();

                 if(empty($titulo)){
                   if($solicitante->sexo==='F'){
                      $titu='la ';
                      $ella='la ';
                   }
                   else if ($solicitante->sexo==='M'){
                      $titu='el ';
                      $ella='el ';
                    }
                 }
                 else{
                    if($titulo->SEXO==='Femenino' || $titulo->SEXO==='F'){
                      $titu='la '.ucfirst(strtolower($titulo->PREFIJO_FEMENINO));
                      $ella='la';
                   }
                   else if ($titulo->SEXO==='Masculino' || $titulo->SEXO==='M'){
                      $titu='el '.ucfirst(strtolower($titulo->PREFIJO_MASCULINO));
                      $ella='el';
                   }
                 }

                  if($tratamiento!=null){
                   if($request->perfil==='PROFESIONAL RESPONSABLE'){
                      $solicitudesVue->TEXTO_AUTO='A sus antecedentes el escrito presentado, por '.$ella.' '.$tratamiento->nombreTratamiento.' '
                      .Session::get('name').' '.Session::get('lastname').', en su calidad de '.$request->perfil.
                   '  de '. $propietario->NOMBRE_PROPIETARIO .' de '. $propietario->NOMBRE_PAIS.' ';
                   }
                   else{
                    $solicitudesVue->TEXTO_AUTO='A sus antecedentes el escrito presentado, por '.$ella.' '.$tratamiento->nombreTratamiento.' '
                    .Session::get('name').' '.Session::get('lastname').', en su calidad de '.$request->perfil.
                    ' de '. $propietario->NOMBRE_PROPIETARIO .' de '. $propietario->NOMBRE_PAIS.' y '.$titu.' '
                    .$profesional->nombres.' '.$profesional->apellidos.' ';
                  }
                 }
                 else{
                    if($request->perfil==='PROFESIONAL RESPONSABLE'){
                      $solicitudesVue->TEXTO_AUTO='A sus antecedentes el escrito presentado, por '.$titu.' '
                      .Session::get('name').' '.Session::get('lastname').', en su calidad de '.$request->perfil.
                      ' de '. $propietario->NOMBRE_PROPIETARIO .' de '. $propietario->NOMBRE_PAIS.' ';
                    }
                    else{
                      $solicitudesVue->TEXTO_AUTO='A sus antecedentes el escrito presentado, por '.$titu.' '
                      .Session::get('name').' '.Session::get('lastname').', en su calidad de '.$request->perfil.
                      ' de '. $propietario->NOMBRE_PROPIETARIO .' de '. $propietario->NOMBRE_PAIS.' y '.$titu.' '
                      .$profesional->nombres.' '.$profesional->apellidos.' ';
                    }

                 }

                   //Este texto solo se mostrara en el pdf no se guardar, se debe concatenar al texto auto
                  //'Profesional Responsable del producto denominado '.'<b>'.$fichasproductos->NOMBRE.'</b>'.'.';
                 $solicitudesVue->TITULO=$request->perfil;
                 $solicitudesVue->PERSONA=Session::get('name').' '.Session::get('lastname');
                 $solicitudesVue->ID_PROFESIONAL=$profesional->id_profesional;
                 if($apoderado!=null){
                    $solicitudesVue->id_apoderado=$apoderado->ID_APODERADO;
                    $solicitudesVue->id_poder_apoderado=$apoderado->id_poder;
                 }

                 if($request->idTramite==46){
                    $solicitudesVue->id_poder_profesional=$request->numPoder;
                 }

                if($request->idTramite==45){
                  $solicitudesVue->id_poder_apoderado=$request->numPoder;
                }


                 //$solicitudesVue->id_apoderado=$apoderado->ID_APODERADO;
                 //
                 //$solicitudesVue->id_representante=
                 //$solicitudesVue->id_poder_representante=
                 $solicitudesVue->CONDICIONES_ALMACENAMIENTO=$fichasproductos->CONDICIONES_ALMACENAMIENTO;
                 $solicitudesVue->INDICACIONES_TERAPEUTICAS=$fichasproductos->INDICACIONES_TERAPEUTICAS;
                 $solicitudesVue->ID_CLASIFICACION=$fichasproductos->ID_CLASIFICACION;
                 $solicitudesVue->modalidad_venta=$fichasproductos->RECETA;
                 $solicitudesVue->ACTIVO='A';
                 $solicitudesVue->ID_USUARIO_CREACION_AUTO="portalenlinea";
                 $solicitudesVue->formula=$fichasproductos->FORMULA;
                 $solicitudesVue->EXCIPIENTES=$fichasproductos->EXCIPIENTES;
                 $solicitudesVue->nacional=$fichasproductos->NACIONAL;
                 $solicitudesVue->ID_ESTADO=$estado;
                 $solicitudesVue->ID_SOLICITUD_WEB=1;
                 $solicitudesVue->USUARIO_CREACION="portalenlinea@".$request->ip();
                 $solicitudesVue->NIT_SOLICITANTE=$nit;
                 // se guarda la solicitud

                 $solicitudesVue->save();

                 if($solicitudesVue->wasRecentlyCreated==true){
                 // obtentenemos el idSolicitud que se acaba de insertar en la tabla
                    $idSolicitudNew=$idSolicitud;
                    $nExpedienteNew=$solicitudesVue->NO_EXPEDIENTE;
                    $usuarioCreacion=$solicitudesVue->USUARIO_CREACION;
                    //dd($idSolicitudNew);
                    //$idSolicitudNew=$idSolicitud;
                  // $nExpedienteNew=$solicitudesVue->NO_EXPEDIENTE;
                  // $usuarioCreacion=$solicitudesVue->USUARIO_CREACION;

                 }
                 else{
                    Session::flash('msnError','Problemas internos con el servidor, guarde su solicitud nuevamente.');
                    return back()->withInput();

                 }
                 //se guardar la solicitud ingresada en la tabla siic_solicitudes_vue_historial_creacion
                 SolicitudesVueHistorial::store($idSolicitudNew,$nExpedienteNew, $usuarioCreacion);
                 // SE EMPAREJA LA SOLCITUD CON EL TIPO DE TRAMITE QUE SE ESTA REALIZANDO
                 SolicitudesVueTramites::store($idSolicitudNew,$request->idTramite);
                 // SE TRAMITE HAY LISTA DE CHEQUEO LA CUAL SE GUARDA CUALES DOCUMENTOS FUERON ENTREGADOS
                 SoliPostListaChequeo::store($idSolicitudNew,$request->idTramite,$request->tipoDocumento,$usuarioCreacion);
                 /*SE GUARDA LOS PRINCIPIOS ACTIVOS DEL PRODUCTO */
                 SoliVuePrinciposA::store($idSolicitudNew,$request->txtregistro);
                 /* SE GUARDA LOS FABRICANTES DEL PRODUCTO DE LA SOLICITUD*/
                 SoliVueFabricantes::store($idSolicitudNew,$request->txtregistro);
                 /* INSERT EN 4 TABLAS A TRAVES DE UN SP*/
                 SolicitudesVue::insertarSolicitudVue($idSolicitudNew,$usuarioCreacion,$request->txtregistro);
                 /* / HASTA AQUI SE COMPLETA EL PROCESO DE GUARDAR UNA SOLICITUD DE POSTREGISTRO */

                 if($request->idTramite==33 || $request->idTramite==35 || $request->idTramite==52 || $request->idTramite==46 || $request->idTramite==45) {
                      if($request->idTramite==35){
                        $solVueFab=SoliVueFabricantes::where('ID_SOLICITUD',$idSolicitudNew)->where('ID_FABRICANTE',$request->fabAlterno)->first();
                        $solVueFab->ULTIMO_PAGO=date($solVueFab->ULTIMO_PAGO, strtotime('+5 years'));
                        $solVueFab->ACTIVIDAD=1;
                        $solVueFab->save();
                        //dd($solVueFab);
                      }

                      $copiado=$this->guardarDocumentos($idSolicitudNew,$request->txtregistro,$request->img_val);
                      if($copiado==1){
                        DB::commit();
                        Session::flash('msnExito','Su solicitud fue recibida existosamente!');
                        return redirect()->route('nueva.solicitud')->with(['idSolicitud' => $idSolicitudNew, 'idTramite' =>$request->idTramite]);
                        //return redirect()->route('nueva.solicitud')->with('idTramite',$request->idTramite);
                      }
                      else{
                        DB::rollback();
                        Session::flash('msnError','Error en el sistema: No se han podido guardar los documentos adjuntos, por favor vuelva intentarlo!');
                        return redirect()->route('nueva.solicitud');
                      }
                 }
                 /*SI LA SOLICITUD ES DIFERENTE A LA CANCELACION DE REGISTRO, SE DICTAMINA Y CERIFICA*/
                elseif($request->idTramite!=44){
                    /*DICTAMINAR UNA SOLICITUD*/
                    SolicitudesVue::dictaminarSolicitudVue($idSolicitudNew,$request->idArea,$request->idTramite,$usuarioCreacion);
                    if($request->idTramite==36){
                        ProdNomExportacion::storeNomExport($idSolicitudNew,$request->txtregistro,$request->idPais,$request->nomexport,$usuarioCreacion);
                    }
                    if($request->idTramite==66){

                      for($i=0;$i<count($request->presentaciones);$i++){
                        $solpresentacion= new SolVuePresetanciones();
                        $solpresentacion->ID_SOLICITUD=$idSolicitudNew;
                        $solpresentacion->ID_PRESENTACION=$request->presentaciones[$i];
                        $solpresentacion->ID_LOTE=strtoupper($request->lotes[$i]);
                        $solpresentacion->UNIDADES=$request->unidades[$i];
                        $solpresentacion->FECHA=date('Y-m-d ',strtotime($request->fechas[$i]));
                        $solpresentacion->USUARIO_CREACION=$usuarioCreacion;

                        $solpresentacion->save();
                      }
                    }

                    $fabricanteDescontinuar="";
                    if($request->idTramite==61){
                         $fabricantes=Productos::getFabricanteByProdCssp($request->txtregistro);
                          //dd($fabricantes);
                         $idFabs=$request->fabDes;

                        for($j=0;$j<count($fabricantes);$j++) {
                            for($i=0;$i<count($idFabs);$i++){
                              if($fabricantes[$j]->id_fabricante===$idFabs[$i]){
                                if($fabricantes[$j]->tipo==='Principal'){
                                $fabricantes[$j]->descontinuar=1;
                                $fabricantes[$j]->nuevotipo='A DESCONTINUAR';
                                $fabricantes[$j+1]->nuevotipo='Principal';
                                }
                                else if($fabricantes[$j]->tipo==='Alterno'){
                                $fabricantes[$j]->descontinuar=1;
                                $fabricantes[$j]->nuevotipo='A DESCONTINUAR';
                                }
                              }
                              else{
                                 $fabricantes[$j]->descontinuar=0;
                                 //$fabricantes[$j]->nuevotipo='';
                              }
                            }

                       }
                       //dd($fabricantes);
                       foreach ($fabricantes as $fabs) {
                         if($fabs->descontinuar==1){
                            $fabricanteDescontinuar=$fabs->nombre;
                            DB::table('cssp.siic_productos_fabricantes')->where('ID_FABRICANTE',$fabs->id_fabricante)
                              ->where('ID_PRODUCTO',$request->txtregistro)->delete();
                            if($fabs->nuevotipo==='PRINCIPAL'){

                             // DB::table('cssp.siic_productos_fabricantes')->where('ID_FABRICANTE',$fabs->id_fabricante)
                              //  ->where('ID_PRODUCTO',$request->txtregistro)->update(['TIPO' => $fabs->nuevotipo]);
                              DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitudNew));
                              //DB::table('cssp.CSSP_PRODUCTOS_HISTORIAL_FABRICANTES')->insert(['ID_SOLICITUD' => $idSolicitudNew, 'ID_FABRICANTE' => $fabs->id_fabricante,'VIGENTE_HASTA' => $fabs->vigente_hasta,'ULTIMO_PAGO' => $fabs->ultimo_pago]);
                            }
                            else{
                              DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitudNew));
                              //DB::table('cssp.CSSP_PRODUCTOS_HISTORIAL_FABRICANTES')->insert(['ID_SOLICITUD' => $idSolicitudNew, 'ID_FABRICANTE' => $fabs->id_fabricante,'VIGENTE_HASTA' => $fabs->vigente_hasta,'ULTIMO_PAGO' => $fabs->ultimo_pago]);
                            }

                         }
                         else{
                          if($fabs->tipo==='Alterno'){
                             if(!empty($fabs->nuevotipo)){
                              DB::table('cssp.siic_productos_fabricantes')->where('ID_FABRICANTE',$fabs->id_fabricante)
                                ->where('ID_PRODUCTO',$request->txtregistro)->update(['TIPO' => $fabs->nuevotipo]);
                            }

                          }
                             //dd($fabs);
                            DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitudNew));
                            DB::table('cssp.CSSP_PRODUCTOS_HISTORIAL_FABRICANTES')->insert(['ID_SOLICITUD' => $idSolicitudNew, 'ID_FABRICANTE' => $fabs->id_fabricante,'VIGENTE_HASTA' => $fabs->vigencia_hasta,'ULTIMO_PAGO' => $fabs->ULTIMO_PAGO]);
                         }

                       }
                    }

                    if($request->idTramite==67){
                        $laboratorios=Productos::getLabsAcondiByProdCssp($request->txtregistro);
                        $labsDes=$request->labDescon;
                         foreach ($laboratorios as $labs) {
                          for($i=0;$i<count($labsDes);$i++){
                            if($labs->ID_LABORATORIO===$labsDes[$i]){
                                $labs->descontinuar=1;
                            }
                            else{
                                $labs->descontinuar=0;
                            }
                          }
                         }

                       //dd($laboratorios);

                       foreach ($laboratorios as $labs) {
                         if($labs->descontinuar==1){

                            DB::table('cssp.siic_PRODUCTOS_LABORATORIOS')->where('ID_LABORATORIO',$labs->ID_LABORATORIO)
                              ->where('ID_PRODUCTO',$request->txtregistro)->delete();
                            DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitudNew));
                              //DB::table('cssp.CSSP_PRODUCTOS_HISTORIAL_FABRICANTES')->insert(['ID_SOLICITUD' => $idSolicitudNew, 'ID_FABRICANTE' => $fabs->id_fabricante,'VIGENTE_HASTA' => $fabs->vigente_hasta,'ULTIMO_PAGO' => $fabs->ultimo_pago]);

                         }
                         else{
                            DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitudNew));
                            DB::table('cssp.CSSP_PRODUCTOS_HISTORIAL_LABORATORIOS')->insert(['ID_SOLICITUD' => $idSolicitudNew, 'ID_LABORATORIO' => $labs->ID_LABORATORIO]);
                         }

                       }
                    }

                    if($request->idTramite==37 ||$request->idTramite==38 || $request->idTramite==39){
                      foreach ($request->idPresentacion as $idpre) {

                        $solpresentacion= new SolVuePresetanciones();
                        $solpresentacion->ID_SOLICITUD=$idSolicitudNew;
                        $solpresentacion->ID_PRESENTACION=$idpre;
                        if($request->titular==1){
                          $solpresentacion->CAMBIO_TITULAR=1;
                        }
                        if($request->fabricante==2){
                          $solpresentacion->CAMBIO_FAB=1;
                        }
                        if($request->nomprod==3){
                          $solpresentacion->CAMBIO_NOM_PRODPOSTREGISTROS_CAMBIO_ARTE_DIR=1;
                        }
                        //if($request->idTramite==38 || $request->idTramite==39){
                          if($request->has('condiciones')){
                            if($request->condiciones==4){
                                $solpresentacion->CAMBIO_COND_ALMAC=1;
                            }
                          }
                          if($request->has('acondicionador')){
                              if($request->acondicionador==5){
                                  $solpresentacion->CAMBIO_ACONDICIONADOR=1;
                              }
                          }

                       // }
                        $solpresentacion->FECHA=$request->fecha;
                        $solpresentacion->USUARIO_CREACION=$usuarioCreacion;

                        $solpresentacion->save();
                      }
                    }

                    if($request->idTramite==21){
                        //dd($idSolicitudNew);
                        $i=0;
                       // for($i=0;$i<count($request->emp);$i++){
                          $emp1=$request->emp[$i];
                          $cont1=$request->cont1[$i];
                          $cant1=$request->cant1[$i];

                          if($request->emp2!=null){
                            for($j=0;$j<count($request->emp2);$j++){
                               $emp2=$request->emp2[$j];
                               $cont2=$request->cont2[$j];
                               $cant2=$request->cant2[$j];
                            }
                          }
                          else{
                            $emp2=null;
                            $cont2=null;
                            $cant2=null;
                          }

                          if($request->emp3!=null){
                            for($w=0;$w<count($request->emp3);$w++){
                               $emp3=$request->emp3[$w];
                               $cont3=$request->cont3[$w];
                               $cant3=$request->cant3[$w];
                            }
                          }
                          else{
                            $emp3=null;
                            $cont3=null;
                            $cant3=null;
                          }

                          $presentacion = new CatalogoEmpaques();
                          $presentacion->ID_PRODUCTO=$request->txtregistro;
                          $presentacion->TIPO_PRESENTACION=$request->tipo;
                          $presentacion->EMPAQUE_PRIMARIO=$emp1;
                          $presentacion->CANTIDAD_PRIMARIA=$cant1;
                          $presentacion->CONTENIDO_PRIMARIO=$cont1;
                          $presentacion->EMPAQUE_SECUNDARIO=$emp2;
                          $presentacion->CANTIDAD_SECUNDARIA=$cant2;
                          $presentacion->CONTENIDO_SECUNDARIO=$cont2;
                          $presentacion->EMPAQUE_TERCIARIO=$emp3;
                          $presentacion->CANTIDAD_TERCIARIA=$cant3;
                          $presentacion->CONTENIDO_TERCIARIO=$cont3;
                          if($request->idmat!=0){
                            $presentacion->ID_MATERIAL=$request->idmat;
                          }
                          if($request->idcolor!=0){
                            $presentacion->ID_COLOR=$request->idcolor;
                          }
                          $presentacion->ACCESORIOS=$request->accesorios;
                          $presentacion->USUARIO_CREACION="portalenlinea";
                          //dd($presentacion);
                          $presentacion->save();

                          $solpresentacion = new SolicitudEmpPresent();
                          $solpresentacion->ID_SOLICITUD=$idSolicitudNew;
                          $solpresentacion->TIPO_PRESENTACION=$request->tipo;
                          $solpresentacion->EMPAQUE_PRIMARIO=$emp1;
                          $solpresentacion->CANTIDAD_PRIMARIA=$cant1;
                          $solpresentacion->CONTENIDO_PRIMARIO=$cont1;
                          $solpresentacion->EMPAQUE_SECUNDARIO=$emp2;
                          $solpresentacion->CANTIDAD_SECUNDARIA=$cant2;
                          $solpresentacion->CONTENIDO_SECUNDARIO=$cont2;
                          $solpresentacion->EMPAQUE_TERCIARIO=$emp3;
                          $solpresentacion->CANTIDAD_TERCIARIA=$cant3;
                          $solpresentacion->CONTENIDO_TERCIARIO=$cont3;
                          if($request->idmat!=0){
                            $solpresentacion->ID_MATERIAL=$request->idmat;
                          }
                          if($request->idcolor!=0){
                            $solpresentacion->ID_COLOR=$request->idcolor;
                          }
                          $solpresentacion->ACCESORIOS=$request->accesorios;
                          $solpresentacion->USUARIO_CREACION="portalenlinea";
                          $solpresentacion->FECHA_CREACION=date('Y-m-d H:i:s');
                          //dd($presentacion);
                          $solpresentacion->save();

                          //dd($presentacion);
                          $newPresent=DB::table('cssp.si_urv_solicitudes_empaques_presentaciones')->orderBy('ID_PRESENTACION_SOLICITUD','DESC')->limit(1)->first();

                          //dd($newPresent);


                          DB::table('cssp.si_urv_solicitudes_empaques_presentaciones_pivote')
                          ->insert(['id_solicitud' => $idSolicitudNew, 'id_presentacion_solicitud' =>$newPresent->ID_PRESENTACION_SOLICITUD,'usu_creacion'=>'portalenlinea']);

                          DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitudNew));
                       // }
                    }
                    if($request->idTramite==51){
                        $idPresentaciones=$request->idPre;
                          //dd($idPresentaciones);
                          //$presentaciones=Productos::getPresentacionByProd($request->txtregistro);
                            $presentaciones=CatalogoEmpaques::where('ID_PRODUCTO',$request->txtregistro)->get();
                            $solpresentaciones=DB::table('cssp.si_urv_solicitudes_empaques_presentaciones')
                            ->where('ID_SOLICITUD',$idSolicitudNew)->where('USUARIO_CREACION','like','%portalenlinea%')
                            ->get();

                          for($i=0;$i<count($presentaciones);$i++){
                              if(in_array((string)$solpresentaciones[$i]->ID_PRESENTACION,$idPresentaciones,true)){
                                //dd('entro');
                                DB::table('cssp.si_urv_solicitudes_empaques_presentaciones_pivote')
                                  ->insert(['id_solicitud' => $idSolicitudNew, 'id_presentacion_solicitud' =>$solpresentaciones[$i]->ID_PRESENTACION,'usu_creacion'=>'portalenlinea','fecha_creacion' => date('Y-m-d H:i:s')]);
                                  if(in_array((string)$presentaciones[$i]->ID_PRESENTACION,$idPresentaciones,true)){
                                  DB::table('cssp.si_urv_productos_empaques_presentaciones')->where('ID_PRODUCTO',$request->txtregistro)
                                      ->where('ID_PRESENTACION',$presentaciones[$i]->ID_PRESENTACION)->delete();

                                  }
                              }

                          }
                          //dd($presentaciones);
                    }
                    if($request->idTramite==29 || $request->idTramite==27){
                      $actualizacion= new ActualizacioMonoInsert();
                      $actualizacion->ID_SOLICITUD=$idSolicitudNew;
                      if($request->has('version')){
                        $actualizacion->VERSION=$request->version;
                      }
                      if($request->has('fecha')){
                        $actualizacion->FECHA=date('Y-m-d H:i:s',strtotime($request->fecha));
                      }
                      $actualizacion->USUARIO_CREACION='portalenlinea';
                      $actualizacion->save();
                    }
                    if($request->idTramite==57){
                        DB::table('cssp.siic_productos_formas_farmaceuticas')
                                ->where('ID_PRODUCTO',$request->txtregistro)->update(['ID_FORMA_FARMACEUTICA' => $request->idFormaN]);
                    }

                    $sol=SolicitudesVue::certificarSolicitudVue($idSolicitudNew,$request->idTramite,$observaciones,$fichasproductos,$usuarioCreacion);
                    $copiado=$this->guardarDocumentos($idSolicitudNew,$request->txtregistro,$request->img_val);

                    if($copiado==0){
                        DB::rollback();
                        Session::flash('msnError','Error en el sistema: No se han podido guardar los documentos adjuntos, por favor vuelva intentarlo!');
                        return redirect()->route('nueva.solicitud');
                    }

                    if($sol==1 and $copiado==1){
                      DB::commit();
                      Session::flash('msnExito','Su solicitud fue recibida y procesada existosamente!');
                      //return redirect()->back();
                      return redirect()->route('nueva.solicitud')->with(['idSolicitud' => $idSolicitudNew, 'idTramite' =>$request->idTramite]);

                    }

                }
                else{
                  $copiado=$this->guardarDocumentos($idSolicitudNew,$request->txtregistro,$request->img_val);
                    if($copiado==1){
                        SolicitudesVue::dictaminarSolicitudVue($idSolicitudNew,$request->idArea,$request->idTramite,$usuarioCreacion);
                        DB::commit();
                        Session::flash('msnExito','Su solicitud fue recibida y procesada existosamente!');
                        //return redirect()->back();
                        return redirect()->route('nueva.solicitud')->with('idTramite',$request->idTramite);

                      /*$client = new Client();
                      $res = $client->request('POST', $this->url.'sesion/rv/storesesion',[
                        'headers' => [
                              'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                          ],
                          'form_params' =>[
                            'idSolicitud' => $idSolicitudNew
                          ]
                        ]);


                      $r = json_decode($res->getBody());

                      if($r->status==200){
                          $solicitudNew=SolicitudesVue::find($idSolicitudNew);
                          $solicitudNew->ID_ESTADO=8;
                          $solicitudNew->save();
                            //return $solicitudNew;
                            DB::commit();
                            Session::flash('msnExito','Su solicitud fue recibida y esta lista para entrar en sesión!');
                            //return redirect()->back();
                            return redirect()->route('nueva.solicitud')->with('idTramite',$request->idTramite);

                      }
                      elseif($r->status==404){
                        if($copiado==1){
                          DB::commit();
                          Session::flash('msnExito','Su solicitud fue recibida y procesada existosamente!');
                          //return redirect()->back();
                          return redirect()->route('nueva.solicitud')->with('idTramite',$request->idTramite);
                        }
                      }*/
                    }
                    else{
                        DB::rollback();
                        Session::flash('msnError','Error en el sistema: No se han podido guardar los documentos adjuntos, por favor vuelva intentarlo!');
                        return redirect()->route('nueva.solicitud');
                    }
                }
      }
      catch (\Illuminate\Database\QueryException $e) {
              DB::rollback();
              /*Session::flash('msnError',' Error en el servidor: No se han guardar su solicitud, intentelo de nuevo!');
                return redirect()->route('nueva.solicitud');*/
              throw $e;
              //Session::flash('msnError', $e->getMessage());
              return $e;
      }
      catch (Throwable $e) {
            DB::rollback();
            throw $e;
            return $e;
      }
      catch (Exception $e) {
              DB::rollback();
              throw $e;
              Session::flash('msnError', $e->getMessage());
              return $e;
      }

    }


     public function verSolicitudes(){

      $data = ['title'           => 'Solicitudes Post-Registro'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Solicitudes Post-Registro', 'url' => '#']
                ]];
       $data['tramites']=DB::table('cssp.vw_pe_seg_solicitudes_tramites')->orderBy('NOMBRE_TRAMITE','ASC')->get();
      return view('registro.verSolicitudesUsuario',$data);

    }

    public function verSolicitudesAdmin(){

      $data = ['title'           => 'Solicitudes Post-Registro'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Solicitudes Post-Registro', 'url' => '#']
                ]];
      $data['tramites']=VueTramitesTipos::getTramiteByTipo(1);
      return view('registro.tramites.versolicitudes',$data);

    }

    public static function guardarDocumentos($idSolicitudNew,$nregistro,$img_val){
          /* FUNCION PARA GUARDAR LOS ARCHIVOS */
      DB::beginTransaction();
      try {

            $carpeta=Session::get('carpeta');
            $path='C:\xampp\htdocs\PortalEnLinea\public\solicitudes'.'\\'.$carpeta;
            $files = File::allFiles($path);
            //dd($files);
            $npath='U:\PostRegistro';

            $filesystem= new Filesystem();
            if($filesystem->exists($npath)){
                if($filesystem->isWritable($npath)){
                //si hay archivos crear la ruta con el id del usuario
                    $newpath=$npath.'\\'.trim($nregistro).$idSolicitudNew;
                    File::makeDirectory($newpath, 0777, true, true);
                    $copy = new Filesystem();

                    $copiado=$copy->copyDirectory($path,$newpath);

                    $archivos=File::allFiles($newpath);

                    //imagen de confirmacion de solicitud
                    $img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img_val));

                    $filepath = $newpath."/confirmacion-solicitud".$idSolicitudNew.".png";

                    // Save the image in a defined path
                    file_put_contents($filepath,$img);

                   // dd($archivos[0]->getExtension());
                    $requisitos=DB::table('cssp.si_urv_solicitudes_postregistro_requisitos_lista_chequeo')
                                ->where('ID_SOLICITUD',$idSolicitudNew)
                                ->where('USUARIO_CREACION','like',"%portalenlinea%")
                                ->get();

                  if($archivos!=null){
                     //$copy->deleteDirectory($path);
                     //Session::forget('carpeta');
                    foreach ($archivos as $arch) {
                        //dd($arch);
                        foreach ($requisitos as $requi) {
                            //dd($requi);
                            $item=$requi->ID_ITEM;
                            //dd(str_replace('.'.$arch->getExtension(),'',$arch->getFilename()));

                            if(str_replace('.'.$arch->getExtension(),'',$arch->getFilename())===(string)$item)
                             {

                                $doc= new SolVueDocs();
                                $doc->ID_REQUISITO=$requi->ID_REQUISITO;
                                $doc->URL_ARCHIVO=substr_replace($arch->getPathname(),"U",0,1);
                                $doc->TIPO_ARCHIVO=strtolower($arch->getExtension());
                                $doc->USUARIO_CREACION='portalenlinea';
                                $doc->save();
                             }
                             else{
                              //dd('no es 17');
                             }
                        }
                    }

                    DB::commit();
                    return 1;
                  }
                    else{
                      DB::rollback();
                      return 0;
                    }
                  }
                  else{
                    DB::rollback();
                    return 0;
                  }
              }
              else{
                DB::rollback();
                return 0;
              }
      }
      catch (Throwable $e) {
            DB::rollback();
            throw $e;
            return $e;
      }
      catch(Exception $e){
        DB::rollback();
        throw $e;
        Session::flash('msnError', $e->getMessage());
        return $e;
      }


}/* /FIN DE LA FUNCION DE GUARDAR LOS ARCHIVOS*/

    public function getDataRowsSolCertificadas(Request $request){
        $nit=Session::get('user');
        if(in_array(9,Session::get('opciones'),true)){
          $solicitudes=VwSolicitudesRv::orderBy('ID_SOLICITUD','DESC')
                        ->whereIn('ID_TRAMITE',['45','46'])
                        ->distinct();
        }
        else{
          $solicitudes=VwSolicitudesRv::orderBy('ID_SOLICITUD','DESC')->distinct();
        }

       // $path='C:\xampp\htdocs\PortalEnLinea\public\solicitudes/';
        if(in_array(1,Session::get('opciones'),true)){
           return Datatables::of($solicitudes)
                      ->addColumn('estado', function ($dt) {
                          if($dt->ID_ESTADO==10)
                            return  'FINALIZADA';
                          else if($dt->ID_ESTADO==2)
                            return  'EN PROCESO';
                          else if($dt->ID_ESTADO==8)
                            return  'LISTA PARA INGRESAR A SESION';
                          else if($dt->ID_ESTADO==1)
                            return  'INGRESADA';
                          else if($dt->ID_ESTADO==9)
                            return  'REVISADA';
                          else if($dt->ID_ESTADO==3)
                            return  'EN APROBACIÓN DE JUNTA';
                          else if($dt->ID_ESTADO==4)
                            return  'DESFAVORABLE';
                          else if($dt->ID_ESTADO==5)
                              return  'OBSERVADA';
                     })

                      ->addColumn('resolucion', function ($dt) {
                          if($dt->ID_ESTADO==10 || $dt->ID_ESTADO==4 || $dt->ID_ESTADO==5){
                            $tramite=SolicitudesVueTramites::find($dt->ID_SOLICITUD);
                            return '<a href="'.route('imprimir.rv',['idSolicitud' => $dt->ID_SOLICITUD , 'idTramite' => $tramite->ID_TRAMITE]).'" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
                          }
                          else{
                              return '';
                          }
                     })
                      ->addColumn('archivos', function ($dt) {

                          //if($dt->ID_ESTADO==10 || $dt->ID_ESTADO==2 || $dt->ID_ESTADO==8 || $dt->ID_ESTADO==1 || $dt->ID_ESTADO==3)
                            return  '<a href="'.route('get.solicitud.post',['idSolicitud'=>Crypt::encrypt($dt->ID_SOLICITUD)]).'" target="_blank" class="btn btn-xs btn-info btn-perspective"><i class="fa fa-archive" aria-hidden="true"></i></a>';
                     })
                      ->filter(function($query) use ($request){

                        if($request->has('nsolicitud')){
                          $query->where('ID_SOLICITUD','=',$request->get('nsolicitud'));
                        }

                        if($request->has('nregistro')){
                          $query->where('NO_REGISTRO','=',$request->get('nregistro'));
                        }
                        elseif($request->has('nomComercial')){
                          $query->where('NOMBRE_COMERCIAL','like','%'.$request->get('nomComercial').'%');
                        }

                        if($request->has('tipo')){
                          if($request->get('tipo')!=0){
                            $query->where('ID_TRAMITE','=',$request->get('tipo'));
                          }
                        }
                        if($request->has('solicitante')){
                          $query->where('solicitante','like','%'.$request->get('solicitante').'%');
                        }

                      })
                     ->make(true);
        }


    }


    public function getSolicitudesRvUsuario(Request $request){

        $nit=Session::get('user');
        $solicitudes=vwSolicitudesRvUsuario::getAllSolicitudesRv($nit);
        //dd($solicitudes->get());
          return Datatables::of($solicitudes)
                      ->addColumn('estado', function ($dt) {

                          if($dt->NIT_SOLICITANTE!='NULL'){
                            if($dt->ID_ESTADO==10)
                              return  'FINALIZADA';
                            else if($dt->ID_ESTADO==2)
                              return  'EN PROCESO';
                            else if($dt->ID_ESTADO==1)
                              return 'INGRESADA';
                            else if($dt->ID_ESTADO==8)
                              return  'LISTA PARA INGRESAR A SESION';
                            else if($dt->ID_ESTADO==5)
                              return 'OBSERVADA';
                            else if($dt->ID_ESTADO==6)
                              return 'SUBSANADA';
                            else if($dt->ID_ESTADO==7)
                              return 'CANCELADA';
                            else if($dt->ID_ESTADO==3)
                              return 'EN APROBACIÓN DE JUNTA';
                            else if($dt->ID_ESTADO==4)
                              return  'DESFAVORABLE';

                          }
                          else{
                              return strtoupper($dt->ESTADO);
                          }
                     })
                      ->addColumn('resolucion', function ($dt) {

                          if($dt->NIT_SOLICITANTE!='NULL'){
                            $tramite=SolicitudesVueTramites::find($dt->ID_SOLICITUD);
                            if(!empty($tramite)){
                               $res = '<a href="'.route('comprobante.ingreso.rv',['idSolicitud' => $dt->ID_SOLICITUD, 'idTramite' => $tramite->ID_TRAMITE]).'" target="_blank" class="btn btn-info btn-xs btn-perspective" title="Comprobante de Ingreso"><i class="fa fa-file-text"></i></a> ';
                            }else{
                                $res='';
                            }
                            if($dt->ID_ESTADO==1){
                              if(in_array($tramite->ID_TRAMITE,[33,35,52])){
                                  $res .= '<a href="'.route('declaracion.jurada',['idSolicitud' => $dt->ID_SOLICITUD, 'idTramite' => $tramite->ID_TRAMITE]).'" class="btn btn-warning btn-xs btn-perspective" target="_blank" title="Declaracion Jurada"><i class="fa fa-gavel"></i></a>';
                              }
                            }
                            else if($dt->ID_ESTADO==2){

                                if(in_array($tramite->ID_TRAMITE,[45,44])){
                                    $observacion=DB::select('select cssp.fn_get_soli_post_num_obs(?) as observacion',array($dt->ID_SOLICITUD));
                                    if(count($observacion)>=1){
                                        if(!empty($observacion[0]->observacion)) {
                                            $res .= '<a style="text-align:center" href="' . route('imprimir.rv', ['idSolicitud' => $dt->ID_SOLICITUD, 'idTramite' => $tramite->ID_TRAMITE]) . '" class="btn btn-primary btn-xs btn-perspective" target="_blank" title="Imprimir Resolución"><i class="fa fa-print"></i></a>  <br>';

                                        }
                                    }
                                }

                            }
                            else if($dt->ID_ESTADO==10 || $dt->ID_ESTADO==4){
                                $res.='';
                                /*$res.='<a style="text-align:center" href="'.route('imprimir.rv',['idSolicitud' => $dt->ID_SOLICITUD , 'idTramite' => $dt->ID_TRAMITE]).'" class="btn btn-primary btn-xs btn-perspective" target="_blank" title="Imprimir Resolución"><i class="fa fa-print"></i></a>';*/
                            }
                            else if($dt->ID_ESTADO==5){
                                if(in_array($tramite->ID_TRAMITE,[45,46])) {
                                    $res = '<a href="' . route('subsanar.solicitud.post', ['idSolicitud' => Crypt::encrypt($dt->ID_SOLICITUD), 'idTramite' => $tramite->ID_TRAMITE]) . '" target="_blank" class="btn btn-warning btn-xs btn-perspective" title="Subsanar">Subsanar</a>'.' '.
                                        '<a style="text-align:center" href="'.route('imprimir.rv',['idSolicitud' => $dt->ID_SOLICITUD , 'idTramite' => $dt->ID_TRAMITE]).'" class="btn btn-primary btn-xs btn-perspective" target="_blank" title="Imprimir Resolución"><i class="fa fa-print"></i></a>';
                                }
                            }
                            else if($tramite->ID_TRAMITE==44){
                              if($dt->NIT_SOLICITANTE==Session::get('user')){
                                  if($dt->ID_ESTADO!=7){
                                    $date1= new DateTime(date('d-m-Y H:i:s'));
                                    //return $date1;
                                    $date2 = DateTime::createFromFormat('d/m/Y H:i:s', $dt->FECHA_CREACION);
                                    $diff=$date2->diff($date1);
                                    //return $diff->d;
                                    if((int)$diff->d <= 0){
                                       return  '<a href="#" onclick="confirmDesistir('.$dt->ID_SOLICITUD.');" class="btn btn-danger btn-xs btn-perspective" title="Desistir Trámite">Cancelar Trámite</a>';
                                    }
                                  }
                              }
                            }
                            return $res;
                        }
                        else{
                          return '';
                        }
                     })
                     ->addColumn('ventanilla', function ($dt) {
                            if($dt->NIT_SOLICITANTE=='NULL'){
                              return 'VENTANILLA';
                            }
                            else{
                              return 'PORTAL EN LINEA';
                            }
                     })
                     ->addColumn('observaciones', function ($dt) {
                        $observacion=DB::select('select cssp.fn_get_soli_post_num_obs(?) as observacion',array($dt->ID_SOLICITUD));
                        if(!empty($observacion)){
                          return $observacion[0]->observacion;
                        }
                        else{
                          return '';
                        }
                     })
                      ->filter(function($query) use ($request){

                        if($request->has('nsolicitud')){
                          $query->where('ID_SOLICITUD','=',$request->get('nsolicitud'));

                        }

                        if($request->has('nregistro')){
                          $query->where('NO_REGISTRO','=',$request->get('nregistro'));
                        }
                        elseif($request->has('nomComercial')){
                          $query->where('NOMBRE_COMERCIAL','like','%'.$request->get('nomComercial').'%');
                        }

                        if($request->has('tipo')){
                          //dd($request->get('tipo'));
                          if($request->get('tipo')!="0"){
                            $query->where('ID_TRAMITE','=',$request->get('tipo'));
                          }

                        }
                        if($request->has('solicitante')){
                          $query->where('solicitante','like','%'.$request->get('solicitante').'%');
                        }

                      })
                      ->make(true);
    }

    public function getSolicitudesRvPreUsuario(Request $request){

        $nit=Session::get('user');
        $solicitudes=vwSolicitudesPreRvPtl::getSolicitudesPreRv($nit);

          return Datatables::of($solicitudes)
                      ->filter(function($query) use ($request){

                        if($request->has('nsolicitud')){
                          $query->where('ID_SOLICITUD','=',$request->get('nsolicitud'));

                        }

                        if($request->has('nregistro')){
                          $query->where('NO_REGISTRO','=',$request->get('nregistro'));
                        }
                        elseif($request->has('nomComercial')){
                          $query->where('NOMBRE_COMERCIAL','like','%'.$request->get('nomComercial').'%');
                        }

                        if($request->has('tipo')){
                          //dd($request->get('tipo'));
                          if($request->get('tipo')!="0"){
                            $query->where('ID_TRAMITE','=',$request->get('tipo'));
                          }

                        }
                        if($request->has('solicitante')){
                          $query->where('solicitante','like','%'.$request->get('solicitante').'%');
                        }

                      })
                      ->make(true);
    }

     public function showSolicitudesPreByUser(){

      $data = ['title'           => 'Solicitudes de Pre-Registro'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Solicitudes de Pre-Registro', 'url' => '#']
                ]];
      return view('registro.verSolicitudesPre',$data);

    }

    public function solicitudesPost($idSolicitud){

      $data = ['title'           => 'Solicitudes Post-Registro Certificadas'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Solicitudes Post-Registro Certificadas', 'url' => '#']
                ]];
      $idSol= Crypt::decrypt($idSolicitud);

      $solicitud=SolicitudesVue::find($idSol);
      $producto= Productos::getDataRows()->where('idProducto',trim($solicitud->NO_REGISTRO))->first();

      //dd($producto);
      $solicitante= PersonaNatural::find($solicitud->NIT_SOLICITANTE);
      $data['solicitud']=$solicitud;
      $data['producto']=$producto;
      $data['solicitante']=$solicitante;
      $tramite=DB::table('cssp.siic_solicitudes_vue_tramites as tra')
                ->join('cssp.siic_solicitudes_vue_tramites_tipos as tipos','tra.ID_TRAMITE','=','tipos.ID_TRAMITE')
                ->select('tra.ID_SOLICITUD','tipos.ID_TRAMITE','NOMBRE_TRAMITE')
                ->where('tra.ID_SOLICITUD',$idSol)->first();
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

      //$file = File::get($archivos[0]->URL_ARCHIVO);
      //dd($file);
      //dd($data);
      return view('registro.solicitudpost',$data);
    }

    public function download($idSolicitudDoc){

    $idSolDoc=Crypt::decrypt($idSolicitudDoc);
    //return 'no llega aqui';
    $archivo = SolVueDocs::find($idSolDoc);
    //return response()->download($archivo->urlArchivo);

    //dd($archivo);

    if($archivo!=null){


      if($archivo->TIPO_ARCHIVO==='pdf'){
        //parte nueva
        //$archivo->tipoArchivo='application/pdf';
        if (File::isFile($archivo->URL_ARCHIVO))
        {
            $file = File::get($archivo->URL_ARCHIVO);
            //dd($file);
            $response = Response::make($file, 200);
            // using this will allow you to do some checks on it (if pdf/docx/doc/xls/xlsx)
            $response->header('Content-Type', 'application/pdf');
            /*
            $response->header([
              'Content-Type'=> 'application/pdf',
              'Content-Disposition' => 'inline; filename="Arte#"'
              ]);*/

            return $response;
        }
      }
      else if($archivo->TIPO_ARCHIVO==='jpg'){
        if (File::isFile($archivo->URL_ARCHIVO))
        {
            $file = File::get($archivo->URL_ARCHIVO);
            //dd($file);
            $response = Response::make($file, 200);
            // using this will allow you to do some checks on it (if pdf/docx/doc/xls/xlsx)
            $response->header('Content-Type', 'image/jpg');
            /*
            $response->header([
              'Content-Type'=> 'application/pdf',
              'Content-Disposition' => 'inline; filename="Arte#"'
              ]);*/

            return $response;
        }
      }
      // Or to download
      else{
        if (File::isFile($archivo->urlArchivo))
        {

            return Response::download($archivo->urlArchivo);
        }
      }
    }

  }

    public function validarFechasProducto($idProducto){
        $producto=Productos::findOrFail($idProducto);
        $fabricantes = $producto->productosFabricantes;
        $validado= 0;
        $yearnow=date("Y");
        $fechanow=date('Y-m-d');
        $fechalimitepago=date("Y")."-03-31";
        $yearold=date("Y")-1;
        //dd($yearold=="2019" && $fechanow<=$fechalimitepago);
        if(count($fabricantes)>0){
            foreach ($fabricantes as $fab){
                if($fab->TIPO!='N/D' && $fab->TIPO!='Relacionado'){
                      $yearfab=date('Y',strtotime($fab->VIGENTE_HASTA));
                      if($yearnow==$yearfab){
                            //SI LA ANUALIDAD ESTA VIGENTE AL AÑO ACTUAL
                              if (strtotime($fab->ULTIMO_PAGO) < strtotime(date('Y/m/d'))) {
                                    $validado=0;
                              } else {
                                    $validado=1;
                                    break;
                                }
                        }else{
                            //VERIFICAMOS SI SU ANUALIDAD ES DEL AÑO PASODO Y SINO ES MENOR A LA FECHA 31 DE MARZO (ULTIMA FECHA PARA PAGAR ANUALIDAD)
                            if($yearold==$yearfab && $fechanow<=$fechalimitepago){
                                  if (strtotime($fab->ULTIMO_PAGO) < strtotime(date('Y/m/d'))) {
                                        $validado=0;
                                  } else {
                                        $validado=1;
                                        break;
                                  }
                            }
                      }
                     /*$date1= new DateTime(date('Y/m/d',strtotime($now)));
                    $date2 = DateTime::createFromFormat('Y/m/d', $fab->VIGENTE_HASTA);
                    $diff=$date2->diff($date1);
                    if($diff->d==0) {
                        //dd(strtotime($fab->ULTIMO_PAGO) < strtotime(date('Y/m/d')));
                        if (strtotime($fab->ULTIMO_PAGO) < strtotime(date('Y/m/d'))) {
                            $validado=0;
                        } else {
                            $validado=1;
                            break;
                        }
                    }
                    else{
                        $validado=0;
                    }*/

                }
            }
        }else{
              return JsonResponse::create(['message'=>'El fabricante no tiene productos!'],404);
        }
        if($validado==1){
            return JsonResponse::create(['message'=>'Producto con vigencia y renovación vigente'],200);
        }
        else{
            return JsonResponse::create(['message'=>'Este producto '.$producto->ID_PRODUCTO.' '.$producto->NOMBRE_COMERCIAL.' no tiene vigente su anualidad o renovacion!'],404);
        }

    }


    public function getFabricantesByProd($idProducto){
        if($idProducto!=null){
            $fabricantes=Productos::getFabricanteByProdCssp($idProducto);
            if($fabricantes!=null){
                return response()->json($fabricantes);
                //return response()->json(['status' => 200,'message' => 'Resultado con exito', 'data' => $fabricantes]);
            }
            else{
                return response()->json(['status' => 400,'message' => 'Error en la Consulta de Fabricantes', 'data' => []],400);
            }
        }
        else{
            return response()->json(['status' => 404,'message' => 'Error: Debe Seleccionar un producto', 'data' => []],404);
        }
    }

}
