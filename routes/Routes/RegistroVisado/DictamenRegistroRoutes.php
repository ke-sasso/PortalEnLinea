<?php

Route::group(['prefix' => 'registro-sol-dictamenes','middleware' => ['has_session']], function() {

    
      Route::get('/dictamenes/RV/estatus',[
        'as' => 'verlista.dictamenes.rv',
        'uses' => 'Registro\PreRegistro\DictamenController@getRowsDictamenRV'
    ]);

    Route::get('/dictamen/laboratorio/{id}',[
        'as' => 'dictamen.laboratorio.rv',
        'uses' => 'Registro\PreRegistro\DictamenController@reporteObservacionLaboratorio'
    ]);
    Route::get('/dictamen/laboratorio/{idsol}/{idDictamen}',[
        'as' => 'dictamen.medico.rv',
        'uses' => 'Registro\PreRegistro\DictamenController@reporteObservacionMedico'
    ]);
    Route::get('/resolucionRevision/nuevoregistro/urv',[
        'as' => 'resolucion.nuevoregistro.rv',
        'uses' => 'Registro\PreRegistro\DictamenController@reporteRevisionUrvPre'
    ]);

});
