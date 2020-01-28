<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use GuzzleHttp\Client;
use DB;
use Mail;
use Config;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function replaceAccents($str) {

        $search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ");

        $replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE");

        return str_replace($search, $replace, $str);
    }
    
    function emailErrorOdin($msj){
    	  $data['error'] = $msj;
          Mail::send('emails.errormail',$data,function($msj){
              $msj->subject('Error en el sistema portal en linea');
              $msj->to('kevin.sasso@medicamentos.gob.sv');
              //$msj->to('informatica@medicamentos.gob.sv');
          });
    }

    function requestOdin($method, $url, $params = []){
      try {
        $api_url = Config::get('app.api');

        $client = new Client();
        $res = $client->request($method, $api_url . $url, [
            'headers' => [
              'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
            'form_params' => $params
         ]);
        $r = json_decode($res->getBody());
        return $r;

      } catch(\Exception $e){ 
        Log::error($e);
        return;
      }
    }

    function getIp(){
      $ip = "";
      if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
      } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
          $ip = $_SERVER['REMOTE_ADDR'];
      }
      return $ip;
    }

    function getBrowser() { 
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
          $platform = 'Linux';
        }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
          $platform = 'Mac';
        }elseif (preg_match('/windows|win32/i', $u_agent)) {
          $platform = 'Windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
          $bname = 'Internet Explorer';
          $ub = "MSIE";
        }elseif(preg_match('/Firefox/i',$u_agent)){
          $bname = 'Mozilla Firefox';
          $ub = "Firefox";
        }elseif(preg_match('/OPR/i',$u_agent)){
          $bname = 'Opera';
          $ub = "Opera";
        }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
          $bname = 'Google Chrome';
          $ub = "Chrome";
        }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
          $bname = 'Apple Safari';
          $ub = "Safari";
        }elseif(preg_match('/Netscape/i',$u_agent)){
          $bname = 'Netscape';
          $ub = "Netscape";
        }elseif(preg_match('/Edge/i',$u_agent)){
          $bname = 'Edge';
          $ub = "Edge";
        }elseif(preg_match('/Trident/i',$u_agent)){
          $bname = 'Internet Explorer';
          $ub = "MSIE";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
          // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
          //we will have two since we are not using 'other' argument yet
          //see if version is before or after the name
          if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
              $version= $matches['version'][0];
          }else {
              $version= $matches['version'][1];
          }
        }else {
          $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
          'userAgent' => $u_agent,
          'name'      => $bname,
          'version'   => $version,
          'platform'  => $platform,
          'pattern'    => $pattern
        );
      } 

      function doLogin($url, $nit, $password, $navegador = "", $ip = ""){
        $client = new Client();
        $res = $client->request('POST', $url. 'pel/u/val', [
         'headers' => [
           'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

         ],
         'form_params' => [
           'nit' => $nit,
           'pass' => $password
         ]
       ]);

        $r = json_decode($res->getBody());

        if ($r->status == 200)
        {

         $passwordReset = $r->data->passwordReset;

         $opciones = DB::table('dnm_usuarios_portal.portal_usuarios_opciones')
         ->where('idUsuario', $r->data->idUsuario)->where('activo', 'A')
         ->select('idOpcion')
         ->get();
         $op = [];
         for ($i = 0; $i < count($opciones); $i++) {
           $op[$i] = $opciones[$i]->idOpcion;
         }

                     //dd($op);
         $res1 = $client->request('POST', $url . 'anualidades/getidProfesional', [
           'headers' => [
             'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
           ],
           'form_params' => [
             'nit' => $r->data->idUsuario
           ]

         ]);
         $r1 = json_decode($res1->getBody());
         if ($r1->status == 200) {
           $idPro = $r1->data[0];
           Session::put('prof', $idPro->ID_PROFESIONAL);
         } else {
           Session::put('prof', '');
         }

         if (in_array(1, $op, true)) {
           $perfil = [];
           Session::put('user', $r->data->idUsuario);
           Session::put('name', $r->data->nombresUsuario);
           Session::put('lastname', $r->data->apellidosUsuario);
           Session::put('perfil', $perfil);
           Session::put('actualizado', $r->data->actualizado);
           Session::put('perfiles', $r->data->rolesUsuarios);
           Session::put('opciones', $op);


         } else {
           $perfil = [];

           if (!empty($r->data->rolesUsuarios)) {
             if (count($r->data->rolesUsuarios) > 1) {
               for ($i = 0; $i < count($r->data->rolesUsuarios); $i++) {
                 if ($r->data->rolesUsuarios[$i]->PERFIL === 'REG') {

                   $perfil[$i] = 1;
                 } elseif ($r->data->rolesUsuarios[$i]->PERFIL === 'PROP' or $r->data->rolesUsuarios[$i]->PERFIL === 'REPR') {
                   $perfil[$i] = 1;
                 } elseif ($r->data->rolesUsuarios[$i]->PERFIL === 'APO' or $r->data->rolesUsuarios[$i]->PERFIL === 'PFR') {
                   $perfil[$i] = 2;
                 }

               }
             } elseif (!empty($r->data->rolesUsuarios[0])) {
               if ($r->data->rolesUsuarios[0]->PERFIL === 'REG' or $r->data->rolesUsuarios[0]->PERFIL === 'PROP' or $r->data->rolesUsuarios[0]->PERFIL === 'REPR') {
                 $perfil[0] = 1;
               } elseif ($r->data->rolesUsuarios[0]->PERFIL === 'APO' or $r->data->rolesUsuarios[0]->PERFIL === 'PFR') {
                 $perfil[0] = 2;
               }

             }
           }

                         //dd($op);
                         // dd(array_unique($perfil));
           Session::put('user', $r->data->idUsuario);
           Session::put('name', $r->data->nombresUsuario);
           Session::put('lastname', $r->data->apellidosUsuario);
           Session::put('perfil', $perfil);
           Session::put('actualizado', $r->data->actualizado);
           Session::put('perfiles', $r->data->rolesUsuarios);
           Session::put('opciones', $op);
                         // se hara por medio de odin 
         }

         if (in_array(7, $op, true)) {

           $res1 = $client->request('POST', $url . 'pel/recetas/getMedico', [
             'headers' => [

               'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

             ],
             'form_params' => [
               'nit' => Session::get('user')
             ]
           ]);

           $r = json_decode($res1->getBody());
                         //dd($r);
           if ($r->status == 200) {
             Session::put('idProfesional', $d = $r->data[0]->id_profesional);
           }
         }

         $res2 = $client->request('POST', $url . 'pel/u/log', [
           'headers' => [
             'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

           ],
           'form_params' => [
             'nit' => $nit,
             'fecha' => date("Y-m-d H:i:s"),
             'navegador' => $navegador,
             'ip' => $ip
           ]
         ]);

         $r2 = json_decode($res2->getBody());
         if ($r2->status == 200) {
           Session::put('idlog', $r2->data->idLog);
                         /*
                          * Validar si debe cambiar password
                         */

                         if($passwordReset == 0)
                         {
                           return view('inicio.passwdReset',['message' => '<i style="color: #2a62bc" class="fa fa-info-circle fa-2x"></i><b>Su contraseña ha expirado, por motivos de seguridad le solicitamos que la actualice inmediatamente</b>']);
                         }

                         return redirect()->route('doInicio');
                       } elseif ($r2->status == 400) {
                         return redirect()->route('doLogin')->withErrors(['errors' => $r2->message]);
                       } 

                     } else if ($r->status == 403) {
                       return redirect()->route('doLogin')->withErrors(['errors' => 'Tu usuario se encuentra inactivo']);
                     } else {
                       return redirect()->route('doLogin')->withErrors(['errors' => 'Usuario y/o Contraseña Invalidos!']);
                     }
      }

}
