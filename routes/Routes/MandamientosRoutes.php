<?php
Route::group(['prefix' => 'mandamientos','middleware' => ['has_session']], function(){
	/*
			Ruta para proceso 
	*/
	Route::group(['prefix' => 'pagosVarios'], function(){
	  Route::get('/',[
			'as' => 'ver.pagos.varios',
			'uses' => 'Mandamientos\PagosVariosController@index'
	 	]);

	   Route::get('/lista',[
    			'as' => 'get.rows.pagosVarios',
     			'uses' => 'Mandamientos\PagosVariosController@lista'
     	]);
       Route::post('/generar',[
    			'as' 	=> 'store.mandamiento.pagosvarios',
    			'uses' 	=> 'Mandamientos\PagosVariosController@store'
    	 ]);

	});//CIERRE DE PAGOS VARIOS

	Route::group(['prefix' => 'pagoCosmeticos'], function(){
	  Route::get('/',[
			'as' => 'ver.pagos.cosmeticos',
			'uses' => 'Mandamientos\PagosCosmeticosController@index'
	 	]);

         Route::post('/generar',[
    			'as' 	=> 'store.mandamiento.pagoscosmeticos',
    			'uses' 	=> 'Mandamientos\PagosCosmeticosController@store'
    	 	]);

	});//CIERRE DE PAGO COSMETICOS

     Route::group(['prefix' => 'pagoHigienicos'], function(){
	  Route::get('/',[
			'as' => 'ver.pagos.higienicos',
			'uses' => 'Mandamientos\PagosHigienicosController@index'
	 	]);

         Route::post('/generar',[
    			'as' 	=> 'store.mandamiento.pagoshigienicos',
    			'uses' 	=> 'Mandamientos\PagosHigienicosController@store'
    	 	]);

	});//CIERRE DE PAGO higiénicos

      Route::group(['prefix' => 'pagoInsumoMedico'], function(){
	  Route::get('/',[
			'as' => 'ver.pagos.medico',
			'uses' => 'Mandamientos\PagosMedicosController@index'
	 	]);

         Route::post('/generar',[
    			'as' 	=> 'store.mandamiento.pagosmedicos',
    			'uses' 	=> 'Mandamientos\PagosMedicosController@store'
    	 	]);

	});//CIERRE DE PAGO higiénicos
});