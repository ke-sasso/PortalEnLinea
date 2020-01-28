<?php
Route::group(['prefix' => 'anualidades','middleware' => ['has_session']], function(){
	/*
			Ruta para proceso
	*/
    Route::post('/eliminar/boleta',[
                'as'    => 'eliminar.boleta',
                'uses'  => 'Anualidades\VariosMetodosController@destroyHoja'
     ]);


	Route::group(['prefix' => 'establecimientos'], function(){
	  Route::get('/',[
			'as' => 'ver.anualidades.establecimientos',
			'uses' => 'Anualidades\EstablecimientosController@index'
	 	]);

	   Route::get('/lista',[
    			'as' => 'get.rows.establecimientos.anu',
     			'uses' => 'Anualidades\EstablecimientosController@lista'
     	]);
       Route::post('/generar',[
    			'as' 	=> 'store.mandamiento.establecimientos',
    			'uses' 	=> 'Anualidades\EstablecimientosController@store'
    	 ]);

       Route::get('/imprimir/hoja/{idEnlace}',[
    			'as' 	=> 'imprimir.boleta.establecimientos',
    			'uses' 	=> 'Anualidades\EstablecimientosController@imprimirHoja'
    	 ]);

      Route::get('/dt-infogeneral',[
            'as' => 'dt.data.infoGeneral',
            'uses' => 'Anualidades\EstablecimientosController@getEstablecimientosGeneral'
         ]);
        Route::get('/dt-infoPropietario',[
            'as' => 'dt.data.propietario',
            'uses' => 'Anualidades\EstablecimientosController@getPropietario'
            ]);

          Route::get('/dt-infoRegerentes',[
            'as' => 'dt.data.regerentes',
            'uses' => 'Anualidades\EstablecimientosController@getRegerentes'
            ]);

           Route::get('/dt-infoHorarios',[
            'as' => 'dt.data.horarios',
            'uses' => 'Anualidades\EstablecimientosController@getRegerentesHorarios'
            ]);

            Route::get('/enviar/informacion/{idEstablecimiento}',[
            'as' => 'enviar.informacion',
            'uses' => 'Anualidades\EstablecimientosController@verInfoGeneral'
            ]);

         Route::post('/store/informacion',[
         'as' => 'guardar.informacion.establecimiento',
         'uses' => 'Anualidades\EstablecimientosController@storeInformacion'
         ]);

          Route::get('/pdf-establecimientos/{idEstablecimiento}',[
            'as' => 'ver.pdf.establecimiento',
            'uses' => 'Anualidades\EstablecimientosController@detalleEstablecimiento'
            ]);

	});//CIERRE DE ESTABLECIMIENTOS

		Route::group(['prefix' => 'importadores'], function(){
	    Route::get('/',[
			'as' => 'ver.anualidades.importadores',
			'uses' => 'Anualidades\ImportadoresController@index'
	 	]);



	   Route::get('/lista/nueva',[
    			'as' => 'get.rows.anualidades.impor',
     			'uses' => 'Anualidades\ImportadoresController@lista'
     	]);
        Route::post('/generar',[
    			'as' 	=> 'store.mandamiento.importadores',
    			'uses' 	=> 'Anualidades\ImportadoresController@store'
    	 ]);

          Route::get('/imprimir/hoja/{idEnlace}',[
                'as'    => 'imprimir.boleta.importador',
                'uses'  => 'Anualidades\ImportadoresController@imprimirHoja'
         ]);

	});//CIERRE DE IMPORTADORES

	    Route::group(['prefix' => 'cosmeticos'], function(){
	    Route::get('/',[
			'as' => 'ver.anualidades.cosmeticos',
			'uses' => 'Anualidades\CosmeticoController@index'
	 	]);

	   Route::get('/lista/nueva',[
    			'as' => 'get.rows.anualidades.cosmeticos',
     			'uses' => 'Anualidades\CosmeticoController@lista'
     	]);

	   Route::post('/generar',[
    			'as' 	=> 'store.mandamiento.cosmeticos',
    			'uses' 	=> 'Anualidades\CosmeticoController@store'
    	 ]);

	     Route::post('/lista/productos',[
    			'as' 	=> 'lista.productos.cosmeticos',
    			'uses' 	=> 'Anualidades\CosmeticoController@hojaProductosCosmeticos'
		 ]);
		 Route::post('/getPropietarios',[
			'as' => 'get.listMunicipios',
			'uses' => 'Anualidades\CosmeticoController@getPropietariosList'
		]);

	});//CIERRE DE COMESTICOS

	     Route::group(['prefix' => 'insumos'], function(){
	    Route::get('/',[
			'as' => 'ver.anualidades.insumos',
			'uses' => 'Anualidades\InsumosController@index'
	 	]);


	   Route::get('/lista/nueva',[
    			'as' => 'get.rows.anualidades.insumos',
     			'uses' => 'Anualidades\InsumosController@lista'
     	]);

	   Route::post('/generar',[
    			'as' 	=> 'store.mandamiento.insumos',
    			'uses' 	=> 'Anualidades\InsumosController@store'
    	 ]);

        Route::post('/lista/productos',[
                'as'    => 'lista.productos.insumos',
                'uses'  => 'Anualidades\InsumosController@hojaProductosInsumo'
		 ]);
	


	});//CIERRE DE INSUMOS


	     Route::group(['prefix' => 'registro'], function(){
	    Route::get('/',[
			'as' => 'ver.anualidades.registro',
			'uses' => 'Anualidades\RegistroController@index'
	 	]);


	   Route::get('/lista/nueva',[
    			'as' => 'get.rows.anualidades.registro',
     			'uses' => 'Anualidades\RegistroController@lista'
     	]);

	   Route::post('/generar',[
    			'as' 	=> 'store.mandamiento.registro',
    			'uses' 	=> 'Anualidades\RegistroController@store'
    	 ]);

        Route::post('/lista/productos',[
                'as'    => 'lista.productos.registro',
                'uses'  => 'Anualidades\RegistroController@hojaProductosRegistro'
         ]);

	});//CIERRE DE REGISTRO Y VISADO


});