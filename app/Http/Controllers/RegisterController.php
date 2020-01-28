<?php

namespace App\Http\Controllers;

use Crypt;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use App\Http\Controllers\Auth;
use App\Http\Requests;
use App\LogUsuario;
use App\Models\Departamentos;
use App\Models\Municipios;
use App\Models\Catalogo\Junta;
use App\Models\Catalogo\Inscripcion;
use App\Models\Catalogo\Rama;
use App\Models\Tratamientos;
use GuzzleHttp\Client;
use Validator;
use Session;
use Config;
use DB;
use Log;
use File;

class RegisterController extends Controller
{
    //

    private $url = null;
    private $path ='Y:\UPL\\';

    public function __construct() { 
        $this->url = Config::get('app.api');
    }

    public function getRegister(){
        $data = ['title'            => ''
                ,'subtitle'         => ''];
        return view('register.pre',$data);
    }

    public function getRegisterFinish(){
        if(!Session::has("step3")) return redirect()->route('pre_register');
        $data = ['title'            => ''
                ,'subtitle'         => ''
                ,'correo'           => Session::get("correo")];

        return view('register.finish',$data);
    }

    public function postRegisterFinish(Request $request){
        $rules = [
            'codigo' => 'required|max:150',
            'file_pdf' => 'sometimes|mimes:jpeg,png,pdf,jpg'
        ];
        $messages =  [
            'codigo.required' => 'Debe ingresar los nombres',
        ];
        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()){
            $errors = $v->messages()->all();
            return redirect()->back()->with(['step3'=>true, 'codigo' => $request->codigo ])->withErrors($errors)->withInput();
        }
        $nit = Crypt::decrypt(Session::get('TKN_NIT'));
        $client = new Client();
        $res = $client->request('POST', $this->url . 'pel/u/validate', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                ],
                'form_params' => [
                    'nit' => $nit,
                    'codigo' => $request->codigo
                ]
            ]);
            
            $r = json_decode($res->getBody());
            if($r->status == 200){
                if ($request->hasFile('file_pdf')){
                    $newpath = $this->path . $nit . "/" . $r->data->idInscripcion;
                    $name = strtolower('inscripcion.'.$request->file('file_pdf')->getClientOriginalExtension());
                    $type = $request->file('file_pdf')->getClientMimeType();
                    $request->file('file_pdf')->move($newpath, $name);
                }
                return redirect()->route('register_password')->with(['step4'=>true, 'codigo'=>$request->codigo]);
            }else{
                return redirect()->back()->with(['step3'=>true, 'codigo' => $request->codigo ])->withErrors(['errors' => 'El código de confirmación no es válido'])->withInput();
            }
    }

    public function getRegisterPassword(){
        if(!Session::has("step4")) return redirect()->route('pre_register');
        $data = ['title'            => ''
                ,'subtitle'         => ''
                ,'codigo'           => Session::get('codigo')];

        return view('register.password',$data);
    }

    public function postRegisterPassword(Request $request){
        $rules = [
                'codigo'   =>  'required|min:6|max:6',
                'password'   =>  'required|min:8|max:200|confirmed',
            ];

            $v = Validator::make($request->all(), $rules);
        if($v->fails()){
            $msg = "<ul>";
            foreach ($v->messages()->all() as $err) {
                $msg .= "<li>$err</li>";
             }
            $msg .= "</ul>";
            return redirect()->back()->with(['step4'=>true, 'codigo' => $request->codigo ])->withErrors($msg)->withInput();
        }
        // Post Odin
        $nit = Crypt::decrypt(Session::get('TKN_NIT'));
        $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/u/migrate', [
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                                ],
                    'form_params' => [
                            'nit' => $nit,
                            'codigo' => $request->codigo,
                            'password' => $request->password,
                         ]
                     ]);
            $r = json_decode($res->getBody());
            if($r->status == 200){
                $ua = $this->getBrowser();
                $navegador = $ua['name'];
                return $this->doLogin($this->url, $nit, $request->password, $navegador, $this->getIp());
                //return redirect()->route('doLogin');
            }else{
                return redirect()->back()->with(['step4'=>true, 'codigo' => $request->codigo ])->withErrors(['errors' => 'No se pudo procesar tu registro, intenta nuevamente'])->withInput();
            }
    }

    public function getComboboxMunicipiosAJAX(Request $request)
    {
        $result = "";
        foreach (Municipios::getList($request->deparamento) as $key => $value) {
            $result .= "<option value='$key'>$value</option>";
        }
        return $result;
    }

    public function getComboboxDepartamentosAJAX(Request $request)
    {
        $result = "";
        foreach (Departamentos::getListByMunicipio($request->deparamento) as $key => $value) {
            $result .= "<option value='$key'>$value</option>";
        }
        return $result;
    }

    public function getComboboxRamasAJAX(Request $request)
    {
        $result = "";
        foreach (Rama::getList($request->deparamento) as $key => $value) {
            $result .= "<option value='$key'>$value</option>";
        }
        return $result;
    }

    public function getPDF($inscripcion){
        $pdf = \App::make('dompdf.wrapper');
        $view = view('register.pdf',array('inscripcion'=>$inscripcion))->render();
        $pdf->loadHTML($view);
        return $pdf->output();
    }

    public function getRegisterForm(Request $request){
        // Verificamos que haya pasado por el paso anterior.
        if(!Session::has("step2")) return redirect()->route('pre_register');

        if(Session::has("info")){
            $data = ['title'        => ''
                ,'subtitle'         => ''
                ,'enabled'          => false
                ,'access'           => md5('update')
                ,'info'             => Session::get("info")];
        }else{
            $access = Session::has('access') ? Session::get('access') : md5('create');
            $enabled = Session::has('nit') ? true : false;
            $data = ['title'        => ''
                ,'subtitle'         => ''
                ,'enabled'          => $enabled
                ,'access'           => $access
                ,'nit'              => Session::get("nit")
                ,'info'             => []];
        }

        if(Session::has("info")){
            $idDepartamento = Municipios::getIdDepartamento(Session::get("info")->idMunicipio);
        }else if(Session('_old_input')){
            $idDepartamento = Session('_old_input')['txtDepartamento'];
        }else{
            $idDepartamento = 1;
        }
        $data['idDepartamento'] = $idDepartamento;
        $data['listaDept'] = Departamentos::getList();
        $data['listJuntas'] = Junta::getList();
        $data['listRamas'] = Rama::getList("P01");
        $data['listaMun'] = Municipios::getList($idDepartamento);
        $data['listaTratamientos'] = Tratamientos::All()->pluck('nombreTratamiento','idTipoTratamiento');

        return view('register.register', $data);
    }

    public function postRegisterForm(Request $request){
        $rules = [
                //'captcha' => ['required', 'captcha'],
                'access' => 'required'
        ];
        $messages =  [
            'access.required' => 'Debe ingresar los nombres',
            //'captcha' => 'El CAPTCHA ingresado es incorrecto'
        ];
        $v = Validator::make($request->all(), $rules, $messages);

        if ($v->fails()){
            $errors = $v->messages()->all();
            return redirect()->back()->with(['step2'=>true, 'nit' => $request->nit ,'access' => $request->access ])->withErrors($errors)->withInput();
        }

        /*
            La variable access almacena el tipo de acción a realizar
                create = no se poseen datos del usuario
                update = el usuario posee registro como persona natural
        */

        $ua = $this->getBrowser();
        $navegador = $ua['name'] . "::" . $ua['version'] . "::" .$ua['platform'];

        if($request->access == md5('create')){
            $rules = [
                    'nombres' => 'required|max:150',
                    'apellidos' => 'required|max:150',
                    'nit' => 'required|min:17|max:17',
                    'dui' => 'required|min:10|max:10',
                    'telefono_fijo' => 'required|min:8|max:9',
                    'telefono_celular' => 'sometimes|min:8|max:9',
                    'id_tipo_tratamiento' => 'required',
                    'id_municipio' => 'required|max:5',
                    'genero' => 'required|in:M,F|max:1',  
                    'direccion' => 'required|max:250',
                    'mail' => 'required|email',
                    'jvp_photo' => 'sometimes|mimes:jpeg,png,pdf,jpg',
                    'dui_photo' => 'sometimes|mimes:jpeg,png,pdf,jpg',
                    'nit_photo' => 'sometimes|mimes:jpeg,png,pdf,jpg'
            ];
            $messages =  [
                'nombres.required' => 'Debe ingresar los nombres',
                'apellidos.required' => 'Debe ingresar los apellidos',
                'id_municipio.required' => 'Debe seleccionar el municipio',
                'id_tipo_tratamiento.required' => 'Debe seleccionar el tipo de tratamiento',
                'nit.required' => 'Debe ingresar el NIT',
                'dui.required' => 'Debe ingresar el DUI',
                'telefono_fijo.required' => 'Debe ingresar un número de teléfono fijo',
                'telefono_celular.required' => 'Debe ingresar un número de teléfono celular',
                //'captcha' => 'El CAPTCHA ingresado es incorrecto'
            ];
            $v = Validator::make($request->all(), $rules, $messages);

            if ($v->fails()){
                $errors = $v->messages()->all();
                return redirect()->back()->with(['step2'=>true, 'nit' => $request->nit, 'access' => md5('create') ])->withErrors($errors)->withInput();
            }

            /* 
                En la variable TKN_NIT se almacena el NIT que el usuario ingresó en el primer paso del formulario
                dado a que el formulario posee un paso extra y los datos se encuentran en la vista, se verifica que el NIT que proviene 
                del segundo paso, sea el mismo NIT que se ingresó al inicio del formulario.

                De igual forma con la variable TKN_ACCESS, la acción debe coincidir con la asignada al inicio del registro
            */
            if(!Session::has('TKN_NIT') || !Session::has('TKN_ACCESS')){
                return redirect()->route('pre_register')->withErrors(['errors' => 'No se pudo procesar tu solicitud, probablemente la sesión expiró']);
            }

            $nit = Crypt::decrypt(Session::get('TKN_NIT'));
            $access = Crypt::decrypt(Session::get('TKN_ACCESS'));

            if($nit !== $request->nit || $access !== 'create'){
                return redirect()->route('pre_register')->withErrors(['errors' => 'No se pudo procesar tu solicitud, probablemente la sesión expiró']);
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/u/create', [
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                                ],
                    'form_params' => [
                            'nombres' => $request->nombres,
                            'apellidos' => $request->apellidos,
                            'nit' => $request->nit,
                            'dui' => $request->dui,
                            'telefono_fijo' => $request->telefono_fijo,
                            'telefono_celular' => $request->telefono_celular,
                            'id_tipo_tratamiento' => $request->id_tipo_tratamiento,
                            'id_municipio' => $request->id_municipio,
                            'genero' => $request->genero,
                            'direccion' => $request->direccion,
                            'mail' => $request->mail,
                            'jvp' => $request->jvp,
                            'ipRemote' => $request->ipRemote,
                            'navegador' => $navegador,
                            'id_rama' => $request->id_rama,
                            'id_junta' => $request->id_junta,
                            'access' => 'create'
                         ]
                     ]);
            $r = json_decode($res->getBody());
            if($r->status == 200){
                // Subir archivos
                $filesystem = new Filesystem();
                if($filesystem->exists($this->path)) {
                    if ($filesystem->isWritable($this->path)) {
                        $newpath = $this->path . $request->nit . "/" . $r->data->idInscripcion;
                        if(!$filesystem->isDirectory($newpath)){
                            File::makeDirectory($newpath, 0777, true, true);
                        }
                        if ($request->hasFile('nit_photo')){
                            $name = strtolower('nit.'.$request->file('nit_photo')->getClientOriginalExtension());
                            $type = $request->file('nit_photo')->getClientMimeType();
                            $request->file('nit_photo')->move($newpath, $name);
                        }
                        if ($request->hasFile('dui_photo')){
                            $name = strtolower('dui.'.$request->file('dui_photo')->getClientOriginalExtension());
                            $type = $request->file('dui_photo')->getClientMimeType();
                            $request->file('dui_photo')->move($newpath, $name);
                        }
                        if ($request->hasFile('jvp_photo')){
                            $name = strtolower('carnet.'.$request->file('jvp_photo')->getClientOriginalExtension());
                            $type = $request->file('jvp_photo')->getClientMimeType();
                            $request->file('jvp_photo')->move($newpath, $name);
                        }
                    }
                }
                $correo = $request->mail;
                \Mail::send('emails.confirm', array("codigo"=>$r->data->codigoVerificacion), function($msj) use ($correo, $r){
                    $msj->subject('Confirmación de registro Portal en Línea - DNM');
                    $msj->attachData($this->getPDF($r->data), 'Inscripcion.pdf');
                    $msj->to($correo);
                    //$msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
                });

                return redirect()->route('register_finish')->with(['step3'=>true,'correo'=>$correo]);
            } else if($r->status == 403){
                return redirect()->back()->with(['step2'=>true, 'nit' => $request->nit, 'access' => md5('create') ])->withErrors(['errors' => $r->message])->withInput();
            }
        }

        if($request->access == md5('update')){

            $rules = [
                    'nit' => 'required|min:17|max:17',
                    'telefono_fijo' => 'required|min:8|max:9',
                    'telefono_celular' => 'sometimes|min:8|max:9',
                    'id_tipo_tratamiento' => 'required|max:5',
                    'id_municipio' => 'required|max:5',
                    'genero' => 'required|in:M,F|max:1',  
                    'direccion' => 'required|max:250',
                    'mail' => 'required|email',
                    'access' => 'required',
                    'jvp_photo' => 'sometimes|mimes:jpeg,png,pdf,jpg',
                    'dui_photo' => 'sometimes|mimes:jpeg,png,pdf,jpg',
                    'nit_photo' => 'sometimes|mimes:jpeg,png,pdf,jpg'
            ];
            $messages =  [
                'id_municipio.required' => 'Debe seleccionar el municipio',
                'id_tipo_tratamiento.required' => 'Debe seleccionar el tipo de tratamiento',
                'nit.required' => 'Debe ingresar el NIT',
                'telefono_fijo.required' => 'Debe ingresar un número de teléfono fijo',
                'telefono_celular.required' => 'Debe ingresar un número de teléfono celular',
            ];
            $v = Validator::make($request->all(), $rules, $messages);

            if ($v->fails()){
                $client = new Client();
                $res = $client->request('POST', $this->url . 'pel/u/info', [
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                                ],
                    'form_params' => [
                             'nit' => $request->nit
                         ]
                     ]);
                $r = json_decode($res->getBody());
                $errors = $v->messages()->all();
                return redirect()->back()->with(['step2'=>true, 'info'=>$r->data ,'nit' => $request->nit, 'access' => md5('update') ])->withErrors($errors)->withInput();
            }

            if(!Session::has('TKN_NIT') || !Session::has('TKN_ACCESS')){
                return redirect()->route('pre_register')->withErrors(['errors' => 'No se pudo procesar tu solicitud, probablemente la sesión expiró']);
            }

            $nit = Crypt::decrypt(Session::get('TKN_NIT'));
            $access = Crypt::decrypt(Session::get('TKN_ACCESS'));

            if($nit !== $request->nit || $access !== 'update'){
                return redirect()->route('pre_register')->withErrors(['errors' => 'No se pudo procesar tu solicitud, probablemente la sesión expiró']);
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/u/create', [
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                                ],
                    'form_params' => [
                            'nit' => $request->nit,
                            'telefono_fijo' => $request->telefono_fijo,
                            'telefono_celular' => $request->telefono_celular,
                            'id_tipo_tratamiento' => $request->id_tipo_tratamiento,
                            'id_municipio' => $request->id_municipio,
                            'genero' => $request->genero,
                            'direccion' => $request->direccion,
                            'mail' => $request->mail,
                            'jvp' => $request->jvp,
                            'ipRemote' => $request->ipRemote,
                            'navegador' => $navegador,
                            'id_rama' => $request->id_rama,
                            'id_junta' => $request->id_junta,
                            'access' => 'update'
                         ]
                     ]);
            $r = json_decode($res->getBody());
            if($r->status == 200){
                // subir archivos
                $filesystem = new Filesystem();
                if($filesystem->exists($this->path)) {
                    if ($filesystem->isWritable($this->path)) {
                        $newpath = $this->path . $request->nit . "/". $r->data->idInscripcion;
                        if(!$filesystem->isDirectory($newpath)){
                            File::makeDirectory($newpath, 0777, true, true);
                        }
                        if ($request->hasFile('jvp_photo')){
                            $name = strtolower('carnet.'.$request->file('jvp_photo')->getClientOriginalExtension());
                            $type = $request->file('jvp_photo')->getClientMimeType();
                            $request->file('jvp_photo')->move($newpath, $name);
                        }
                    }
                }
                $correo = $request->mail;
                \Mail::send('emails.confirm', array("codigo"=>$r->data->codigoVerificacion), function($msj) use ($correo, $r){
                    $msj->subject('Confirmación de registro Portal en Línea - DNM');
                    $msj->attachData($this->getPDF($r->data), 'Inscripcion.pdf');
                    $msj->to($correo);
                    //$msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
                });

                return redirect()->route('register_finish')->with(['step3'=>true,'correo'=>$correo]);
            } else if($r->status == 403){
                return redirect()->back()->with(['step2'=>true, 'nit' => $request->nit, 'access' => md5('update') ])->withErrors(['errors' => $r->message])->withInput();
            }
          
          
        }
        return redirect()->back()->with(['step2'=>true, 'nit' => $request->nit ])->withErrors(['errors' => 'No se pudo procesar tu solicitud'])->withInput();
    }

    public function postRegister(Request $request){
        try {
            $rules = [
                'captcha' => ['required', 'captcha'],
                'username' => 'required|min:17|max:17'
            ];
            $messages =  [
                'username.required' => 'Debe ingresar el NIT',
                'captcha' => 'El CAPTCHA ingresado es incorrecto'
            ];
            $v = Validator::make($request->all(), $rules, $messages);
            if ($v->fails()){
                $errors = $v->messages()->all();
                return redirect()->route('pre_register')->withErrors($errors);
            }

            // Vallidar NIT 
           /* if(!$this->validarNIT($request->username)){
                return redirect()->route('pre_register')->withErrors(['errors' => 'Formato de NIT incorrecto']);
            } */
            // Verificamos que no sea ingresado el NIT 0000-000000-000-0
            if($request->username == "0000-000000-000-0"){
                return redirect()->route('pre_register')->withErrors(['errors' => 'Formato de NIT incorrecto']);
            }

            // Si la validación pasó...
           
            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/u/info', [
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                            ],
                'form_params' => [
                         'nit' => $request->username
                     ]
                 ]);
            $r = json_decode($res->getBody());

            if ($r->status == 200){
                if($r->access->code == 2){
                    return redirect()->route('doLogin')->withErrors(['errors' => 'Tu NIT ya está registrado como usuario. Por favor Inicia Sesión']);
                }
                if($r->access->code == 3){
                    $encryptedNIT = Crypt::encrypt($r->data->nitNatural);
                    $encryptedACCESS = Crypt::encrypt('update');
                    Session::put('TKN_NIT', $encryptedNIT);
                    Session::put('TKN_ACCESS', $encryptedACCESS);
                    return redirect()->route('register')->with(['step2'=>true, 'info' => $r->data]);
                }
                if($r->access->code == 1){
                    if($this->validarNIT($request->username)){
                        $encryptedNIT = Crypt::encrypt($request->username);
                        $encryptedACCESS = Crypt::encrypt('create');
                        Session::put('TKN_NIT', $encryptedNIT);
                        Session::put('TKN_ACCESS', $encryptedACCESS);
                        return redirect()->route('register')->with(['step2'=>true, 'nit'=> $request->username ]);
                    }else{
                        return redirect()->route('pre_register')->withErrors(['errors' => 'Formato de NIT incorrecto']);
                    }
                }
            }

            // En caso de no cumplir ninguna condición...
            return view('errors.500', ['error'=>'No fue posible realizar la acción solicitada, por favor intentelo nuevamente']);

        }catch(\Exception $e){ 
            Log::error($e);
            return view('errors.500', ['error'=>'No fue posible realizar la acción solicitada, por favor intentelo nuevamente']);
        }
    }

    protected function validarNIT($cadena){
        $calculo = 0;
        $digitos = (int)(substr($cadena, 12, 15));
        $resultado = 0;
        if ( $digitos <= 100 ) {
            for ( $posicion = 0; $posicion <= 14; $posicion++ ) {
                if ( !( $posicion == 4 || $posicion == 11 ) ){
                    $calculo += ( 14 *  (int)( $cadena[$posicion] ) );
                    //calculo += ((15 - posicion) * (int)(cadena.charAt(posicion)));
                }
                $calculo = $calculo % 11;
            }
        } else {
            $n = 1;
            for ( $posicion = 0; $posicion <= 14; $posicion++ ){
                if ( !( $posicion == 4 || $posicion == 11 ) ){
                    $calculo = (int)( $calculo + ( ( (int)( $cadena[$posicion] ) ) ) * ( ( 3 + 6 * floor( abs( ( $n + 4) / 6 ) ) ) - $n ) );
                    $n++;
                }
            }
            $calculo = $calculo % 11;
            if ( $calculo > 1 ){
                $calculo = 11 - $calculo;
            } else {
                $calculo = 0;
            }
        }
        return ($calculo == ( (int)( $cadena[16] ) ) ) ? true : false; 
    }

}
