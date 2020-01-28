<?php
Route::group(['prefix' => 'post-registro-cos','middleware' => ['has_session']], function(){

    Route::get('/',[
        'as' => 'get.cospostregistro.solicitudes',
        'uses' => 'Cosmeticos\PostRegistro\CosmeticosPostController@index'
    ]);

    Route::get('/nueva-solicitud',[
        'as' => 'get.cospostregistro.nuevasolicitud',
        'uses' => 'Cosmeticos\PostRegistro\CosmeticosPostController@nuevaSolicitud'
    ]);

    Route::post('/validar-mandamiento',[
        'as' => 'cospostregistro.validarmandamiento',
        'uses' => 'Cosmeticos\PostRegistro\CosmeticosPostController@validarMandamiento'
    ]);


    //GETTERS
    Route::group(['prefix' => 'get'], function() {
        Route::get('/productos/post/cos/byprofesional', [
            'as' => 'cosproregistro.get.productos.prof',
            'uses' => 'Cosmeticos\PostRegistro\CosmeticosPostController@getDataProducByProfesional'
        ]);
        Route::get('/rows/solicitudes/post', [
            'as' => 'cosproregistro.get.rows.solicitudes',
            'uses' => 'Cosmeticos\PostRegistro\CosmeticosPostController@getDataRows'
        ]);
    
    });

    Route::group(['prefix' => 'tramite-post'], function() {

                Route::post('/cambio-empaque',[
                    'as' => 'cospostregistro.cambio.empaque',
                    'uses' => 'Cosmeticos\PostRegistro\CosmeticosPostController@storeCambioEmpaque'
                ]);

     });




});