<?php
Route::group(['prefix' => 'Productos','middleware' => ['has_session']], function(){
	/*
			Ruta para proceso de productos
	*/
	Route::post('/getGenerales',[
			'as' => 'get.generales.prod',
			'uses' => 'Registro\ProductosController@getGenerales'
	 	]);	

	Route::post('/getPropietario',[
			'as' => 'get.propietario.prod',
			'uses' => 'Registro\ProductosController@getPropietario'
	 	]);

	Route::post('/getProfesional',[
			'as' => 'get.profesional.prod',
			'uses' => 'Registro\ProductosController@getProfesional'
	 	]);

	Route::post('/getFabricantes',[
			'as' => 'get.fabricantes.prod',
			'uses' => 'Registro\ProductosController@getFabricantes'
	 	]);

	Route::post('/getDistribuidores',[
			'as' => 'get.dist.prod',
			'uses' => 'Registro\ProductosController@getDistribuidores'
	 	]);

	Route::post('/getFormaFarma',[
			'as' => 'get.formfarma.prod',
			'uses' => 'Registro\ProductosController@getFormaFarma'
	 	]);

	Route::post('/getPresentaciones',[
			'as' => 'get.presentaciones.prod',
			'uses' => 'Registro\ProductosController@getPresentaciones'
	 	]);

	Route::post('/getLabAcondi',[
			'as' => 'get.labacondi.prod',
			'uses' => 'Registro\ProductosController@getLabsAcondi'
	 	]);

	Route::post('/getNomExpo',[
			'as' => 'get.nomexpo.prod',
			'uses' => 'Registro\ProductosController@getNomExpProducto'
	 	]);

	Route::post('/getPoderes',[
			'as' => 'get.poderes.prod',
			'uses' => 'Registro\ProductosController@getPoderes'
	 	]);

	Route::post('/getPrincipiosA',[
			'as' => 'get.principiosa.prod',
			'uses' => 'Registro\ProductosController@getPrincipiosAct'
	 	]);

	Route::post('/getFormula',[
			'as' => 'get.formula.prod',
			'uses' => 'Registro\ProductosController@getFormula'
	 	]);

	Route::post('/getExcipientes',[
			'as' => 'get.excipiente.prod',
			'uses' => 'Registro\ProductosController@getExcipiente'
	 	]);

	Route::get('/getTabsProductos',[
			'as' => 'get.tabs.prod',
			'uses' => 'Registro\ProductosController@getTabsProductos'
	 	]);

	Route::post('/productoConTramite',[
			'as' => 'productos.con.tramites.rv',
			'uses' => 'Registro\ProductosController@tramitesDeProductoRv'
	 	]);	

});
