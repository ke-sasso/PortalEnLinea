<?php
Route::group(['prefix' => 'Registro','middleware' => ['has_session']], function(){

			//Ruta para proceso de establecimientos

	Route::group(['prefix' => 'inicio'], function(){
		/*
			ADMINISTRADOR
        */
		//vista index nueva solicitud
		Route::get('/nuevaSolicitud',[
			'as' => 'nueva.solicitud',
			'uses' => 'Registro\RegistroVController@index'
	 	]);

		//vista index de los productos
		Route::get('/dt-row-data-prod',[
			'as' => 'dt.data.prod',
			'uses' => 'Registro\RegistroVController@getDataRowsProductos'
	 	]);

	 	//vista index de los productos
		Route::get('/dt-row-data-solc',[
			'as' => 'dt.data.solicitudes.c',
			'as' => 'dt.data.solicitudes.c',
			'uses' => 'Registro\RegistroVController@getDataRowsSolCertificadas'
	 	]);

	 	//vista index de los productos
		Route::get('/dt-row-data-sol-usuario',[
			'as' => 'dt.data.solicitudes.usuario',
			'as' => 'dt.data.solicitudes.usuario',
			'uses' => 'Registro\RegistroVController@getSolicitudesRvUsuario'
	 	]);

	 	//vista de los nombres de los exportadores
		Route::post('/getExportaciones',[
			'as' => 'get.export',
			'uses' => 'Registro\RegistroVController@getDataRowsExport'
	 	]);

	 	//vista de los paises
		Route::get('/getPaises',[
			'as' => 'get.paises',
			'uses' => 'Registro\RegistroVController@getPaises'
	 	]);


		Route::get('/getFamilias',[
			'as' => 'get.familias',
			'uses' => 'Registro\RegistroVController@getFamiliasFormas'
	 	]);

	 	Route::post('/getFormasFar',[
			'as' => 'get.formas.far',
			'uses' => 'Registro\RegistroVController@getFormas'
	 	]);

		Route::get('/getAllEmpaques',[
			'as' => 'get.all.empaques',
			'uses' => 'Registro\RegistroVController@getAllEmpaques'
	 	]);


	 	Route::post('/getEmpaques',[
			'as' => 'get.empaques',
			'uses' => 'Registro\RegistroVController@getEmpaques'
	 	]);

	 	Route::post('/getContenidosByProd',[
			'as' => 'get.contenidos.prod',
			'uses' => 'Registro\RegistroVController@getContenidosByProd'
	 	]);

	 	Route::get('/getContenidos',[
			'as' => 'get.contenidos',
			'uses' => 'Registro\RegistroVController@getContenidos'
	 	]);

	 	Route::get('/verSolicitud/{idSolicitud}',[
			'as' => 'get.solicitud.post',
			'uses' => 'Registro\RegistroVController@solicitudesPost'
	 	]);

	 	//vista de los fabricantes por productos
		Route::post('/getFabricantes',[
			'as' => 'get.fabricantes',
			'uses' => 'Registro\RegistroVController@getFabricantes'
	 	]);

	 	Route::post('/getFormas',[
			'as' => 'get.formas',
			'uses' => 'Registro\RegistroVController@getForma'
	 	]);

	 	//vista de los presentaciones por productos
		Route::post('/getPresetaciones',[
			'as' => 'get.presentaciones',
			'uses' => 'Registro\RegistroVController@getDataRowsPresentaciones'
	 	]);

		//vista de los laboratorios acondicionadores por productos
		Route::post('/getLabs',[
			'as' => 'get.labs',
			'uses' => 'Registro\RegistroVController@getDataRowsLabs'
	 	]);

		//vista index de los productos
		Route::post('/verificar-mandamiento',[
			'as' => 'verificar-mandamiento',
			'uses' => 'Registro\RegistroVController@verificarMandamiento'
	 	]);

		//verificacion de la solicitud
		Route::post('/confirmacion-solicitud',[
			'as' => 'confi.solicitud.rv',
			'uses' => 'Registro\RegistroVController@confirmacion'
	 	]);

	 	//guardar la solicitud
		Route::post('/guardar-solicitud',[
			'as' => 'guardar.solicitud.rv',
			'uses' => 'Registro\RegistroVController@store'
	 	]);

	 	//vista ver solicitudes
		Route::get('/solicitudes-rv',[
			'as' => 'ver.solicitudes.rv',
			'uses' => 'Registro\RegistroVController@verSolicitudes'
	 	]);

	 	//vista ver solicitudes admin
		Route::get('/solicitudes-rv-admin',[
			'as' => 'ver.solicitudes.rv.admin',
			'uses' => 'Registro\RegistroVController@verSolicitudesAdmin'
	 	]);

	 	//vista ver solicitudes pre
		Route::get('/solicitudes-rv-pre',[
			'as' => 'ver.solicitudes.rv.pre',
			'uses' => 'Registro\RegistroVController@showSolicitudesPreByUser'
	 	]);

		//vista index de los solicitudes pre
		Route::get('/dt-row-data-sol-pre',[
			'as' => 'dt.data.solicitudes.pre',
			'uses' => 'Registro\RegistroVController@getSolicitudesRvPreUsuario'
	 	]);

	 	//imprimir resolucion
		Route::get('/imprimir-rv/{idSolicitud}/resolucion/{idTramite}',[
			'as' => 'imprimir.rv',
			'uses' => 'PdfController@ResolucionRV'
	 	]);

	 	Route::get('/download/{idSolicitudDoc}',[
   	 			'as' => 'download.file',
    			'uses' => 'Registro\RegistroVController@download'
    	]);
	});

	Route::group(['prefix' => 'pdfs'], function(){

		Route::get('/declaracionJurada/{idSolicitud}/Rv/{idTramite}',[
			'as' => 'declaracion.jurada',
			'uses' => 'PdfController@declaracionJuradaRv'
	 	]);

	 	Route::get('/comprobante/{idSolicitud}/ingreso/{idTramite}',[
			'as' => 'comprobante.ingreso.rv',
			'uses' => 'PdfController@comprobanteIngresoRv'
	 	]);
	});

	Route::group(['prefix' => 'subsanacion'], function(){

		Route::get('/subsanarSol/{idSolicitud}/{idTramite}',[
			'as' => 'subsanar.solicitud.post',
			'uses' => 'Registro\SubsanacionController@getSolicitudObservada'
	 	]);

	 	Route::post('/subsanarSolicitud',[
			'as' => 'subsanacion.solicitud.post',
			'uses' => 'Registro\SubsanacionController@subsanarSolicitud'
	 	]);
	});


    //Validar fechas productos
    Route::get('/validarProducto/{idProducto}',[
        'as' => 'validarProducto',
        'uses' => 'Registro\RegistroVController@validarFechasProducto'
    ]);

    //vista de los fabricantes por productos
    Route::get('/getFabricantes/{idProducto}',[
        'as' => 'fabricantes.prod',
        'uses' => 'Registro\RegistroVController@getFabricantesByProd'
    ]);

});
