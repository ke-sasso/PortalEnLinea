<?php
Route::group(['prefix' => 'pre-registro-urv','middleware' => ['has_session']], function(){


   Route::post('/validar-mandamiento',[
        'as' => 'registrovisado.validarmandamiento',
        'uses' => 'Registro\PreRegistro\NuevoRegistroController@validarMandamiento'
    ]);

    /*
			Ruta para proceso de productos
	*/
	Route::get('/',[
			'as' => 'get.preregistrorv.index',
			'uses' => 'Registro\PreRegistro\NuevoRegistroController@index'
	]);

	Route::get('/get-solicitudes/usuario',[
			'as' => 'get.preregistrorv.index.getSolicitudes',
			'uses' => 'Registro\PreRegistro\SolicitudPreEdicionController@getDataRows'
	]);

	Route::get('editar/solicitud/{idSolicitud}',[
			'as' => 'get.preregistrorv.index.editarSolicitud',
			'uses' => 'Registro\PreRegistro\SolicitudPreEdicionController@edit'
	]);

    Route::get('subsanar/solicitud/{idSolicitud}',[
            'as' => 'get.preregistrorv.index.subsanarSolicitud',
            'uses' => 'Registro\PreRegistro\SolicitudPreEdicionController@subsanacion'
    ]);


	Route::get('/nuevo-solicitud',[
			'as' => 'get.preregistrorv.nuevosolicitud',
			'uses' => 'Registro\PreRegistro\NuevoRegistroController@nuevaSolicitud'
	]);

	Route::get('/imprimir-formulario/{idSolicitud}',[
			'as' => 'preregistrorv.imprimir.formulario',
			'uses' => 'Registro\PreRegistro\FormularioController@imprimir'
	]);


    Route::group(['prefix' => 'get'], function() {
        Route::get('/tiposMedicamentos',[
            'as' => 'get.preregistrorv.tiposmedicamentos',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getTiposMedicamentos'
        ]);

        Route::get('/formasfarmaceuticas',[
            'as' => 'get.preregistrorv.formasfarmaceuticas',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getFormasFarmaceuticas'
        ]);

        Route::get('/viasadministracion',[
            'as' => 'get.preregistrorv.viasadministracion',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getviasAdmin'
        ]);

        Route::get('/materiasprimas',[
            'as' => 'get.preregistrorv.materiasprimas',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getMateriasPrimas'
        ]);

        Route::get('/unidadesmedida',[
            'as' => 'get.preregistrorv.unidadesmedida',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getUnidadesMedida'
        ]);

        Route::get('/empaques',[
            'as' => 'get.preregistrorv.empaques',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getEmpaques'
        ]);

        Route::get('/contenidos',[
            'as' => 'get.preregistrorv.contenidos',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getContenidos'
        ]);

        Route::get('/colores-presentacion',[
            'as' => 'get.preregistrorv.colorespresent',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getColoresPresent'
        ]);

        Route::get('/materiales-presentacion',[
            'as' => 'get.preregistrorv.materialespresent',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getMaterialesPresent'
        ]);

         Route::get('/expediente-documentos',[
            'as' => 'get.expedientes.documentos',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getExpDocumentos'
        ]);

           Route::get('/get-codigosAtc',[
            'as' => 'get.codigosAtc',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getParentAtc'
         ]);

         Route::get('/comprobante-ingreso/{idSolicitud}',[
            'as' => 'comprobante.ingreso.rv.pre',
            'uses' => 'Registro\PreRegistro\FormularioController@comprobanteIngresoRegistroVisado'
         ]);

          Route::get('/get-modalidad-venta',[
            'as' => 'get.modalidad.venta',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getModalidadVenta'
         ]);

          Route::get('/get-views-pro-reconocimiento',[
            'as' => 'get.paisnumero.reconocimiento',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@reconocimientoMutuoView'
         ]);

          Route::get('/get-poder-maquila',[
            'as' => 'get.poder.fab.maquila',
            'uses' => 'Registro\PreRegistro\NuevoRegistroController@getPoderFabMaquila'
         ]);
    });

      Route::get('/mandamiento/{idSolicitud}/{idMandamiento}',[
            'as' => 'get.mandamiento.rv',
            'uses' => 'Mandamientos\PagoPreRVController@store'
         ]);

       Route::get('/search/solicitud/nombre/{search}',[
            'as' => 'search.autocomplete.nombresolicitud',
            'uses' => 'Registro\PreRegistro\SolicitudPreEdicionController@serachNombreSolicitud'
         ]);

});
