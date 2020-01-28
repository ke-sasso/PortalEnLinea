<?php
Route::group(['prefix' => 'api' , 'middleware' => ['verifyacceskey']], function(){

	Route::group(['prefix' => 'resol'], function(){
	 	Route::get('/sim/{idSolicitud}/resolucion/{idTramite}',[
	 		'as' => 'imprimir.sim',
			'uses' => 'ApiController@resolucionSim'
	 	]);
	});
});