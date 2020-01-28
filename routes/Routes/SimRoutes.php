<?php
Route::group(['prefix' => 'Insumos','middleware' => ['has_session']], function(){
	/*
			Ruta para proceso de establecimientos
	*/
	Route::group(['prefix' => 'inicio'], function(){
		/*
			ADMINISTRADOR
		*/
		//vista index de los productos
		Route::get('/',[
			'as' => 'indexsim',
			'uses' => 'InsumosController@index'
	 	]);
		//vista index de los productos
		Route::get('/dt-row-data-prod-sim',[
			'as' => 'dt.data.prod.sim',
			'uses' => 'InsumosController@getDataProdSim'
	 	]);

		//verificacion de la solicitud
		Route::post('/confirmacion-solicitud-sim',[
			'as' => 'confi.solicitud.sim',
			'uses' => 'InsumosController@confirmacion'
	 	]);

		//verificar mandamiento
		Route::post('/verificar-mandamiento-sim',[
			'as' => 'verificar-mandamiento-sim',
			'uses' => 'InsumosController@verificarMandamiento'
	 	]);

	 	//consultar PIM
		Route::post('/consultar-pim',[
			'as' => 'get-pim',
			'uses' => 'InsumosController@getPIM'
	 	]);

		//guardar la solicitud
		Route::post('/guardar-solicitud-sim',[
			'as' => 'guardar.solicitud.sim',
			'uses' => 'InsumosController@storeSolicitud'
	 	]);

	 	//imprimir resolucion
		Route::get('/imprimir-sim/{idSolicitud}/resolucion/{idTramite}',[
			'as' => 'imprimir.sim',
			'uses' => 'PdfController@resolucionSim'
	 	]);

	 	//imprimir desistimiento
		Route::get('/desistimiento-sim/{idSolicitud}/{idTramite}',[
			'as' => 'desistimiento.sim',
			'uses' => 'PdfController@desistimientoSolicitud'
	 	]);


	 	//vista ver solicitudes
		Route::get('/solicitudes-sim',[
			'as' => 'ver.solicitudes.sim',
			'uses' => 'InsumosController@verSolicitudes'
	 	]);

	 	Route::get('/dt-row-data-solc-sim',[
			'as' => 'dt.data.solicitudes.c.sim',
			'uses' => 'InsumosController@getDataRowsSolCertificadas'
	 	]);

	 	//vista de solicitudes pre
	 	Route::get('/solicitudes-pre',[
			'as' => 'ver.solicitudes.pre',
			'uses' => 'InsumosController@verSolicitudesPre'
	 	]);

	 	Route::get('/dt-row-data-sim-pre',[
			'as' => 'dt.data.solicitudes.sim.pre',
			'uses' => 'InsumosController@getSolicitudesPreDt'
	 	]);

	 	//vista de solicitudes post
	 	Route::get('/solicitudes-post',[
			'as' => 'ver.solicitudes.post',
			'uses' => 'InsumosController@verSolicitudesPost'
	 	]);

	 	Route::get('/dt-row-data-sim-post',[
			'as' => 'dt.data.solicitudes.sim.post',
			'uses' => 'InsumosController@getSolicitudesPostDt'
	 	]);

	 	Route::post('/getProductoSim',[
			'as' => 'get.producto.sim',
			'uses' => 'InsumosController@getProducto'
	 	]);

	 	Route::post('/getModelosByIm',[
			'as' => 'get.modelos.sim',
			'uses' => 'InsumosController@getModelosByIM'
	 	]);
	 	//datatable de codigos y modelo segun producto
	 	Route::get('/getCodsModsByIm',[
			'as' => 'get.cods.modelos.sim',
			'uses' => 'InsumosController@getCodModByIM'
	 	]);

	 	Route::post('/getFabribyProd',[
			'as' => 'get.fabricantes.sim.prod',
			'uses' => 'InsumosController@getFabricantesByProducto'
	 	]);

	 	Route::post('/cargarExcel',[
			'as' => 'cargar.excel.codmods',
			'uses' => 'InsumosController@cargarExcel'
	 	]);

	});

	Route::group(['prefix' => 'pdfs'], function(){
		//imprimir comprobante de solicitud
		Route::get('/comprobante-sim/{idSolicitud}',[
			'as' => 'comprobante.sim',
			'uses' => 'PdfController@comprobanteIngresoSim'
	 	]);


	});
});
