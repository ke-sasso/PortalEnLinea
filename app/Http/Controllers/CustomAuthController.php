<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Auth;
use App\Http\Requests;
use App\LogUsuario;
use GuzzleHttp\Client;
use Validator;
use Session;
use Config;
use DB;
use Log;
class CustomAuthController extends Controller
{
    //

    private $url=null;
    
    public function __construct() { 
        $this->url = Config::get('app.api');
    }

    public function getLogin(){
        $data = ['title'            => ''
                ,'subtitle'         => ''];
        //Verificamos si ya esta logueado de lo contrario se redirige al login
        return view('login.login',$data);
 } 

    public function getLogout(){
    
        $idlog=Session::get('idlog');
       
        $client = new Client();
        $res = $client->request('POST', $this->url.'pel/u/logout',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            
                ],
                'form_params' =>[
                    'log'   =>$idlog,
                    'fecha' =>date('Y-m-d H:i:s')
                ]   
            ]);

            $r = json_decode($res->getBody());

            if($r->status == 200){
                    //dd($r);
                    Session::forget('user');
                    Session::forget('name');
                    Session::forget('lastname');
                    Session::forget('perfil');
                    Session::forget('actualizado');
                    Session::forget('idlog');
                    Session::forget('op');
                    Session::forget('perfiles');
                    Session::forget('prof');
                    return redirect()->route('doLogin');
            }
    } 

     public function postLogin(Request $request)
     {
         try {
             $rules = array('captcha' => ['required', 'captcha']);
             $validator = Validator::make(
                 ['captcha' => $request->captcha],
                 $rules,
                 // Mensaje de error personalizado
                 ['captcha' => 'El captcha ingresado es incorrecto.']
             );
             if ($validator->passes())
             { 
                $ip = (!$request->ipRemote) ? $request->ip() : $request->ipRemote;
                return $this->doLogin($this->url, $request->username, $request->password, $request->navegador, $ip);
             } else {
                 return redirect()->route('doLogin')->withErrors(['errors' => 'El captcha ingresado es incorrecto!']);
             }
         }
         catch(\Exception $ex)
         {
             Log::error($ex);
             return view('errors.500',['error'=>'No fue posible realizar la acción solicitada, por favor intentelo nuevamente']);
         }
    }
}
