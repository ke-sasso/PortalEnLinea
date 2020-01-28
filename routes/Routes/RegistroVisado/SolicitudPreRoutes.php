<?php

Route::group(['prefix' => 'registro-sol-registro','middleware' => ['has_session']], function() {

    Route::post('/storeStep1y2',[
        'as' => 'registropresolicitud.store.step1y2',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@storeStep1y2'
    ]);

    Route::post('/storeStep3',[
        'as' => 'registropresolicitud.store.step3',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@storeStep3'
    ]);

    Route::post('/storeStep4',[
        'as' => 'registropresolicitud.store.step4',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@storeStep4'
    ]);

     Route::post('/storeStep5',[
        'as' => 'registropresolicitud.store.step5',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@storeStep5'
    ]);

    Route::post('/storeStep6',[
        'as' => 'registropresolicitud.store.step6',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@storeStep6'
    ]);
    Route::post('/storeStep7',[
        'as' => 'registropresolicitud.store.step7',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@storeStep7'
    ]);
    Route::post('/storeStep8',[
        'as' => 'registropresolicitud.store.step8',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@storeStep8'
    ]);
    Route::post('/storeStep9',[
        'as' => 'registropresolicitud.store.step9',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@storeStep9'
    ]);

    Route::post('/storeStep10',[
        'as' => 'registropresolicitud.store.step10',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@storeStep10'
    ]);

    Route::post('/storeStep11',[
        'as' => 'registropresolicitud.store.step11',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@storeStep11'
    ]);

    Route::get('/showDocumento/{idDocumento}',[
        'as' => 'registrovisado.showdocumento',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@showDocByRequisito'
    ]);

    Route::get('/expediente-documentos/editar/{idSolicitud}/{vista}',[
            'as' => 'get.editar.expedientes.documentos',
            'uses' => 'Registro\PreRegistro\SolicitudPreEdicionController@getExpDocumentosEdit'
    ]);

    Route::get('eliminar-sol/{idSolicitud}',[
        'as' => 'registrosolicitud.eliminarsol',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@eliminarSol'
    ]);
    Route::get('desistir-sol/{idSolicitud}',[
        'as' => 'registrosolicitud.desistirsol',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@desistirSol'
    ]);

    Route::get('/solicitud-urv/desistir-sol/{idSolicitud}',[
        'as' => 'pdf.desistir.sol.rv',
        'uses' => 'Registro\PreRegistro\FormularioController@pdfDesistirSol'
    ]);

    Route::get('/comprobante-subsanacion/{idSolicitud}',[
        'as' => 'pdf.comprobante.subsanacion.sol.rv',
        'uses' => 'Registro\PreRegistro\FormularioController@pdfComprobanteSubsanacion'
    ]);

    Route::get('/comprobante-archivado',[
        'as' => 'pdf.comprobante.archivado.sol.rv',
        'uses' => 'Registro\PreRegistro\FormularioController@pdfComprobanteArchivado'
    ]);

    Route::get('/comprobante-ingresourv',[
        'as' => 'pdf.comprobante.ingreso.sol.rv',
        'uses' => 'Registro\PreRegistro\FormularioController@pdfComprobanteIngresoUrv'
    ]);

    Route::get('/eliminar-documento/{idSolicitud}/{idDocumento}',[
        'as' => 'registropresolicitud.eliminar.documento',
        'uses' => 'Registro\PreRegistro\SolicitudPreController@deleteDocumento'
    ]);


});
