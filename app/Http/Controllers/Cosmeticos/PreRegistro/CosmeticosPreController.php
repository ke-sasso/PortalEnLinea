<?php

namespace App\Http\Controllers\Cosmeticos\PreRegistro;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use Validator;
use Log;
use Carbon\Carbon;
use Config;



class CosmeticosPreController extends Controller
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

        return view('registro.nuevoregistro.index',$data);
    }

    public function nuevaSolicitud(){

        $data = ['title'           => 'Nueva Solicitud Pre Registro'
            ,'subtitle'         => ''
            ,'breadcrumb'       => [
                ['nom'  =>  'Cosméticos', 'url' => '#'],
                ['nom'  =>  'Nueva Solicitud Pre Registro', 'url' => '#']
            ]];

        return view('cosmeticos.nuevoregistro.nuevasolicitud',$data);
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
            $res = $client->request('POST', $this->url . 'cospre/solicitudpre/validar-mandamiento', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' => [
                    'numMandamiento' => $rq->numMandamiento
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

    public function getMarcas(Request $rq){

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/marcas', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $items="";
                foreach ($r->data as $data) {
                        if($rq->has('idMarca')){
                            if(!empty($rq->idMarca))
                                if($data->idMarca==$rq->idMarca)
                                    $items .="<option value='".$data->idMarca."' selected>".$data->nombreMarca."</option>";
                                else
                                    $items .="<option value='".$data->idMarca."'>".$data->nombreMarca."</option>";
                            else
                                $items .="<option value='".$data->idMarca."'>".$data->nombreMarca."</option>";
                        }
                        else
                            $items .="<option value='".$data->idMarca."'>".$data->nombreMarca."</option>";

                }
                return response()->json(['status' => 200, 'data' => $items], 200);
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

    public function getAreasAplicacion(Request $rq){
        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/areasAplicacion', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $items="";
                foreach ($r->data as $data) {
                    if($rq->has('idArea')) {
                        if (!empty($rq->idArea))
                            if ($data->idAreaAplicacion == $rq->idArea)
                                $items .= "<option value='" . $data->idAreaAplicacion . "' selected>" . $data->nombreArea . "</option>";
                            else
                                $items .= "<option value='" . $data->idAreaAplicacion . "'>" . $data->nombreArea . "</option>";
                        else
                            $items .= "<option value='" . $data->idAreaAplicacion . "'>" . $data->nombreArea . "</option>";
                    }
                    else
                        $items .="<option value='".$data->idAreaAplicacion."'>".$data->nombreArea."</option>";
                }
                    return response()->json(['status' => 200, 'data' => $items], 200);
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

    public function getClasificacionHig(){
        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/clasificacionHig', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $items="<option value=\"\" disabled selected hidden>Seleccione...</option>";
                foreach ($r->data as $data) {
                    $items .="<option data-fragancia='".$data->poseeFragancia."' data-tono='".$data->poseeTono."'  value='".$data->idClasificacion."'>".$data->nombreClasificacion."</option>";
                }
                return response()->json(['status' => 200, 'data' => $items], 200);
            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            //throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getClasificacionesByArea(Request $rq){

        //dd($rq->all());

        $rules=[
            'idArea' => 'required',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }


        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/clasificacionCos', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                     'idArea' => trim($rq->idArea),
                 ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $items="<option value=\"\" disabled selected hidden>Seleccione...</option>";
                foreach ($r->data as $data) {
                    if($rq->has('idClasi')) {
                        if (!empty($rq->idClasi))
                            if ($data->idClasificacion == $rq->idClasi)
                                $items .= "<option data-fragancia='" . $data->poseeFragancia . "' data-tono='" . $data->poseeTono . "'  value='" . $data->idClasificacion . "' selected>" . $data->nombreClasificacion . "</option>";
                            else
                                $items .= "<option data-fragancia='" . $data->poseeFragancia . "' data-tono='" . $data->poseeTono . "'  value='" . $data->idClasificacion . "'>" . $data->nombreClasificacion . "</option>";
                        else
                            $items .= "<option data-fragancia='" . $data->poseeFragancia . "' data-tono='" . $data->poseeTono . "'  value='" . $data->idClasificacion . "'>" . $data->nombreClasificacion . "</option>";
                    }
                    else
                        $items .="<option data-fragancia='".$data->poseeFragancia."' data-tono='".$data->poseeTono."'  value='".$data->idClasificacion."'>".$data->nombreClasificacion."</option>";
                }
                return response()->json(['status' => 200, 'data' => $items], 200);
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

    public function getFormasCosByClasificacion(Request $rq){

        $rules=[
            'idClasificacion' => 'required',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/formasCosByClasi', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'idClasificacion' => trim($rq->idClasificacion),
                ],
                'allow_redirects'=> true
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $items="<option value=\"\" disabled selected hidden>Seleccione...</option>";
                foreach ($r->data as $data) {
                    if($rq->has('idForma')) {
                        if (!empty($rq->idForma))
                            if ($data->idForma == $rq->idForma)
                                $items .= "<option value='" . $data->idForma . "' selected>" . $data->nombreForma . "</option>";
                            else
                                $items .= "<option value='" . $data->idForma . "'>" . $data->nombreForma . "</option>";
                        else
                            $items .= "<option value='" . $data->idForma . "'>" . $data->nombreForma . "</option>";
                    }
                    else
                        $items .="<option value='".$data->idForma."'>".$data->nombreForma."</option>";
                }
                return response()->json(['status' => 200, 'data' => $items], 200);
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

    public function getFormulaInci(Request $rq){

        $rules=[
            'q' => 'sometimes',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {

            $searchDenominacion = null;

            if($rq->has('q')) {
                $searchDenominacion = trim($rq->q);
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/formulainci', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'searchDenominacion' => $searchDenominacion,
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                /*$items="";
                foreach ($r->data as $data) {
                    $items .="<option value='".$data->idDenominacion."'>".trim($data->numeroCAS).' '.htmlentities(trim($data->denominacionINCI))."</option>";
                }*/
                return response()->json(['status' => 200, 'data' => $r->data], 200);

            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
            throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }


    public function getRequisitosByTramite(Request $rq){

        $rules=[
            'tipoTramite' => 'required'
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {

            $client = new Client();
            $res = $client->request('POST', $this->url . 'cospre/solicitudpre/items-by-tramite', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' =>[
                    'idTramite'    => $rq->tipoTramite
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $data['documentos'] = $r->data;
                return view('cosmeticos.nuevoregistro.pasos.paso6.bodydocumentos',$data);
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
            throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }

    }

    public function getReconocimientoView(){
        return view('cosmeticos.nuevoregistro.pasos.paso2.reconocimiento');
    }

    public function getGnralCosOrHigView(Request $rq){

        $rules=[
            'tipoTramite' => 'required',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {

            if($rq->tipoTramite==2 || $rq->tipoTramite==3){
                return view('cosmeticos.nuevoregistro.pasos.paso2.gnralCosmeticos');
            }
            elseif ($rq->tipoTramite==4 || $rq->tipoTramite==5){
                return view('cosmeticos.nuevoregistro.pasos.paso2.gnralHigienico');
            }

        }
        catch (\Exception $e){
            //throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage() . $e->getLine() . $e->getFile()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getEvanses(){

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/envanse', [
                'headers' => [
                    'tk' => $this->token,
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $items="";
                foreach ($r->data as $data) {
                    $items .="<option value='".$data->idEnvase."'>".htmlentities(trim($data->nombreEnvase))."</option>";
                }
                return response()->json(['status' => 200, 'data' => $items], 200);

            }
            else if ($r->status == 400){
                return response()->json(['status' => 400, 'message' => $r->message],200);
            }
        }
        catch (\Exception $e){
           // throw $e;
            Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new JsonResponse([
                'message'=>'Error, favor contacte a DNM informática'
            ],500);
        }
    }

    public function getMateriales(){

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/materiales/presentaciones', [
                'headers' => [
                    'tk' => $this->token,
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $items="";
                foreach ($r->data as $data) {
                    $items .="<option value='".$data->idMaterial."'>".htmlentities(trim($data->material))."</option>";
                }
                return response()->json(['status' => 200, 'data' => $items], 200);

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

    public function getUnidadesMedida(){

        try {
            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/medidas', [
                'headers' => [
                    'tk' => $this->token,
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $items="";
                foreach ($r->data as $data) {
                    $items .="<option value='".$data->idMedida."'>".htmlentities(trim($data->abreviatura))."</option>";
                }
                return response()->json(['status' => 200, 'data' => $items], 200);

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

    public function getFormulaHig(Request $rq){

        $rules=[
            'q' => 'sometimes',
        ];

        $v = Validator::make($rq->all(),$rules);

        if ($v->fails()){
            return response()->json(['errors'=>$v->errors()],400);
        }

        try {

            $searchDenominacion = null;

            if($rq->has('q')) {
                $searchDenominacion = trim($rq->q);
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pelcos/get/formulahig', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'searchDenominacion' => $searchDenominacion,
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                /*$items="";
                foreach ($r->data as $data) {
                    $items .="<option value='".$data->idDenominacion."'>".trim($data->numeroCAS).' '.htmlentities(trim($data->denominacionINCI))."</option>";
                }*/
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
}
