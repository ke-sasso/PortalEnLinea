<?php
Route::group(['prefix' => 'pre-registro-cos','middleware' => ['has_session']], function(){
    /*

    */
    /*
    Route::get('/',[
        'as' => 'get.preregistrorv.index',
        'uses' => 'Registro\PreRegistro\NuevoRegistroController@index'
    ]);*/

    Route::get('/nuevo-solicitud',[
        'as' => 'get.cospreregistro.nuevasolicitud',
        'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@nuevaSolicitud'
    ]);

    Route::post('/validar-mandamiento',[
        'as' => 'cospreregistro.validarmandamiento',
        'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@validarMandamiento'
    ]);


    //GETTERS
    Route::group(['prefix' => 'get'], function() {
        Route::get('/marcas', [
            'as' => 'cospreregistro.get.marcas',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getMarcas'
        ]);

        Route::get('/areasaplicacion', [
            'as' => 'cospreregistro.get.areasaplicacion',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getAreasAplicacion'
        ]);

        Route::get('/clasificacion-hig', [
            'as' => 'cospreregistro.get.clasificacionhig',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getClasificacionHig'
        ]);

        Route::get('/clasificacion-by-area', [
            'as' => 'cospreregistro.get.clasificacionbyarea',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getClasificacionesByArea'
        ]);

        Route::get('/formas-by-clasificacion', [
            'as' => 'cospreregistro.get.formasbyclasificacion',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getFormasCosByClasificacion'
        ]);

        Route::get('/formulas-inci', [
            'as' => 'cospreregistro.get.formulasinci',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getFormulaInci'
        ]);

        Route::get('/requisitos', [
            'as' => 'cospreregistro.get.requisitos',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getRequisitosByTramite'
        ]);

        Route::get('/reconocimiento-view', [
            'as' => 'cospreregistro.get.reconocimientoview',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getReconocimientoView'
        ]);

        Route::get('/gnralprod-view', [
            'as' => 'cospreregistro.get.gnralprodview',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getGnralCosOrHigView'
        ]);

        Route::get('/envases', [
            'as' => 'cospreregistro.get.envases',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getEvanses'
        ]);

        Route::get('/material-presentacion', [
            'as' => 'cospreregistro.get.material.present',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getMateriales'
        ]);

        Route::get('/unidades-medida', [
            'as' => 'cospreregistro.get.unidmedida',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getUnidadesMedida'
        ]);

        Route::get('/formulas-hig', [
            'as' => 'cospreregistro.get.formulashig',
            'uses' => 'Cosmeticos\PreRegistro\CosmeticosPreController@getFormulaHig'
        ]);


    });



});