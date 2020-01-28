<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\PersonaNatural;
use App\UserPortal;
use App\User;
use App\Http\Requests;
use Session;
use GuzzleHttp\Client;
use Config;
use Mail;
use App\Tratamiento;
use Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\CustomAuthController;
use Log;
class InicioController extends Controller
{
    //
    private $url=null;

    public function __construct() {
        $this->url = Config::get('app.api');
    }

    // funcion que muestra la vista de actualizacion de datos del usuario con sus
    //respectivos datos.
    public function index(){

            $data = [
                'title' => 'Inicio',
                'subtitle' => '',
                'persona' => null,
                'alertSesion' => false
            ];
            $nit=Session::get('user');

            //$persona=PersonaNatural::find($nit);
            if(in_array(1,Session::get('opciones'),true)){
                return view('inicio.index',$data);
            }
            else{
            $client = new Client();
            //request para obtener los datos de la última sesión
            $res = $client->request('POST', $this->url.'pel/u/lastLogin',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

                ],
                'form_params' =>[
                    'nit'   =>$nit,
                    'idLog' => Session::get('idlog')
                ]
            ]);

            $r = json_decode($res->getBody());

            if($r->status == 200)
            {
                $log = $r->data;

                if($log->alert)
                {
                    $data['alertSesion'] = true;
                }
            }


            $persona=PersonaNatural::find($nit);
            $data['persona']=$persona;
            $actualizado=Session::get('actualizado');
            $data['nit']=$nit;
            $data['actualizado']=$actualizado;
            $data['persona']->tels=json_decode($persona->telefonosContacto);
            $data['tratamientos']=Tratamiento::all();
            $data['notificaciones']=$persona->usuarioPortal;
           // dd($data);
            return view('inicio.index',$data);
            }
    }

    //funcion para actualizar los datos del usuario
    public function ActualizarDatos(Request $request){

        //dd($request->all());

        if($request->tratamiento=="0"){
            Session::flash('msnError', 'Debe seleccionar el tipo de tratamiento!');
            return redirect()->route('doInicio');
        }

        $nit=Session::get('user');
        $telefonosContacto=[];
        if($request->numtel!=null){
            $telefonosContacto[0]=$request->numtel;
        }
        else{
            $telefonosContacto[0]="";
        }
        if($request->numte1l!=null){
            $telefonosContacto[1]=$request->numte1l;
        }
        else{
            $telefonosContacto[1]="";
        }

        $client = new Client();
        $res = $client->request('POST', $this->url.'cat/persona/upd',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

            ],
            'form_params' =>[
                'nit'        =>$nit,
                'telefonos'  =>json_encode($telefonosContacto),
                'email'      =>$request->correo,
                'ip'         =>$request->ip()
            ]
        ]);

        $r = json_decode($res->getBody());

        if($r->status == 200){
            if($request->has('tratamiento')){
                $persona=PersonaNatural::find($nit);
                $persona->idTipoTratamiento=$request->tratamiento;
                $persona->fax=$request->fax;
                $persona->save();
                Session::forget('actualizado');
                Session::put('actualizado',1);
                $actualizado=1;
                $notific= $persona->updateUsuarioPortal;
                if($request->has('oirNotificaciones')){
                    if($request->oirNotificaciones==1 || $request->oirNotificaciones==2){
                            $notific->oirNotificaciones=$request->oirNotificaciones;
                            $notific->save();
                    }
                }
            }
            Session::flash('msnExito', 'SE ACTUALIZARON CORRECTAMENTE SUS DATOS!');
            return redirect()->route('doInicio')->with('actualizado', $actualizado);

        }
        elseif($r->status == 400){
            Session::flash('msnError', 'NO SE PUDO ACTUALIZAR SUS DATOS, INTENTELO DE NUEVO!');
            return redirect()->route('doInicio');
        }

    }

    public function cfgHideMenu()
    {
        $cfgHideMenu = Session::get('cfgHideMenu',false);
        $cfgHideMenu = ($cfgHideMenu)?false:true;
        Session::put('cfgHideMenu',$cfgHideMenu);
    }

    public function cambiopassword(){
        return view('inicio.passwdReset');

    }
    public function confirmCodigo(Request $request){


        if(Session::get('number')==(int)$request->codigo){
            return view('inicio.cambiopassword');
        }
        else{
            return back();
        }
    }
    public function getresetpass(){
        if(Session::get('nit'))
        {
            return view('inicio.resetpassword',['nit' => Session::get('nit')]);
        }
        else
        {

        }

    }


    public function resetpassword(Request $request){

        $user=UserPortal::find($request->idUsuario);
        $persona=PersonaNatural::find($request->idUsuario);

        if ($user!=null) {
           if($persona->emailsContacto!=null){
            $data=[];
            $correo=$persona->emailsContacto;

            $codigo=Str::random(32);
            $user->tokenPasswordReset = $codigo;
            $user->cambiarPassword = 0;
            $user->save();
            Session::put('nit',$request->idUsuario);

            $data['codigo']=$codigo;
            $data['correo']=$correo;
            Mail::send('emails.resetpass',$data,function($msj) use ($correo){
            $msj->subject('Correo de reseteo de contraseña');
            $msj->to($correo);
            $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
            });
            return response()->json(['status' => 200,'message' => 'Resultado con exito']);
           }
           else{
            return response()->json(['status' => 400,'message' => 'No existe correo electronico asociado a su nit. Contacte a: soporteenlinea@medicamentos.gob.sv']);
           }
        }
        else{
            //Session::flash('errors','El nit ingresado es incorrecto o no existe');
            //return back();
            return response()->json(['status' => 404,'message' => 'Nit no encontrado o no valido.']);
        }

    }

    public function changecontrasea(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'pwdnew1' => [
                    'required',
                    'min:7',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                ],
                'pwdnew2' => [
                            'required',
                            'same:pwdnew1',
                ],
            ],
                [
                    'pwdnew1.required' => 'La nueva contraseña es obligatoria',
                    'pwdnew2.required'  => 'Debe confirmar la nueva contraseña',
                    'pwdnew1.min'   => '',
                    'pwdnew1.regex' =>  '<p style="color: #0b0b0b">La nueva contraseña debe cumplir las siguientes condiciones: </p><ul><li>Debe contener por lo menos 8 caracteres</li><li>Una Letra Mayúscula</li><li>Una Letra Minúscula</li><li>Un valor numérico (0-9)</li><li>Debe ser diferente a la contraseña anterior</li></ul></p>',
                ]);

            if ($validate->fails()) {
                $msg = "<ul class=''>";
                foreach ($validate->messages()->all() as $err) {
                    $msg .= "<li>$err</li>";
                }
                $msg .= "</ul>";
                return response()->json(['status' => 404, 'message' => $msg], 200);
            }
            $nit = Session::get('user');
            $user = UserPortal::find($nit);

            $pass = md5($request->pwdnew1);

            if($pass == $user->password)
            {
                return response()->json(['status' => 404, 'message' => '<i style="text-align: center; color: #903d2c" class="fa fa-5x fa-warning"></i><b>La nueva contraseña debe ser diferente de su contraseña anterior</b>'], 200);
            }
            $user->password = $pass;
            $user->fechaCaducaPassword = Carbon::now('America/El_Salvador')->addDay(60);
            $user->tokenPasswordReset = null;
            $user->cambiarPassword = 1;
            if ($user->save()) {
                return response()->json(['status' => 200, 'message' => '<i style="text-align: center; color: #2a900d" class="fa fa-5x fa-check-circle"></i>Se ha actualziado su nueva contraseña con vigencia de 60 días.<br><b>Recuerde que esta contraseña será utilizada para el acceso a los sistemas de la DNM, por lo que se recomienda resguardarla en un lugar seguro</b>'], 200);
            } else {
                return redirect()->route('cambiocontraseña');
            }
        }
        catch (\Exception $ex)
        {
            Log::error($ex);
            return response()->json(['status' => 500, 'message' => 'No ha sido posible completar la acción solicitada. Por favor intentelo nuevamente'], 200);
        }
    }



}
