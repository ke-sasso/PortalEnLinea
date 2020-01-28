<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Sim\TramitesPost;
use App\Models\Sim\VwProductosSim;
use Datatables;
use DB;
use Excel;
use App\Models\Sim\SimProductos;
use App\Models\Sim\SimTramitesRequisitos;
use App\Models\Sim\SimSolicitudes;
use App\Models\Sim\SolicitudTramitePost;
use App\Models\Sim\SimDictamenPost;
use App\Models\Sim\CatalogoItemsPost;
use App\Models\Sim\DictamenPostItems;
use App\Models\Sim\RequisitoDocumento;
use App\Models\Sim\SolPostListaChequeo;
use App\Http\Controllers\PdfController;
use App\Models\Cssp\SolicitudesVue;
use App\Models\Sim\SolPostArchivos;
use App\Models\Sim\VWSimProducFabricantes;
use App\Models\Sim\TramitesFabPost;
use App\Models\Sim\SolCodigoModelo;
use App\Models\Sim\ProdCodModelo;
use App\Models\Sim\CertificacionPost;
use App\Models\Sim\DesistimientoSol;
use App\Models\Sim\SolicitudesFabs;
use App\Models\Sim\vwSolicitudesSimPre;
use App\Models\Sim\vwSolicitudesSimPost;
use File;
use Mail;
use App\PersonaNatural;
use Crypt;
use Illuminate\Filesystem\Filesystem;
use Config;
use Validator;
use GuzzleHttp\Client;
use Exception;

class InsumosController extends Controller
{
    /*public $url  = null;

    public function __construct()
    {
        $this->url  = Config::get('app.api');
    }*/

    //
    public function index()
    {

        $data = ['title' => 'Nueva Solicitud : Insumos Médicos'
            , 'subtitle' => ''
            , 'breadcrumb' => [
                ['nom' => 'Insumos Médicos', 'url' => '#'],
                ['nom' => 'Nueva Solicitud', 'url' => '#']
            ]];
        $nit = Session::get('user');

        $documentos = SimTramitesRequisitos::getDocumentosByRequisito();
        $requisitos = SimTramitesRequisitos::getRequisitosByTramite();
        $tramites = TramitesPost::whereIn('idClasificacion', [1, 2])->orderBy('nombre', 'asc')->get();
        $data['tramites'] = $tramites;
        //1 son los de ventilla express
        $perfiles = DB::select('select * from dnm_usuarios_portal.vwperfilportal where NIT = "' . $nit . '" and UNIDAD = CONVERT( "SIM" USING UTF8) COLLATE utf8_general_ci');

        if (!empty($perfiles)) {
            $data['perfiles'] = $perfiles;
            $data['sinPerfil'] = 0;
        } else {
            $perfiles1 = Session::get('perfiles');
            $array = [];
            foreach ($perfiles1 as $perfil) {
                $i = 0;
                //if($perfil->UNIDAD==='EST'){
                $array[$i] = $perfil;
                //}
                $i++;
            }
            $data['perfiles'] = $array;
            $data['sinPerfil'] = 1;
        }

        $data['documentos'] = $documentos;
        $data['requisitos'] = $requisitos;
        $data['requisitoDoc'] = DB::table('sim.sim_tramite_post_requisito_documento')->get();
        //dd($data);
        return view('sim.index', $data);

    }

    public function getDataProdSim(Request $request)
    {

        if ($request->idTramite != 19) {
            $nit = Session::get('user');
            $simprods = VwProductosSim::getDataRows()->where('idPersonaNatural', $nit)->distinct();
        } else {
            $simprods = VwProductosSim::getDataRows();
        }

        $alerta = 0;
        return Datatables::of($simprods)
            ->addColumn('alerta', function ($dt) {
                if ($dt->VIGENTE_HASTA < date('Y-m-d', strtotime("2018/12/31")))
                    if (date("Y", strtotime($dt->ULTIMA_RENOVACION)) <= date("2017")) {
                        //return $alerta=1;
                        return 1;
                    } else {
                        return 1;

                    }
                else {
                    return 0;
                }

            })
            ->make(true);
    }


    public function verificarMandamiento(Request $request)
    {
        //dd($request->all());
        if ($request->mandamiento == '1228522') {
            return response()->json(['status' => 200, 'message' => "El mandamiento es válido para usar en este trámite!"], 200);
        } else {

            try {
                if ($request->mandamiento != null) {

                    $verificacion = DB::table('cssp.cssp_mandamientos as m')
                        ->join('cssp.cssp_mandamientos_detalle as md', 'md.id_mandamiento', '=', 'm.id_mandamiento')
                        ->join('cssp.cssp_mandamientos_recibos as mr', 'mr.id_mandamiento', '=', 'm.id_mandamiento')
                        ->whereNotIn('m.id_mandamiento', function ($query) use ($request) {
                            $query->select('NUMERO_MANDAMIENTO')
                                ->from('sim.sim_solicitudes')
                                ->where('NUMERO_MANDAMIENTO', $request->mandamiento)
                                ->whereIn('ESTADO_SOLICITUD', [0, 2, 4]);
                        })
                        ->where('m.id_mandamiento', $request->mandamiento)
                        ->select('md.correlativo', 'md.id_mandamiento', 'md.id_tipo_pago', 'md.valor')
                        ->get();


                    if (count($verificacion) > 0) {
                        $tramite = TramitesPost::find($request->idTipoTramite);

                        $valorTramite = DB::table('cssp.cssp_tipos_pagos_col')->where('ID_TIPO_PAGO', $tramite->id_tipo_pago)->first();

                        $tiposPagos = $verificacion->pluck('id_tipo_pago')->toArray();

                        if (in_array($tramite->id_tipo_pago, $tiposPagos, true)) {
                            return response()->json(['status' => 200, 'message' => "El mandamiento es válido para usar en este trámite!"], 200);

                        } else {
                            return response()->json(['status' => 400, 'message' => "Mandamiento no es valido, no posee el tipo de pago correspondiente al tramite " . mb_strtolower($tramite->nombre, 'utf8') . "!"], 200);
                        }


                    } else {
                        return response()->json(['status' => 400, 'message' => "Mandamiento no es valido o ya ha sido utilizado en otra solicitud!"], 200);
                    }
                }
            } catch (Exception $e) {
                throw $e;

                return response()->json(['status' => 404, 'message' => "Error, favor contacte a DNM informática"], 200);
            }

        }


    }


    public function confirmacion(Request $request)
    {

        if ($request->hasFile('files')) {
            $reqValidados = SimTramitesRequisitos::validarRequisitos(array_keys($request->file('files')), $request->idTramite);

            if (!$reqValidados) {
                Session::flash('msnError', 'Es obligatorio que suba todos los archivos adjuntos según documentos');
                return back()->withInput();
            }
        } else {

            Session::flash('msnError', 'Es obligatorio que suba todos los archivos adjuntos según documentos');
            return back()->withInput();
        }


        $data = ['title' => 'Verificacion de la Solicitud:'
            , 'subtitle' => ''
            , 'breadcrumb' => [
                ['nom' => 'Insumos Médicos', 'url' => '#'],
                ['nom' => 'Nueva Solicitud Post-Registro', 'url' => '#']
            ]];
        $data['idTramite'] = $request->idTramite;
        $solicitud = $this->getPIM($request);
        $data['solicitud'] = $solicitud->getData()->data;


        if ($request->idTramite != 28) {
            $producto = SimProductos::find($request->correlativo);
            $data['producto'] = $producto;
            $data['mandamiento'] = $request->mandamiento;

            $data['perfil'] = $request->perfil;

            //dd($numeroDocumentos);
            if ($request->idTramite == 13) {
                $data['descripcion'] = $request->descripcion1;
            } elseif ($request->idTramite == 9 || $request->idTramite == 10) {
                $data['descripcion'] = $request->descripcion2;
            } elseif ($request->idTramite == 6 || $request->idTramite == 11) {
                $data['descripcion'] = $request->descripcion4;
            } elseif ($request->idTramite == 8 || $request->idTramite == 16) {
                $data['descripcion'] = $request->descripcion3;
            } elseif ($request->idTramite == 7) {
                //dd($request->all());
                $data['codigos'] = $request->codigos;
                $data['modelo'] = $request->modelo;
                $data['descripcion5'] = $request->descripcion5;
            } elseif ($request->idTramite == 5) {
                if ($request->has('cods')) {
                    if (count($request->cods) > 0) {
                        $data['codigo'] = $request->cods;
                        $modelos = $request->modelos;

                        $mod = [];
                        $des6 = [];
                        // $mod[[]] = array(0 => 3);
                        for ($i = 0; $i < count($modelos); $i++) {
                            if ($modelos[$i] != 0 and $modelos[$i] != -1) {

                                $prodcodmodelo = ProdCodModelo::find($modelos[$i]);
                                $mod[$i] = ['id' => $prodcodmodelo->id_producto_codmod, 'modelo' => $prodcodmodelo->modelos];
                            } else {
                                //$mod[$i]=0;
                                $mod[$i] = ['id' => 0, 'modelo' => 'NO APLICA'];
                            }

                            if ($request->descrip6[$i] != 0 and $request->descrip6[$i] != -1) {
                                //SI SELECCIONO UNA DESCRIPCION DEL SELECT ESE SE GUARDA
                                $prodcodmodelo = ProdCodModelo::find($request->descrip6[$i]);
                                $des6[$i] = ['id' => $prodcodmodelo->id_producto_codmod, 'descripcion' => $prodcodmodelo->descripcion];
                            } elseif ($request->descrip6[$i] == 0) {
                                //si es no aplica se le pone lo que digite el usuario en el campo descripcion
                                //$mod[$i]=0;
                                if (empty($request->descripcion6[$i])) {
                                    $des6[$i] = ['id' => 0, 'descripcion' => 'NO APLICA'];
                                } else {
                                    $des6[$i] = ['id' => 0, 'descripcion' => $request->descripcion6[$i]];
                                }
                            } else {

                                $des6[$i] = ['id' => 0, 'descripcion' => $request->descripcion6[$i]];
                            }


                        }
                        //dd($mod);
                        $data['modelos'] = $mod;
                        $data['descripcion6'] = $des6;
                    } else {
                        Session::flash('msnError', 'Es obligatorio que digite o suba un archivo con los códigos a adicionar!');
                        return back()->withInput();
                    }
                } else {
                    Session::flash('msnError', 'Es obligatorio que digite o suba un archivo con los códigos a adicionar!');
                    return back()->withInput();
                }
            } elseif ($request->idTramite == 12) {
                $data['descripcion'] = strtoupper($request->descripcion);
            }
        }
        //dd($data);
        $data['idClasificacion'] = $request->idClasificacion;
        $indexs = array_keys($request->file('files'));
        //dd($indexs);
        $file = [];
        $path = 'C:\xampp\htdocs\PortalEnLinea\public\simpost';
        //$path='C:\xampp\htdocs\PortalEnLinea\public\solicitudes';
        if ($request->idTramite == 28) {
            $carpeta = rand();
        } else {
            $carpeta = trim($request->correlativo) . rand();
        }

        Session::put('carpeta', $carpeta);
        //si hay archivos crear la ruta con registro
        $newpath = $path . '\\' . $carpeta;
        File::makeDirectory($newpath, 0777, true, true);
        for ($j = 0; $j < count($indexs); $j++) {
            $file[$j]['idDoc'] = $indexs[$j];
            //$file[$j]['doc']=DB::table('cssp.si_urv_solicitudes_postregistro_lista_chequeo_items')->where('ID_ITEM',$indexs[$j])->first()->NOMBRE_ITEM;
            $file[$j]['uploadfile'] = $request->file('files')[$indexs[$j]]->getClientOriginalName();
            /* FUNCION PARA GUARDAR LOS ARCHIVOS */


            $files = $request->file('files');

            if (!empty($files)) {
                $index = $indexs[$j];
                //$name= $files[$i]->getClientOriginalName();
                $requiDoc = RequisitoDocumento::find($index);
                $name = $requiDoc->requisitoId . '-' . $requiDoc->documentoTramiteId . '.' . $files[$index]->getClientOriginalExtension();
                $type = $files[$index]->getClientMimeType();
                $files[$index]->move($newpath, $name);
            }

        }

        if ($request->idTramite == 6) {
            $fabricantes = VWSimProducFabricantes::where('ID_PRODUCTO', $request->txtidproducto)->where('TIPO', 4)->get();;
            $data['fabricantes'] = $fabricantes;
            //dd($fabricantes);
        }
        if ($request->idTramite == 9) {
            $acondicionadores = VWSimProducFabricantes::where('ID_PRODUCTO', $request->txtidproducto)->where('TIPO', 5)->get();
            $data['acondicionadores'] = $acondicionadores;
            //dd($fabricantes);
        }

        if ($request->idTramite == 10) {
            $acondicionadores = VWSimProducFabricantes::where('ID_FABRICANTE', $request->idFab[0])
                ->where('ID_PRODUCTO', $request->txtidproducto)
                ->get();
            $data['acondicionadores'] = $acondicionadores;
        }
        if ($request->idTramite == 11 || $request->idTramite == 18 || $request->idTramite == 17 || $request->idTramite == 12) {
            $fabricantes = VWSimProducFabricantes::where('ID_FABRICANTE', $request->idFab[0])
                ->where('ID_PRODUCTO', $request->txtidproducto)
                ->get();
            $data['fabricantes'] = $fabricantes;
        }
        /*
        if ($request->idTramite == 1) {
            $fabricantes = VWSimProducFabricantes::where('ID_PRODUCTO', $request->txtidproducto)->get();
            foreach ($fabricantes as $fab) {
                if (in_array((string)$fab->ID_FABRICANTE, $request->idFab, true)) {
                    $fab->seleccionado = 1;
                } else {
                    $fab->seleccionado = 0;
                }
            }
            $data['fabricantes'] = $fabricantes;
        }*/

        if ($request->idTramite == 14 || $request->idTramite == 15) {
            $codmods = ProdCodModelo::whereIn('id_producto_codmod', $request->codmod)->get();
            //dd($codmods);
            $data['codmods'] = $codmods;
        }

        $data['files'] = $file;
        $documentos = SimTramitesRequisitos::getDocumentosByRequisitoByTramite()->where('tramiteTipoId', $request->idTramite);
        $requisitos = SimTramitesRequisitos::getRequisitosByTramite()->where('tramiteTipoId', $request->idTramite);
        $data['documentos'] = $documentos;
        $data['requisitos'] = $requisitos;
        $data['requisitoDoc'] = DB::table('sim.sim_tramite_post_requisito_documento')->get();
        //$data['documentos']=DB::table('cssp.si_urv_solicitudes_postregistro_lista_chequeo_items')->get();
        //dd($data);
        return view('sim.showsolicitud', $data);
    }

    public function storeSolicitud(Request $request)
    {
        $nit = Session::get('user');
        $tratamiento = PersonaNatural::getTratamiento($nit);
        DB::beginTransaction();
        try {
            if ($request->idTramite != 28) {
                //dd($tratamiento->nombreTratamiento);
                $producto = SimProductos::find($request->correlativo);
                //dd($producto);

                $newsolicitud = new SimSolicitudes();
                $newsolicitud->ID_TRAMITE = 'SIM02';
                $newsolicitud->CLASIFICACION_POST_ID = $request->idClasificacion;

                if ($request->idTramite == 27) {
                    $newsolicitud->NUMERO_MANDAMIENTO = 0;
                } else {
                    $newsolicitud->NUMERO_MANDAMIENTO = $request->mandamiento;
                }

                $newsolicitud->IM = $producto->ID_PRODUCTO;
                $newsolicitud->NOMBRE_INSUMO = $request->nomcomercial;
                $newsolicitud->PROPIETARIO = $producto->ID_PROPIETARIO;
                $newsolicitud->PODER_PROFESIONAL = $producto->ID_PODER_PROFESIONAL;
                $newsolicitud->PODER_APODERADO_REPRESENTANTE = $producto->ID_PODER_APODERADO;
                $newsolicitud->USUARIO_CREACION = "portalenlinea";
                $newsolicitud->NIT_SOLICITANTE = $nit;
                if ($request->idClasificacion == 2) {
                    $newsolicitud->ESTADO_SOLICITUD = 0;
                    if($request->idTramite!=7 && $request->idTramite!=27 && $request->idTramite!=5){
                        $newsolicitud->DESCRIPCION_TRAMITE = strtoupper($request->descripcion);
                    }
                } else {
                    if ($request->idTramite == 8) {
                        $newsolicitud->DESCRIPCION_TRAMITE = strtoupper($request->descripcion);
                    }
                    $newsolicitud->ESTADO_SOLICITUD = 2;
                }

                if ($request->perfil === 'APODERADO') {
                    $newsolicitud->TIPO_PODER_AR = 2;
                } elseif ($request->perfil === 'PROFESIONAL RESPONSABLE') {
                    $newsolicitud->TIPO_PODER_AR = 1;
                } elseif ($request->perfil === 'PROPIETARIO') {
                    $newsolicitud->TIPO_PODER_AR = 3;
                }

                if ($newsolicitud->save()) {
                    //dd($newsolicitud);
                    $idSolicitud = $newsolicitud->ID_SOLICITUD;
                    $solTramitePost = new SolicitudTramitePost();
                    $solTramitePost->tramite_id = $request->idTramite;
                    $solTramitePost->solicitud_id = $idSolicitud;
                    $solTramitePost->save();

                    SolPostListaChequeo::store($idSolicitud, $request->tipoDocumento, "portalenlinea");
                    $savedocs = $this->guardarDocumentos($idSolicitud, $request->correlativo, $request->img_val);
                    if ($savedocs == 0) {
                        DB::rollback();
                        //throw $e;
                        //return $e;
                        Session::flash('msnError', 'Error en el sistema: No se han podido guardar los documentos adjuntos, por favor vuelva intentarlo!');
                        return redirect()->route('indexsim');
                    }

                    if ($request->idTramite == 7) {
                        for ($i = 0; $i < count($request->modelo); $i++) {
                            $solCodModelo = new SolCodigoModelo();
                            $solCodModelo->solicitud_id = $idSolicitud;
                            $solCodModelo->codigos = $request->codigos[$i];
                            $solCodModelo->modelos = $request->modelo[$i];
                            $solCodModelo->descripcion = $request->descripcion5[$i];
                            $solCodModelo->usu_creacion = 'portalenlinea';
                            $solCodModelo->save();
                        }
                    }

                    if ($request->idTramite == 5) {
                        for ($i = 0; $i < count($request->codigo); $i++) {
                            $solCodModelo = new SolCodigoModelo();
                            $solCodModelo->solicitud_id = $idSolicitud;
                            $solCodModelo->codigos = strtoupper($request->codigo[$i]);
                            if ($request->modelos[$i] == "0") {
                                $solCodModelo->modelos = 'NO APLICA';
                            } else {
                                $prodcodmodelo = ProdCodModelo::find($request->modelos[$i]);
                                $solCodModelo->modelos = $prodcodmodelo->modelos;
                            }
                            $solCodModelo->descripcion = $request->descrip[$i];
                            $solCodModelo->usu_creacion = 'portalenlinea';
                            $solCodModelo->save();

                            /*if ($request->modelos[$i] != 0) {
                                $prodcodmodelo = ProdCodModelo::find($request->modelos[$i]);
                                //dd($prodcodmodelo);
                                $prodcodmodelo = new ProdCodModelo();
                                $prodcodmodelo->codigos = strtoupper($request->codigo[$i]);
                                $prodcodmodelo->modelos = $prodcodmodelo->modelos;
                                $prodcodmodelo->descripcion = $request->descrip[$i];
                                $prodcodmodelo->usu_creacion = 'portalenlinea';
                                $prodcodmodelo->save();

                            } else {
                                $prodCodigoM = new ProdCodModelo();
                                $prodCodigoM->producto_id = $producto->ID_PRODUCTO;
                                $prodCodigoM->codigos = strtoupper($request->codigo[$i]);
                                $prodCodigoM->modelos = 'NO APLICA';
                                $prodCodigoM->descripcion = $request->descrip[$i];
                                $prodCodigoM->usu_creacion = 'portalenlinea';
                                $prodCodigoM->save();
                                //$mod[$i]=['id'  => 0, 'modelo' => 'NO APLICA'];
                            }*/

                        }
                    }

                    if ($request->idTramite == 14) {
                        foreach ($request->codmod as $com) {

                            $prodcodmod = ProdCodModelo::find($com);

                            $solCodModelo = new SolCodigoModelo();
                            $solCodModelo->solicitud_id = $idSolicitud;
                            $solCodModelo->codigos = $prodcodmod->codigos;
                            $solCodModelo->modelos = $prodcodmod->modelos;
                            $solCodModelo->descripcion = $prodcodmod->descripcion;
                            //actividad 3 de eliminar
                            $solCodModelo->actividad = 3;
                            $solCodModelo->usu_creacion = 'portalenlinea';
                            $solCodModelo->save();
                        }
                    }

                    if ($request->idTramite == 10 || $request->idTramite == 11 || $request->idTramite == 18 || $request->idTramite == 17 || $request->idTramite == 12) {
                        $tramitesFabs = new TramitesFabPost();
                        $tramitesFabs->id_solicitud = $idSolicitud;
                        $tramitesFabs->id_fabricante = $request->idFab[0];
                        //1 eliminacion 2 adicion, 3 lugar de fabs.
                        if ($request->idTramite == 12) {
                            $tramitesFabs->id_actividad = 3;
                        } elseif ($request->idTramite == 10 || $request->idTramite == 11) {
                            $tramitesFabs->id_actividad = 2;
                        } else {
                            $tramitesFabs->id_actividad = 1;
                        }

                        $tramitesFabs->usuario_creacion = "portalenlinea";
                        $tramitesFabs->save();
                    }
                    /*
                    if ($request->idTramite == 1) {
                        $fabs = $request->idFab;
                        for ($i = 0; $i < count($fabs); $i++) {
                            $simSolFabs = new SolicitudesFabs();
                            $simSolFabs->ID_SOLICITUD = $idSolicitud;
                            $simSolFabs->ID_FABRICANTE = $fabs[$i];
                            $simSolFabs->TIPO_FABRICANTE = $request->tipoFab[$i];
                            $simSolFabs->save();
                        }

                    }*/

                    if ($request->idTramite == 8) {

                        $simproducto = SimProductos::find($request->correlativo);
                        $simproducto->PRESENTACIONES = str_replace(".", "", trim($simproducto->PRESENTACIONES)) . '; ' . ucfirst(strtolower($request->descripcion));
                        $simproducto->FECHA_MODIFICACION = date('Y-m-d H:i:s');
                        $simproducto->USUARIO_MODIFICACION = 'portalenlinea';
                        $simproducto->save();

                    }
                    if ($request->idClasificacion != 2) {
                        $dictamenpost = new SimDictamenPost();
                        $dictamenpost->solicitud_id = $idSolicitud;
                        //2 de aprobada
                        $dictamenpost->estado = 2;
                        $hora = PdfController::numAletras(date('H'));
                        $min = PdfController::numAletras(date('i'));
                        $dias = PdfController::numAletras(date('d'));
                        $year = PdfController::numAletras(date('Y'));
                        $meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");

                        $encabezado = "<strong>DIRECCION NACIONAL DE MEDICAMENTOS:</strong>" . " a las " . $hora . ' horas ' . $min . " minutos del día " . $dias . " de " . $meses[date('n') - 1] . " del " . $year . ".";

                        $tramite = TramitesPost::find($request->idTramite);
                        $dictamenpost->resolucion_encabezado = $encabezado;

                        $titulo = DB::table('cssp.cssp_profesionales as pro')
                            ->join('cssp.cssp_ramas as ra', 'pro.id_rama', '=', 'ra.id_rama')
                            ->where('pro.activo', 'A')
                            ->where('NIT', $nit)->first();
                        //dd($titulo);

                        $propietario = SolicitudesVue::getPropietarioByProd($producto->ID_PROPIETARIO);
                        //dd($propietario);
                        if ($titulo != null) {
                            if ($titulo->SEXO === 'Femenino') {
                                $titu = 'la ' . ucfirst(strtolower($titulo->PREFIJO_FEMENINO));
                                $ella = 'la';
                            } else if ($titulo->SEXO === 'Masculino') {
                                $titu = 'el ' . ucfirst(strtolower($titulo->PREFIJO_MASCULINO));
                                $ella = 'el';
                            }
                        } else {
                            $solicitante = PersonaNatural::find($nit);
                            if ($solicitante->sexo === 'F') {
                                $titu = 'la ' . ucfirst(strtolower($tratamiento->nombreTratamiento));
                                $ella = 'la';
                            } else if ($solicitante->sexo === 'M') {
                                $titu = 'el ' . ucfirst(strtolower($tratamiento->nombreTratamiento));
                                $ella = 'el';
                            }

                        }

                        if ($tratamiento != null) {
                            if ($request->perfil === 'PROFESIONAL RESPONSABLE') {
                                if ($request->idTramite == 17 || $request->idTramite == 18 || $request->idTramite == 15) {
                                    $parrafo1 = 'Se tiene por recibido el escrito de fecha ' . $dias . ' de ' . $meses[date('n') - 1] . ' del ' . $year . ' en curso, suscrito por  la sociedad ' .
                                        $propietario->NOMBRE_PROPIETARIO . ' y ' . $ella . ' ' . $tratamiento->nombreTratamiento . ' <b>' . ' ' . Session::get('name') . ' ' . Session::get('lastname') . '</b>,' .
                                        ' en su calidad de Profesional Responsable, en el cual manifiestan su deseo de ';
                                    $resto1 = $this->getParrafoI($request);
                                    $resto2 = 'relacionado al dispositivo médico denominado <b>' . $request->nomcomercial . '</b>, con número de registro sanitario: <b>' . $producto->ID_PRODUCTO . '</b>.';
                                    $resolucionParrafoI = $parrafo1 . $resto1 . ' ' . $resto2;
                                    //dd($resolucionParrafoI);
                                    $dictamenpost->resolucion_parrafo_I = $resolucionParrafoI;
                                } else {
                                    $resolucionParrafoI = 'Vista la solicitud con referencia a ' . mb_strtolower($tramite->nombre,"UTF-8") .
                                        ', suscrita por la ' . $ella . ' ' . $tratamiento->nombreTratamiento . ' <u>' . Session::get('name') . ' ' . Session::get('lastname') .
                                        '</u>, en su calidad de ' . strtolower($request->perfil) . ' en relación al dispositivo médico <b><u>' . $request->nomcomercial . '</u></b>, de la Sociedad <b><u>' . $propietario->NOMBRE_PROPIETARIO . '</u></b> inscrito en esta Dirección al número: <b>' . $producto->ID_PRODUCTO . '</b>.';
                                    $dictamenpost->resolucion_parrafo_I = $resolucionParrafoI;
                                }
                            } else {
                                if ($request->idTramite == 17 || $request->idTramite == 18 || $request->idTramite == 15) {
                                    $parrafo1 = 'Se tiene por recibido el escrito de fecha ' . $dias . ' de ' . $meses[date('n') - 1] . ' del ' . $year . ' en curso, suscrito por  la sociedad ' .
                                        $propietario->NOMBRE_PROPIETARIO . ' y ' . $ella . ' ' . $tratamiento->nombreTratamiento . ' <b>' . ' ' . Session::get('name') . ' ' . Session::get('lastname') . '</b>,' .
                                        ' en su calidad de Profesional Responsable, en el cual manifiestan su deseo de ';
                                    $resto1 = $this->getParrafoI($request);
                                    $resto2 = 'relacionado al dispositivo médico denominado <b>' . $request->nomcomercial . '</b>, con número de registro sanitario: <b>' . $producto->ID_PRODUCTO . '</b>.';
                                    $resolucionParrafoI = $parrafo1 . $resto1 . $resto2;
                                    //dd($resolucionParrafoI);
                                    $dictamenpost->resolucion_parrafo_I = $resolucionParrafoI;
                                } else {
                                    $resolucionParrafoI = 'Vista la solicitud con referencia a <b>' . mb_strtolower($tramite->nombre,"UTF-8") .
                                        '</b>, suscrita por ' . $ella . ' ' . $tratamiento->nombreTratamiento . ' <b>' . Session::get('name') . ' ' . Session::get('lastname') . '</b>, en su calidad de <b>' .
                                        $request->perfil . '</b> de la sociedad <b>' . $propietario->NOMBRE_PROPIETARIO . '</b> en relación al dispositivo médico <b>' . $request->nomcomercial . '</b> inscrito en esta Dirección al número : <b>' . $producto->ID_PRODUCTO . '</b>.';
                                    $dictamenpost->resolucion_parrafo_I = $resolucionParrafoI;
                                }
                            }
                        } else {
                            if ($request->perfil === 'PROFESIONAL RESPONSABLE') {
                                $resolucionParrafoI = 'Vista la solicitud con referencia a <b>' . mb_strtolower($tramite->nombre,"UTF-8") .
                                    '</b>, suscrita por la sociedad <b>' . $propietario->NOMBRE_PROPIETARIO . '</b> y por ' . $titu . ' <b>' . Session::get('name') . ' ' . Session::get('lastname') . '</b>, en su calidad de <b>' .
                                    $request->perfil . '</b> en relación al dispositivo médico <b>' . $request->nomcomercial . '</b> inscrito en esta Dirección al número : <b>' . $producto->ID_PRODUCTO . '</b>.';
                                $dictamenpost->resolucion_parrafo_I = $resolucionParrafoI;
                            } else {
                                $resolucionParrafoI = 'Vista la solicitud con referencia a <b>' . mb_strtolower($tramite->nombre,"UTF-8") .
                                    '</b>, suscrita por ' . $titu . ' <b>' . Session::get('name') . ' ' . Session::get('lastname') . '</b>, en su calidad de <b>' .
                                    $request->perfil . '</b> de la sociedad <b>' . $propietario->NOMBRE_PROPIETARIO . '</b> en relación al dispositivo médico <b>' . $request->nomcomercial . '</b> inscrito en esta Dirección al número  : <b>' . $producto->ID_PRODUCTO . '</b>.';
                                $dictamenpost->resolucion_parrafo_I = $resolucionParrafoI;
                            }
                        }

                        $dictamenpost->usuario_creacion = 'portalenlinea';
                        $dictamenpost->usuario_modificacion = 'portalenlinea';
                        $dictamenpost->save();
                        //dd($dictamenpost);
                        $iddictamenpost = $dictamenpost->id_dictamen_post;
                        //dd($request->idFab[0]);
                        if ($request->idTramite == 18 || $request->idTramite == 17) {
                            //DB::transaction(function () {
                            DB::table('sim.sim_productos_fabricantes')
                                ->where('id_fabricante', $request->idFab[0])
                                ->where('id_producto', $producto->ID_PRODUCTO)
                                ->delete();
                            //});
                        }

                        if ($request->idTramite == 15) {
                            //dd($request->codmod);
                            foreach ($request->codmod as $com) {
                                $prodcodmod = ProdCodModelo::find($com);

                                $solCodModelo = new SolCodigoModelo();
                                $solCodModelo->solicitud_id = $idSolicitud;
                                $solCodModelo->codigos = $prodcodmod->codigos;
                                $solCodModelo->modelos = $prodcodmod->modelos;
                                $solCodModelo->descripcion = $prodcodmod->descripcion;
                                //actividad 3 de eliminar
                                $solCodModelo->actividad = 3;
                                $solCodModelo->usu_creacion = 'portalenlinea';
                                $solCodModelo->save();

                                $prodcodmod->modelos = '';
                                $prodcodmod->save();
                            }
                        }

                        $items = CatalogoItemsPost::all();

                        foreach ($items as $item) {
                            $dictamenitempost = new DictamenPostItems();
                            $dictamenitempost->dictamen_id = $iddictamenpost;
                            $dictamenitempost->item_id = $item->id_item;
                            $dictamenitempost->orden = $item->id_item;
                            $dictamenitempost->estado = 2;
                            $dictamenitempost->usuario_creacion = 'portalenlinea';
                            $dictamenitempost->usuario_modificacion = 'portalenlinea';
                            $dictamenitempost->save();
                        }

                        $certificacion = new CertificacionPost();
                        $certificacion->solicitud_id = $idSolicitud;
                        $certificacion->producto_id = $producto->ID_PRODUCTO;
                        $certificacion->resolucion_encabezado = $encabezado;
                        $certificacion->resolucion_parrafo_I = $resolucionParrafoI;
                        $certificacion->estado = 1;
                        $certificacion->usuario_creacion = 'portalenlinea';
                        $certificacion->save();
                        //dd($request->all());

                        DB::commit();
                        Session::flash('msnExito', 'Su solicitud fue recibida y procesada existosamente!');
                        //return redirect()->back();
                        return redirect()->route('indexsim')->with(['idSolicitud' => $idSolicitud, 'idTramite' => $request->idTramite]);
                    } else {
                        if ($request->idTramite == 27) {
                            DB::commit();
                            Session::flash('msnExito', 'Su solicitud fue recibida y esta lista para entrar en sesión!');
                            return redirect()->route('indexsim')->with(['SinCerificar' => 1, 'idSolicitud' => $idSolicitud]);
                            /*
                      $client = new Client();
                      $res = $client->request('POST', $this->url.'sesion/sim/storesesionsim',[
                        'headers' => [
                              'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                          ],
                          'form_params' =>[
                            'idSolicitud' => $idSolicitud
                          ]
                        ]);


                      $r = json_decode($res->getBody());

                      if($r->status==200){

                            Session::flash('msnExito','Su solicitud fue recibida y esta lista para entrar en sesión!');
                            //return redirect()->back();
                            return redirect()->route('indexsim')->with(['SinCerificar' => 1,'idSolicitud' => $idSolicitud]);
                      }
                      elseif($r->status==404){
                          Session::flash('msnExito','Su solicitud fue recibida e ingresada existosamente!');
                          //return redirect()->back();
                          return redirect()->route('indexsim')->with(['SinCerificar' => 1,'idSolicitud' => $idSolicitud]);

                      }*/

                        }

                    }

                    DB::commit();
                    Session::flash('msnExito', 'Su solicitud fue recibida e ingresada existosamente!');
                    //return redirect()->back();
                    return redirect()->route('indexsim')->with(['SinCerificar' => 1, 'idSolicitud' => $idSolicitud]);
                }

            } else {
                //dd($request->all());
                $solicitudpre = $this->getPIM($request);
                //dd($solicitud);

                $solicitud = SimSolicitudes::find($request->idsolicitud);
                //dd($solicitud);
                $solicitud->ESTADO_SOLICITUD = 3;
                $solicitud->USUARIO_MODIFICACION = 'portalenlinea';
                $solicitud->NIT_SOLICITANTE = $nit;
                $solicitud->FECHA_MODIFICACION = date('Y-m-d H:i:s');


                $hora = PdfController::numAletras(date('H'));
                $min = PdfController::numAletras(date('i'));
                $dias = PdfController::numAletras(date('d'));
                $year = PdfController::numAletras(date('Y'));

                $meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");

                $encabezado = "<strong>DIRECCION NACIONAL DE MEDICAMENTOS:</strong>" . " a las " . $hora . ' horas ' . $min . " minutos del día " . $dias . " de " . $meses[date('n') - 1] . " del " . $year . ".";

                $datosSol = DB::table('sim.sim_solicitudes as sol')
                    ->join('sim.sim_solicitudes_fabricantes as solfab', 'sol.ID_SOLICITUD', '=', 'solfab.ID_SOLICITUD')
                    ->join('sim.sim_fabricantes as fab', 'solfab.ID_FABRICANTE', '=', 'fab.ID_FABRICANTE')
                    ->join('cssp.cssp_paises as pa', 'fab.PAIS', '=', 'pa.ID_PAIS')
                    ->select('fab.NOMBRE_FABRICANTE', 'pa.NOMBRE_PAIS')
                    ->whereIn('solfab.TIPO_FABRICANTE', [1, 2])
                    ->where('sol.ID_SOLICITUD', $request->idsolicitud)
                    ->first();

                $propietario = DB::table('cssp.cssp_propietarios as pro')
                    ->join('cssp.cssp_paises as pa', 'pro.ID_PAIS', '=', 'pa.ID_PAIS')
                    ->where('pro.ID_PROPIETARIO', $solicitud->PROPIETARIO)
                    ->first();
                //dd($propietario);
                $solicitante = PersonaNatural::find($nit);
                //dd($solicitante);
                if ($solicitante->sexo === 'F') {
                    $ella = 'la';
                } else if ($solicitante->sexo === 'M') {
                    $ella = 'el';
                }
                //dd($ella);
                if ($solicitud->save()) {

                    $desistimiento = new DesistimientoSol();
                    $desistimiento->pim = $solicitud->PIM;
                    $desistimiento->encabezado = $encabezado;
                    if ($tratamiento != null) {

                        if ($request->perfil === 'PROFESIONAL RESPONSABLE') {
                            $desistimiento->texto_I = 'Se tiene por recibido el escrito de fecha ' . $dias . ' de ' . $meses[date('n') - 1] . ' del ' . $year . ' en curso, suscrito por  la sociedad ' .
                                $propietario->NOMBRE_PROPIETARIO . ' y ' . $ella . ' ' . $tratamiento->nombreTratamiento . '<b>' . Session::get('name') . ' ' . Session::get('lastname') . '</b>,' .
                                'en su calidad de Profesional Responsable en relación al producto denominado <b>' . $solicitud->NOMBRE_INSUMO . ' </b>,' .
                                'con número de control interno PIM <b>' . $solicitud->PIM . '</b>, cuyo fabricante es ' . $datosSol->NOMBRE_FABRICANTE . ',  del domicilio de ' . $datosSol->NOMBRE_PAIS . ',' .
                                'en el cual manifiestan su deseo de desistir del trámite de registro sanitario del producto antes mencionado.';
                        } else {
                            $desistimiento->texto_I = 'Se tiene por recibido el escrito de fecha ' . $dias . ' de ' . $meses[date('n') - 1] . ' del ' . $year . ' en curso, suscrito por'
                                . $ella . ' ' . $tratamiento->nombreTratamiento . ' <b>' . Session::get('name') . ' ' . Session::get('lastname') . '</b>, y de la sociedad ' .
                                $propietario->NOMBRE_PROPIETARIO . ' en relación al producto denominado <b>' . $solicitud->NOMBRE_INSUMO . ' </b>,' .
                                'con número de control interno PIM <b>' . $solicitud->PIM . '</b>, cuyo fabricante es ' . $datosSol->NOMBRE_FABRICANTE . ',  del domicilio de ' . $datosSol->NOMBRE_PAIS . ',' .
                                'en el cual manifiestan su deseo de desistir del trámite de registro sanitario del producto antes mencionado.';

                        }
                    }
                    $desistimiento->usuario_creacion = 'portalenlinea';
                    $desistimiento->save();
                    $saveDocs = $this->guardarDocumentos($request->idsolicitud, $solicitud->PIM, $request->img_val);
                    if ($saveDocs == 0) {
                        DB::rollback();
                        //throw $e;
                        //return $e;
                        Session::flash('msnError', 'Error en el sistema: No se han podido guardar los documentos adjuntos, por favor vuelva intentarlo!');
                        return redirect()->route('indexsim');
                    }
                    //dd($solicitudpre);
                    $data['solicitudpre'] = $solicitudpre->getData()->data;
                    $data['solicitudpre']->solicitante = Session::get('name') . ' ' . Session::get('lastname');
                    //dd($data);
                    /*Mail::send('emails.desistimientosimpre', $data, function ($msj) use ($data) {
                        $msj->subject('Solicitud de Desistimiento de tramite pre-registro');
                        $msj->to('haryes.funes@medicamentos.gob.sv');
                    });*/

                    DB::commit();
                    Session::flash('msnExito', 'Su solicitud fue recibida e ingresada existosamente!');

                    return redirect()->route('indexsim')->with(['desestimiento' => $solicitud->PIM, 'idTramite' => $request->idTramite]);
                }
            }
        }//END TRY
        catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            throw $e;
            Session::flash('msnError', $e->getMessage());
            return $e;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
            return $e;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
            Session::flash('msnError', $e->getMessage());
            return $e;
        }

    }

    public function getProducto(Request $request)
    {
        //dd($request->all());
        if ($request->idProducto != null) {
            $producto = SimProductos::where('ID_PRODUCTO', $request->idProducto)->first();
            if ($producto != null) {

                return response()->json(['status' => 200, 'message' => 'Resultado con exito', 'data' => $producto]);
            } else {
                return response()->json(['status' => 400, 'message' => 'Error en la Consulta de Productos', 'data' => []]);
            }
        } else {
            return response()->json(['status' => 404, 'message' => 'Error: Debe Seleccionar un producto', 'data' => []]);
        }

    }

    public function getModelosByIM(Request $request)
    {
        //dd($request->all());
        if ($request->idProducto != null) {
            $modelos = ProdCodModelo::getModelosByIm($request->idProducto);
            if ($modelos != null) {
                return response()->json(['status' => 200, 'message' => 'Resultado con exito', 'data' => $modelos]);
            } else {
                return response()->json(['status' => 400, 'message' => 'Error en la Consulta de Modelos por producto', 'data' => []]);
            }
        } else {
            return response()->json(['status' => 404, 'message' => 'Error: Debe Seleccionar un producto', 'data' => []]);
        }

    }

    public function getPIM(Request $request)
    {
        //dd($request->all());
        if ($request->pim != null) {
            $solicitud1 = SimSolicitudes::where('ID_TRAMITE', 'SIM01')->where('ESTADO_SOLICITUD', 0)
                ->where('PIM', $request->pim)->first();
            //dd($solicitud1);
            if ($solicitud1->TIPO_PODER_AR == 1) {
                //Si el tipo es 1 entonces es por apoderado
                $solicitud = DB::table('sim.sim_solicitudes as dbSimSoli')
                    ->select('dbSimSoli.*', DB::raw('CONCAT(dbCP.NOMBRES," ",dbCP.APELLIDOS ) as PROFESIONAL'), DB::raw('CONCAT(dbSA.NOMBRES," ",dbSA.APELLIDOS ) as APODERADO'))
                    ->join('cssp.siic_profesionales_poderes as dbSPP', 'dbSimSoli.PODER_PROFESIONAL', '=', 'dbSPP.ID_PODER')
                    ->join('cssp.cssp_profesionales as dbCP', 'dbSPP.ID_PROFESIONAL', '=', 'dbCP.ID_PROFESIONAL')
                    ->join('cssp.siic_apoderados_poderes as dbAP', 'dbSimSoli.PODER_APODERADO_REPRESENTANTE', '=', 'dbAP.ID_PODER')
                    ->join('cssp.siic_apoderados as dbSA', 'dbAP.ID_APODERADO', '=', 'dbSA.ID_APODERADO')
                    ->where('dbSimSoli.ID_SOLICITUD', $solicitud1->ID_SOLICITUD)
                    ->first();
            } elseif ($solicitud1->TIPO_PODER_AR == 2) {
                //Si el tipo es 2 entonces es por representante
                $solicitud = DB::table('sim.sim_solicitudes as dbSimSoli')
                    ->select('dbSimSoli.*', DB::raw('CONCAT(dbCP.NOMBRES," ",dbCP.APELLIDOS ) as PROFESIONAL'), DB::raw('CONCAT(dbSR.NOMBRES," ",dbSR.APELLIDOS ) as REPRESENTANTE'))
                    ->join('cssp.siic_profesionales_poderes as dbSPP', 'dbSimSoli.PODER_PROFESIONAL', '=', 'dbSPP.ID_PODER')
                    ->join('cssp.cssp_profesionales as dbCP', 'dbSPP.ID_PROFESIONAL', '=', 'dbCP.ID_PROFESIONAL')
                    ->join('cssp.siic_representantes_legales_poderes as dbRLP', 'dbSimSoli.PODER_APODERADO_REPRESENTANTE', '=', 'dbRLP.ID_PODER')
                    ->join('cssp.siic_apoderados as dbSR', 'dbRLP.ID_REPRESENTANTE', '=', 'dbSR.ID_APODERADO')
                    ->where('dbSimSoli.ID_SOLICITUD', $solicitud1->ID_SOLICITUD)
                    ->first();
            } else if ($solicitud1->TIPO_PODER_AR == 3) {
                //Si el tipo es 3 entonces es un representante que NO existe en la bdd de Jurídico, por lo tanto será manual
                $solicitud = DB::table('sim.sim_solicitudes as dbSimSoli')
                    ->select('dbSimSoli.*', DB::raw('CONCAT(dbCP.NOMBRES," ",dbCP.APELLIDOS ) as PROFESIONAL'))
                    ->join('cssp.siic_profesionales_poderes as dbSPP', 'dbSimSoli.PODER_PROFESIONAL', '=', 'dbSPP.ID_PODER')
                    ->join('cssp.cssp_profesionales as dbCP', 'dbSPP.ID_PROFESIONAL', '=', 'dbCP.ID_PROFESIONAL')
                    ->where('dbSimSoli.ID_SOLICITUD', $solicitud1->ID_SOLICITUD)
                    ->first();
            } else if ($solicitud1->TIPO_PODER_AR == 4) {
                //Si el tipo es 3 entonces es una solicitud desde el propietario
                $solicitud = DB::table('sim.sim_solicitudes as dbSimSoli')
                    ->select('dbSimSoli.*', DB::raw('CONCAT(dbCP.NOMBRES," ",dbCP.APELLIDOS ) as PROFESIONAL'), DB::raw('dbSP.NOMBRE_PROPIETARIO'))
                    ->join('cssp.siic_profesionales_poderes as dbSPP', 'dbSimSoli.PODER_PROFESIONAL', '=', 'dbSPP.ID_PODER')
                    ->join('cssp.cssp_profesionales as dbCP', 'dbSPP.ID_PROFESIONAL', '=', 'dbCP.ID_PROFESIONAL')
                    ->join('cssp.cssp_propietarios as dbSP', 'dbSimSoli.PROPIETARIO', '=', 'dbSP.ID_PROPIETARIO')
                    ->where('dbSimSoli.ID_SOLICITUD', $solicitud1->ID_SOLICITUD)
                    ->first();
            }
            if ($solicitud != null) {
                return response()->json(['status' => 200, 'message' => 'Resultado con exito', 'data' => $solicitud]);
            } else {
                return response()->json(['status' => 400, 'message' => 'Error: no se ha encontrado la solicitud con el numero de PIM en nuestra base de datos!', 'data' => []]);
            }
        } else {
            return response()->json(['status' => 404, 'message' => 'Error: Debe digitar el PIM', 'data' => []]);
        }

    }

    public function getCodModByIM(Request $request)
    {
        //dd($request->all());
        if ($request->idProducto != null) {
            if ($request->idTramite == 14) {
                $codmods = DB::table('sim.sim_producto_codigos_modelos')
                    ->where('producto_id', $request->idProducto)
                    ->where('codigos', '<>', '');
                return Datatables::of($codmods)->make(true);
            } elseif ($request->idTramite == 15) {
                $codmods = DB::table('sim.sim_producto_codigos_modelos')
                    ->where('producto_id', $request->idProducto)
                    ->where('modelos', '<>', '');
                return Datatables::of($codmods)->make(true);
            }

        } else {
            return response()->json(['status' => 404, 'message' => 'Error: Debe Seleccionar un producto', 'data' => []]);
        }

    }

    public function getFabricantesByProducto(Request $request)
    {
        //dd($request->all());
        if ($request->idProducto != null) {
            //
            if ($request->idtipo == 0) {
                $fabricantes = VWSimProducFabricantes::where('ID_PRODUCTO', $request->idProducto)->get();
            } else if ($request->idtipo == 1) {
                $fabricantes = VWSimProducFabricantes::where('ID_PRODUCTO', $request->idProducto)->whereNotIn('TIPO', [5, 3])->get();
            } else {
                if ($request->idtipo == 4) {
                    $fabricantes = VWSimProducFabricantes::where('ID_PRODUCTO', $request->idProducto)->whereNotIn('TIPO', [5])->get();
                } else {
                    $fabricantes = VWSimProducFabricantes::where('ID_PRODUCTO', $request->idProducto)->whereIn('TIPO', [$request->idtipo])->get();
                }
            }
            //dd($fabricantes);
            if ($fabricantes != null) {
                //dd($fabricantes);
                return response()->json(['status' => 200, 'message' => 'Resultado con exito', 'data' => $fabricantes]);
            } else {
                return response()->json(['status' => 400, 'message' => 'Error en la Consulta de fabricantes', 'data' => []]);
            }
        } else {
            return response()->json(['status' => 404, 'message' => 'Error: Debe Seleccionar un producto', 'data' => []]);
        }

    }

    public function verSolicitudes()
    {

        $data = ['title' => 'Solicitudes Post-Registro'
            , 'subtitle' => ''
            , 'breadcrumb' => [
                ['nom' => 'Insumos Médicos', 'url' => '#'],
                ['nom' => 'Solicitudes Post-Registro', 'url' => '#']
            ]];

        return view('sim.versolicitudes', $data);

    }

    public function getDataRowsSolCertificadas()
    {
        $nit = Session::get('user');
        $solicitudes = SimSolicitudes::getSolicitudesCertificadas($nit);

        return Datatables::of($solicitudes)
            ->addColumn('resolucion', function ($dt) {
                $solicitud = SimSolicitudes::find($dt->ID_SOLICITUD);

                if ($solicitud->CLASIFICACION_POST_ID == 0) {
                    if ($dt->estado == 3) {
                        return '<a href="' . route('imprimir.sim', ['idSolicitud' => Crypt::encrypt($dt->ID_SOLICITUD), 'idTramite' => Crypt::encrypt($dt->tramite_id)]) . '" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
                    }
                } elseif ($solicitud->CLASIFICACION_POST_ID == 1) {
                    if ($dt->estado == 2) {
                        return '<a href="' . route('imprimir.sim', ['idSolicitud' => Crypt::encrypt($dt->ID_SOLICITUD), 'idTramite' => Crypt::encrypt($dt->tramite_id)]) . '" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
                    }
                } elseif ($solicitud->CLASIFICACION_POST_ID == 2) {
                    if ($dt->estado == 0) {
                        return '<a href="' . route('comprobante.sim', ['idSolicitud' => Crypt::encrypt($dt->ID_SOLICITUD), 'idTramite' => Crypt::encrypt($dt->tramite_id)]) . '" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
                    } elseif ($dt->estado == 3) {
                        return '<a href="' . route('imprimir.sim', ['idSolicitud' => Crypt::encrypt($dt->ID_SOLICITUD), 'idTramite' => Crypt::encrypt($dt->tramite_id)]) . '" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
                    }
                }
            })
            ->make(true);
    }

    public static function guardarDocumentos($idSolicitudNew, $nregistro, $img_val)
    {
        /* FUNCION PARA GUARDAR LOS ARCHIVOS */
        DB::beginTransaction();
        try {
            $carpeta = Session::get('carpeta');
            $path = 'C:\xampp\htdocs\PortalEnLinea\public\simpost' . '\\' . $carpeta;
            $files = File::allFiles($path);
            //dd($files);
            //$npath='C:\Sim';
            $npath = 'S:\Sim';
            //$npath = Config::get('app.mapeo_files_sim_dev');
            //si hay archivos crear la ruta con el id del usuario
            $newpath = $npath . '\\' . trim($nregistro) . $idSolicitudNew;

            $filesystem = new Filesystem();
            if ($filesystem->exists($npath)) {
                if ($filesystem->isWritable($npath)) {
                    File::makeDirectory($newpath, 0777, true, true);
                    $copy = new Filesystem();
                    $copiado = $copy->copyDirectory($path, $newpath);
                    $archivos = File::allFiles($newpath);
                    $img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img_val));

                    $filepath = $newpath . "/confirmacion-solicitud" . $idSolicitudNew . ".png";

                    // Save the image in a defined path
                    file_put_contents($filepath, $img);

                    $requisitos = DB::table('sim.sim_solicitudes_post_requisitos_lista_chequeo')
                        ->where('ID_SOLICITUD', $idSolicitudNew)
                        // ->where('USUARIO_CREACION','like',"%portalenlinea%")
                        ->get();

                    if ($archivos != null) {
                        //$copy->deleteDirectory($path);
                        //Session::forget('carpeta');
                        foreach ($archivos as $arch) {
                            foreach ($requisitos as $requi) {
                                $nombreArch = str_replace('.' . $arch->getExtension(), '', $arch->getFilename());
                                $separacion = explode('-', $nombreArch);
                                //dd($separacion[0]);
                                if ($separacion[0] === (string)$requi->requisitoId && $separacion[1] === (string)$requi->tramiteDocumentoId) {
                                    //dd('entro');
                                    //dd(substr_replace($arch->getPathname(),"C",0,1));
                                    $doc = new SolPostArchivos();
                                    $doc->lista_chequeo_id = $requi->id_lista_chequeo;
                                    $doc->URL_ARCHIVO = substr_replace($arch->getPathname(), "S", 0, 1);
                                    $doc->tipo_archivo = $arch->getExtension();
                                    $doc->usuario_creacion = 'portalenlinea';
                                    $doc->save();
                                }
                            }
                        }
                        $copy->deleteDirectory($path);
                        DB::commit();
                        return 1;
                    } else {
                        DB::rollback();
                        throw new Exception("Error: Documentos adjuntos no han podido ser guardado junto ha la solicitud, vuelva a intentar a realizar el tramite", 1);
                        return 0;
                    }
                } else {
                    DB::rollback();
                    throw new Exception("Error: No se ha podido guardar los documentos adjuntos, vuelva a intentar a realizar el tramite", 1);
                    return 0;
                    //throw new Exception("Error Processing Request", 1);
                }
            } else {
                DB::rollback();
                throw new Exception("Error: Documentos adjuntos no han podido ser guardados, vuelva a intentar a realizar el tramite", 1);
                return 0;
            }

        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
            return $e;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
            return $e;
            Session::flash('msnError', $e->getMessage());
        }


    }/* /FIN DE LA FUNCION DE GUARDAR LOS ARCHIVOS*/

    public function cargarExcel(Request $request)
    {
        //dd($request->all());
        $filePost = $request->file('excelcod');
        if (null != $filePost) {
            $dataFile = Excel::selectSheetsByIndex(0)->load($filePost)->get();
            //if($dataFile[911]['codigos']==NULL)
            //return 'hola';
            $fields = ['codigos', 'descripcion'];
            $headers = $dataFile->first()->toArray();

            foreach ($fields as $field) {
                if (!array_key_exists($field, $headers)) {
                    Session::flash('msnError', 'Las columnas del archivo no coinciden con el formato correcto.');
                    //return Redirect::route('productos.modificarView', array('id_producto' => Input::get('idCorrelativoFile')));
                    return back()->withInput();
                }
            }


            $codmods = [];
            for ($i = 0; $i < count($dataFile); $i++) {
                if ($dataFile[$i]['codigos'] != NULL) {
                    $codmods[$i]['codigo'] = $dataFile[$i]->codigos;

                    if ($dataFile[$i]->descripcion != null) {
                        $codmods[$i]['descripcion'] = $dataFile[$i]->descripcion;
                    } else {
                        $codmods[$i]['descripcion'] = '';
                    }
                }
            }

            if (count($codmods) > 0) {
                return response()->json(['status' => 200, 'message' => 'Resultado con exito', 'data' => $codmods]);
            } else {
                return response()->json(['status' => 400, 'message' => 'Error no se pudo leer archivo de excel', 'data' => []]);
            }
        } else {
            return response()->json(['status' => 404, 'message' => 'Error: Debe subir un archivo de excel', 'data' => []]);
            //dd($codmods);
        }
    }

    public function getParrafoI(Request $request)
    {
        //dd($request->all());
        if ($request->idTramite == 15) {
            if (count($request->codmod) == 1) {
                $modelo = ProdCodModelo::find($request->codmod[0]);
                return 'desistir del registro de el modelo <b>' . $modelo->modelos . '</b>,';
            } else {
                $modelos = DB::table('sim.sim_producto_codigos_modelos')
                    ->whereIn('id_producto_codmod', $request->codmod)
                    ->select(DB::raw('group_concat(modelos) as modelos'))
                    ->first();
                return 'desistir del registro de los modelos <b>' . $modelos->modelos . '</b>,';
            }

        } else if ($request->idTramite == 18) {
            //dd(count($request->idFab));
            if (count($request->idFab) == 1) {
                $fab = VWSimProducFabricantes::where('ID_FABRICANTE', $request->idFab[0])->first();
                //dd($fab);
                if ($fab->TIPO == 5) {
                    return 'descontinuar del registro el acondicionador <b>' . $fab->NOMBRE_FABRICANTE . '</b>, del'
                        . ' domicilio de <b>' . $fab->NOMBRE_PAIS . '</b>';
                } else {
                    //dd($fab);
                    return 'descontinuar del registro el fabricante <b>' . $fab->NOMBRE_FABRICANTE . '</b>, del'
                        . ' domicilio de <b>' . $fab->NOMBRE_PAIS . '</b>';
                }
            } /*else {
                if ($fab->TIPO == 5) {
                    return 'descontinuar del registro el acondicionador <b>' . $fab->NOMBRE_FABRICANTE . '</b>, del'
                        . ' domicilio de <b>' . $fab->NOMBRE_PAIS . '</b>';
                } else {
                    //dd($fab);
                    return 'descontinuar del registro el fabricante <b>' . $fab->NOMBRE_FABRICANTE . '</b>, del'
                        . ' domicilio de <b>' . $fab->NOMBRE_PAIS . '</b>';
                }
                return 'descontinuar del registro los _______,';
            }*/
        } else if ($request->idTramite == 17) {
            //dd(count($request->idFab));
            if (count($request->idFab) == 1) {
                $fab = VWSimProducFabricantes::where('ID_FABRICANTE', $request->idFab[0])->first();
                return 'descontinuar del registro el acondicionador <b>' . $fab->NOMBRE_FABRICANTE . '</b>, del'
                    . ' domicilio de <b>' . $fab->NOMBRE_PAIS . '</b>';
            }
        }
    }

    public function verSolicitudesPre()
    {

        $data = ['title' => 'Solicitudes Nuevo-Registro'
            , 'subtitle' => ''
            , 'breadcrumb' => [
                ['nom' => 'Insumos Médicos', 'url' => '#'],
                ['nom' => 'Solicitudes Nuevo-Registro', 'url' => '#']
            ]];
        $nit = Session::get('user');
        /*$perfiles = DB::select('select * from dnm_usuarios_portal.vwperfilportal where NIT = "' . $nit . '" and UNIDAD = CONVERT( "SIM" USING UTF8) COLLATE utf8_general_ci');

        if (!empty($perfiles)) {
            return view('sim.tramitespredt', $data);
        } else {
            return redirect()->route('doInicio');
        }*/

        return view('sim.tramitespredt', $data);
    }

    public function getSolicitudesPreDt(Request $request)
    {
        $nit = Session::get('user');
        $solicitudes = vwSolicitudesSimPre::getSolicitudesPre($nit);

        return Datatables::of($solicitudes)
            ->addColumn('estado_sol', function ($dt) {
                if ($dt->ID_ESTADO == 0) {
                    if ($dt->ID_ESTADO_DIC == 0 && ($dt->estado_dic_sistema == 0 || $dt->estado_dic_sistema == 1)) {
                        return 'EN PROCESO';
                    } else if ($dt->ID_ESTADO_DIC == 2) {
                        return 'EN PROCESO';
                    } else if ($dt->ID_ESTADO_DIC == 1) {
                        return 'EN PROCESO';
                    } else {
                        return 'INGRESADA';
                    }

                } else if ($dt->ID_ESTADO == 1) {
                    return 'EN APROBACIÓN DE JUNTA';
                } else if ($dt->ID_ESTADO == 2) {
                    return 'CERTIFICADA';
                } else if ($dt->ID_ESTADO == 3) {
                    return 'DESISTIDA';
                }
            })
            ->addColumn('resolucion', function ($dt) {
                $solicitud = SimSolicitudes::find($dt->ID_SOLICITUD);

                if ($solicitud->ID_TRAMITE == 'SIM01') {
                    if ($dt->ID_ESTADO == 3) {
                        $desistimiento= DesistimientoSol::where('pim',$solicitud->PIM)->first();
                        if (!empty($desistimiento)) {
                            return '<a href="' . route('imprimir.sim', ['idSolicitud' => Crypt::encrypt($dt->ID_SOLICITUD), 'idTramite' => Crypt::encrypt(28)]) . '" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
                        }
                    }
                }
            })
            ->filter(function ($query) use ($request) {

                if ($request->has('nsolicitud')) {
                    $query->where('ID_SOLICITUD', '=', $request->get('nsolicitud'));
                }

                if ($request->has('nregistro')) {
                    $query->where('PIM', '=', $request->get('nregistro'));
                } elseif ($request->has('nomComercial')) {
                    $query->where('NOMBRE_INSUMO', 'like', '%' . $request->get('nomComercial') . '%');
                }

                if ($request->has('estado')) {
                    if ($request->get('estado') != "-1") {
                        $query->where('ID_ESTADO', $request->get('estado'));
                    }
                }

            })
            ->make(true);
    }

    public function verSolicitudesPost()
    {

        $data = ['title' => 'Solicitudes Post-Registro'
            , 'subtitle' => ''
            , 'breadcrumb' => [
                ['nom' => 'Insumos Médicos', 'url' => '#'],
                ['nom' => 'Solicitudes Post-Registro', 'url' => '#']
            ]];
        $tramites = TramitesPost::whereIn('idClasificacion', [1, 2])->orderBy('nombre', 'asc')->get();
        $data['tramites'] = $tramites;

        return view('sim.tramitespostdt', $data);

    }

    public function getSolicitudesPostDt(Request $request)
    {

        $nit = Session::get('user');
        $solicitudes = vwSolicitudesSimPost::getSolicitudesPost($nit);

        return Datatables::of($solicitudes)
            ->addColumn('estado_sol', function ($dt) {
                if ($dt->ID_ESTADO == 0) {
                    return 'INGRESADA';
                } else if ($dt->ID_ESTADO == 1) {
                    return 'EN PROCESO';
                } else if ($dt->ID_ESTADO == 2) {
                    return 'CERTIFICADA';
                } else if ($dt->ID_ESTADO == 3) {
                    return 'EN PROCESO';
                } else if ($dt->ID_ESTADO == 4) {
                    return 'DENEGADA';
                } else if ($dt->ID_ESTADO == 5) {
                    return 'CANCELADA';
                }
            })
            ->addColumn('resolucion', function ($dt) {
                if ($dt->NIT_SOLICITANTE != null) {
                    $solicitud = SimSolicitudes::find($dt->ID_SOLICITUD);

                    if ($solicitud->CLASIFICACION_POST_ID == 0) {
                        if ($dt->ID_ESTADO == 3) {
                            return '';
                            //return '<a href="'.route('imprimir.sim',['idSolicitud' => Crypt::encrypt($dt->ID_SOLICITUD) , 'idTramite' => Crypt::encrypt($dt->ID_TRAMITE)]).'" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
                        }
                    } elseif ($solicitud->CLASIFICACION_POST_ID == 1) {
                        if ($dt->ID_ESTADO == 2) {
                            return '<a href="' . route('imprimir.sim', ['idSolicitud' => Crypt::encrypt($dt->ID_SOLICITUD), 'idTramite' => Crypt::encrypt($dt->ID_TRAMITE)]) . '" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
                        }
                    } elseif ($solicitud->CLASIFICACION_POST_ID == 2) {
                        if ($dt->ID_ESTADO == 0) {
                            return '<a href="' . route('comprobante.sim', ['idSolicitud' => Crypt::encrypt($dt->ID_SOLICITUD), 'idTramite' => Crypt::encrypt($dt->ID_TRAMITE)]) . '" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
                        } elseif ($dt->ID_ESTADO == 3) {
                            return '';
                            //return '<a href="'.route('imprimir.sim',['idSolicitud' => Crypt::encrypt($dt->ID_SOLICITUD) , 'idTramite' => Crypt::encrypt($dt->ID_TRAMITE)]).'" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
                        }
                    }
                }
            })
            ->filter(function ($query) use ($request) {

                if ($request->has('nsolicitud')) {
                    $query->where('ID_SOLICITUD', '=', $request->get('nsolicitud'));
                }

                if ($request->has('nregistro')) {
                    $query->where('IM', '=', $request->get('nregistro'));
                } elseif ($request->has('nomComercial')) {
                    $query->where('NOMBRE_INSUMO', 'like', '%' . $request->get('nomComercial') . '%');
                }

                if ($request->has('tramite')) {
                    if ($request->get('tramite') != "0") {
                        $query->where('ID_TRAMITE', '=', $request->get('tramite'));
                    }
                }
                if ($request->has('estado')) {
                    if ($request->get('estado') != "-1") {

                        if ($request->get('estado') == "0") {
                            $query->whereIn('ID_ESTADO', [0]);
                        } else if ($request->get('estado') == "1") {
                            $query->whereIn('ID_ESTADO', [1, 3]);
                        } else if ($request->get('estado') == "2") {
                            $query->whereIn('ID_ESTADO', [2]);
                        } else if ($request->get('estado') == "4") {
                            $query->where('ID_ESTADO', '4');
                        }

                    }
                }

            })
            ->make(true);
    }

}
