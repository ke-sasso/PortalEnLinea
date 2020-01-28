<?php

namespace App\Http\Controllers\Cosmeticos\PostRegistro;
use App\Http\Requests;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Filesystem\Filesystem;
use App\Http\Controllers\Controller;

use Session;
use DB;
use Validator;
use Datatables;
use Log;
use Config;
use DateTime;
use File;
use App\Models\Cosmeticos\VwPostSolicitudes;
use App\Models\Cosmeticos\Post\Tramite;
use App\Models\Cosmeticos\Post\TramiteRequisito;
use App\Models\Cosmeticos\Post\Solicitud;
use App\Models\Cosmeticos\Post\Requisitos;
use App\Models\Cosmeticos\Post\SolicitudDocumento;
use App\Models\Cosmeticos\Post\Documento;
use App\Models\Cosmeticos\Post\SolicitudFragancia;
use App\Models\Cosmeticos\Post\SolicitudTono;
use App\Models\Cosmeticos\Cos\ProfesionalCos;
use App\Models\Cosmeticos\Cos\Cosmeticos;
use App\Models\Cosmeticos\Sol\EstadosSolicitud;


class CosmeticosPostController extends Controller
{
    //
    private $url=null;
    private $token=null;

    public function __construct() {
        $this->url = Config::get('app.api');
        $this->token = Config::get('app.token');
        $this->pathfiles= Config::get('app.mapeo_files_cos');
    }

    //listado de solicitudes
    public function nuevaSolicitud(){

        $data = ['title'           => 'Nueva Solicitud Post Registro'
            ,'subtitle'         => ''
            ,'breadcrumb'       => [
                ['nom'  =>  'Cosméticos', 'url' => '#'],
                ['nom'  =>  'Nueva Solicitud Post Registro', 'url' => '#']
            ]];
        $data['tramites'] = Tramite::allActive();
        $nit = Session::get('user');
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
        /*  $idProfesional=Session::get('prof');
        $productos = ProfesionalCos::productosByProfesional($idProfesional)->get();
        dd($productos);*/
        $data['documentos'] = TramiteRequisito::getDocumentosByTramite();
        return view('cosmeticos.postregistro.nuevaSolicitud',$data);
    }
     public function index(){

        $data = ['title'           => 'Solicitudes Post Registro'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Cosméticos e Higiénicos', 'url' => '#'],
                    ['nom'  =>  'Solicitudes Post Registro', 'url' => '#']
                ]];
        $data['estados'] = EstadosSolicitud::select('idEstado','estado')->where('activo','A')->get();
        $data['tramites'] = Tramite::select('idTramite','nombreTramite')->where('portalEnlinea',1)->where('activo','A')->get();
        return view('cosmeticos.postregistro.index',$data);
    }



    public function getDataRows(Request $request)
    {
      $nit=Session::get('user');
      $sol = VwPostSolicitudes::getSolicitudes($nit);
      return Datatables::of($sol)
         ->addColumn('nombreTramite',function($dt){
          return '<span class="label label-primary">'.$dt->nombreTramite.'</span>';
        })
        ->addColumn('estado',function($dt){
          return '<span class="label label-primary">'.$dt->estado.'</span>';
        })
        ->addColumn('tipoProducto',function($dt){
            if($dt->tipoProducto=='COS'){
                 return '<span class="label label-primary">COSMÉTICO</span>';
           }else{
                return '<span class="label label-primary">HIGIÉNICO</span>';
           }
        })
         ->filter(function($query) use ($request){
               if($request->has('nsolicitud')){
                    $query->where('numeroSolicitud','=',$request->get('nsolicitud'));
                }
                if($request->has('nomComercial')){

                    $query->where('nombreComercial','like','%'.$request->get('nomComercial').'%');
                }
                if($request->has('estado')){
                        $query->where('idEstado','=',$request->get('estado'));
                }
                if($request->has('fecha')){
                    $query->where('fechaCreacion','like',"%".$request->fecha."%");
                }
                if($request->has('idtipo')){
                    $query->where('idTramite','=',$request->get('idtipo'));
                }
               
            })

        ->make(true);
    }


    public function getDataProducByProfesional(Request $request)
    {
        $idProfesional=Session::get('prof');
        $productos = ProfesionalCos::productosByProfesional($idProfesional);
        return Datatables::of($productos)
            ->addColumn('alerta', function ($dt) {

                            $d1= $dt->vigenciaHasta;
                            $d2= date("Y")."-12-31"; 
                            $dias   = (strtotime($d1)-strtotime($d2))/86400;
                            $dias   = abs($dias); $dias = floor($dias); 
                    if($dias==0) {
                        if (strtotime($dt->renovacion) < strtotime(date('Y-m-d'))) {
                             return '<span class="label label-danger">Anualidad o renovación no vigente</span>';
                        }else{
                            return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="selectProducto(\''.$dt->idCosmetico.'\',\''.$dt->nombreComercial.'\',\''.$dt->vigenciaHasta.'\',\''.$dt->renovacion.'\');" ><i class="fa fa-check-square-o"></i></a>';
                        }
                    }
                    else{
                         return '<span class="label label-danger">Anualidad o renovación no vigente</span>';
                    }



            })
           ->make(true);
    }

    public function validarMandamiento(Request $rq){
        $rules=[
            'numMandamiento' => 'required',
            'tipo' => 'required',
            'idTramite' => 'required'
        ];
        $v = Validator::make($rq->all(),$rules);
        if ($v->fails()){
                $msg = "<ul class='text-warning'>";
                foreach ($v->messages()->all() as $err) {
                    $msg .= "<li>$err</li>";
                }
                $msg .= "</ul>";
               return response()->json(['status' => 400, 'message' =>  $msg],200);
        }

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'cospost/solicitudpost/validar-mandamiento', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' => [
                    'numMandamiento' => $rq->numMandamiento,
                    'tipo'           => $rq->tipo,
                    'idTramite'      => $rq->idTramite
                ]
            ]);

            $r = json_decode($res->getBody());
            if ($r->status == 200) {
                return response()->json(['status' => 200, 'message' => $r->message], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }


    public function storeCambioEmpaque(Request $rq){
        $rules=[
            'tipo' =>               'required',
            'perfil' =>             'required',
            'idproducto' =>         'required',
            'mandamiento' =>        'required',
            'idTramite' =>          'required',
            'nombreComercial' =>    'required',
            'files' =>               'required|array|min:2',
        ];
        $v = Validator::make($rq->all(),$rules);
        $v->setAttributeNames([
            'tipo' =>            'Tipo de producto',
            'perfil' =>          'Tipo de título',
            'idproducto' =>      'Producto',
            'mandamiento' =>     'Mandamiento',
            'idTramite' =>       'ID Tramite',
            'nombreComercial' => 'Nombre comercial',
            'files' =>           'Documento',
        ]);
        if ($v->fails()){
                $msg = "<ul class='text-warning'>";
                foreach ($v->messages()->all() as $err) {
                    $msg .= "<li>$err</li>";
                }
                $msg .= "</ul>";
               return $msg;
        }
        if($rq->idTramite==3 || $rq->idTramite==4){
                if(!$rq->has('campo1')){
                    return '¡Verificar que a llenado todos los campos!';
                }
        }
        try{
             DB::connection('sqlsrv')->beginTransaction();
            $nit = Session::get('user');
            $sol = new Solicitud();
            $sol->noRegistro = $rq->idproducto;
            $sol->tipoProducto = $rq->tipo;
            $sol->nombreComercial = $rq->nombreComercial;
            $sol->nitSolicitante =$nit;
            $sol->idMandamiento = $rq->mandamiento;
            $sol->idEstado=1;
            $sol->solicitudPortal=1;
            $sol->idTramite=$rq->idTramite;
            $sol->usuarioCreacion=$nit.'@'.$rq->ip();
            $sol->save();

            if($rq->idTramite==3){
                $solfra = new SolicitudFragancia();
                $solfra->idSolicitud = $sol->idSolicitud;
                $solfra->fragancia = $rq->campo1;
                $solfra->usuarioCreacion=$nit.'@'.$rq->ip();
                $solfra->save();
            }else if($rq->idTramite==4){
                $soltono = new SolicitudTono();
                $soltono->idSolicitud = $sol->idSolicitud;
                $soltono->tono = $rq->campo1;
                $soltono->usuarioCreacion=$nit.'@'.$rq->ip();
                $soltono->save();
            }
            $usuarioCreacion = $nit.'@'.$rq->ip();
            $saveDocs=$this->guardarDocumentos($sol->idSolicitud,$rq->file('files'),$usuarioCreacion);
            if($saveDocs==0){
                DB::connection('sqlsrv')->rollback();
                return 'Error en el sistema: No se han podido guardar los documentos adjuntos, por favor vuelva intentarlo!';
            }
            DB::connection('sqlsrv')->commit();
        }catch (\Exception $e){
            DB::connection('sqlsrv')->rollback();
            return $e->getMessage();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
        return response()->json(['state' => 'success', 'msg' => '¡La solicitud de tramite fue ingresada con exito!']);
    }

      public function guardarDocumentos($idSolicitudNew,$files,$idUsuarioCrea){
        /* FUNCION PARA GUARDAR LOS ARCHIVOS */
        DB::connection('sqlsrv')->beginTransaction();

        try {

            $npath=$this->pathfiles.'Post\\';

            $filesystem= new Filesystem();
            if($filesystem->exists($npath)) {
                if ($filesystem->isWritable($npath)) {
                    $newpath = $npath . $idSolicitudNew;
                    File::makeDirectory($newpath, 0777, true, true);
                    if (!empty($files)) {
                        $indexs=array_keys($files);
                        for ($i = 0; $i < count($indexs); $i++) {
                            $index = $indexs[$i];
                            $req = Requisitos::findOrFail($index);
                            $name = $this->replaceAccents($req->nombreRequisito). '.' . $files[$index]->getClientOriginalExtension();
                            $type = $files[$index]->getClientMimeType();
                            $files[$index]->move($newpath, $name);

                            $doc = new Documento();
                            $doc->urlDoc = $newpath.'\\'.$name;
                            $doc->tipoDoc = $type;
                            $doc->usuarioCreacion = $idUsuarioCrea;
                            $doc->save();

                            $solDoc = new SolicitudDocumento();
                            $solDoc->idSolicitud=$idSolicitudNew;
                            $solDoc->idDocumento= $doc->idDocumento;
                            $solDoc->idRequisito= $index;
                            $solDoc->usuarioCreacion=$idUsuarioCrea;
                            $solDoc->save();
                        }
                        DB::connection('sqlsrv')->commit();
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    DB::connection('sqlsrv')->rollback();
                    return 0;
                }
            }
        }
        catch (Throwable $e) {
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getLine().$e->getFile()]);
            return 0;
        }
        catch(Exception $e){
            DB::connection('sqlsrv')->rollback();
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getLine().$e->getFile()]);
            return 0;
        }
    }/* /FIN DE LA FUNCION DE GUARDAR LOS ARCHIVOS*/

  

}
