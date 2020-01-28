<?php

Route::group(['prefix' => 'registro-sol-registro-subsanacion','middleware' => ['has_session']], function() {

    Route::post('/storeStep1y2',[
        'as' => 'registrosubsanar.store.step1y2',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@storeStep1y2'
    ]);

    Route::post('/storeStep3',[
        'as' => 'registrosubsanar.store.step3',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@storeStep3'
    ]);

    Route::post('/storeStep4',[
        'as' => 'registrosubsanar.store.step4',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@storeStep4'
    ]);

     Route::post('/storeStep5',[
        'as' => 'registrosubsanar.store.step5',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@storeStep5'
    ]);

    Route::post('/storeStep6',[
        'as' => 'registrosubsanar.store.step6',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@storeStep6'
    ]);
    Route::post('/storeStep7',[
        'as' => 'registrosubsanar.store.step7',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@storeStep7'
    ]);
    Route::post('/storeStep8',[
        'as' => 'registrosubsanar.store.step8',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@storeStep8'
    ]);
    Route::post('/storeStep9',[
        'as' => 'registrosubsanar.store.step9',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@storeStep9'
    ]);

    Route::post('/storeStep10',[
        'as' => 'registrosubsanar.store.step10',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@storeStep10'
    ]);

    Route::post('/storeStep11',[
        'as' => 'registrosubsanar.store.step11',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@storeStep11'
    ]);


       Route::get('/obtenerValidaciones',[
        'as' => 'solicitud.obtener.validaciones.campo',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@obtenerValidacionesCampo'
    ]);

      Route::get('/documentos/numeracion/{idSolicitud}',[
        'as' => 'verlista.doc.numeracion',
        'uses' => 'Registro\PreRegistro\SubsanacionPreController@indexDocNumeracion'
    ]);

        Route::post('/pdf/numeracion/documentos',[
        'as' => 'imprimir.numereacion.doc.rv',
        'uses' => 'Registro\PreRegistro\FormularioController@imprimirDocNumeracion'
    ]);

      

});
