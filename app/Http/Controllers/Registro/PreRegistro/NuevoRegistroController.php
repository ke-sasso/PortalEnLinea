<?php

namespace App\Http\Controllers\Registro\PreRegistro;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests;
use App\Models\Cssp\SolicitudesVue;
use App\Models\Cssp\Propietarios;
use App\Models\Registro\Cat\CodigoAtc;
use App\Models\Registro\Sol\EstadosSolicitud;
use App\Http\Controllers\Controller;
use App\Models\Registro\Sol\Paso2ProductoGenerales;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Log;
use Crypt;
use Config;
use Validator;

class NuevoRegistroController extends Controller
{
    //
    private $url=null;
    private $token=null;

    public function __construct() {
        $this->url = Config::get('app.api');
        $this->token = Config::get('app.token');
    }

    //listado de solicitudes
    public function index(){

	    $data = ['title'           => 'Solicitudes Nuevo Registro'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Solicitudes nuevo registro', 'url' => '#']
                ]];
        $data['estados'] = EstadosSolicitud::select('idEstado','estadoPortal')->where('activo','A')->get();
	    return view('registro.nuevoregistro.index',$data);
    }

    public function nuevaSolicitud(){

        $data = ['title'           => 'Nueva Solicitud'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Registro y Visado', 'url' => '#'],
                    ['nom'  =>  'Solicitudes nuevo registro', 'url' => route('get.preregistrorv.index')],
                    ['nom'  =>  'Nueva Solicitud', 'url' => '#']
                ]];

        return view('registro.nuevoregistro.nuevasolicitud',$data);
    }

     public function validarMandamiento(Request $rq){

        $rules=[
            'numMandamiento' => 'required',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'urvpre/solicitudpre/validar-mandamiento', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' => [
                    'numMandamiento' => $rq->numMandamiento
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'message' => $r->message,'pago'=> $r->pago], 200);
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


    public function getTiposMedicamentos(Request $rq){

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'preregistrourv/get/tiposmedicamentos', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $array=$r->data;
                        //dd($array);
                if($rq->has('val')){
                        $ino=$rq->val; //CAMPO INNOVADOR 1.(SI) 0.(NO)
                        if($ino==1){
                              //RECORREMOS TODA LA LISTA PARA QUITAR LOS SIGUIENTES TIPOS DE MEDICAMENTOS PORQUE EL USUARIO SELECCIONAR QUE SI ES INNOVADOR
                              for($a=0;$a<count($array);$a++){
                                    $var=trim($array[$a]->tipoMed);
                                   if($var==6){
                                         //SUPLEMENTO NUTRICIONALES
                                          $a1=$a;
                                   }else if($var==5){
                                         //HUERFANOS
                                         $a2=$a;
                                   }else if($var==7){
                                         //NATURALES
                                         $a3=$a;
                                   }else if($var==8){
                                        //HOMEOPATICO
                                         $a4=$a;
                                   }else if($var==9){
                                        //GENERICO
                                         $a5=$a;
                                   }else if($var==10){
                                        //MULTIORIGEN
                                          $a6=$a;
                                   }else if($var==11){
                                        //RADIOFARMACOS
                                         $a7=$a;
                                   }else if($var==12){
                                         //GASES MEDICINALES
                                         $a8=$a;
                                   }
                              }
                           unset($array[$a1],$array[$a2],$array[$a3],$array[$a4],$array[$a5],$array[$a6],$array[$a7],$array[$a8]);
                        }else if($ino==0){
                            //RECORREMOS TODA LA LISTA PARA QUITAR LOS SIGUIENTES TIPOS DE MEDICAMENTOS PORQUE EL USUARIO SELECCIONAR QUE NO ES INNOVADOR
                             for($a=0;$a<count($array);$a++){
                                    $var=trim($array[$a]->tipoMed);
                                  if($var==1){
                                         //SINTESIS QUIMICA
                                         $a9=$a;
                                   }else if($var==5){
                                         //HUERFANOS
                                         $a10=$a;
                                   }
                              }
                             unset($array[$a9],$array[$a10]);
                        }
                }

                return response()->json(['status' => 200, 'data' => $array], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getFormasFarmaceuticas(Request $rq){
        $rules=[
            'q' => 'sometimes',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {

            $nomForma = null;

            if($rq->has('q')) {
                $nomForma = trim($rq->q);
            }


            $client = new Client();
            $res = $client->request('POST', $this->url . 'preregistrourv/get/formasfarmaceuticas', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'form_params' =>[
                    'nomForma' => $nomForma,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {

                return response()->json(['status' => 200, 'data' => $r->data], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getviasAdmin(Request $rq){
        $rules=[
            'q' => 'sometimes',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {

            $searchVia = null;

            if($rq->has('q')) {
                $searchVia = trim($rq->q);
            }


            $client = new Client();
            $res = $client->request('POST', $this->url . 'preregistrourv/get/viasadministracion', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'form_params' =>[
                    'searchVia' => $searchVia,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {

                return response()->json(['status' => 200, 'data' => $r->data], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getMateriasPrimas(Request $rq){
        $rules=[
            'q' => 'sometimes',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {

            $nomMateria='';
            if($rq->has('q')) {
                $nomMateria = trim($rq->q);
            }


            $client = new Client();
            $res = $client->request('POST', $this->url . 'preregistrourv/get/materiasprimas', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'form_params' =>[
                    'nomMateria' => $nomMateria,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {

                return response()->json(['status' => 200, 'data' => $r->data], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getUnidadesMedida(){

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'preregistrourv/get/unidadesmedida', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'data' => $r->data], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getEmpaques(Request $rq){
        $rules=[
            'q' => 'sometimes',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {
            $nomEmpaque='';
            if($rq->has('q')) {
                $nomEmpaque = trim($rq->q);
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'preregistrourv/get/empaques', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'form_params' =>[
                    'nomEmpaque' => $nomEmpaque,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {

                return response()->json(['status' => 200, 'data' => $r->data], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getContenidos(Request $rq){
        $rules=[
            'q' => 'sometimes',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {
            $nomContenido='';
            if($rq->has('q')) {
                $nomContenido = trim($rq->q);
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'preregistrourv/get/contenidos', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'form_params' =>[
                    'nomContenido' => $nomContenido,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {

                return response()->json(['status' => 200, 'data' => $r->data], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getColoresPresent(Request $rq){
        $rules=[
            'q' => 'sometimes',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }
        try {
            $color='';
            if($rq->has('q')) {
                $color = trim($rq->q);
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'preregistrourv/get/colores-presentacion', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'form_params' =>[
                    'color' => $color,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'data' => $r->data], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getMaterialesPresent(Request $rq){
        $rules=[
            'q' => 'sometimes',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }
        try {
            $material='';
            if($rq->has('q')) {
                $material = trim($rq->q);
            }
            $client = new Client();
            $res = $client->request('POST', $this->url . 'preregistrourv/get/materiales-presentacion', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'form_params' =>[
                    'material' => $material,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'data' => $r->data], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getExpDocumentos(Request $rq){

        try {

            $client = new Client();
            $res = $client->request('GET', $this->url . 'preregistrourv/get/expediente-documentos', [
                'headers' => [
                    'tk' => $this->token,

                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {

                $data['expDoc'] = $r->data;
               // dd($data['expDoc']);
                return view('registro.nuevoregistro.pasos.paso10.expedientetabla',$data);
            }
            else if ($r->status == 400){
                return new JsonResponse([
                    'status' => 400,
                    'message' => $r->message
                ],200);

            }
            else if ($r->status == 404){
                return new JsonResponse([
                    'status' => 404,
                    'message' => $r->message
                ],200);
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


     public function getParentAtc(){
        try {
            $atcs = CodigoAtc::getOptions();

            $items = "";
            foreach ($atcs as $atc) {
                //dd($atc['subatcs']);
                if (!empty($atc['subatcs'])) {
                    $items .= "<optgroup label='" . trim($atc['atc']) . "'>";
                    foreach ($atc['subatcs'] as $sub) {
                        $items .= $sub;
                    }
                    $items .= "</optgroup>";
                } else {
                    $items .= "<option value='" . $atc['id'] . "'>" . $atc['atc'] . "</option>";
                }
            }
            return new JsonResponse(['data' => $items], 200);
        }
        catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática '.$e->getMessage()
            ], 500);
        }

    }

      public function getModalidadVenta(){

        try {
            $client = new Client();
            $res = $client->request('GET', $this->url . 'preregistrourv/get/modalidad-venta', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'data' => $r->data], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

      public function reconocimientoMutuoView(Request $request){
        //1.Vista nuevo 2.Vista editar
        if($request->idvista==1){
             $data=['paises'=>CodigoAtc::getPaisesCA(),'idvista'=>$request->idvista];
        }else{
             $sol = Paso2ProductoGenerales::where('idSolicitud',Crypt::decrypt($request->idSolicitud))->select('idPaisReconocimiento','numeroRegistroReconocimiento')->first();
             $data=['paises'=>CodigoAtc::getPaisesCA(),'idvista'=>$request->idvista,'solicitud'=>$sol];
        }
        if(view()->exists('registro.nuevoregistro.pasos.paso2.paisregistroReconocimiento')){
            $view= view('registro.nuevoregistro.pasos.paso2.paisregistroReconocimiento',$data);
            return response()->json(['status' => 200, 'data' => (String)$view],200);
        }
        else{
            return response()->json(['status' => 404, 'message' => "No se han encontrado la vista de reconocimiento Mutuo!"],404);
        }
    }

        public function getPoderFabMaquila(Request $rq){
        $rules=[
            'q' => 'sometimes',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {

            $poder = null;

            if($rq->has('q')) {
                $poder = trim($rq->q);
            }


            $client = new Client();
            $res = $client->request('POST', $this->url . 'preregistrourv/get/poder-distribuidor-maquila', [
                'headers' => [
                    'tk' => $this->token,
                ],
                'form_params' =>[
                    'search' => $poder,
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                if($r->data){
                    return response()->json(['status' => 200, 'data' => $r->data], 200);
                }else{
                    return response()->json(['status' => 400, 'message' => 'No se han encontrado resultados en su búsqueda.'],200);
                }

            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage().$e->getFile().$e->getLine()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }



}
