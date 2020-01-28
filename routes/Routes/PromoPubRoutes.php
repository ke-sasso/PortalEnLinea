<?php
Route::group(['prefix' => 'PyP','middleware' => ['has_session']], function(){
	/*
			Ruta para proceso de establecimientos
	*/
	Route::group(['prefix' => 'inicio'], function(){

		//	ADMINISTRADOR

		//vista index de los productos
		Route::get('/',[
			'as' => 'indexpromopub',
			'uses' => 'PromoPubController@index'
	 	]);

	 	Route::get('/solicitud',[
			'as' => 'solicitudext',
			'uses' => 'PromoPubController@solicituext'
	 	]);

		//datatable de todos los insumos
	 	Route::get('/dt-row-data',[
			'as' 	=> 'dt.row.data.productos',
			'uses' 	=> 'PromoPubController@getProductosByEst'
	 	]);

	 	Route::get('/dt-row-data-pp',[
			'as' 	=> 'dt.row.data.productospp',
			'uses' 	=> 'PromoPubController@getProductosByPP'
	 	]);

	 	Route::post('/validar',[
			'as' 	=> 'validar.mandamiento',
			'uses' 	=> 'PromoPubController@ValidarMandamiento'
	 	]);

	 	Route::get('/dt-row-data-soli',[
			'as' 	=> 'dt.row.data.solicitudes',
			'uses' 	=> 'PromoPubController@getDataRowsPublicidadBySearch'
	 	]);

	 	Route::get('/verSolicitudes',[
			'as' 	=> 'ver.solicitudes',
			'uses' 	=> 'PromoPubController@verSolicitudes'
	 	]);

	 	Route::post('/guardar',[
		 		'as' => 'guardar.solicitudes',
				'uses' => 'PromoPubController@store'
		 	]);

	 	Route::post('/guardarExt',[
		 		'as' => 'guardar.solicitudes.ext',
				'uses' => 'PromoPubController@storeExt'
		 	]);

	 	Route::get('/subsanacion/{idSolicitud}',[
		 		'as' => 'subsanar',
				'uses' => 'PromoPubController@subsanacion'
		 	]);

	 	Route::get('/imprimir/{idSolicitud}',[
		 		'as' => 'imprimir.solicitud',
				'uses' => 'PdfController@invoice'
		 	]);

		Route::get('/imprimir/{idSolicitud}/dictamen/{idEstado}',[
		 		'as' => 'imprimir.dictamen',
				'uses' => 'PromoPubController@imprimir'
		 	]);

	});
});
