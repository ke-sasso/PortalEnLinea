<?php
Route::group(['prefix' => 'Recetas','middleware' => ['has_session']], function(){



		Route::get('/talonario',[
			'as' => 'index.talonario',
			'uses' => 'RecetasController@talonario'
	 	]);

	 	Route::post('/guardar/informacion',[
			'as' => 'store.talonario',
			'uses' => 'RecetasController@storeTalonario'
	 	]);

	 	Route::get('/lista',[
			'as' => 'ver.lista.talonario',
			'uses' => 'RecetasController@lista'
	 	]);

        Route::get('/lista/talonarios',[
			'as' => 'get.lista.rows.talonario',
			'uses' => 'RecetasController@listaTalonario'
	 	]);

	 	Route::get('/home',[
			'as' => 'index.recetas',
			'uses' => 'RecetasController@homeRecetas'
	 	]);

	 		Route::get('/nueva',[
			'as' => 'ingresar.receta',
			'uses' => 'RecetasController@nuevaReceta'
	 	]);

         Route::post('/validar/recetas/store',[
			'as' 	=> 'validar.mandamiento.recetas',
			'uses' 	=> 'RecetasController@verificarMandamiento'
	 	]);

         Route::post('/consultar/persona',[
			'as' 	=> 'consultar.persona',
			'uses' 	=> 'RecetasController@consultarPersona'
	 	]);

         Route::post('/paciente/store',[
			'as' 	=> 'store.paciente',
			'uses' 	=> 'RecetasController@storePaciente'
	 	]);

          Route::post('/store/receta/nueva',[
			'as' 	=> 'guardar.receta',
			'uses' 	=> 'RecetasController@storeReceta'
	 	]);


         Route::get('/dt-row-data-pp/productos/recetas',[
    			'as' 	=> 'dt.row.data.productos.recetas',
     			'uses' 	=> 'RecetasController@getProductosReceta'
     	 	]);



        Route::get('/historial',[
			'as' => 'ver.historial.recetas',
			'uses' => 'RecetasController@historialRecetas'
	 	]);

 		Route::get('/lista/historial/get',[
			'as' => 'get.lista.rows.historial.recetas',
			'uses' => 'RecetasController@listaHistorial'
	 	]);


		Route::get('/editar/{idReceta}',[
			'as' => 'editar.receta',
			'uses' => 'RecetasController@editarReceta'
	 	]);

	 	Route::post('/store/receta/editar',[
			'as' 	=> 'store.editar.receta',
			'uses' 	=> 'RecetasController@storeEditarReceta'
	 	]);

	 	Route::get('/ver/detalle/{idReceta}',[
			'as' => 'ver.detalle.receta',
			'uses' => 'RecetasController@verDetalle'
	 	]);

	 	Route::post('/receta/anular',[
			'as' 	=> 'receta.anular',
			'uses' 	=> 'RecetasController@anularReceta'
	 	]);

		Route::get('generar-mandamiento',[
		'as' => 'recetas.generar.mandamiento',
		'uses' => 'Recetas\MandamientoController@index'
		]);

		Route::post('generar-mandamiento/store',[
		'as' => 'recetas.generar.mandamiento.store',
		'uses' => 'Recetas\MandamientoController@store'
		]);

	 	Route::any('/productos', [
	 		'as' => 'productos.list',
	 		'uses' => 'RecetasController@vwConsultarProductos'
	 	]);

	 	Route::get('municipios/{id}', [
	 		'as' => 'list.municipios.departamento',
	 		'uses' => 'RecetasController@getMunicipios'
	 	]);

	 	Route::any('/dataInfo',[
	 		'as' => 'get.data.info',
	 		'uses' => 'RecetasController@getDataFromInfo'
	 	]);

	 	Route::any('/getMap',['as' => 'get.mapa.farmacias','uses' => 'RecetasController@getMapaFarmacias']);


	 	Route::post('consulta/produco/paciente',[
		'as' => 'consulta.producto.paciente',
		'uses' => 'RecetasController@consultarProPaciente'
		]);

		Route::get('/lista/productos/paciente',[
			'as' => 'get.rows.productos.paciente',
			'uses' => 'RecetasController@getProductosPaciente'
	 	]);

	 	Route::post('verificar/produco/paciente',[
		'as' => 'verificar.producto.paciente',
		'uses' => 'RecetasController@verificarPro'
		]);


		Route::get('/comprobante-pdf/{idReceta}/{idEstado}',[
			'as' => 'get.pdf.comprobante.receta',
			'uses' => 'RecetasController@comprobanteReceta'
	 	]);





});
