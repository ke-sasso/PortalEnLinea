@extends('master')
@section('css')

<style type="text/css">
     
    body {
        
      }
      .dlgwait {
          display:    inline;
          position:   fixed;
          z-index:    1000;
          top:        0;
          left:       0;
          height:     100%;
          width:      100%;
          background: rgba( 255, 255, 255, .3 ) 
                      url("{{ asset('/img/ajax-loader.gif') }}") 
                      50% 50% 
                      no-repeat;
      }
      .modal {
          width:      100%;
          background: rgba( 255, 255, 255, .8 );
      }

      /* When the body has the loading class, we turn
         the scrollbar off with overflow:hidden */
      body.loading {
          overflow: hidden;
      }

      /* Anytime the body has the loading class, our
         modal element will be visible */
      body.loading .dlgwait {
          display: block;
      }
    td.details-control {
        background: url("{{ asset('/plugins/datatable/images/details_open.png') }}") no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url("{{ asset('/plugins/datatable/images/details_close.png') }}") no-repeat center center;
    }
</style>
@endsection

@section('contenido')


  <div class="panel panel-primary">
  <div class="panel-heading">
  	<h3 class="panel-title">{{ $paneltitle }}</h3>
  </div>
  <div class="panel-body">
<div>

         

                <!-- INICIO DEL FORMULARIO DE LA SOLICITUD -->
                <div id="solicitud_main_form" class="panel-body row">
                <form class="">
                    
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">*Número de Registro:</label>
                                <input type="text" class="form-control" placeholder="Buscar N° de Registro" >
                            </div>
                        </div>
                        <div class="col-sm-9">
                             <div class="form-group">
                                <label for="">Nombre del Producto (Comercial)</label>
                                <input ng-model="solicitudes.current.data.nombre_producto" type="text" class="form-control" id="" placeholder="" disabled="disabled">
                            </div>
                        </div>
                 
                         
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">*Principio Activo</label>
                                 <input type="text" class="form-control" placeholder="Buscar Principio Activo">
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label for="">Nombre del Principio Activo <small>(Según serie de informes técnicos de la OMS o Farmacopéa de Referencia)</small></label>
                                <input type="text" class="form-control">    
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="">Potencia</label>
                                <input type="text" class="form-control" placeholder="Potencia">    
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label for="">Nombre del Principio Activo (Si no se encuentra en el listado)</label>
                                <input type="text" class="form-control">    
                            </div>
                        </div>

                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">*ATC</label>
                                <input type="text" class="form-control" placeholder="Buscar ATC" >
                            </div>
                        </div>
                        
                <div class="col-sm-9">
                             <div class="form-group">
                                <label for="">Descripcion Clase Terapeútica ATC</label>
                                <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>

                <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">*Forma Farmacéutica</label>
                                <input type="text" class="form-control" placeholder="Buscar Forma Farmacéutica" >
                            </div>
                        </div>
                        <div class="col-sm-9">
                             <div class="form-group">
                                <label for="">Nombre Forma Farmacéutica</label>
                                <input disabled="" ng-model="solicitudes.current.data.forma_farmaceutica_nombre" type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="">
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label style="width:100%" for="">*Dosis</label>
                                <input ng-model="solicitudes.current.data.dosis.magnitud" style="width:40%;display:inline-block" type="text" class="form-control ng-pristine ng-valid ng-touched" id="" placeholder="Dosis">
                                <select ng-model="solicitudes.current.data.dosis.unidad" style="width:58%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" ng-options="item.id as item.nombreum for item in form.optionList.unidades| filter:filterUnidades('dosis')"><option value="0" selected="selected" label="Unidad Global">Unidad Global</option><option value="1" label="Gramo">Gramo</option><option value="2" label="Mililitro">Mililitro</option><option value="3" label="Dosis">Dosis</option></select>
                            </div>
                        </div>
                        <div class="col-sm-5">
                             <div class="form-group">
                                <label for="" style="width:100%">*Envase Primario / N° Dosis Por Envase P.</label>
                                <input ng-model="solicitudes.current.data.envase_primario" style="width:58%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Envase Primario">
                                <input ng-model="solicitudes.current.data.dosis_envase_primario" style="width:40%;display:inline-block" ng-change="form.autofill.onChangeFunctions.selectLote()" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="N° de Dosis">
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="" style="width:100%">*Envase Secundario</label>
                                <input ng-model="solicitudes.current.data.envase_secundario" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Envase Secundario">
                            </div>
                        </div>
                        
                        <h4 class="col-sm-12">Información de Lote</h4>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">*Condiciones de Almacenamiento (Min)</label>
                                <input ng-model="solicitudes.current.data.condiciones_almacenamiento.min" style="width:58%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Min">
                                <input class="form-control" disabled="" style="width:40%;display:inline-block" value="°C">
                            </div>
                        </div>
                         <div class="col-sm-4">
                             <div class="form-group">
                    <label for="">*Condiciones de Almacenamiento (Max)</label>
                                <input ng-model="solicitudes.current.data.condiciones_almacenamiento.max" style="width:58%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Max">
                                <input class="form-control" disabled="" style="width:40%;display:inline-block" value="°C">
                            </div>
                        </div>
                         <div class="col-sm-4">
                             <div class="form-group">
                                <label for="" style="width:100%">*Vida Útil Aprobado</label>
                                <input ng-model="solicitudes.current.data.vida_util.magnitud" style="width:40%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Vida Útil">
                                <select ng-model="solicitudes.current.data.vida_util.unidad" style="width:58%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" ng-options=" item.id as item.plural for item in form.optionList.unidades_tiempo"><option value="0" selected="selected" label="Días">Días</option><option value="1" label="Meses">Meses</option><option value="2" label="Años">Años</option></select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                 <label for="" style="width:100%">*Tamaño/Volumen del Lote</label>
                                 <input ng-model="solicitudes.current.data.tamano_lote.magnitud" style="width:40%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Cantidad">
                                <select ng-model="solicitudes.current.data.tamano_lote.unidad" style="width:58%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" ng-options="item.id as item.nombreum for item in form.optionList.unidades | filter:filterUnidades('volumen')"><option value="0" selected="selected" label="Unidad Global">Unidad Global</option><option value="1" label="Gramo">Gramo</option><option value="2" label="Dosis">Dosis</option></select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="" style="width:100%">*Total de Envases a Liberar Por Lote</label>
                                <input ng-model="solicitudes.current.data.envases_a_liberar" style="width:40%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Número" ng-change="form.autofill.onChangeFunctions.selectLote()">
                                <input class="form-control" disabled="" style="width:58%;display:inline-block" value="Envases x Lote">
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="" style="width:100%">*Total de Dosis a Liberar Por Lote</label>
                                <input disabled="" ng-model="solicitudes.current.data.dosis_a_liberar" style="width:40%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="">
                                <input class="form-control" disabled="" style="width:58%;display:inline-block" value="Dosis x Lote">
                            </div>
                        </div>
                          
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">*Fecha de Fabricación</label>
                                <div class="input-group">
                                    <input datepicker-localdate="" ng-model="solicitudes.current.data.fecha_fabricacion" type="text" class="form-control ng-pristine ng-untouched ng-valid ng-isolate-scope ng-valid-date" disabled="" is-open="form.widgets.calendar.pool.widget1.isOpen" datepicker-popup="yyyy-MM-dd" datepicker-options="form.widgets.calendar.options" close-text="Cerrar" clear-text="Borrar" current-text="Hoy"><!-- ngIf: isOpen -->
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" ng-click="form.widgets.calendar.pool.widget1.open()"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">*Fecha de Expiración</label>
                                <div class="input-group">
                                    <input datepicker-localdate="" ng-model="solicitudes.current.data.fecha_expiracion" type="text" class="form-control ng-pristine ng-untouched ng-valid ng-isolate-scope ng-valid-date" disabled="" is-open="form.widgets.calendar.pool.widget2.isOpen" datepicker-popup="yyyy-MM-dd" datepicker-options="form.widgets.calendar.options" close-text="Cerrar" clear-text="Borrar" current-text="Hoy" show-weeks="false;"><!-- ngIf: isOpen -->
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" ng-click="form.widgets.calendar.pool.widget2.open()"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">*Número de Lote (Clave)</label>
                                <input ng-model="solicitudes.current.data.numero_lote" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="N° Lote">
                            </div>
                        </div>
                        
                        
                        <h4 class="col-sm-12">Diluyente (si aplica)</h4>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Nombre del Diluyente</label>
                                <input ng-model="solicitudes.current.data.diluyente.nombre" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Diluyente">
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Expiración Diluyente</label>
                                <div class="input-group">
                                    <input datepicker-localdate="" ng-model="solicitudes.current.data.diluyente.fecha_expiracion" type="text" class="form-control ng-pristine ng-untouched ng-valid ng-isolate-scope ng-valid-date" disabled="" is-open="form.widgets.calendar.pool.widget3.isOpen" datepicker-popup="yyyy-MM-dd" datepicker-options="form.widgets.calendar.options" close-text="Cerrar" clear-text="Borrar" current-text="Hoy" show-weeks="false;"><!-- ngIf: isOpen -->
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" ng-click="form.widgets.calendar.pool.widget3.open()"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="" style="width:100%">Volumen de Diluyente</label>
                                <input ng-model="solicitudes.current.data.diluyente.volumen.magnitud" style="width:35%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Volumen">
                                <select ng-model="solicitudes.current.data.diluyente.volumen.unidad" style="width:60%;display:inline-block" type="text" class="form-control ng-pristine ng-untouched ng-valid" ng-options="item.id as item.nombreum for item in form.optionList.unidades| filter:filterUnidades('diluyente')"><option value="0" selected="selected" label="Unidad Global">Unidad Global</option><option value="1" label="Gramo">Gramo</option><option value="2" label="Mililitro">Mililitro</option><option value="3" label="Dosis">Dosis</option></select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">N° Lote Diluyente</label>
                                <input ng-model="solicitudes.current.data.diluyente.lote" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="N° Lote">
                            </div>
                        </div>
                        
                        <h4 class="col-sm-12">Componentes Adicionales (si aplica)</h4>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Nombre del Adyuvante</label>
                                <input ng-model="solicitudes.current.data.adyuvante" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Adyuvante">
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Nombre del Preservante</label>
                                <input ng-model="solicitudes.current.data.preservante" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Preservante">
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Nombre del Estabilizante</label>
                                <input ng-model="solicitudes.current.data.estabilizante" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Estabilizante">
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Nombre del Excipiente</label>
                                <input ng-model="solicitudes.current.data.excipiente" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Excipiente">
                            </div>
                        </div>
                        
                        <h3 class="col-sm-12">2. Titular/Propietario del Producto</h3>
                    
                        <h4 class="col-sm-12"> </h4>
                        <div class="col-sm-8">
                             <div class="form-group">
                                <label for="">*Nombre del Titular</label>
                                <input ng-model="solicitudes.current.data.propietario.nombre" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Titular/Propietario">
                            </div>
                        </div>
                         <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Correo Electrónico</label>
                                <input ng-model="solicitudes.current.data.propietario.email" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-sm-8">
                             <div class="form-group">
                                <label for="">Domicilio</label>
                                <input ng-model="solicitudes.current.data.propietario.domicilio" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Domicilio">
                            </div>
                        </div>
                      
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Número Telefónico:</label>
                                <input ng-model="solicitudes.current.data.propietario.tel" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Teléfono">
                            </div>
                        </div>
                        
                       <h4 class="col-sm-12">Laboratorios </h4>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">Laboratorio(s) Fabricante(s)</label>
                                <input ng-model="solicitudes.current.data.fabricante.nombre" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Fabricante">
                            </div>
                        </div>
                        <div class="col-sm-5">
                             <div class="form-group">
                                <label for="">Domicilio</label>
                                <input ng-model="solicitudes.current.data.fabricante.domicilio" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Domicilio Fab.">
                            </div>
                        </div>
                         <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">País</label>
                                 <input ng-model="solicitudes.current.data.fabricante.pais" type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Paises" typeahead="option.nombre as option.nombre for option in form.autofill.services.getPaises($viewValue)" typeahead-loading="loadingPaises" typeahead-no-results="noResultsPaises" typeahead-wait-ms="500" typeahead-min-length="3" typeahead-on-select="form.autofill.onChangeFunctions.selectPaises($item)" ng-change="form.autofill.onChangeFunctions.selectPaises()" aria-autocomplete="list" aria-expanded="false" aria-owns="typeahead-30-6457"><ul class="dropdown-menu ng-isolate-scope ng-hide" ng-show="isOpen() &amp;&amp; !moveInProgress" ng-style="{top: position().top+'px', left: position().left+'px'}" style="display: block;;display: block;" role="listbox" aria-hidden="true" typeahead-popup="" id="typeahead-30-6457" matches="matches" active="activeIdx" select="select(activeIdx)" move-in-progress="moveInProgress" query="query" position="position">
    <!-- ngRepeat: match in matches track by $index -->
</ul>
                                <i ng-show="loadingPaises &amp;&amp; solicitudes.current.data.paises" class="glyphicon glyphicon-refresh ng-hide" style="float:right;margin-top:-60px"></i>
                                <div ng-show="noResultsPaises &amp;&amp; solicitudes.current.data.paises" class="typeahead-no-result ng-hide"><i class="glyphicon glyphicon-remove"></i> Sin Resultados </div>

                                <!--<select ng-model="solicitudes.current.data.fabricante.pais" style="" type="text" class="form-control" ng-options="item.id as item.nombre for item in form.optionList.paises"></select>-->
                            </div>
                        </div>
                         <h4 class="col-sm-12">Acondicionador</h4>
                        <div class="col-sm-12">
                             <div class="form-group">
                                <label for="">Nombre(s) del o los Laboratorio(s) Acondicionador(es)</label>
                                <input ng-model="solicitudes.current.data.acondicionador" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Acondicionador">
                            </div>
                        </div>
                        
                        <h4 class="col-sm-12">Importador</h4>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="" class="ng-binding">*Código Importador DNM</label>
                                <input ng-disabled="session.currentEstablecimiento.id_establecimiento != 'DNM'" ng-model="solicitudes.current.data.establecimiento" type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Importador" typeahead="option.id_establecimiento as option.nombre_comercial for option in form.autofill.services.getImportadores($viewValue)" typeahead-loading="loadingImportadores" typeahead-no-results="noResultsImportadores" typeahead-wait-ms="500" typeahead-min-length="3" typeahead-on-select="form.autofill.onChangeFunctions.selectEstablecimiento($item)" ng-change="form.autofill.onChangeFunctions.selectEstablecimiento()" aria-autocomplete="list" aria-expanded="false" aria-owns="typeahead-31-4335"><ul class="dropdown-menu ng-isolate-scope ng-hide" ng-show="isOpen() &amp;&amp; !moveInProgress" ng-style="{top: position().top+'px', left: position().left+'px'}" style="display: block;;display: block;" role="listbox" aria-hidden="true" typeahead-popup="" id="typeahead-31-4335" matches="matches" active="activeIdx" select="select(activeIdx)" move-in-progress="moveInProgress" query="query" position="position">
    <!-- ngRepeat: match in matches track by $index -->
</ul>
                                <i ng-show="loadingImportadores &amp;&amp; solicitudes.current.data.establecimiento" class="glyphicon glyphicon-refresh ng-hide" style="float:right;margin-top:-60px"></i>
                                 <div ng-show="noResultsImportadores  &amp;&amp; solicitudes.current.data.establecimiento" class="typeahead-no-result ng-hide"><i class="glyphicon glyphicon-remove"></i> Sin Resultados </div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                             <div class="form-group">
                                <label for="">Nombre del Importador</label>
                                <input disabled="" ng-model="solicitudes.current.data.importador.nombre" type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="">
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="">Domicilio</label>
                                <input ng-model="solicitudes.current.data.importador.domicilio" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Domicilio">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Correo Electrónico</label>
                                <input ng-model="solicitudes.current.data.importador.email" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Número Telefónico:</label>
                                <input ng-model="solicitudes.current.data.importador.tel" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Teléfono">
                            </div>
                        </div>
                        
                        
                        <h4 class="col-sm-12"> Químico Farmacéutico Responsable </h4>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">Número de Inscripción DNM</label>
                                <input ng-model="solicitudes.current.data.quimico.inscripcion" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="N° Inscripción">
                            </div>
                        </div>
                        <div class="col-sm-8">
                             <div class="form-group">
                                <label for="">Nombre Completo del Químico Farmacéutico Responsable</label>
                                <input ng-model="solicitudes.current.data.quimico.nombre" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Nombre">
                            </div>
                        </div>
                        

                        <h4 class="col-sm-12"> Apoderado / Representante Legal </h4>
                         <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">Número de Inscripcion DNM</label>
                                <input ng-model="solicitudes.current.data.representante.inscripcion" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="N° Inscripción">
                            </div>
                        </div>
                        <div class="col-sm-8">
                             <div class="form-group">
                                <label for="">Nombre Completo del Apoderado / Representante Legal</label>
                                <input ng-model="solicitudes.current.data.representante.nombre" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Nombre">
                            </div>
                        </div>
                       
                        <h3 class="col-sm-12 hidden" ng-class="{hidden:solicitudes.current.data.tipo === 1}">3. Criterios</h3>
                        
                         <h4 class="col-sm-12 hidden" ng-class="{hidden:solicitudes.current.data.tipo === 1}">Criterios Contemplados</h4>
                        
                         <div class="col-sm-12 hidden" ng-class="{hidden:solicitudes.current.data.tipo === 1}">
                             <div class="form-group">
                                <label for="">Seleccionar y Agregar Criterios</label>
                                 <select ng-model="form.metadata.criterio_on" type="text" style="display:inline-block;width:calc(100% - 100px)" class="form-control ng-pristine ng-untouched ng-valid" ng-options="item.id as item.criterio for item in form.optionList.criterios"><option value="" selected="selected" label=""></option><option value="0" label="Casos de Desastre">Casos de Desastre</option><option value="1" label="Epidemias">Epidemias</option><option value="2" label="Urgencia en Territorio Nacional">Urgencia en Territorio Nacional</option><option value="3" label="Emergencia Nacional">Emergencia Nacional</option><option value="4" label="Necesidad Pública Declarada Oficialmente">Necesidad Pública Declarada Oficialmente</option><option value="5" label="Escasez de Vacuna en el Mercado">Escasez de Vacuna en el Mercado</option><option value="6" label="Donaciones con Justificación Médica">Donaciones con Justificación Médica</option></select>
                                 <button class="btn btn-default" ng-click="solicitudes.addCriterio()">Agregar</button>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 hidden" ng-class="{hidden:solicitudes.current.data.tipo === 1}">
                            <div class="form-group">
                                <label for="">Criterios Agregados</label>
                                <table class="table table-condensed">
                                    <tbody><!-- ngRepeat: criterio in solicitudes.current.data.criterios -->
                                </tbody></table>
                            </div>
                        </div>
                            
                        <h4 class="col-sm-12 hidden" ng-class="{hidden:solicitudes.current.data.tipo === 1}">Criterios No Contemplados</h4>
                        <div class="col-sm-12 hidden" ng-class="{hidden:solicitudes.current.data.tipo === 1}">
                            <div class="form-group">
                                <label for="">Si el criterio no se encuentra en la lista de arriba, detallar y agregar el criterio no contemplado</label>
                                 <input ng-model="solicitudes.current.data.criterio_no_contemplado" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Detalle el Criterio No Contemplado">
                            </div>
                        </div>

                        
                        
                        <h3 class="col-sm-12" ng-class="{hidden:solicitudes.current.data.tipo === 2}">3. Notificaciones</h3>
                        <h3 class="col-sm-12 hidden" ng-class="{hidden:solicitudes.current.data.tipo === 1}">4. Notificaciones</h3>
                        <h4 class="col-sm-12">Info Contacto</h4>
                        <div class="col-sm-6">
                             <div class="form-group">
                                <label for="">Correo Electrónico</label>
                                <input ng-model="solicitudes.current.data.notificacion_email" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Email Notificaciones">
                            </div>
                        </div>
                        <div class="col-sm-6">
                             <div class="form-group">
                                <label for="">Teléfono</label>
                                <input ng-model="solicitudes.current.data.notificacion_tel" type="text" class="form-control ng-pristine ng-untouched ng-valid" id="" placeholder="Teléfono Notificaciones">
                            </div>
                        </div>
                        
                    

                        <!-- INICIO SECCION DE FICHAS -> CARGA Y LISTADO --> 
                        <h3 class="col-sm-12">
                            Fichas
                            <button class="btn btn-success btn-xs pull-right" ng-click="form.functions.toggleAddFicha()" ng-show="!form.metadata.showCargarFicha">
                                <i class="fa fa-plus"></i> Agregar Ficha
                            </button>
                        </h3>
                        

                        <div class="well  well-sm ng-hide" ng-show="form.metadata.showCargarFicha"><!-- INICIO DIV DIALOGO AGREGAR FICHA-->
                        <h4 class="col-sm-12">Cargar Fichas</h4>
                        <div class="col-sm-12"><!-- INICIO SECCION CARGA DE FICHAS -->
                            <div class="alert alert-warning ng-hide" ng-hide="solicitudes.current.data.id > 0">
                                <i class="fa fa-exclamation fa-lg"></i> Es necesario guardar la solicitud antes de poder adjuntar fichas.
                            </div>
                            <div class="" ng-show="solicitudes.current.data.id > 0" style="margin-top:10px">
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label>Descripción de Ficha</label>
                                        <input type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Descripción" ng-model="solicitudes.current.data.fichaToAdd.data.descripcion">
                                    </div>
                                </div>
                                <div class="well well-sm" style="padding:1px;margin-bottom:5px">
                                    <button ng-disabled="solicitudes.current.data.estatus > 1 || solicitudes.current.data.estatus === 0" class="button btn btn-success ng-pristine ng-untouched ng-valid" ngf-select="" ng-model="solicitudes.current.data.fichaToAdd.file" name="file" ngf-pattern="'application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword'" accept="application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" ngf-max-size="20MB" ng-click="ficha.resetProgress()" disabled="disabled">
                                        <i class="fa fa-folder-open"></i>
                                        Elegir Archivo
                                    </button>
                                    <span class="ng-binding" style="margin-left:10px;">...</span>
                                </div>
                                
                                <div class="progress-striped active progress ng-isolate-scope" value="ficha.uploadProgress" type="success" style="height:5px">
  <div class="progress-bar progress-bar-success" ng-class="type &amp;&amp; 'progress-bar-' + type" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" ng-style="{width: (percent < 100 ? percent : 100) + '%'}" aria-valuetext="0%" style="min-width: 0px; width: 0%;" ng-transclude="">
                                            <span ng-show="ficha.uploadProgress < 100 &amp;&amp; ficha.uploadProgress > 0" class="ng-binding ng-scope ng-hide">0</span>
                                            <span ng-show="ficha.uploadProgress == 100" class="ng-scope ng-hide">Carga Completada</span>
                                </div>
</div>
                                <div class="form-group pull-right">
                                    <button class="btn btn-default" ng-click="form.functions.toggleAddFicha()">
                                        <i class="fa fa-close"></i>
                                        Cancelar
                                    </button>
                                    <button class="btn btn-success" type="submit" ng-click="ficha.add()" style="display:inline-block;" ng-disabled="!solicitudes.current.data.fichaToAdd.file.name" disabled="disabled">
                                        <i class="fa fa-plus"></i>
                                        Agregar Ficha
                                    </button>
                                </div>
                                
                            </div>
                        </div> <!-- FIN SECCION CARGA DE FICHAS -->
                        </div><!-- FIN DIV DIALOGO AGREGAR FICHA-->
                        
                        <h4 class="col-sm-12">Fichas Adjuntas</h4>
                        <div class="col-sm-12"><!-- INICIO SECCION LISTADOS DE FICHAS -->
                            <div class="well well-sm ng-hide" ng-show="solicitudes.current.data.fichas.length  == 0">
                                No se ha agregado ninguna ficha.
                            </div>
                            <table class="table table-hover" ng-show="solicitudes.current.data.fichas.length > 0">
                                <tbody><tr>
                                    <th>Descargar</th>
                                    <th>N°</th>
                                    <th>Agregado</th>
                                    <th>Titulo</th>
                                    <th>Descripción</th>
                                    <th></th>
                                </tr>
                                <!-- ngRepeat: item in solicitudes.current.data.fichas --><tr ng-repeat="item in solicitudes.current.data.fichas" class="ng-scope">
                                    <td><a class="btn btn-default" target="_blank" href="backendapi/downloadfilesolicitud.php?type=1&amp;id=41">
                                        <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                    <td class="ng-binding">1</td>
                                    <td class="ng-binding">2017-02-20 14:24:13</td>
                                    <td class="ng-binding">FT Vacuna Prevenar 13 LOTE N71179.docx</td>
                                    <td style="font-style:italic" class="ng-binding"></td>
                                    <td>
                                        <a class="btn btn-default" ng-click="ficha.remove($index)">
                                        <i class="fa fa-close"></i>
                                        </a>
                                    </td>
                                </tr><!-- end ngRepeat: item in solicitudes.current.data.fichas -->
                            </tbody></table>
                        </div> <!-- FIN SECCION LISTADOS DE FICHAS -->



                        <!-- INICIO SECCION DE ANEXOS -> CARGA Y LISTADO --> 
                        <h3 class="col-sm-12">
                            Anexos
                            <button class="btn btn-success btn-xs pull-right" ng-click="form.functions.toggleAddAnexo()" ng-show="!form.metadata.showCargarAnexo">
                                <i class="fa fa-plus"></i> Agregar Anexo
                            </button>
                        </h3>
                        
                        <div class="well well-sm ng-hide" ng-show="form.metadata.showCargarAnexo"><!-- INICIO DIV DIALOGO AGREGAR ANEXO-->
                        <h4 class="col-sm-12">Adjuntar Anexos</h4>
                        <div class="col-sm-12"><!-- INICIO SECCION CARGA DE ANEXOS -->
                            <div class="alert alert-warning ng-hide" ng-hide="solicitudes.current.data.id > 0">
                                <i class="fa fa-exclamation fa-lg"></i> Es necesario guardar la solicitud antes de poder adjuntar fichas.
                            </div>
                            <div class="form-group" ng-show="solicitudes.current.data.id > 0" style="margin-top:10px">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Tipo de Anexo</label>

                                         <select ng-class="{hidden:solicitudes.current.data.tipo === 1}" ng-model="solicitudes.current.data.anexoToAdd.data.tipo" type="text" class="form-control ng-pristine ng-untouched ng-valid hidden" ng-options="item.id as item.nombre for item in form.optionList.anexos | filter : {tipo: 'E'}: true" ng-change="anexo.updateTipo()"><option value="?" selected="selected" label=""></option><option value="0" label="Justificación">Justificación</option><option value="1" label="Certificado de Liberación de lote del pais de origen de la vacuna">Certificado de Liberación de lote del pais de origen de la vacuna</option><option value="2" label="Certificado de Análisis del fabricante del producto">Certificado de Análisis del fabricante del producto</option></select>

                                        <select ng-class="{hidden:solicitudes.current.data.tipo === 2}" ng-model="solicitudes.current.data.anexoToAdd.data.tipo" type="text" class="form-control ng-pristine ng-untouched ng-valid" ng-options="item.id as item.nombre for item in form.optionList.anexos | filter : {tipo: 'L'}: true" ng-change="anexo.updateTipo()"><option value="?" selected="selected" label=""></option><option value="0" label="Protocolo Resumido de Fabricación">Protocolo Resumido de Fabricación</option><option value="1" label="Ficha Técnica de la Vacuna">Ficha Técnica de la Vacuna</option><option value="2" label="Certificado de Liberación de Lote del País de Origen de la Vacuna">Certificado de Liberación de Lote del País de Origen de la Vacuna</option><option value="3" label="Certificado de Análisis del Fabricante del Producto">Certificado de Análisis del Fabricante del Producto</option></select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Descripción de Anexo</label>
                                        <input class="form-control ng-pristine ng-untouched ng-valid" placeholder="Descripción" ng-model="solicitudes.current.data.anexoToAdd.data.descripcion">
                                    </div>
                                </div>
                                <div class="well well-sm" style="padding:1px;margin-bottom:5px">
                                    <button ng-disabled="solicitudes.current.data.estatus > 1 || solicitudes.current.data.estatus === 0" class="button btn btn-success ng-pristine ng-untouched ng-valid" ngf-select="" ng-model="solicitudes.current.data.anexoToAdd.file" name="file" ngf-pattern="'application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword'" accept="application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" ngf-max-size="20MB" ng-click="anexo.resetProgress()" disabled="disabled">
                                        <i class="fa fa-folder-open "></i>  
                                        Elegir Archivo
                                    </button>
                                    <span class="ng-binding" style="margin-left:10px;">...</span>
                                </div>
                                <div class="progress-striped active progress ng-isolate-scope" value="anexo.uploadProgress" type="success" style="height:5px;">
  <div class="progress-bar progress-bar-success" ng-class="type &amp;&amp; 'progress-bar-' + type" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" ng-style="{width: (percent < 100 ? percent : 100) + '%'}" aria-valuetext="0%" style="min-width: 0px; width: 0%;" ng-transclude="">
                                    <span ng-show="anexo.uploadProgress < 100 &amp;&amp; anexo.uploadProgress > 0" class="ng-binding ng-scope ng-hide">0</span>
                                    <span ng-show="anexo.uploadProgress == 100" class="ng-scope ng-hide">Carga Completada</span>
                                </div>
</div>
                                <div class="form-group pull-right">
                                    <button class="btn btn-default" ng-click="form.functions.toggleAddAnexo()">
                                        <i class="fa fa-close"></i>
                                        Cancelar
                                    </button>
                                    <button class="btn btn-success" type="submit" ng-click="anexo.add()" ng-disabled="!solicitudes.current.data.anexoToAdd.file.name" disabled="disabled">
                                        <i class="fa fa-plus"></i>
                                        Agregar Anexo
                                    </button>
                                </div>
                            </div>
                        </div><!-- FIN SECCION CARGA DE ANEXOS -->
                        </div><!-- FIN DIV DIALOGO AGREGAR ANEXO-->
                        
                        <h4 class="col-sm-12">Listado de Anexos</h4>
                        <div class="col-sm-12"><!-- INICIO SECCION LISTADOS DE ANEXOS -->
                            <div class="well well-sm ng-hide" ng-show="solicitudes.current.data.anexos.length == 0">
                                No se ha agregado ningun anexo.
                            </div>
                            <table class="table table-hover" style="width:100%" ng-show="solicitudes.current.data.anexos.length > 0">
                                <tbody><tr>
                                    <th>Descargar</th>
                                    <th>N°</th>
                                    <th>Agregado</th>
                                    <th>Tipo Anexo / Nombre de Archivo</th>
                                    <th>Descripción</th>
                                    <th></th>
                                    
                                </tr>
                                <!-- ngRepeat: item in solicitudes.current.data.anexos --><tr ng-repeat="item in solicitudes.current.data.anexos" class="ng-scope">
                                    <td><a class="btn btn-default" target="_blank" href="backendapi/downloadfilesolicitud.php?type=2&amp;id=591">
                                        <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                    <td class="ng-binding">1</td>
                                    <td class="ng-binding">2017-02-20 14:22:45</td>
                                    <td class="ng-binding"><strong class="ng-binding">Protocolo Resumido de Fabricación</strong><br>prevenar-n71179-protocolo-de-fabricacion.pdf</td>
                                    <td><small class="ng-binding"></small></td>
                                    <td>
                                        <a class="btn btn-default" ng-click="anexo.remove($index)">
                                        <i class="fa fa-close"></i>
                                        </a>
                                    </td>
                                </tr><!-- end ngRepeat: item in solicitudes.current.data.anexos --><tr ng-repeat="item in solicitudes.current.data.anexos" class="ng-scope">
                                    <td><a class="btn btn-default" target="_blank" href="backendapi/downloadfilesolicitud.php?type=2&amp;id=592">
                                        <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                    <td class="ng-binding">2</td>
                                    <td class="ng-binding">2017-02-20 14:23:02</td>
                                    <td class="ng-binding"><strong class="ng-binding">Certificado de Liberación de Lote del País de Origen de la Vacuna</strong><br>prevenar-n71179-certificado-de-liberacion-pais-de-origen.pdf</td>
                                    <td><small class="ng-binding"></small></td>
                                    <td>
                                        <a class="btn btn-default" ng-click="anexo.remove($index)">
                                        <i class="fa fa-close"></i>
                                        </a>
                                    </td>
                                </tr><!-- end ngRepeat: item in solicitudes.current.data.anexos --><tr ng-repeat="item in solicitudes.current.data.anexos" class="ng-scope">
                                    <td><a class="btn btn-default" target="_blank" href="backendapi/downloadfilesolicitud.php?type=2&amp;id=593">
                                        <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                    <td class="ng-binding">3</td>
                                    <td class="ng-binding">2017-02-20 14:23:16</td>
                                    <td class="ng-binding"><strong class="ng-binding">Certificado de Análisis del Fabricante del Producto</strong><br>prevenar-n71179-certificado-de-analisis-del-laboratorio.pdf</td>
                                    <td><small class="ng-binding"></small></td>
                                    <td>
                                        <a class="btn btn-default" ng-click="anexo.remove($index)">
                                        <i class="fa fa-close"></i>
                                        </a>
                                    </td>
                                </tr><!-- end ngRepeat: item in solicitudes.current.data.anexos --><tr ng-repeat="item in solicitudes.current.data.anexos" class="ng-scope">
                                    <td><a class="btn btn-default" target="_blank" href="backendapi/downloadfilesolicitud.php?type=2&amp;id=594">
                                        <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                    <td class="ng-binding">4</td>
                                    <td class="ng-binding">2017-02-20 14:24:28</td>
                                    <td class="ng-binding"><strong class="ng-binding">Ficha Técnica de la Vacuna</strong><br>ft-vacuna-prevenar-13-lote-n71179.docx</td>
                                    <td><small class="ng-binding"></small></td>
                                    <td>
                                        <a class="btn btn-default" ng-click="anexo.remove($index)">
                                        <i class="fa fa-close"></i>
                                        </a>
                                    </td>
                                </tr><!-- end ngRepeat: item in solicitudes.current.data.anexos -->
                            </tbody></table>
                        </div>

                    </div><!-- FIN DE ROW-->
                </form>
                </div>
                <!-- FIN FORMULARIO DE LA SOLICITUD-->    
            
            </div>
        </div>
@endsection

@section('js')
<script type="text/javascript">
  $(document).ready(function() {
        $('#btnregistro').click(function(event) {

          perfil=$("#perfil option:selected" ).val();
          if(perfil==='0'){
            alertify.alert('Mensaje del Sistema','Seleccione un titulo para este trámite.');
          }
          else{
          var dtproductos = $('#dt-producto').DataTable({
                              processing: true,
                              filter:true,
                              serverSide: true,
                              destroy: true,
                              pageLength: 5,
                              ajax: {
                                url: "{{route('dt.data.productos.registro')}}",
                                data: function (d) {
                                  d.perfil=perfil;
                                }

                              },
                              columns:[                        
                                      {data: 'ID_PRODUCTO',name:'ID_PRODUCTO'}, 
                                      {data: 'NOMBRE_COMERCIAL',name:'NOMBRE_COMERCIAL'},                
                                      {data: 'VIGENTE_HASTA',name:'VIGENTE_HASTA'}, 
                                      {data: 'ULTIMA_RENOVACION',name:'ULTIMA_RENOVACION'},
                                      {       searchable: false,
                                              "mData": null,
                                              "bSortable": false,
                                              "mRender": function (data,type,full) { 
                                                if(data.alerta==1){
                                                    return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="alertaProducto();" >' + '<i class="fa fa-check-square-o"></i>' + '</a>';
                                                }
                                                else{

                                                  return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="selectProducto(\''+data.idProducto+'\',\''+data.nombreComercial+'\',\''+data.tipoProd+'\',\''+data.vigenteHasta+'\',\''+data.ultimaRenovacion+'\');" >' + '<i class="fa fa-check-square-o"></i>' + '</a>'; 
                                                }
                                              }
                                      }                                  
                                      
                                  ],
                             language: {
                              processing: '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                              
                          },                            
          });
          

          $('#dlgProductos').modal('toggle');
        }
        }); 

  
  });
</script>
@endsection