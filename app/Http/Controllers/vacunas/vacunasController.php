<?php

namespace App\Http\Controllers\vacunas;

use Illuminate\Http\Request;
use App\Http\Requests\VacunasRequest;
use App\Http\Controllers\Controller;
use Session;
use GuzzleHttp\Client;
use Config;
use App\Tratamiento;
use App\Models\Cssp\Productos;
use App\Models\Cssp\SolicitudesVue;
use App\Models\Cssp\CatalogoEmpaques;
use App\Models\Establecimientos;
use App\Models\vacunas\solicitudes;
use App\Models\vacunas\CatAnexos;
use App\Models\vacunas\SolicitudAnexos;
use App\Models\vacunas\SolPrincipiosActi;
use App\Models\vacunas\CatUmc;
use App\PersonaNatural;
use Datatables;
use DB;
use Illuminate\Filesystem\Filesystem;
use File;
use Auth;
class vacunasController extends Controller
{
    private $url=null;
    
    public function __construct() { 
        $this->url = Config::get('app.api');
    }
    public function index()
    {
         $data = ['title'            => 'LIBERACI&Oacute;N DE LOTES DE VACUNAS' 
                ,'paneltitle'   => 'NUEVA SOLICITUD'
                ,'subtitle'         => ''];
            
        $unidadesMed= DB::table('dnm_vacunas.cat_umc')->select('id_um','nombre_plural','tipo')
                     ->get();

        $data['unidadesMed']=$unidadesMed;
        return view('vacunas.nuevaSolicitud',$data);
            
    }
    public function sinRegistro()
    {
         $data = ['title'            => 'LIBERACI&Oacute;N DE LOTES DE VACUNAS' 
                ,'paneltitle'   => 'PRE-REGISTRO'
                ,'subtitle'         => ''];
            
            return view('vacunas.sinRegistro',$data);
            
    }

    public function verSolicitudes()
    {
        $data = [
                'title' => 'Solicitudes',
                'subtitle' => 'Liberaci&oacute;n de lotes'
        ];

        $nit = Session::get('user');

        return view('vacunas.versolicitudes',$data);
    }

    public function  getDataSolVacunas(){

            $solicitudes = solicitudes::where('nitSolicitante',Session::get('user'))->get();
            
            return Datatables::of($solicitudes)
            ->addColumn('estado', function ($dt) {
                          if($dt->estatus==1){     
                            return  'RECIBIDA';
                        }
                          else if($dt->estatus==2){
                            return  'EN PROCESO';
                        }
                        else if($dt->estatus==3){
                            return  'FAVORABLE';
                        }
                        else if($dt->estatus==4){
                            return  'OBSERVADA';
                        }
                        else if($dt->estatus==5){
                            return  'DESFAVORABLE';
                        }

                     }) 
                      ->addColumn('fechaEnPais', function ($dt) {
                            if($dt->estatus == 1)
                            {
                                return '<a class="btn btn-primary" data-toggle="modal" href=\'#modal-id\'><i class="fa fa-calendar"></i>Registrar Ingreso</a>';
                            }
                            elseif($dt->estatus == 3)
                            {
                                return '<a class="btn btn-primary bg-success" target="_blank" href="http://localhost/PortalEnLinea/public/lotes/certLieberacion.pdf"><i class="fa fa-print"></i></a>';
                            }
                            elseif($dt->estatus == 4)
                            {
                                return '<a class="btn btn-primary bg-success" target="_blank" href="http://localhost/PortalEnLinea/public/lotes/observada.pdf"><i class="fa fa-print"></i></a>';
                            }
                            elseif($dt->estatus == 5)
                            {
                                return '<a class="btn btn-primary bg-success" target="_blank" href="http://localhost/PortalEnLinea/public/lotes/desfavorable.pdf"><i class="fa fa-print"></i></a>';
                            }                                                        
                          }
                     )
            ->make(true);

    }

    public function getProductos(Request $request)
    {
        $productos = Productos::where('ACTIVO','A')->where('ES_VACUNA','Si');
        

        return Datatables::of($productos)->make(true);
    }

    public function getFormaFarm(Request $request)
    {
        $prod = $request->param;
        $forma = DB::select("select a.id_forma_farmaceutica,b.nombre_forma_farmaceutica as nombre from cssp.siic_productos_formas_farmaceuticas a, cssp.siic_formas_farmaceuticas b where a.id_forma_farmaceutica = b.id_forma_farmaceutica and b.activo = 'A'  and a.id_producto = '".$prod."'");

        return json_encode($forma);

    }

    public function getDosis(Request $request)
    {
        $prod = $request->param;
        $dosis = DB::select("select unidad_de_dosis  from cssp.cssp_productos  where id_producto = '".$prod."'");
        return json_encode($dosis);
    }

    public function getPresentaciones(Request $request)
    {
        $prod = $request->param;
        
        $presentaciones=DB::table('cssp.vw_productos_presentaciones as vpp')
                        ->join('cssp.si_urv_productos_empaques_presentaciones as prp','vpp.ID_PRESENTACION','=','prp.ID_PRESENTACION')
                        ->where('vpp.ID_PRODUCTO',$prod)
                        ->select('vpp.*','prp.CANTIDAD_SECUNDARIA')
                        ->get();


        return $presentaciones;
    }

    public function getPrincipiosActivos(Request $request)
    {
        $prod = $request->param;
        $pa = DB::select("select a.*,b.nombre_materia_prima as nombre,c.nombre_unidad_medida from cssp.siic_productos_formula a, cssp.siic_materias_primas b , cssp.cssp_unidades_medida c where a.id_principio_activo = b.id_materia_prima and a.id_unidad_medida = c.id_unidad_medida and b.activo = 'A'  and a.id_producto = '".$prod."'");

        return json_encode($pa);
    }

    public function getDataRowATC(Request $request)
    {
        $act = DB::table('dnm_vacunas.catalogo_registro as reg')
                        ->join('dnm_vacunas.catalogo_pa_vacunas as pa','pa.id','=','reg.idpavacunas')
                        ->join('dnm_vacunas.atc_cat_current as atc','atc.atc_code','=','pa.atc')
                        ->where('reg.numero_registro',$request->param)
                        ->get();
        //dd($act);               
        return json_encode($act);
    }


    public function getEstablecimientos(Request $request)
    {
        $est = Establecimientos::whereIn('estado',['A','CS']);

        return Datatables::of($est)->make(true);
    }

    public function getTitularProducto($idProducto)
    {   //dd($idProducto);
        //$prod = $request->param;
        
        $titular = DB::select("Select prop.id_propietario,prop.nombre_propietario, pais.nombre_pais from cssp.cssp_productos prod 
            inner join cssp.cssp_propietarios prop on prop.id_propietario = prod.id_propietario
            inner join cssp.cssp_paises pais on pais.id_pais = prop.id_pais
            where prod.id_producto = '".$idProducto."';");


        return $titular;
    }

    public function getFabricantesProducto(Request $request)
    {
        $prod = $request->param;
        $fab = DB::select("select a.id_fabricante,b.nombre_comercial as nombre, a.tipo, a.vigente_hasta, a.ultimo_pago, c.nombre_pais from cssp.siic_productos_fabricantes a, cssp.cssp_establecimientos b, cssp.cssp_paises c where a.id_fabricante = b.id_establecimiento and b.id_pais = c.id_pais and a.id_producto = '".$prod."'");

        return json_encode($fab);
    }

    public function getAcondicionadorProducto(Request $request)
    {

    }


    public function getPAxATC(Request $request)
    {
        
        $nom = DB::select('select * from dnm_vacunas.catalogo_pa_vacunas where atc = \''.$request->param.'\'');

        return json_encode($nom);
    }


    public function saveSolicitud(VacunasRequest $request)
    {   
        //dd($request->all());
         
        $solicitud = new solicitudes();
    
        if(!isset($request->idProducto))
        {

        }
        elseif (!isset($request->idEstablecimiento)) {
            
        }
        elseif (!isset($request->pa)) {
           return  back()->withErrors(['Debe seleccionar una o mas potencias!']);
        }
        else
        {
            DB::beginTransaction();
            $paNombres = '';
            if(is_array($request->pa))
            {
                foreach ($request->pa as $key => $value) {
                   $paNombres .= $value;
                }
            }

            try {
                $nit=Session::get('user');
                $persona=PersonaNatural::find($nit);

                $presentacion=DB::table('cssp.vw_productos_presentaciones')
                                ->where('ID_PRESENTACION',(int)$request->presentaciones)
                                ->first();

                $fabricante=DB::table('cssp.vwestablecimientos')
                            ->where('ID_ESTABLECIMIENTO',$request->fabs)->first();

                $propietario=Productos::getPropietario($request->titus);

                $unidadCont1=trim(strtolower($presentacion->CONTENIDO_PRIMARIO));

                $rg_pro=DB::table('dnm_establecimientos_si.vwestablecimiento_rg_pro_cidnm')
                        ->where('idEstablecimiento',$request->idEstablecimiento)
                        ->first();

                $solicitud->noregistro = $request->idProducto;
                $solicitud->tipo = 1;
                //enviado=2
                $solicitud->estatus = 2;
                $solicitud->establecimiento = $request->idEstablecimiento;
                $solicitud->es_fondo_rotatorio = 0;
                $solicitud->nombre_producto = $request->nombreComercial;
                $solicitud->principio_activo=$request->principio_activo;
                $solicitud->principio_activo_nombre = $request->nombreVacuna;
                $solicitud->atc=$request->atc_code;
                $solicitud->atc_nombre = $request->atc;
                $solicitud->forma_farmaceutica_nombre=$request->idFarm;
                $solicitud->forma_farmaceutica = $request->formaFarmaceutia;
                $solicitud->dosis_magnitud = $presentacion->CANTIDAD_PRIMARIA;
                
                $unidadCont1=trim(strtolower($presentacion->CONTENIDO_PRIMARIO));
                $compunidad=CatUmc::where('abr','like','%'.$unidadCont1.'%')->first();
                if($compunidad!=null){
                    $solicitud->dosis_unidad=$compunidad->id_um;
                }
                else{
                    $umc = new CatUmc();
                    $umc->nombre_um=$unidadCont1;
                    $umc->abr=$unidadCont1;
                    $umc->tipo=1;
                    $umc->es_base=0;
                    $umc->estatus=1;
                    $umc->save();
                }
                
                $solicitud->id_presentacion=$presentacion->ID_PRESENTACION;
                $solicitud->nom_presentacion=$presentacion->PRESENTACION_COMPLETA;

                if($request->presentaciones=="-1"){
                    $solicitud->envase_primario = $request->presentacion1;
                }
                else{
                    $solicitud->envase_primario = $presentacion->EMPAQUE_PRIMARIO;    
                }
                
                $solicitud->envase_secundario=$presentacion->EMPAQUE_SECUNDARIO;
                $solicitud->dosis_envase_primario=(int)$presentacion->CANTIDAD_SECUNDARIA;
                $solicitud->cond_alm_min = $request->loteMin;
                $solicitud->cond_alm_max = $request->loteMax;
                $solicitud->tamano_lote_magnitud = $request->loteVolumen;
                $solicitud->tamano_lote_unidad = $request->loteVolumenUnidad;
                $solicitud->vida_util_magnitud = $request->loteVidaUtil;
                $solicitud->vida_util_unidad = $request->loteVidaUtilUnidad;
                $solicitud->envases_a_liberar = $request->envasesLote;
                $solicitud->dosis_a_liberar = $request->dosisLote;
                $solicitud->fecha_fabricacion = $request->loteFecFabricacion;
                $solicitud->fecha_expiracion = $request->loteFecExpiracion;
                $solicitud->numero_lote = $request->numLote;
                $solicitud->diluyente = $request->diluyente;
                $solicitud->diluyente_volumen = $request->volDiluyente;
                $solicitud->diluyente_unidad = $request->volDiluyenteUnidad;
            
                if($request->expDiluyente!='' || $request->expDiluyente!='0000-00-00'){
                    $solicitud->diluyente_expiracion = $request->expDiluyente;    
                }
                
                $solicitud->adyuvante = $request->adyuvante;
                $solicitud->preservante = $request->preservante;
                $solicitud->estabilizante = $request->estabilizante;
                $solicitud->excipiente = $request->excipiente;
                $solicitud->antibiotico = $request->antibiotico;

                $solicitud->propietario_nombre=$propietario->NOMBRE_PROPIETARIO;
                $solicitud->propietario_domicilio=$propietario->DIRECCION;
                $solicitud->propietario_email=$propietario->EMAIL;
                $solicitud->propietario_tel=$propietario->TELEFONO_1;
                
                $solicitud->fabricante_nombre=$fabricante->NOMBRE_COMERCIAL;
                $solicitud->fabricante_domicilio=$fabricante->DIRECCION;
                $solicitud->fabricante_pais=$fabricante->pais;
                //$solicitud->acondicionador=
                $solicitud->importador_nombre=$request->nombreEstablecimiento;
                $solicitud->importador_domicilio=$request->dirEstablecimiento;
                $solicitud->importador_email=$request->telefonosEstablecimiento;
                $solicitud->importador_tel=$request->emailEstablecimiento;
                
                $solicitud->quimico_inscripcion=$rg_pro->idProfesional;
                $solicitud->quimico_nombre=$rg_pro->nombreregente;
                $solicitud->representante_inscripcion=$rg_pro->nitPropietario;
                $solicitud->representante_nombre=$rg_pro->nombrepropietario;
                
                $solicitud->notificacion_email=$persona->emailsContacto;
                $tels=json_decode($persona->telefonosContacto);
                $solicitud->notificacion_tel=$tels[0].','.$tels[1];
                
                $solicitud->nitSolicitante = Session::get('user');
                $solicitud->creado_por = "portalenlinea@".$request->ip();
                $solicitud->save();

                $idSolicitud=$solicitud->id;

                foreach ($request->pa as $prinpa) {
                    $solprinActivo = new SolPrincipiosActi();
                    if($prinpa==="N\R"){
                        $solprinActivo->idSolicitud=$idSolicitud;
                        $solprinActivo->idPrincipioActivo=$prinpa;
                        $solprinActivo->nombrePrincipioActivo=$request->noconcidepotencia;
                        
                    }
                    else{
                        $datos= SolPrincipiosActi::getPrincpioActivoByProd($prinpa,$request->idProducto);
                        $solprinActivo->idSolicitud=$idSolicitud;
                        $solprinActivo->idPrincipioActivo=$prinpa;
                        $solprinActivo->nombrePrincipioActivo=$datos->nombre_materia_prima;
                        $solprinActivo->idUnidadMedida=$datos->id_unidad_medida;
                        $solprinActivo->nombreUnidadMedida=$datos->nombre_unidad_medida;
                        $solprinActivo->concentracion=$datos->CONCENTRACION;
                        $solprinActivo->porcentaje1=$datos->PORCENTAJE_1;
                        $solprinActivo->porcentaje2=$datos->PORCENTAJE_2;
                    }
                    $solprinActivo->save();
                }
               
                $path='C:\Vacunas';
                $file= $request->file('file');

                if(!empty($file)){
                    $indexs=array_keys($request->file('file'));
                    //dd($indexs);
                    $filesystem= new Filesystem();
                    if($filesystem->exists($path)){
                        if($filesystem->isWritable($path)){
                            //si hay archivos crear la ruta con el id del usuario
                            $carpeta=$path.'\\'.$request->idProducto.$idSolicitud;
                            //crea la nueva carpeta
                            File::makeDirectory($carpeta, 0777, true, true);
                            // se guadarn en el disco 
                            //dd($indexs);
                            for($j=0;$j<count($indexs);$j++){
                                //$index=;
                                //dd($index);
                                $catanexos=CatAnexos::find($indexs[$j]);
                                $name= $catanexos->nombre_anexo.'-'.$request->idProducto.'.'.$file[$indexs[$j]]->getClientOriginalExtension();
                                //$name= $indexs[$j].'.'.$file[$indexs[$j]]->getClientOriginalExtension();
                                $type = $file[$indexs[$j]]->getMimeType();
                                
                                $file[$indexs[$j]]->move($carpeta,$name);
                                //dd($file[$index]);
                                //se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora
                                $anexo = new SolicitudAnexos();
                                $anexo->id_solicitud=$idSolicitud; 
                                $anexo->fecha_adicion=date('Y-m-d H:i:s');
                                $anexo->id_anexo=$catanexos->id_anexo;
                                $anexo->ruta=$carpeta.'\\'.$name;
                                $anexo->nombre_archivo=$name; 
                                $anexo->titulo=$catanexos->nombre_anexo;
                                $anexo->save();
                                
                            }
                        }
                        else{
                            Session::flash('msnError', 'PROBLEMAS INTERNOS CON EL DIRECTORIO!,NO SE HA PODIDO GUARDAR EL DOCUMENTO DE SU SOLICITUD, VUELVA A INGRESAR SU SOLICITUD!');
                            return redirect()->back();
                        }
                    }
                    else{
                        Session::flash('msnError', 'PROBLEMAS INTERNOS CON EL SERVIDOR!, NO SE HA PODIDO ACCEDER A LA UNIDAD DE DISCO, VUELVA A INGRESAR SU SOLICITUD!');
                        return redirect()->back();
                    }
                        
                }
                                
            } catch(Exception $e){
                DB::rollback();
                return $e->getMessage();  
            }
            
            DB::commit();
            Session::flash('msnExito','Su solicitud fue recibida y procesada existosamente!');      
          //return redirect()->back();
          return redirect()->route('nueva.solicitud.vacuna')->with('idTramite',$solicitud->id);
        }
    }

    public function updEstadoSolicitudes(Request $request)
    {
        
    }

    
}
