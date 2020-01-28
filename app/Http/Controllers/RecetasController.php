<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\PersonaNatural;
use App\UserPortal;
use App\User;
use App\Http\Requests;
use Session;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Config;
use Crypt;
use Mail;
use Validator;
use Datatables;
use App\Tratamiento;
use App\Http\Controllers\CustomAuthController;
use Log;
class RecetasController extends Controller
{
    //
 private $url=null;

    public function __construct() {
        $this->url = Config::get('app.api');
    }

    public function talonario(Request $request){

    		$data = ['title' 			=> 'Recetas'
				,'subtitle'			=> 'Solicitar talonario'
				,'breadcrumb' 		=> [
			 		['nom'	=>	'Recetas', 'url' => '#'],
			 		['nom'	=>	'Talonario', 'url' => '#']
				]];

	        $nit=Session::get('user');
	        $actualizado=Session::get('actualizado');
            $data['nit']=$nit;
            $data['actualizado']=$actualizado;
            return view('recetas.talonario',$data);
    }

    public function verificarMandamiento(Request $request){
        //dd($request->all());

        try {




            if($request->mandamiento!=null) {

            $client = new Client();
            $res = $client->request('POST', $this->url.'recetas/getMandamientoBySol',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'mandamiento'   =>$request->mandamiento
                ]
            ]);

            $r = json_decode($res->getBody());

            if($r->status == 200){
                $verificacion=DB::table('cssp.cssp_mandamientos as m')
                      ->join('cssp.cssp_mandamientos_detalle as md','md.id_mandamiento','=','m.id_mandamiento')
                      ->join('cssp.cssp_mandamientos_recibos as mr','mr.id_mandamiento', '=','m.id_mandamiento')
                      ->where('m.id_mandamiento',$request->mandamiento)
                      ->where('md.id_tipo_pago','3730')
                      ->select('md.correlativo','md.id_mandamiento','md.id_tipo_pago',DB::raw('sum(md.valor) as total'))
                      ->first();


                if($verificacion->id_mandamiento!=null){
                    //este tiene trae el valor del tipo de pago
                    $valorTramite=DB::table('cssp.cssp_tipos_pagos_col')->where('ID_TIPO_PAGO','3730')->first();

                    $valorTotal=$valorTramite->VALOR*$request->numTalonario;
                    //dd($verificacion->total);
                    if($verificacion->total==$valorTotal){
                    return response()->json(['status' => 200, 'message' => "El mandamiento es válido para usar en este trámite!"],200);
                    }else{
                    return response()->json(['status' => 400, 'message' => "El mandamiento es válido, pero el número de talonarios solicitados es incorrecto!"],200);
                    }
                }
                else{
                    return response()->json(['status' => 400, 'message' => "El mandamiento no es válido!"],200);
                }
            }
            else{
               return response()->json(['status' => 400, 'message' => $r->message],200);
            }
          }
          else{
            return response()->json(['status' => 400, 'message' => "Error: Es necesario que digite un número de mandameinto, para validarlo!"],200);
          }
        }
        catch (Exception $e) {
            return response()->json(['status' => 400, 'message' => "Error, favor contacte a DNM informática!"],200);
        }

    }

    public function storeTalonario(Request $request){

			$v = Validator::make($request->all(),[
        	'numTalonario'=>'required|min:1',
        	'nit'=>'required',
            'justificacion'=>'required',
            'mandamiento'=>'required'
			    ]);

	   		$v->setAttributeNames([
	   		    'numTalonario' => 'número de talonarios',
	   		    'nit'=>'NIT',
                'justificacion'=>'justificación',
                'mandamiento'=>'mandamiento'

		    ]);
			if ($v->fails())
		    {
		    	$msg = "<ul class='text-warning'>";
		    	foreach ($v->messages()->all() as $err) {
		    	 	$msg .= "<li>$err</li>";
		    	}
		    	$msg .= "</ul>";
		        return back()->withErrors($v);
		    }

		    try {

                $result=$this->verificarMandamiento($request);

                if($result->getData()->status!=200){
                    return "<ul class='text-danger'>¡ERROR! ".$result->getData()->message." <ul/>";
                }


              //--------------------------------------------------------------------------------------
                $client2 = new Client();
                $idProfesional=Session::get('idProfesional');
                $res = $client2->request('POST', $this->url.'pel/recetas/storeTalonario',[
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                    ],
                    'form_params' =>[
                         'id'  =>$idProfesional,
                         'num' =>$request->numTalonario,
                         'just'=>$request->justificacion,
                         'mandamiento'=>$request->mandamiento,
                         'usuarioCreacion' =>$request->nit.'@'.$request->ip()
                    ]
                ]);

                $r = json_decode($res->getBody());

                if($r->status == 200){
                     Session::flash('msnExito','Se ha ingreado su solicitud de talonario de forma exitosa!');
                    return redirect()->route('ver.lista.talonario');
                }
                elseif($r->status == 400){
                   return back()->withErrors($r->message);

                }
                else{
                    Session::flash('msnError','No se ha podido guardar su solicitud de talonario, contáctese con el administrador!');
                    return back()->withInput();
                }

			} catch(Exception $e){
			    throw $e;
                 Session::flash('msnError', $e->getMessage());
                 return $e;
			}
    }

     public function lista(Request $request){
             $data = ['title'            => 'Recetas'
                ,'subtitle'         => 'Lista de talonario'
                ,'breadcrumb'       => [
                    ['nom'  =>  'Recetas', 'url' => '#'],
                    ['nom'  =>  'Talonarios', 'url' => '#']
                ]];

       return view('recetas.lista',$data);
     }



    public function listaTalonario(Request $request){
        //dd($request->all());

        $client = new Client();
        //request para obtener los datos de una persona
        $nit=Session::get('user');

        $id = Session::get('idProfesional');
        if(!empty($id)){

        $res = $client->request('POST', $this->url.'pel/recetas/lista/talonarios',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'id'     => $id,
            ]

        ]);

        $r = json_decode($res->getBody());
          if($r->status==200){

                    $collection = collect($r->data[0]);
                    return Datatables::of($collection)
                     ->addColumn('numero_talonarios', function ($dt) {

                 return '<center><span class="label label-primary">'.$dt->numero_talonarios.'</span></center>';

                    })
                       ->addColumn('nombreEstado', function ($dt) {

                 return '<center><span class="label label-info">'.$dt->nombreEstado.'</span></center>';

                    })

                    ->make(true);
                }
                elseif($r->status==404){
                    $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                    return json_encode($results);

                    /*Session::flash('msnError',$r->message);
                    return back();
                    $collection=null;*/
                }
       }else{
            $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
            return json_encode($results);
            /*return Datatables::of($collection)
                 ->make(true);*/
        }


    }

    public function homeRecetas(Request $request){

            $data = ['title'            => 'Recetas'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Recetas', 'url' => '#'],
                    ['nom'  =>  '', 'url' => '#']
                ]];
        try {

            $nit=Session::get('user');
            $idProfesional=Session::get('idProfesional');

      //-----------------------------------COUNT RECETAS DISPONIBLES---------------------
            $client = new Client();
            $res = $client->request('POST', $this->url.'recetas/count/recetasDisponibles',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'id'   =>$idProfesional
                ]
            ]);

            $r = json_decode($res->getBody());

            if($r->status == 200){
                $data['data']=$r->data;
            }

        } catch (Exception $e) {
            throw $e;
            Session::flash('msnError', $e->getMessage());
            return $e;
        }

        return view('recetas.index',$data);

    }

     public function nuevaReceta(Request $request){

          //--------------------------------------------------------------------------
            $data = ['title'            => 'Recetas'
                ,'subtitle'         => 'Nueva'
                ,'breadcrumb'       => [
                    ['nom'  =>  'Recetas', 'url' => '#'],
                    ['nom'  =>  'Nueva', 'url' => '#']
                ]];
            $nit=Session::get('user');

          //-------------------------CONSULTA PARA RECETA-------------------------------------------------
            $client3 = new Client();
            //request para obtener los datos de una persona
            $res3 = $client3->request('POST', $this->url.'recetas/getIdRecetaMin',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'idProfesional'   =>Session::get('idProfesional')
                ]
            ]);

            $r3 = json_decode($res3->getBody());
            if($r3->status == 200){
                $d3=$r3->data;
            }
            else{
                //return "<ul class='text-danger'>¡ERROR! No tiene los permisos necesario<ul/>"
                Session::flash('msnError','Error: Usted no tiene ninguna solicitud de talonario aprobada,por esto no puede crear una nueva receta!');

                return redirect()->route('index.recetas');
            }
            $data['idReceta']=$d3;

           return view('recetas.nueva',$data);

    }

    public function consultarPersona(Request $request){

            $v = Validator::make($request->all(),[
                'num'=>'required',
                    ]);

            $v->setAttributeNames([
                'num' => 'número de documento',

            ]);
            if ($v->fails())
            {
                $msg = "<ul class='text-warning'>";
                foreach ($v->messages()->all() as $err) {
                    $msg .= "<li>$err</li>";
                }
                $msg .= "</ul>";
                return $msg;
            }
            try {
             //--------------------------------------------------------------------------------------
            $client = new Client();
            //request para obtener los datos de una persona
            $num = $request->num;
            $res = $client->request('POST', $this->url.'recetas/getPaciente',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'numero_docto'   =>$num
                ]
            ]);

            $r = json_decode($res->getBody());
            if($r->status == 200){
                return response()->json(['status' => 200, 'message' => $r->data],200);
            }
            else if($r->status == 400){
                return response()->json(['status' => 400, 'message' => "Error, problemas con los datos"],200);
            }else{
               return response()->json(['status' => 400, 'message' => "Error, no existen datos"],200);
            }



            } catch(Exception $e){
                throw $e;
               return response()->json(['status' => 400, 'message' => "Error, no existen datos"],200);
            }





    }

     public function storePaciente(Request $request){

            $v = Validator::make($request->all(),[
                'nombresP' => 'required',
                'apellidosP' => 'required',
                'edad' => 'required',
                'domicilio' => 'required',
                'numDocumentoP' => 'sometimes',
                'numDocumento2' => 'sometimes'
                    ]);

            $v->setAttributeNames([
               'nombresP' => 'Nombres',
                'apellidosP' => 'Apellidos',
                'edad' => 'Edad',
                'domicilio' => 'Domicilio',
                'numDocumentoP' => 'Numero de documento'
            ]);
            if ($v->fails())
            {
                $msg = "<ul class='text-warning'>";
                foreach ($v->messages()->all() as $err) {
                    $msg .= "<li>$err</li>";
                }
                $msg .= "</ul>";
                return response()->json(['status' => 400, 'message' => $msg]);
            }

            try {

                $client = new Client();

                $nombres =$request->nombresP;
                $apellidos =$request->apellidosP;
                $edad = $request->edad;
                $tipo = $request->tipo;
                if($tipo=='DUI'){
                 $numDocumento= $request->numDocumentoP;
                    if(!preg_match("/^[0-9]{8}-[0-9]{1}$/",$numDocumento)){
                        $response = ['state' => 'success','status' => 400, 'message' =>'El formato del número de documento no es el correcto!'];
                        return response()->json($response);
                    }
                 }else{
                 $numDocumento= $request->numDocumento2;
                 }
                $domicilio = $request->domicilio;
                $sexo = $request->sexo;
                $usu=Session::get('user').'@'.$request->ip();

                $res = $client->request('POST', $this->url.'recetas/store/paciente',[
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                    ],
                    'form_params' =>[
                          'nombres'       =>  mb_strtoupper($nombres, "utf-8"),
                          'apellidos'     =>  mb_strtoupper($apellidos, "utf-8"),
                          'sexo'          =>  $sexo,
                          'edad'          =>  $edad,
                          'tipo_docto'    =>  $tipo,
                          'numero_docto'  =>  $numDocumento,
                          'domicilio'     =>  $domicilio,
                          'idUsuarioCrea' =>  $usu,
                          'fecha_creacion' => date('Y-m-d H:i:s')
                    ]
                ]);

                $r = json_decode($res->getBody());
                //dd($r);
                if($r->status == 200){
                    return response()->json(['state' => 'success','status' => 200,'message' =>'Se ha registrado el paciente de forma existosa!','data'=>$r->data]);
                }
                else if($r->status==400){
                    $response = ['state' => 'success','status' => 400, 'message' =>$r->message];
                    return response()->json($response);
                }

            } catch(Exception $e){
                throw $e;
                return response()->json(['status' => 400, 'message' => "No se ha podido ingrear el paciente, contacte al administrador del sistema!"],200);
            }
            return response()->json(['state' => 'success']);

    }

    public function getProductosReceta(Request $request){



        $nit=Session::get('user');
        $client = new Client();
        $res = $client->request('POST', $this->url.'recetas/getProductos/retenidos',[
        'headers' => [
        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

        ],
        'form_params' =>[

        ]

        ]);

        $r = json_decode($res->getBody());


        if($r->status==200){
             $collection = collect($r->data);
            return Datatables::of($collection)
              ->addColumn('detalle', function ($dt) {

                    return '<a class="btn btn-xs btn-success btn-perspective" onclick="selectInfo(\''.$dt->idProducto.'\',\''.$dt->nombreComercial.'\');" ><i class="fa fa-check-square-o"></i></a>';

            })
            ->make(true);

        }
        elseif($r->status==400){
            Session::flash('msnError',$r->message );
            return back()->withInput();
            $productos_pupi=null;
        }

    }

    public function storeReceta(Request $request){
       //dd($request->all());

            $v = Validator::make($request->all(),[
                  'fecha'=>'required',
                  'idReceta'=>'required',
                  'idProfesional'=>'required',
                  'numIngreso'=>'required',
                  'idProducto'=>'required',
                  'totalDosis'=>'required',
                  'dosisReceta'=>'required',
                  'totalTomas'=>'required',
                  'cicloDosis'=>'required',
                  'duracionTratamiento'=>'required',
                  'idUso'=>'required'

                    ]);

            $v->setAttributeNames([
                   'fecha'=>'fecha',
                  'idReceta'=>'Id receta',
                  'idProfesional'=>'Id profesional',
                  'numIngreso'=>'# Documento',
                  'idProducto'=>'Producto',
                  'totalDosis'=>'Total de dosis',
                  'dosisReceta'=>'Dosis receta',
                  'totalTomas'=>'Total de tomas',
                  'cicloDosis'=>'Ciclo de dosis',
                   'duracionTratamiento'=>'Duración tratamiento',
                   'idUso'=>'Tipo uso'

            ]);
            if ($v->fails())
            {
                $msg = "<ul class='text-warning'>";
                foreach ($v->messages()->all() as $err) {
                    $msg .= "<li>$err</li>";
                }
                $msg .= "</ul>";
                return back()->withErrors($v);
            }

            try {


                //-------------------------CONSULTA PARA RECETA-------------------------------------------------
                    $client3 = new Client();
                    //request para obtener los datos de una persona
                    $res3 = $client3->request('POST', $this->url.'recetas/getIdRecetaMin',[
                        'headers' => [
                            'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                        ],
                        'form_params' =>[
                            'idProfesional'   =>Session::get('idProfesional')
                        ]
                    ]);
                    $d3 = null;
                    $r3 = json_decode($res3->getBody());
                    if($r3->status == 200)
                    {
                        if($request->idReceta != $r3->data)
                        {
                            $data['error'] = 'La receta en pantalla <b>'.$request->idReceta.'</b> no coincide con <b>'.$r3->data.'</b> generada desde el sistema';
                            Mail::send('emails.errormail',$data,function($msj){
                                $msj->subject('Error en el sistema portal en linea');
                                $msj->to('rogelio.menjivar@medicamentos.gob.sv');
                            });
                            Session::flash('msnError','No fue posible guardar su solicitud de receta, intentelo nuevamente!');
                            return back()->withInput();
                        }
                    }
                    else
                    {
                         $data['error']=$r->message;
                            Mail::send('emails.errormail',$data,function($msj){
                                $msj->subject('Error en el sistema portal en linea');
                                $msj->to('rogelio.menjivar@medicamentos.gob.sv');
                            });
                            Session::flash('msnError','No se ha podido guardar su solicitud de receta, contáctese con el administrador!');
                            return back()->withInput();
                    }

                $fCreacion =date('Y-m-d H:i:s');
                $client2 = new Client();
                $usu=Session::get('user').'@'.$request->ipRemote;
                $res = $client2->request('POST', $this->url.'recetas/store/receta',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                        'idReceta'      =>   $request->idReceta,
                        'idMedico'      =>   $request->idProfesional,
                        'idPaciente'    =>   $request->numIngreso,
                        'idProducto'    =>  $request->idProducto,
                        'totalDosis'    =>  $request->totalDosis,
                        'dosisDescrip'  =>  $request->dosisReceta,
                        'dosisCiclo'    =>  $request->totalTomas,
                        'ciclo'         =>  $request->cicloDosis,
                        'duracionTrata' =>  $request->duracionTratamiento,
                        'tipoUso'         =>  $request->idUso,
                        'usuarioCreacion' =>  $usu,
                        'fechaCreacion' =>$fCreacion
                ]
            ]);

                $r = json_decode($res->getBody());
                if($r->status == 200){
                    Session::flash('msnExito','Se ha ingreado su solicitud de receta de forma exitosa!');
                    return redirect()->route('get.pdf.comprobante.receta',['idReceta'=>Crypt::encrypt($request->idReceta),'idEstado'=>Crypt::encrypt(1)]);
                }
                elseif($r->status == 400){
                   return back()->withErrors($r->message);

                }
                else{
                    $data['error']=$r->message;
                    Mail::send('emails.errormail',$data,function($msj){
                        $msj->subject('Error en el sistema portal en linea');
                        $msj->to('rogelio.menjivar@medicamentos.gob.sv');
                    });
                    Session::flash('msnError','No se ha podido guardar su solicitud de receta, contáctese con el administrador!');
                    return back()->withInput();
                }

            }
            catch (ClientException $e) {

                $data['error']=$e->getMessage();
                Mail::send('emails.errormail',$data,function($msj){
                    $msj->subject('Error en el sistema portal en linea');
                    $msj->to('rogelio.menjivar@medicamentos.gob.sv');
                });
                Log::info($e->getMessage());
                Session::flash('msnError','No se ha podido guardar su solicitud de receta, por favor intentelo de nuevo!');
                return back()->withInput();
            } catch (RequestException $e) {
                throw $e;

                $data['error']=$e->getMessage();
                Mail::send('emails.errormail',$data,function($msj){
                    $msj->subject('Error en el sistema portal en linea');
                    $msj->to('rogelio.menjivar@medicamentos.gob.sv');
                });
                Log::info($e->getMessage());
                Session::flash('msnError','No se ha podido guardar su solicitud de receta, por favor intentelo de nuevo!');
                return back()->withInput();
            }
            catch(Exception $e){
                throw $e;
                return response()->json(['status' => 400, 'message' => "No se ha podido ingrear la solicitud de receta, contacte al administrador del sistema!"],200);
            }

    }




    public function historialRecetas(Request $request){
             $data = ['title'            => 'Historial de recetas'
                ,'subtitle'         => ''
                ,'breadcrumb'       => [
                    ['nom'  =>  'Regresar', 'url' => route('index.recetas')],
                    ['nom'  =>  'Historial', 'url' => '#']
                ]];

       return view('recetas.historialRecetas',$data);
     }


    public function listaHistorial(Request $request){
        //dd($request->all());
        $client = new Client();
        $nit=Session::get('user');

        $id = Session::get('idProfesional');
        //dd($id);
        if(!empty($id)){

        $res = $client->request('POST', $this->url.'recetas/getHistorial',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idMedico'     => $id,
            ]

        ]);


        $r = json_decode($res->getBody());

        if($r->status==200){

                    $collection = collect($r->data[0]);
                    return Datatables::of($collection)
                     ->addColumn('ubicacion_estado', function ($dt) {
                        if($dt->ubicacion_estado==1){
                         return '<span class="label label-primary">Emitida</span>';
                        }else if($dt->ubicacion_estado==2){
                         return '<span class="label label-info">Dispensada</span>';
                        }
                        else if($dt->ubicacion_estado==3){
                         return '<span class="label label-danger">Anulada</span>';
                        }

                    })
                ->addColumn('detalle', function ($dt) {

                    if($dt->ubicacion_estado==1){
                        //<li><a  href="><i class="fa fa-edit"></i>EDITAR</a></li>
                       return '<a class="btn btn-warning btn-xs" href="'.route('editar.receta',['idReceta'=>Crypt::encrypt($dt->ID_RECETA)]).'"><i class="fa fa-edit"></i>EDITAR</a> &nbsp;&nbsp;<a target="_blank" title="Imprimir comprobante" class="btn btn-info btn-xs" href="'.route('get.pdf.comprobante.receta',['idReceta'=>Crypt::encrypt($dt->ID_RECETA),'idEstado'=>Crypt::encrypt(1)]).'" ><i class="fa fa-print"></i></a>';
                     }else if($dt->ubicacion_estado==2){
                        return '<a class="btn btn-warning btn-xs" href="'.route('ver.detalle.receta',['idReceta'=>Crypt::encrypt($dt->ID_RECETA)]).'" ><i class="fa fa-eye"></i>VER DETALLES</a>&nbsp;&nbsp;<a title="Imprimir comprobante" target="_blank" class="btn btn-info btn-xs" href="'.route('get.pdf.comprobante.receta',['idReceta'=>Crypt::encrypt($dt->ID_RECETA),'idEstado'=>Crypt::encrypt(2)]).'" ><i class="fa fa-print"></i></a>';

                     }


                    })

                    ->make(true);

                }elseif($r->status==404){
                    $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                    return json_encode($results);

                    /*Session::flash('msnError',$r->message);
                    return back();
                    $collection=null;*/
                }
       }else{
            $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
            return json_encode($results);
            /*return Datatables::of($collection)
                 ->make(true);*/
        }


    }

     public function editarReceta($idReceta){

           $id = Crypt::decrypt($idReceta);
            try {
             //--------------------------------------------------------------------------------------
            $client = new Client();
            $res = $client->request('POST', $this->url.'recetas/info',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'idReceta'   =>$id,
                    'estado'     =>1
                ]
            ]);

            $r = json_decode($res->getBody());
            if($r->status == 200){

             $data = ['title'            => 'Receta'
                ,'subtitle'         => 'Editar'
                ,'breadcrumb'       => [
                    ['nom'  =>  'Historial recetas', 'url' => route('ver.historial.recetas')],
                    ['nom'  =>  'Editar', 'url' => '#']
                ]];

            $data['info'] = $r->data;
            //dd($r->data[0]->ubicacion_estado);
            return view('recetas.editarReceta',$data);

            }else{
                Session::flash('msnError','¡Problemas con los datos!');
                return redirect()->route('index.recetas');
            }



            } catch(Exception $e){
                DB::rollback();
                throw $e;
                 Session::flash('msnError','¡Problemas con los datos!');
                return redirect()->route('index.recetas');
            }

    }


    public function storeEditarReceta(Request $request){
            //dd($request->all());

            $v = Validator::make($request->all(),[
                  'fecha'=>'required',
                  'idReceta'=>'required',
                  'idProfesional'=>'required',
                  'numIngreso'=>'required',
                  'idProducto'=>'required',
                  'totalDosis'=>'required',
                  'dosisReceta'=>'required',
                  'totalTomas'=>'required',
                  'cicloDosis'=>'required',
                   'duracionTratamiento'=>'required',
                   'idUso'=>'required'

                    ]);

            $v->setAttributeNames([
                  'fecha'=>'Fecha receta',
                  'idReceta'=>'Id receta',
                  'idProfesional'=>'Id profesional',
                  'numIngreso'=>'# Documento',
                  'idProducto'=>'Producto',
                  'totalDosis'=>'Total de dosis',
                  'dosisReceta'=>'Dosis receta',
                  'totalTomas'=>'Total de tomas',
                  'cicloDosis'=>'Ciclo de dosis',
                   'duracionTratamiento'=>'Duración tratamiento',
                   'idUso'=>'Tipo uso'

            ]);
            if ($v->fails())
            {
                $msg = "<ul class='text-warning'>";
                foreach ($v->messages()->all() as $err) {
                    $msg .= "<li>$err</li>";
                }
                $msg .= "</ul>";
                return $msg;
            }
            try {

           //-----------CONSULTAMOS A VER SI LA RECETA NO ESTA Dispensada -----------
            $client = new Client();
            $res = $client->request('POST', $this->url.'recetas/info',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'idReceta'   =>$request->idReceta,
                    'estado'     =>1
                ]
            ]);

            $r = json_decode($res->getBody());

            //CONSULTAR NUMERO DE DOCUMENTO DEL PACIENTE
            $client3 = new Client();
            $res3 = $client3->request('POST', $this->url.'recetas/getPaciente',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'numero_docto'   =>$request->numIngreso
                ]
            ]);

            $r3 = json_decode($res3->getBody());
            $validadoPaciente=0;

            if($r3->status==200){
                $validadoPaciente=1;
            }
            else{
                return "<ul class='text-danger'>El número de documento no es válido, ingrese un número de documento valido, para poder editar la receta!<ul/>";
            }


            if($r->status == 200 && $validadoPaciente==1){
                $client2 = new Client();
                $usu=Session::get('user').'@'.$request->ipRemote;
                $res = $client2->request('POST', $this->url.'recetas/store/editar',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                        'fecha'         =>   $request->fecha,
                        'idReceta'      =>   $request->idReceta,
                        'idMedico'      =>   $request->idProfesional,
                        'idPaciente'    =>   $request->numIngreso,
                        'idProducto'    =>  $request->idProducto,
                        'totalDosis'    =>  $request->totalDosis,
                        'dosisDescrip'  =>  $request->dosisReceta,
                        'dosisCiclo'    =>  $request->totalTomas,
                        'ciclo'         =>  $request->cicloDosis,
                        'duracionTrata' =>  $request->duracionTratamiento,
                        'tipoUso'         =>  $request->idUso,
                        'usuarioCreacion'       =>  $usu,
                ]
                ]);

                $r = json_decode($res->getBody());
                if($r->status == 200){
                    return response()->json(['state' => 'success']);
                }
                else{
                     DB::rollback();
                   return "<ul class='text-danger'>Problemas con los datos<ul/>";
                }


            }
            else{
                   return "<ul class='text-danger'>Problemas al consultar la receta<ul/>";
                }

           //-----------VALIDAMOS SI ESTA DISPENSADA----------------------



            } catch(Exception $e){
                DB::rollback();
                throw $e;
                 return "<ul class='text-danger'>Problemas con los datos<ul/>";

            }
            return response()->json(['state' => 'success']);

    }


    public function verDetalle($idReceta){

           $id = Crypt::decrypt($idReceta);
            try {
             //--------------------------------------------------------------------------------------
            $client = new Client();
            $res = $client->request('POST', $this->url.'recetas/info',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'idReceta'   =>$id,
                    'estado'     =>2
                ]
            ]);

            $r = json_decode($res->getBody());
            if($r->status == 200){

             $data = ['title'            => 'Receta'
                ,'subtitle'         => 'Editar'
                ,'breadcrumb'       => [
                    ['nom'  =>  'Historial recetas', 'url' => route('ver.historial.recetas')],
                    ['nom'  =>  'Editar', 'url' => '#']
                ]];

            $data['info'] = $r->data;
            //dd($data);
            return view('recetas.verDetalle',$data);

            }else{
                Session::flash('msnError','¡Problemas con los datos!');
                return redirect()->route('index.recetas');
            }



            } catch(Exception $e){
                DB::rollback();
                throw $e;
                 Session::flash('msnError','¡Problemas con los datos!');
                return redirect()->route('index.recetas');
            }

    }


    public function anularReceta(Request $request){

        //dd($request->all());
            try {
             //--------------------------------------------------------------------------------------
            $client = new Client();
            $res = $client->request('POST', $this->url.'recetas/anular',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'idReceta'              =>$request->idReceta,
                    'idMedico'              =>$request->idMedico,
                    'usuarioModificacion'   =>Session::get('user').'@'.$request->ip()
                ]
            ]);

            $r = json_decode($res->getBody());
            if($r->status == 200){
               return response()->json(['status' => 200, 'message' => "Se ha anulado la receta con éxito!"]);
            }else{
              return response()->json(['status' => 400, 'message' => "No se ha podido anular la receta, porfavor intentelo de nuevo!"]);
            }

            } catch(Exception $e){
                DB::rollback();
                throw $e;
                return response()->json(['status' => 404, 'message' => "No se ha podido anular la receta!"]);
            }

    }


    public function vwConsultarProductos(Request $request)
    {
        try
        {

            $data = ['title'            => 'Consulta de Productos'
                    ,'subtitle'         => ''
                    ,'breadcrumb'       => [
                        ['nom'  =>  'Productos en Farmacias', 'url' => route('ver.historial.recetas')],

                    ]];

            $client = new Client();

            $res = $client->request('GET', $this->url.'dnm_catalogos/catDepartamentos',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[]
            ]);



            $r = json_decode($res->getBody());

            if($r->status == 200)
            {
                $data['departamentos'] = $r->data;
            }
            else
            {

            }

            if($request->has('idProducto') && ($request->idProducto != '') )
            {
                $data['busqueda'] = $request->all();

                $data['farmacias'] = $this->getMapaFarmacias($request);

            }
            elseif ($request->has('submit'))
            {
                Session::flash('msnError','¡Debe Seleccionar un producto!');
            }

            return view('recetas.vConsultaProductos',$data);

        } catch (Exception $e) {

        }


    }

    public function getMunicipios($id = null)
    {
        if($id)
        {
            $client = new Client();
            $res = $client->request('POST', $this->url.'dnm_catalogos/catDepartamentos',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'id' => $id
                ]
            ]);

            $r = json_decode($res->getBody());
            if($r->status == 200)
            {
                return response()->json($r->data[0]);
            }
            else
            {
                return response()->json([]);
            }

        }
        else
        {
            return response()->json([]);
        }
    }

    public function getDataFromInfo(Request $request)
    {
        $response = ['message'=>'No hay resultados de la búsqueda','status' => 404,'data' => []];

        Log::info($request->idDepartamento);
        if($request->has('idProducto'))
        {

            $client = new Client();

            $res = $client->request('POST', $this->url.'recetas/getProductosFarmacias',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'idProducto' => $request->idProducto    ,
                    'idDepartamento' => (intval($request->idDepartamento) > 0) ? $request->idDepartamento : 0,
                    'idMunicipio' => (intval($request->idMunicipio) > 0) ? $request->idMunicipio : 0
                ]
            ]);

            $r = json_decode($res->getBody());

            if($r->status == 200)
            {
                if(sizeof($r->data) > 0 )
                {
                    $datarows = collect($r->data);

                    return Datatables::of($datarows)->make(true);

                }
                else
                {
                    $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                    return json_encode($results);
                }

            }
            else
            {
                $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                    return json_encode($results);
            }
        }
        else
        {
            $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                    return json_encode($results);
        }
    }

    public function getMapaFarmacias(Request $request)
    {
        $markers = [];
        if($request->has('idProducto'))
        {

            $client = new Client();

            $res = $client->request('POST', $this->url.'recetas/getProductosFarmacias',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'idProducto' => $request->idProducto    ,
                    'idDepartamento' => (intval($request->cbDepartamentos) > 0) ? $request->cbDepartamentos : 0,
                    'idMunicipio' => (intval($request->cbMunicipios) > 0) ? $request->cbMunicipios : 0
                ]
            ]);

            $r = json_decode($res->getBody());

            if($r->status == 200)
            {
                if(sizeof($r->data) > 0 )
                {

                    foreach ($r->data as $key => $value)
                    {
                        if(intval($value->pointX) > 0)
                        {
                            $telefonosContacto = json_decode($value->telefonosContacto);
                            $markers['marker_'.$key] = ['lat' => $value->pointX,'lng' => $value->pointY,'draggable' => 'false','animation' => 'DROP','info' => 'Nombre del Establecimiento:<b>'.$value->nombreComercial.'</b><br>Disponible: <b>'.$value->saldo.'</b><br>Horario de Atención: '.$value->horarioServicio.'<br>Teléfonos: '.$telefonosContacto[0].','.$telefonosContacto[1]];
                        }

                    }
                }
            }
        }


        return $markers;

    }

    public function consultarProPaciente(Request $request){

            $v = Validator::make($request->all(),[
                'txtProducto'=>'required',
                'txtPaciente'=>'required',
                    ]);

            $v->setAttributeNames([
                'txtProducto'=>'producto controlado',
                'txtPaciente'=>'paciente',

            ]);
            if ($v->fails())
            {
                $msg = "<ul class='text-warning'>";
                foreach ($v->messages()->all() as $err) {
                    $msg .= "<li>$err</li>";
                }
                $msg .= "</ul>";
                return $msg;
            }
            try {

            $client = new Client();
            $res = $client->request('POST', $this->url.'pf/data/receta',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'numPaciente' => $request->txtPaciente,
                    'idProducto' => $request->txtProducto,
                ]
            ]);

            $r = json_decode($res->getBody());
            if($r->status == 200){
                $datos= $r->data[0];

                if($datos->ubicacion_estado==1){
                     return response()->json(['state' => 'success','tipo'=>1,'fecha'=>$datos->fecha_creacion]);
                }else{
                    $f1=$datos->fecha_retiro;
                    if($f1==''){
                        //el producto no tiene fecha de retiro
                        return response()->json(['state' => 'success','tipo'=>2,'val'=>1]);
                    }else{
                //calculamos los dias de la fecha_retiro de retiro más dosis_duracion_trat
                        $dosisdias=$datos->dosis_duracion_trat;
                        $fecha =date('Y-m-d',strtotime($datos->fecha_retiro));
                        $fechaf=date_add(date_create($fecha), date_interval_create_from_date_string($dosisdias.' days'));
                        $nuevaFecha=date_format($fechaf, 'Y-m-d');

                //calculamos los días desde la fecha actual hasta la fecha de retiro más la dosis de duración
                        $d1=date("Y-m-d");
                        $d2= $nuevaFecha;
                        $datetime1 = date_create($d1);
                        $datetime2 = date_create($d2);
                        $interval = date_diff($datetime1, $datetime2);
                        $ddias=$interval->format('%r%a days');
                        $ddias= $ddias+1;
                        //dd($ddias);

                        return response()->json(['state' => 'success','tipo'=>2,'val'=>2,'dias'=>$ddias]);
                    }


                }

            }else{
                 return response()->json(['state' => 'success','tipo'=>3]);
            }


            }catch(Exception $e){
                DB::rollback();
                throw $e;
                return $e->getMessage();
            }
            return response()->json(['state' => 'success']);

    }


     public function getProductosPaciente(Request $request){
     //   dd($request->all());

            $client = new Client();
            $res = $client->request('POST', $this->url.'recetas/getRecetas/paciente',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'numPaciente'   =>$request->num
                ]
            ]);

            $r = json_decode($res->getBody());

            if($r->status==200){

                    $collection = collect($r->data[0]);
                    return Datatables::of($collection)
                    ->addColumn('fecha_retiro', function ($dt) {
                        if($dt->fecha_retiro==''){
                            return '<center><span class="label label-primary">N/A</span></center>';
                        }else{
                            return $dt->fecha_retiro;
                        }


                    })
                  ->make(true);

             }else{
            $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
            return json_encode($results);
            /*return Datatables::of($collection)
                 ->make(true);*/
        }
    }

    public function verificarPro(Request $request){

        $v = Validator::make($request->all(),[
            'txt'=>'required'

                ]);

        $v->setAttributeNames([
            'txt'=>'paciente'
        ]);
        if ($v->fails())
        {
            $msg = "<ul class='text-warning'>";
            foreach ($v->messages()->all() as $err) {
                $msg .= "<li>$err</li>";
            }
            $msg .= "</ul>";
            return $msg;
        }
        try {

            $client = new Client();
            $res = $client->request('POST', $this->url.'recetas/getRecetas/paciente',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'numPaciente'   =>$request->txt
                ]
            ]);

            $r = json_decode($res->getBody());

            if($r->status==200){
                if(!empty($r->data)){
                    return response()->json(['status' => 200,'message' =>'Se han encontrado recetas con el paciente!']);
                }
                else{
                     return response()->json(['status' => 404,'message' =>'No se han encontrado recetas con el paciente!']);
                }
            }
            else if($r->status==400) {
                 return response()->json(['status' => 400,'message' =>$r->message]);
            }
            else if($r->status==404){
                return response()->json(['status' => 404,'message' =>'No se han encontrado recetas con el paciente!']);
            }
            else{
                return response()->json(['status' => 500,'message' =>'El sistema esta presetando problemas, por favor contacte al administrador!']);
            }
        }catch(Exception $e){
                DB::rollback();
                throw $e;
                return $e->getMessage();
        }


    }
    public function comprobanteReceta(Request $request){
            $idReceta=Crypt::decrypt($request->idReceta);
            $idEstado=Crypt::decrypt($request->idEstado);
            try{
            $client = new Client();
            $res = $client->request('POST', $this->url.'recetas/info',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'idReceta'   =>$idReceta,
                    'estado'     =>$idEstado
                ]
            ]);

            $r = json_decode($res->getBody());
            if($r->status == 200){
                  $data['info'] = $r->data;
                  //dd($r->data);
                  $view =  \View::make('recetas.comprobanteIngresoReceta',$data)->render();
                  $pdf = \App::make('dompdf.wrapper');
                  $pdf->loadHTML($view);
                  //return $pdf->download('RECETA #'.$idReceta.'.pdf');
                  return $pdf->stream('RECETA #'.$idReceta.'.pdf');

            }else{
                Session::flash('msnError','¡Problemas al generar pdf de comprobante para receta!');
                return redirect()->route('index.recetas');
            }

            } catch(Exception $e){
                Session::flash('msnError','¡Problemas con los datos!');
                Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->message,'msg'=>$e->message]);
                return redirect()->route('index.recetas');
            }
    }


}
