<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

foreach (new DirectoryIterator(__DIR__.'/Routes') as $file)
{
    if (!$file->isDot() && !$file->isDir() && $file->getFilename() != '.gitignore')
    {
        require_once __DIR__.'/Routes/'.$file->getFilename();
        //require_once __DIR__.'/Routes/'.$file->getFilename();
    }
}

foreach (new DirectoryIterator(__DIR__.'/Routes/Cosmeticos') as $file)
{
    if (!$file->isDot() && !$file->isDir() && $file->getFilename() != '.gitignore')
    {
        require_once __DIR__.'/Routes/Cosmeticos/'.$file->getFilename();
        //require_once __DIR__.'/Routes/'.$file->getFilename();
    }
}

foreach (new DirectoryIterator(__DIR__.'/Routes/RegistroVisado') as $file)
{
    if (!$file->isDot() && !$file->isDir() && $file->getFilename() != '.gitignore')
    {
        require_once __DIR__.'/Routes/RegistroVisado/'.$file->getFilename();
        //require_once __DIR__.'/Routes/'.$file->getFilename();
    }
}

Route::get('/', ['as' => 'doLogin', 'uses' => 'CustomAuthController@getLogin']);
Route::get('register', ['as' => 'pre_register', 'uses' => 'RegisterController@getRegister']);
Route::get('register/form', ['as' => 'register', 'uses' => 'RegisterController@getRegisterForm']);
Route::get('register/finish', ['as' => 'register_finish', 'uses' => 'RegisterController@getRegisterFinish']);
Route::post('register/finish', ['as' => 'register_finish_post', 'uses' => 'RegisterController@postRegisterFinish']);
Route::get('register/password', ['as' => 'register_password', 'uses' => 'RegisterController@getRegisterPassword']);
Route::post('register/password', ['as' => 'register_password_post', 'uses' => 'RegisterController@postRegisterPassword']);
Route::post('register', ['as' => 'postRegister', 'uses' => 'RegisterController@postRegister']);
Route::post('register/form', ['as' => 'postRegisterForm', 'uses' => 'RegisterController@postRegisterForm']);
Route::post('/login', ['as' => 'loginpost', 'uses' => 'CustomAuthController@postLogin']);
Route::get('/logout', 'CustomAuthController@getLogout');
Route::post('/resetcontraseña',['as' => 'resetpass', 'uses' => 'InicioController@resetpassword']);
Route::get('/resetcontraseña',['as' => 'resetcontraseña', 'uses' => 'InicioController@getresetpass']);
Route::post('/confirmacion',['as' => 'confircodigo', 'uses' => 'InicioController@confirmCodigo']);


Route::group(['prefix' => '/index','middleware' => ['has_session']], function(){
	Route::get('/inicio',['as' => 'doInicio', 'uses' => 'InicioController@index']);
	Route::post('/actualizar',[
		 		'as' => 'actualizar.datos',
				'uses' => 'InicioController@ActualizarDatos'
		 	]);
	Route::get('/cambiocontraseña',['as' => 'cambiocontraseña', 'uses' => 'InicioController@cambiopassword']);
	Route::post('/cambiocontraseña',['as' => 'cambiocontraseapost', 'uses' => 'InicioController@changecontrasea']);
});

Route::get('cfg/menu', 'InicioController@cfgHideMenu');


Route::post('/getMunicipios',[
    'as' => 'get.listMunicipios',
    'uses' => 'RegisterController@getComboboxMunicipiosAJAX'
]);

Route::post('getRamas',[
    'as' => 'get.listRamas',
    'uses' => 'RegisterController@getComboboxRamasAJAX'
]);
