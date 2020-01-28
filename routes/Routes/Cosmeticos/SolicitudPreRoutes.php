<?php
/**
 * Created by PhpStorm.
 * User: steven.mena
 * Date: 1/3/2018
 * Time: 9:45 AM
 */

Route::group(['prefix' => 'pre-sol-cos','middleware' => ['has_session']], function() {

    Route::post('/storeStep1y2',[
        'as' => 'cospresolicitud.store.step1y2',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@storeStep1y2'
    ]);

    Route::post('/storeStep3',[
        'as' => 'cospresolicitud.store.step3',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@storeStep3'
    ]);

    Route::post('/storeStep4',[
        'as' => 'cospresolicitud.store.step4',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@storeStep4'
    ]);

    Route::post('/storeStep5',[
        'as' => 'cospresolicitud.store.step5',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@storeStep5'
    ]);

    Route::post('/storeStep6',[
        'as' => 'cospresolicitud.store.step6',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@storeStep6'
    ]);


    Route::post('/deleteFabs',[
        'as' => 'cospresolicitud.delete.fabs',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@deleteFab'
    ]);

    Route::post('/deleteDist',[
        'as' => 'cospresolicitud.delete.dist',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@deleteDist'
    ]);

    Route::post('/storeMain',[
        'as' => 'cospresolicitud.storemain',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@storeMain'
    ]);

    //MOSTRAR DOCUMENTO GUARDADO DE LA SOLICITUD
    Route::get('/showDocumento/{idSolicitud}/{idItemDoc}',[
        'as' => 'cospresolicitud.showdocumento',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@showDocByRequisito'
    ]);

    Route::post('/store',[
        'as' => 'cospresolicitud.store',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@store'
    ]);

    Route::get('/lista/solicitudes',[
        'as' => 'cospresolicitud.lista-sol',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@index'
    ]);

    Route::get('/dtrows/solicitudes',[
        'as' => 'cospresolicitud.dtrows-sol',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@getSolicitudesByUsuario'
    ]);

    Route::get('edit/{idSolicitud}',[
        'as' => 'cospresolicitud.edit',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@edit'
    ]);

    //imprimir comprobante de solicitud 
    Route::get('/comprobante-cospre/{idSolicitud}',[
        'as' => 'comprobante.cospre',
        'uses' => 'PdfController@comprobanteIngresoCosPre'
    ]);
    //imprimir formulario de registro sanitario de productos cosméticos
    Route::any('/formulario-cosehig',[
        'as' => 'formulario.cosehig',
        'uses' => 'PdfController@formularioRegSanitarioProdCosmEHig'
    ]);
    //Eliminar(Desistir) Solicitud
    Route::get('eliminar-solCosm/{idSolicitud}',[
        'as' => 'cospresolicitud.desistirsol',
        'uses' => 'Cosmeticos\PreRegistro\SolicitudPreController@desistirSol'
    ]);

    //resolucion observada de cosméticos
    Route::get('/cos/{idSol}',[
        'as' => 'imprimir.resolob.cos',
        'uses' => 'ApiController@resolucionCosObservada'
    ]);

});
