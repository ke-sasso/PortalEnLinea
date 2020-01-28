<?php
Route::group(['prefix' => 'vacunas','middleware' => ['has_session']], function(){
	/*
			Ruta para proceso 
	*/
	Route::group(['prefix' => 'inicio'], function(){
		/*
			ADMINISTRADOR
		*/
		//vista index de los productos
		
		/*Route::get('/',[
			'as' => 'indexsim',
			'uses' => 'InsumosController@index'
	 	]);	
		*/		

		//vista ver solicitudes
		Route::get('/solicitudes-vacunas',[
			'as' => 'ver.solicitudes.vacunas',
			'uses' => 'vacunas\vacunasController@verSolicitudes'
	 	]);

		Route::get('/solicitudVacunas',[
			'as' => 'nueva.solicitud.vacuna',
			'uses' => 'vacunas\vacunasController@index'
			]);
		Route::post('/solicitudVacunas',[
			'as' => 'nueva.solicitud.vacuna',
			'uses' => 'vacunas\vacunasController@saveSolicitud'
			]);
		//vista index de los productos
		Route::get('/dt-solicitudes-vacunas',[
			'as' => 'dt.data.vacunas.solicitudes',
			'uses' => 'vacunas\vacunasController@getDataSolVacunas'
	 	]);	

		Route::get('/dt-productos-registro',[
			'as' => 'dt.data.productos.registro',
			'uses' => 'vacunas\vacunasController@getProductos'
			]);

		Route::get('/dt-formafarm-registro',[
			'as' => 'dt.data.formafarm.registro',
			'uses' => 'vacunas\vacunasController@getFormaFarm'
			]);

		Route::get('/dt-dosis-registro',[
			'as' => 'dt.data.dosis.registro',
			'uses' => 'vacunas\vacunasController@getDosis'
			]);

		Route::get('/dt-presentaciones-registro',[
			'as' => 'dt.data.presentaciones.registro',
			'uses' => 'vacunas\vacunasController@getPresentaciones'
			]);

		Route::get('/dt-pa-registro',[
			'as' => 'dt.data.pa.registro',
			'uses' => 'vacunas\vacunasController@getPrincipiosActivos'
			]);
			
		Route::get('/dt-atc-registro',[
			'as' => 'dt.data.atc.registro',
			'uses' => 'vacunas\vacunasController@getDataRowATC'
			]);


		Route::get('/dt-establecimientos-registro',[
			'as' => 'dt.data.establecimientos.registro',
			'uses' => 'vacunas\vacunasController@getEstablecimientos'
			]);

		Route::get('/dt-titular-registro/{idProducto}',[
			'as' => 'dt.data.titular.registro',
			'uses' => 'vacunas\vacunasController@getTitularProducto'
			]);

		Route::get('/dt-fabricante-registro',[
			'as' => 'dt.data.fabricante.registro',
			'uses' => 'vacunas\vacunasController@getFabricantesProducto'
			]);
		
		Route::get('/dt-paoms-registro',[
			'as' => 'dt.data.paoms.registro',
			'uses' => 'vacunas\vacunasController@getPAxATC'
			]);

		Route::get('/sinRegistro', [
			'as' => 'form.sin.registro',
			'uses' => 'vacunas\vacunasController@sinRegistro'
			]);

		Route::post('/fecIngresoLote',[
			'as' => 'fecha.ingreso.lote',
			'uses' => 'vacunas\vacunasController@updEstadoSolicitudes'
			]);
	});
});
