<?php
Route::group(['prefix' => 'toolsrv','middleware' => ['has_session']], function(){
	/*
			Ruta para proceso de tools 
	*/
	Route::group(['prefix' => 'poderes'], function(){
			
	 	Route::post('/getProfesional',[
			'as' => 'get.profesional',
			'uses' => 'Registro\ToolsRvController@validatePoderProf'
	 	]);	

	 	Route::post('/getApoderado',[
			'as' => 'get.apoderado',
			'uses' => 'Registro\ToolsRvController@validatePoderApo'
	 	]);	

	});
	
	Route::group(['prefix' => 'desistimiento'], function(){
			
	 	Route::post('/solicitudRv/post',[
			'as' => 'solrv.desistimiento',
			'uses' => 'Registro\ToolsRvController@desistimientoSolicitud'
	 	]);	


	});

    /*
    conjunto GET
    */
    Route::group(['prefix' => 'get'], function(){

        Route::get('/tiposmedicamentos',[
            'as' => 'tiposmedicamentos',
            'uses' => 'Registro\ToolsRvController@getTiposMedicamentos'
        ]);

        Route::get('/viasadministracion',[
            'as' => 'viasadministracion',
            'uses' => 'Registro\ToolsRvController@getViasAdministracion'
        ]);

        Route::get('/formasfarmaceuticas',[
            'as' => 'formasfarmaceuticas',
            'uses' => 'Registro\ToolsRvController@getFormasFarmaceuticas'
        ]);

        Route::get('/materiasprimas',[
            'as' => 'materiasprimas',
            'uses' => 'Registro\ToolsRvController@getMateriasPrimas'
        ]);

        Route::get('/unidadesmedida',[
            'as' => 'unidadesmedida',
            'uses' => 'Registro\ToolsRvController@getUnidadesMedida'
        ]);

        Route::get('/propietario',[
            'as' => 'getPropietario',
            'uses' => 'Registro\ToolsRvController@getPropietario'
        ]);
    });


    Route::get('/search/titular',[
        'as' => 'search.titular',
        'uses' => 'Tools\ToolsController@searchTitular'
    ]);

    Route::get('/get/titular',[
        'as' => 'get.titular',
        'uses' => 'Tools\ToolsController@getTitular'
    ]);

      Route::get('/get/titular/registro',[
        'as' => 'get.titular.registro',
        'uses' => 'Tools\ToolsController@getTitularRegistro'
    ]);

    Route::get('/get/profesional/bypoder',[
        'as' => 'get.profesional.bypoder',
        'uses' => 'Tools\ToolsController@getProfesionalByPoder'
    ]);

    Route::get('/search/fab-or-imp',[
        'as' => 'search.fab.imp',
        'uses' => 'Tools\ToolsController@searchFabOrImp'
    ]);

    Route::get('/search/distribuidor',[
        'as' => 'search.distribuidor',
        'uses' => 'Tools\ToolsController@searchDistribuidor'
    ]);

    Route::get('/search/distribuidor/cos',[
        'as' => 'search.distribuidor.cos',
        'uses' => 'Tools\ToolsController@searchDistribuidorCos'
    ]);

      Route::get('/search/distribuidor/todos',[
        'as' => 'search.distribuidor.todos',
        'uses' => 'Tools\ToolsController@searchDistribuidorAll'
    ]);

      Route::get('/get/representantelegal/bypoder',[
        'as' => 'get.representantelegal.bypoder',
        'uses' => 'Tools\ToolsController@getRepresentanteLegalByPoder'
    ]);

       Route::get('/get/apoderados/bypoder',[
        'as' => 'get.apoderados.bypoder',
        'uses' => 'Tools\ToolsController@getTodosApoderadosByPoder'
    ]);


       Route::get('/get/establecimientos/relacionados',[
        'as' => 'get.esta.relacionados',
        'uses' => 'Tools\ToolsController@searchEstaRelacionados'
    ]);


       


	
});
