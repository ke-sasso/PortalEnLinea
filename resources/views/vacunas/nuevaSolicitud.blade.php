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
    .text-uppercase {
      text-transform: uppercase;
    }
    ::-webkit-file-upload-button {
  color:#fff;background-color:#29A0CB;border-color:#29A0CB

}
</style>
@endsection

@section('contenido')
@if($errors->any())
    <div class="alert alert-warning square fade in alert-dismissable">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
        <strong>Oops!</strong>
        Debes corregir los siguientes errores para poder continuar      
        <ul class="inline-popups">
            @foreach ($errors->all() as $error)
                <li  class="alert-link">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
  @if(Session::has('msnExito'))
    <div class="modal fade" id="imprimirModal"  data-dismiss="modal" tabindex="-1" role="dialog" aria-labelledby="myModalImprimir" aria-hidden="false" style="display: block;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="js-title-step">{!! Session::get('msnExito') !!}</h4>
      </div>
      <div class="modal-body">
          <div align="center">
              SU SOLICITUD HA SIDO REGISTRADA.
          </div>
          <br>
      </div>
      <div class="modal-footer">
        <button type="button" id="cerrar1"  data-dismiss="modal" class="btn btn-primary btn-perspective">Cerrar</button>
          
      </div>
    </div>
  </div>
</div>
  @endif
  <div class="alert alert-warning" id="confirmar">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Verifique</strong> los datos de la solicitud
  </div>
  <div  id="formSol">    
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Datos del Registro de la Vacuna</h3>
        </div>
        <div class="panel-body">
        <form action="" method="POST" id="frmSolicitud" class="form-vertical" role="form" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <fieldset>
              <legend>Generales</legend>
              <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                      <div class="input-group">
                        <div class="input-group-addon"><b>No. de Registro</b></div>
                        <input type="text" class="form-control text-uppercase" id="idProducto" readonly="readonly" name="idProducto"">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-primary" id="btnregistro"><i class="fa fa-search" ></i></button>
                        </span>
                      </div>  
                    </div>        
                   
                  </div>
                </div>  
              </div>
              <br>
              <br>
              <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                          <div class="input-group">
                            <div class="input-group-addon"><b>Nombre Comercial</b></div>
                            <input type="text" class="form-control" readonly="readonly" id="nombreComercial" name="nombreComercial">
                          </div>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <br>
              <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                      <div class="input-group">
                        <div class="input-group-addon">ATC</div>
                        <input type="hidden" class="form-control"  id="atc_code" name="atc_code">
                        <input type="text" class="form-control text-uppercase" readonly="readonly" id="atc" name="atc">
                      </div>
                    </div>
                  </div>
                </div>  
              </div>
              <br>
              <br>
              <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                      <div class="input-group">
                        <div class="input-group-addon"><b>Nombre de la Vacuna </b></div>
                        <input type="hidden" class="form-control"  id="principio_activo" name="principio_activo">
                        <input type="text" class="form-control text-uppercase" readonly="readonly" id="nombreVacuna" name="nombreVacuna" >
                      </div>                      
                    </div>
                  </div>
                </div>  
              </div>
               <br>
              <br>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="input-group">
                  <div class="input-group-addon">Forma Farmac&eacute;utica</div>
                  <input type="hidden" class="form-control" id="idFarm" name="idFarm">
                  <input type="text" class="form-control" id="formaFarmaceutia" readonly="readonly" name="formaFarmaceutia">
                </div>
              </div>
              <br>
              <br>
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="input-group">
                  <div class="input-group-addon">D&oacute;sis</div>
                  <input type="text" class="form-control" id="dosis" name="dosis" readonly="readonly">
                </div>
              </div>                            
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="input-group">
                  <div class="input-group-addon">Vida Útil</div>
                  <input type="text" class="form-control" id="vidaUtil" name="vidaUtil" readonly="readonly">
                </div>
              </div>              
              <br>
              <br>
              <br>
              <fieldset>
                <legend>Presentaciones</legend>
                <div class="table-responsive">
                  <table class="table table-hover" id="dt-presentaciones">
                    <tbody>
                      <tr>
                        <td>
                          <div id="optPresentaciones">
                            
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </fieldset>
              <br>
              <br>
              <fieldset>
                <legend>Potencia</legend>
                <div class="table-responsive">
                  <table class="table table-hover" id="dt-potencias">
                    <tbody>
                     
                    </tbody>
                  </table>
                </div>
              </fieldset>
              <br>
              <br>
              <fieldset>
                <legend>Informaci&oacute;n del Lote</legend>
                        <div class="form-group">
                              <div class="input-group">
                                <div class="input-group-addon">Condiciones de Almacenamiento seg&uacute;n Registro:</div>
                                <label style="word-wrap: break-word" class="form-control" id="condicionAlmacena"></label>
                              </div>
                              
                            </div>
                        <div class="col-sm-4 col-md-4 col-lg-4">                            
                             <div class="form-group">
                                <label for="">*Condiciones de Almacenamiento (Min)</label>
                                <div class="input-group">
                                  <input type="number" class="form-control" id="min" name="loteMin">
                                  <div class="input-group-addon">°C</div>  
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">*Condiciones de Almacenamiento (Max)</label>
                                <div class="input-group">
                                  <input type="number" class="form-control" id="max" name="loteMax">
                                  <div class="input-group-addon">°C</div>  
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-4">
                             <div class="form-group">
                                <label for="" style="width:100%">*Vida Útil Etiquetada</label>
                                <input style="width:40%;display:inline-block" type="text" class="form-control" id="loteVidaUtil" name="loteVidaUtil">
                                <select style="width:58%;display:inline-block" type="text" class="form-control" id="loteVidaUtilUnidad" name="loteVidaUtilUnidad">
                                  <option value="dias" selected="selected" label="Días">Días</option>
                                  <option value="meses" label="Meses">Meses</option>
                                  <option value="años" label="Años">Años</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                 <label for="" style="width:100%">*Tamaño/Volumen del Lote</label>
                                 <input style="width:40%;display:inline-block" type="text" class="form-control" id="loteVolumen" name="loteVolumen">
                                <select style="width:58%;display:inline-block" type="text" class="form-control" id="loteVolumenUnidad" name="loteVolumenUnidad">
                                  @foreach($unidadesMed as $uniMed)
                                    @if($uniMed->tipo==3 || $uniMed->tipo==1)
                                      <option value="{{$uniMed->id_um}}">{{$uniMed->nombre_plural}}</option>
                                    @endif
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="" style="width:100%">*Total de Envases a Liberar Por Lote</label>
                                <input style="width:40%;display:inline-block" type="text" class="form-control " id="envasesLote" name="envasesLote">
                                <input class="form-control" style="width:58%;display:inline-block" value="Envases x Lote">
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="" style="width:100%">*Total de Dosis a Liberar Por Lote</label>
                                <input style="width:40%;display:inline-block" readonly type="text" class="form-control" id="dosisLote" name="dosisLote">
                                <input class="form-control" disabled=""  style="width:58%;display:inline-block" value="Dosis x Lote">
                            </div>
                        </div>
                          
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">*Fecha de Fabricación</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="loteFecFabricacion" name="loteFecFabricacion"> 
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">*Fecha de Expiración</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="loteFecExpiracion" name="loteFecExpiracion">
                                </div>                                
                            </div>
                        </div>
                        <div class="col-sm-4">
                             <div class="form-group">
                                <label for="">*Número de Lote (Clave)</label>
                                <input type="text" class="form-control" id="numLote" name="numLote" >
                            </div>
                        </div>
                        <h4 class="col-sm-12" data-toggle="tooltip" data-placement="left" title="Solo llenar los campos si aplica para la solicitud!">Diluyente (si aplica)</h4>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Nombre del Diluyente</label>
                                <input type="text" class="form-control" id="diluyente" name="diluyente">
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Expiración Diluyente</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="expDiluyente" name="expDiluyente">
                                </div>                                
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="" style="width:100%">Volumen de Diluyente</label>
                                <input style="width:35%;display:inline-block" type="text" class="form-control" id="volDiluyente" name="volDiluyente" placeholder="Volumen">
                                <select style="width:60%;display:inline-block" type="text" class="form-control" name="volDiluyenteUnidad" id="volDiluyenteUnidad">
                                  <option value="0" selected="selected" label="Unidad Global">Unidad Global</option>
                                  @foreach($unidadesMed as $uniMed)
                                    @if($uniMed->tipo==3 || $uniMed->tipo==1)
                                      <option value="{{$uniMed->id_um}}">{{$uniMed->nombre_plural}}</option>
                                    @endif
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">N° Lote Diluyente</label>
                                <input type="text" class="form-control " id="loteDiluyente" name="loteDiluyente" placeholder="N° Lote">
                            </div>
                        </div>
                        
                        <h4 class="col-sm-12" data-toggle="tooltip" data-placement="left" title="Solo llenar los campos si aplica para la solicitud!">Componentes Adicionales (si aplica)</h4>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Nombre del Adyuvante</label>
                                <input type="text" class="form-control" id="adyuvante" name="adyuvante"> 
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Nombre del Preservante</label>
                                <input type="text" class="form-control" id="preservante" name="preservante">
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Nombre del Estabilizante</label>
                                <input type="text" class="form-control" id="estabilizante" name="estabilizante">
                            </div>
                        </div>
                        <div class="col-sm-3">
                             <div class="form-group">
                                <label for="">Nombre del Excipiente</label>
                                <input type="text" class="form-control" id="excipiente" name="excipiente">
                            </div>
                        </div>
                        <div class="col-sm-6">
                             <div class="form-group">
                                <label for="">Nombre del Antibi&oacute;tico</label>
                                <input type="text" class="form-control" id="antibiotico" name="antibiotico">
                            </div>
                        </div>
              </fieldset>
              <fieldset>
                <legend>Titular del Producto</legend>
                <div class="table-responsive">
                  <table class="table table-hover" id="dt-titulares">
                    <thead>
                      <tr>
                        <th>-</th>
                        <th>Nombre Titular</th>
                        <th>Pa&iacute;s</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                  </table>
                </div>
              </fieldset>
              
                <legend>Fabricante</legend>
                
                  <table class="table table-hover" id="dt-fabricantes">
                    <thead>
                      <tr>
                        <th>-</th>
                        <th>Nombre Fabricante</th>
                        <th>Pais</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                
              
              <fieldset>
                <legend>Laboratorio Acondicionador</legend>
                <div class="table-responsive">
                  <table class="table table-hover" id="dt-laboratorios">
                    <thead>
                      <tr>
                        <th>Nombre Laboratorio</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </fieldset>
              <fieldset>
                <legend>Importador</legend>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                  <div class="input-group">
                    <div class="input-group-addon">No. Registro Importador</div>
                    <input type="text" class="form-control" id="idEstablecimiento" name="idEstablecimiento" placeholder="">
                    <span class="input-group-btn">
                      <button type="button" id="btnEstablecimiento" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </span>
                  </div>
                </div>
                <br>
                <br>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="input-group">
                    <div class="input-group-addon">Nombre del Importador</div>
                    <input type="text" class="form-control" readonly id="nombreEstablecimiento" name="nombreEstablecimiento" placeholder="" value="">
                  </div>
                </div>
                <br>
                <br>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="input-group">
                    <div class="input-group-addon">Direcci&oacute;n</div>
                    <input type="text" class="form-control" readonly id="dirEstablecimiento" name="dirEstablecimiento" placeholder="" value="">
                  </div>
                </div>
                <br>
                <br>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="input-group">
                    <div class="input-group-addon">Tel&eacute;fonos de Contacto</div>
                    <input type="text" class="form-control" readonly id="telefonosEstablecimiento" name="telefonosEstablecimiento" value="">
                  </div>    
                </div>  
                <br>
                <br>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="input-group">
                    <div class="input-group-addon">Correo Electr&oacute;nico</div>
                    <input type="text" class="form-control" readonly id="emailEstablecimiento" name="emailEstablecimiento" value="">
                  </div>    
                </div>  
              </fieldset>
            </fieldset>
            <br>
            <br>           
            <!-- ANEXOS -->
            <fieldset>
            
              <legend>Anexos</legend>
              <!--              
                        Protocolo Resumido de Fabricación
                        Declaración Jurada
                        Certificado de Liberación de Lote del País de Origen de la Vacuna
                        Certificado de Análisis del Fabricante del Producto

    
              -->
              <div class="table-responsive">
                <table class="table table-hover">                  
                  <tbody>
                    <tr>
                      <td>Protocolo Resumido de Fabricación</td>
                      <td><input type="file" name="file[1]" value="" placeholder=""></td>
                    </tr>
                    <tr>
                      <td>Declaración Jurada</td>
                      <td><input type="file" name="file[5]" value="" placeholder=""></td>
                    </tr>
                    <tr>
                      <td>Certificado de Liberación de Lote del País de Origen de la Vacuna</td>
                      <td><input type="file" name="file[3]" value="" placeholder=""></td>
                    </tr>
                    <tr>
                      <td>Certificado de Análisis del Fabricante del Producto</td>
                      <td><input type="file" name="file[4]" value="" placeholder=""></td>
                    </tr>
                  </tbody>
                </table>
              </div>

            </fieldset>        
            <div class="panel-footer">
              <div class="text-center">
                <button type="button" id="btnSend" class="btn btn-primary">Guardar</button>
                <button type="button" id="btnSubmit" class="btn btn-primary">Confirmar</button>
              </div>
            </div>      
        </form>
        </div>
      
      </div>

  </div>
  <div class="modal fade modal-center" id="dlgProductos"  tabindex="-1" role="dialog" >
      <div class="modal-dialog modal-lg" >
          <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header bg-success">
                  <button type="button" class="close" 
                     data-dismiss="modal">
                         <span aria-hidden="true">&times;</span>
                         <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="frmModalLabel">
                      B&Uacute;SQUEDA DE PRODUCTOS
                  </h4>
              </div>
      
      </br>                
              <!-- Modal Body -->
              <div class="modal-body">
                
                    <div class="table">
                      <table width="100%" class="table table-hover" id="dt-producto">
                        <thead class="the-box dark full">
                          <tr>
                            <th>No.REGISTRO</th>
                            <th>NOMBRE</th>
                            <th>VIGENCIA</th>
                            <th>RENOVACION</th>
                            <th>-</th>
                          </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                      </table>
                    </div>
               
              </div>
              <!-- End Modal Body -->
              <!-- Modal Footer -->
              <div class="modal-footer">                        
                  <button type="button" class="btn btn-default"
                          data-dismiss="modal">
                              Cancelar
                  </button>                
              </div>
          </div>
      </div>
  </div>
  <div class="modal fade" id="modalATC">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">ATC</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-hover" id="dtmodalatc">
              <thead>
                <tr>
                  <th>Nombre ATC</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="dlgEstablecimientos">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Establecimientos</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-hover" id="dt-establecimientos">
              <thead>
                <tr>
                  <th>ID ESTABLECIMIENTO</th>
                  <th>NOMBRE ESTABLECIMIENTO</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
<script type="text/javascript">
  
  var presentaciones;
  var idPresentSelect;

$(document).ready(function() {


        $('#envasesLote').keypress(function(e) {
          if($('#envasesLote').val()!==undefined || $('#envasesLote').val()!=0){
            var key = e.which;
            if (key == 13) // the enter key code
            { for(i=0;i<presentaciones.length;i++){
                if(presentaciones[i].ID_PRESENTACION==idPresentSelect){
                    $('#dosisLote').val($(this).val() * presentaciones[i].CANTIDAD_SECUNDARIA);
                }
              }            
            }
          }
        });

        $('#dosisLote').click(function() {
            if($('#envasesLote').val()!==undefined || $('#envasesLote').val()!=0){
              for(i=0;i<presentaciones.length;i++){
                if(presentaciones[i].ID_PRESENTACION==idPresentSelect){
                    $('#dosisLote').val($('#envasesLote').val() * presentaciones[i].CANTIDAD_SECUNDARIA);
                }
              }
            }
          
        });

        $('#dt-presentaciones').on('click','input[type="checkbox"]', function() {
            if($(this).is(':checked')){
              idPresentSelect=$(this).val();
              var checked=$(this);
               $('#dt-presentaciones tbody tr td input[type="checkbox"]').each(function(){
                    $(this).not(checked).prop('disabled', true);  
              });
            }
            else{
               $('#dt-presentaciones tbody tr td input[type="checkbox"]').each(function(){
                    $(this).not(checked).prop('disabled', false);  
              });
            }
        });

        $('#dt-fabricantes').on('click','input[type="checkbox"]', function() {
            if($(this).is(':checked')){
              var checked=$(this);
               $('#dt-fabricantes tbody tr td input[type="checkbox"]').each(function(){
                    $(this).not(checked).prop('disabled', true);  
              });
            }
            else{
               $('#dt-fabricantes tbody tr td input[type="checkbox"]').each(function(){
                    $(this).not(checked).prop('disabled', false);  
              });
            }
        });

        $('#dt-titulares').on('click','input[type="checkbox"]', function() {
            if($(this).is(':checked')){
              var checked=$(this);
               $('#dt-titulares tbody tr td input[type="checkbox"]').each(function(){
                    $(this).not(checked).prop('disabled', true);  
              });
            }
            else{
               $('#dt-titulares tbody tr td input[type="checkbox"]').each(function(){
                    $(this).not(checked).prop('disabled', false);  
              });
            }
        });

        $('#dt-laboratorios').on('click','input[type="checkbox"]', function() {
            if($(this).is(':checked')){
              var checked=$(this);
               $('#dt-laboratorios tbody tr td input[type="checkbox"]').each(function(){
                    $(this).not(checked).prop('disabled', true);  
              });
            }
            else{
               $('#dt-laboratorios tbody tr td input[type="checkbox"]').each(function(){
                    $(this).not(checked).prop('disabled', false);  
              });
            }
        }); 
        
        

        <?php
          if(Session::has('msnExito'))
          {
            echo "$('#imprimirModal').modal('toggle');";
          }
        ?>
        $('#confirmar').hide();
        $('#btnSubmit').hide();
        $(function () {
                $('.datepicker').datepicker({format: 'yyyy-mm-dd'});
            });
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

                                                  return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="selectProducto(\''+data.ID_PRODUCTO+'\',\''+data.NOMBRE_COMERCIAL+'\',\''+data.SOLVENTES_DILUYENTES+'\',\''+data.VIDA_UTIL+'\',\''+data.CONDICIONES_ALMACENAMIENTO+'\');" >' + '<i class="fa fa-check-square-o"></i>' + '</a>'; 
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


        $('#btnAtc').click(function(event) {
          var dtatc = $('#dtmodalatc').DataTable({
                              processing: true,
                              filter:true,
                              serverSide: true,
                              destroy: true,
                              pageLength: 5,
                              ajax: {
                                url: "{{route('dt.data.atc.registro')}}"
                              },
                              columns:[                        
                                      {data: 'atc_name',name:'atc_name'}, 
                                      {       searchable: false,
                                              "mData": null,
                                              "bSortable": false,
                                              "mRender": function (data,type,full) { 
                                                if(data.alerta==1){
                                                    return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="alertaProducto();" >' + '<i class="fa fa-check-square-o"></i>' + '</a>';
                                                }
                                                else{

                                                  return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="selectATC(\''+data.atc_name+'\',\''+data.atc_code+'\');" >' + '<i class="fa fa-check-square-o"></i>' + '</a>'; 
                                                }
                                              }
                                      }                                  
                                      
                                  ],
                             language: {
                              processing: '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                              
                          },                            
          });
          

          $('#modalATC').modal('toggle');
        });

        $('#btnEstablecimiento').click(function(event) {
          var dtestableciimientos = $('#dt-establecimientos').DataTable({
                              processing: true,
                              filter:true,
                              serverSide: true,
                              destroy: true,
                              pageLength: 5,
                              ajax: {
                                url: "{{route('dt.data.establecimientos.registro')}}"
                              },
                              columns:[                        
                                      {data: 'idEstablecimiento',name:'idEstablecimiento'},
                                      {data: 'nombreComercial',name:'nombreComercial'}, 
                                      {       searchable: false,
                                              "mData": null,
                                              "bSortable": false,
                                              "mRender": function (data,type,full) { 
                                                var tels;
                                                var tel1;
                                                var tel2;
                                                var telContacto;
                                                if(data.alerta==1){
                                                    return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="alertaProducto();" >' + '<i class="fa fa-check-square-o"></i>' + '</a>';
                                                }
                                                else{
                                                  try
                                                  {
                                                    tels = JSON.parse(data.telefonosContacto);
                                                    tel1 = tels[0];
                                                    tel2 = tels[1];
                                                    telContacto = tel1 + ', ' + tel2;
                                                  }
                                                  catch(e)
                                                  {
                                                    console.log(e);
                                                  }
                                                  //console.log(telContacto);
                                                  return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="selectEstablecimiento(\''+data.idEstablecimiento+'\',\''+data.nombreComercial+'\',\''+data.direccion+'\',\''+telContacto+'\',\''+data.emailContacto+'\');" >' + '<i class="fa fa-check-square-o"></i>' + '</a>'; 
                                                }
                                              }
                                      }                                  
                                      
                                  ],
                             language: {
                              processing: '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                              
                          },                            
          });
          

          $('#dlgEstablecimientos').modal('toggle');
        });

        $('#btnSend').on('click', function(event) {
          alertify.confirm('Esta Seguro que desea continuar?', function(){
              $('#btnSend').hide(); 
              $('#btnSubmit').show();
              window.scrollTo(0, 0);
              $('#confirmar').show();

           });
        });

        $('#btnSubmit').on('click',  function(event) {
          event.preventDefault();
          $('#frmSolicitud').submit();
        });
  });


  function selectProducto(idProducto,nombreComercial,sol_dil,vidaUtil,condAlmacena)
  {       
          $('#frmSolicitud')[0].reset();
          $('#idProducto').val(idProducto);
          $('#nombreComercial').val(nombreComercial);
          $('#diluyente').val(sol_dil);
          $('#vidaUtil').val(vidaUtil);
          $('#condicionAlmacena').html(condAlmacena);

          $.get("{{route('dt.data.formafarm.registro')}}?param="+idProducto, function(data) {
            
            var obj = JSON.parse(data);
            
            $('#idFarm').val(obj[0].id_forma_farmaceutica);
            $('#formaFarmaceutia').val(obj[0].nombre);
          });

          $.get("{{route('dt.data.atc.registro')}}?param="+idProducto, function(data) {
            try{
              var obj = JSON.parse(data);
              
              $('#atc_code').val(obj[0].atc_code); 
              $('#atc').val(obj[0].atc_name);  
              $('#principio_activo').val(obj[0].idpavacunas);
              $('#nombreVacuna').val(obj[0].pa_es);
            }
            catch(e)
            {
              console.log(e);
            }
            
          });

          $.get("{{route('dt.data.dosis.registro')}}?param="+idProducto, function(data) {
            //console.log(data);
            var obj = JSON.parse(data);
            $('#dosis').val(obj[0].unidad_de_dosis);
          });

          $.get("{{route('dt.data.presentaciones.registro')}}?param="+idProducto, function(data) {
            //console.log(data);
            var obj = data;
            presentaciones=data;
            $('#optPresentaciones').html('');
            $.each(obj, function(i, value) {
              //console.log(value);
              $('#optPresentaciones').append('<div class="checkbox"><label><input type="checkbox" class="present" name="presentaciones" value="'+value.ID_PRESENTACION+'">'+value.PRESENTACION_COMPLETA+' '+value.ACCESORIOS+'</label></div>');
            });
            $('#optPresentaciones').append('<div class="checkbox"><label><input type="checkbox" name="presentaciones" value="-1 "><span>NO COINCIDE CON EL REGISTRO: <div class="input-group"><div class="input-group-addon">PRESENTACI&Oacute;N:</div><input type="text" class="form-control text-uppercase" id="presentacion1" name="presentacion1"></div></span></label></div>');
          });          

          $.get("{{route('dt.data.pa.registro')}}?param="+idProducto, function(data) {
            //console.log(data);
            var obj = JSON.parse(data);            
            $("#dt-potencias tbody tr").remove(); 
            $.each(obj, function(i, value) {
              //console.log(value);
              
              $('#dt-potencias').append('<div class="checkbox"><label><input type="checkbox" name="pa[]" value="'+value.ID_PRINCIPIO_ACTIVO+'">'+value.nombre+'. Concentraci&oacute;n '+value.CONCENTRACION+' '+value.nombre_unidad_medida+'</label></div>');
            });
            $('#dt-potencias').append('<div class="checkbox"><label><input type="checkbox" name="pa[]" value="N\\R"><span>NO COINCIDE CON EL REGISTRO: <div class="input-group"><div class="input-group-addon">POTENCIA:</div><input type="text" class="form-control text-uppercase" id="noconcidepotencia" name="noconcidepotencia"></div></span></label></div>');
          });

          
          $.get("{{URL::to('/vacunas/inicio/dt-titular-registro')}}/"+idProducto, function(data) {
            //console.log(data);
            var obj =data;
             $("#dt-titulares tbody tr").remove(); 
             $.each(obj, function(i, value) {
                $('#dt-titulares tbody').append('<tr><td><input type="checkbox" name="titus" value="'+value.id_propietario+'"></td><td>'+value.nombre_propietario+'</td><td>'+value.nombre_pais+'</td></tr>');
            });
          });

          $.get("{{route('dt.data.fabricante.registro')}}?param="+idProducto, function(data) {
            //console.log(data);
            var obj = JSON.parse(data);
            $("#dt-fabricantes tbody tr").remove(); 
             $.each(obj, function(i, value) {
              //console.log(value);
                $('#dt-fabricantes tbody').append('<tr><td><input type="checkbox" class="fabris" name="fabs" value="'+value.id_fabricante+'"></td><td>'+value.nombre+'</td><td>'+value.nombre_pais+'</td></tr>');
            });
          });
        }

  function selectATC(atcname,atccode)
  {
    $('#atc').val(atcname);

    $.get("{{route('dt.data.paoms.registro')}}?param="+atccode, function(data) {
      //console.log(data);
    });
  }

  function selectEstablecimiento(id,nombre,dir,tel,email)
  {
    //console.log(email);
    $('#idEstablecimiento').val(id);
    $('#nombreEstablecimiento').val(nombre);
    $('#dirEstablecimiento').val(dir);
    $('#telefonosEstablecimiento').val(tel);
    $('#emailEstablecimiento').val(email);
  }

</script>
@endsection