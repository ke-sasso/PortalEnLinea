@extends('master')

@section('css')
{!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal.css') !!}
{!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') !!}
<style type="text/css">

body{

    overflow-x: hidden;
    overflow-y: scroll !important;
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
textarea {
    white-space: normal;
    text-align: justify;
    -moz-text-align-last: left; /* Firefox 12+ */
    text-align-last: left;
}
.text-uppercase
{ text-transform: uppercase; }
@media screen and (min-width: 768px) {
  
  #modal-id .modal-dialog  { width:900px;}

}

#dlgProductos{
    width:0px;
    height: 0px;  
    position: center;
    top: 0%;
    left: 0%;
    margin-top: -0px;
    margin-left: 300px;
    padding: 0px;

    }


</style>

@endsection

@section('contenido')
{{-- MENSAJE DE EXITO --}}
@if(Session::has('msnExito'))
    <div class="modal fade" id="imprimirModal" tabindex="-1" role="dialog" aria-labelledby="myModalImprimir" aria-hidden="false" style="display: block;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="js-title-step">{!! Session::get('msnExito') !!}</h4>
      </div>
      <div class="modal-body">
          <div align="center">

            
            @if(Session::get('idTramite')==44)
              <label>Esta solicitud entrara a sesión</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            @else
            <label>Imprimir Resolucion de la Solicitud</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{route('imprimir.rv',['idSolicitud' => Session::get('idSolicitud'), 'idTramite' => Session::get('idTramite')])}}" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>
            @endif
          </div>
          <br>
      </div>
      <div class="modal-footer">
        <button type="button" id="cerrar1" class="btn btn-primary btn-perspective">Cerrar</button>
          
      </div>
    </div>
  </div>
</div>
  @endif
  {{-- MENSAJE DE ERROR --}}
  @if(Session::has('msnError'))
    <div id="error" class="alert alert-danger square fade in alert-dismissable">
      <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
      <strong>Error:</strong>
        Algo ha salido mal{!! Session::get('msnError') !!}
    </div>
  @endif


<div class="alert alert-warning">
  <h4><strong>Verifique que los datos ingresados son acorde a su tramite y su solicitud.</strong></h4>
</div>



<div class="panel panel-success">
  <div class="panel-heading" role="tab" id="headingSix">
    <h3 id="leyendTramite" class="panel-title">
        DATOS DE LA SOLICITUD: 
    </h3>
  </div>

  <div class="panel-body">
    <form method="POST" id='guardarSolicitudSIM' enctype="multipart/form-data" action="{{ route('guardar.solicitud.sim') }}" class="form-horizontal" role="form">
      @if($idTramite!=28)
        <div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true">
          
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <label>Seleccione el tipo de t&iacute;tulo para este tr&aacute;mite:</label>
              <div class="input-group">
                <div class="input-group-addon"><b>T&Iacute;TULO:</b></div>
                <select name="perfil" id="perfil" class="form-control">
                    
                      @if($perfil==='APODERADO')
                          <option value="APODERADO" selected readonly>APODERADO</option>
                      @elseif($perfil==='PROFESIONAL')
                         <option value="PROFESIONAL RESPONSABLE" selected readonly>PROFESIONAL RESPONSABLE</option>
                      @elseif($perfil==='PROPIETARIO')
                          <option value="PROPIETARIO" selected readonly>PROPIETARIO</option>
                      @elseif($perfil==='REPRESENTATE')
                          <option value="REPRESENTATE LEGAL" selected>REPRESENTATE LEGAL</option>
                      @endif
        
                </select>  
              </div>
          </div>
          <br>
          <br>
          <br>
          <br>
          <br>
          <div class="panel panel-success">
                  
            <div class="panel-heading" role="tab" id="headingSix">
              <h4 class="panel-title">
                  PRODUCTO:
              </h4>
            </div>        
            <div class="panel-body">
              <div class="container-fluid the-box">
                <div class="form-group">
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="input-group">
                      <div class="input-group-addon"><b>NUM. REGISTRO</b></div>
                      <input type="hidden" class="form-control" id="correlativo" name="correlativo" value="{{$producto->CORRELATIVO}}">
                      <input type="text" class="form-control" id="txtidproducto" name="txtidproducto" value="{{$producto->ID_PRODUCTO}}" readonly required>
                    </div>
                  </div>
                  <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                    
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="tipo" >
                   
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="nombre" >
                    <div class="input-group">
                      <div class="input-group-addon"><b>NOMBRE:</b></div>
                      <input type="text" class="form-control" id="nomcomercial" name="nomcomercial" value="{{$producto->NOMBRE_COMERCIAL}}"  required readonly>
                    </div>
                  </div>
                  
                </div>
                
                <div class="form-group">
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="renovacion">
                    <div class="input-group">
                      <div class="input-group-addon"><b>RENOVACION</b></div>
                      <input type="text" class="form-control" id="txtrenovacion" name="txtrenovacion" value="{{$producto->ULTIMA_RENOVACION}}"  required readonly>
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="vigencia">
                    <div class="input-group">
                      <div class="input-group-addon"><b>VIGENCIA</b></div>
                      <input type="text" class="form-control" id="txtvigencia" name="txtvigencia" value="{{$producto->VIGENTE_HASTA}}" required readonly>
                    </div>
                  </div>  
                </div>
              </div>                              
            </div>  
          </div>        

      @if($idTramite==13)

      <div class="panel panel-success">
        <div class="panel-heading" role="tab" >
          <h4 class="panel-title">
             CAMBIO DE PERÍODO DE VIDA ÚTIL DEL PRODUCTO:
          </h4>
        </div>
          <div class="panel-body">
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="input-group">
                    <div class="input-group-addon"><b>NUEVO PERÍODO DE VIDA &Uacute;TIL:</b></div>
                    <textarea class="form-control text-uppercase" id="descripcion" name="descripcion" value=""  required readonly>{{$descripcion}}</textarea>
                  </div>
                </div>
                        
            </div>
          </div>
        
      </div>
      @elseif($idTramite==7)
        <div class="panel panel-success">
          <div class="panel-heading" >
            <h4 class="panel-title" >
                 NUEVOS MODELOS A AGREGAR AL PRODUCTO:
            </h4>
          </div>
            <div class="panel-body">
                <br>
                <br> 
                @for($i=0;$i<count($modelo);$i++)
                  <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="input-group">
                      <div class="input-group-addon"><b>NUEVO CÓDIGO:</b></div>
                          <input type="text" class="form-control text-uppercase" name="codigos[]" id="modelo" value="{{$codigos[$i]}}" readonly></input>
                    </div>
                  </div>
                  <br>
                  <br>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="input-group">
                      <div class="input-group-addon"><b>NUEVO MODELO:</b></div>
                          <input type="text" class="form-control text-uppercase" name="modelo[]" id="modelo" value="{{$modelo[$i]}}" readonly></input>
                  </div>
                </div>
                <br>
                <br>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="input-group">
                    <div class="input-group-addon"><b>DESCRIPCIÓN:</b></div>
                    <textarea class="form-control text-uppercase" id="descripcion" name="descripcion5[]" value=""  readonly>{{$descripcion5[$i]}}</textarea>
                  </div>
                </div>
                <br>
                <br>
                <br>
                <br>
                @endfor
            </div>
        </div>
       @elseif($idTramite==5)
       <div class="panel panel-success">
          <div class="panel-heading" >
            <h4 class="panel-title" >
                 NUEVOS CÓDIGOS A AGREGAR AL PRODUCTO:
            </h4>
          </div>
            <div class="panel-body">
                <br>
                <br> 
                
                <table width="100%" class="table table-bordered table-hover table-responsive">
                  <thead class="thead-inverse">
                    <tr>
                      <th>Códigos</th>
                      <th>Modelos</th>
                      <th>Descripción</th>
                    </tr>
                  </thead>
                  <tbody>
                    @for($i=0;$i<count($codigo);$i++)
                      <tr>
                        <td><input type="hidden" name="codigo[]" value="{{$codigo[$i]}}">{{strtoupper($codigo[$i])}}</td>
                        <td><input type="hidden" name="modelos[]" value="{{$modelos[$i]['id']}}">{{$modelos[$i]['modelo']}}</td>
                        <td><input type="hidden" name="descripcion[]" value="{{$descripcion6[$i]['id']}}"><input type="hidden" name="descrip[]" value="{{$descripcion6[$i]['descripcion']}}">{{$descripcion6[$i]['descripcion']}}</td>
                      </tr>
                    @endfor
                  </tbody>
                </table>  
                
            </div>
        </div>
      @elseif($idTramite==8 || $idTramite==16)
        <div class="panel panel-success">
          <div class="panel-heading" role="tab" >
            <h4 class="panel-title">
               
               @if($idTramite==8)
                ADICIÓN DE PRESENTACIONES:
              @elseif($idTramite==16)
                ELIMINACIÓN DE PRESENTACIONES:
              @endif
            </h4>
          </div>
            <div class="panel-body">
              <div class="form-group">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="input-group">
                      <div class="input-group-addon"><b>PRESENTACIONES ACTUALES:</b></div>
                      <textarea class="form-control text-uppercase" id="presentacion" name="presentacion" value=""  disabled readonly>{{$producto->PRESENTACIONES}}</textarea>
                    </div>
                  </div>
                  <br>
                  <br>
                  <br>
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="input-group">
                      <div class="input-group-addon"><b>
                        @if($idTramite==8)
                          NUEVO PRESENTACIÓN:
                        @elseif($idTramite==16)
                          PRESENTACIÓNES A ELIMINAR:
                        @endif
                        </b></div>
                      <textarea class="form-control text-uppercase" id="descripcion" name="descripcion" value=""  required readonly>{{$descripcion}}</textarea>
                    </div>
                  </div>
                          
              </div>
            </div>
          
        </div>
      @elseif($idTramite==14 || $idTramite==15)
        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">C&Oacute;DIGOS Y MODELOS SELECCIONADOS A ELIMINAR</h3>
            </div>
            <div class="panel-body">
              <div class="table-responsive">
                <table width="100" id="vwCodMods" style="font-size: 12px;" class="table table-hover table-fixed table-warning">
                  <thead>
                    <tr>
                      <th>Código</th>
                      <th>Modelo</th>
                      <th>Descripción</th>
                    </tr>
                  </thead>
                  
                  <tbody>
                      @foreach($codmods as $codmod)
                        <tr>
                          <td class="table-warning"><input type="hidden" name="codmod[]" value="{{$codmod->id_producto_codmod}}">{{$codmod->codigos}}</td>
                          <td>{{$codmod->modelos}}</td>
                          <td>{{$codmod->descripcion}}</td>
                          <th><span class="label label-danger">ELIMINAR</span></th>
                        </tr>
                      @endforeach
                  </tbody>
                </table>
              </div>
            </div>
      </div>
      @elseif($idTramite==12)
        <div class="panel panel-success">
          <div class="panel-heading">
            <h4 class="panel-title" >
              FABRICANTE SELECCIONADO:
            </h4>
          </div>
          <div  class="panel-collapse collapse in">
            <div class="panel-body">
              <div class="table-responsive">
                <table width="100%" class="table table-hover table-striped" id="fabris">
                  <thead>
                    <th></th>
                    <th>NOMBRE</th>
                    <th>TIPO FABRICANTE</th>
                    <th>DIRECCION</th>
                    <th>PAÍS</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="checkbox" class="chkPrese" checked name="idFab[]" value="{{$fabricantes[0]->ID_FABRICANTE}}"  onclick="return false;"></td>
                      <td>{{$fabricantes[0]->NOMBRE_FABRICANTE}}</td>
                      <td>{{$fabricantes[0]->TIPO_FABRICANTE}}</td>
                      <td>{{$fabricantes[0]->DIRECCION}}</td>
                      <td>{{$fabricantes[0]->NOMBRE_PAIS}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="panel panel-success">
          <div class="panel-heading" >
            <h4 class="panel-title" >
               NUEVA DIRECCION DEL FABRICANTE SELECCIONADO:
            </h4>
          </div>
          <div  class="panel-collapse collapse in" >
            <div class="panel-body">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="input-group">
                      <div class="input-group-addon"><b>NUEVA DIRECCION DEL FABRICANTE:</b></div>
                      <textarea class="form-control text-uppercase" id="descripcion" name="descripcion" value="" readonly>{{$descripcion}}</textarea>
                    </div>
                  </div>
            </div>
          </div>
        </div>
      @elseif($idTramite==6 || $idTramite==11 || $idTramite==18 || $idTramite==17 )
          <div class="panel panel-success">
          <div class="panel-heading" role="tab" id="headingFive">
            <h4 class="panel-title">
              @if($idTramite==6)
                FABRICANTES ACTUALES DEL PRODUCTO:
              @elseif($idTramite==11)
                FABRICANTE SELECCIONADO A CAMBIAR:
              @elseif($idTramite==18)
                FABRICANTE SELECCIONADO A ELIMINAR:
              @elseif($idTramite==17)
                ACONDICIONADOR SELECCIONADO A ELIMINAR:
              @endif
            </h4>
          </div>
          <div  class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-hover table-striped" id="fabricantes">
                  <thead>
                    @if($idTramite==11 || $idTramite==18 || $idTramite==17 )
                    <th></th>
                    @endif
                    <th>NOMBRE</th>
                    <th>TIPO FABRICANTE</th>
                    <th>PAÍS</th>
                  </thead>
                  <tbody>
                  @if(count($fabricantes)>0)
                    @foreach($fabricantes as $fabs)
                      <tr>
                        @if($idTramite==11 || $idTramite==18 || $idTramite==17)
                        <td><input type="checkbox" class="chkPrese" checked name="idFab[]" value="{{$fabs->ID_FABRICANTE}}" onclick="return false;"></td>
                        <td>{{$fabs->NOMBRE_FABRICANTE}}</td>
                        <td>{{$fabs->TIPO_FABRICANTE}}</td>
                        <td>{{$fabs->NOMBRE_PAIS}}</td>
                        @endif
                      </tr>
                    @endforeach
                  @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        @if($idTramite!=18 && $idTramite!=17 && $idTramite!=1)
          <div class="panel panel-success">
            <div class="panel-heading" role="tab" id="headingFive">
              <h4 class="panel-title">
                  
                  @if($idTramite==6)
                  AGREGRE EL NUEVO FABRICANTE:
                  @elseif($idTramite==11)
                  NUEVO FABRICANTE :
                @endif
              </h4>
            </div>
            <div  class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
              <div class="panel-body">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="input-group">
                        <div class="input-group-addon"><b>NUEVO FABRICANTE :</b></div>
                        <textarea class="form-control text-uppercase" id="descripcion" name="descripcion" value="" readonly>{{$descripcion}}</textarea>
                      </div>
                    </div>
              </div>
            </div>
        </div>
        @endif
      @elseif($idTramite==9 || $idTramite==10)
        <div class="panel panel-success">
          <div class="panel-heading" role="tab" id="headingFive">
            <h4 class="panel-title">
              @if($idTramite==9)
                ACONDICIONADORES ACTUALES DEL PRODUCTO:
              @elseif($idTramite==10)
                ACONDICIONADOR SELECCIONADO A CAMBIAR:
              @endif
            </h4>
          </div>
          <div  class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-hover table-striped" id="acondicionador">
                  <thead class="thead-inverse">
                    @if($idTramite==10)
                    <th></th>
                    @endif
                    <th>NOMBRE</th>
                    <th>TIPO</th>
                    <th>PAÍS</th>
                  </thead>
                  <tbody>
                    @if(count($acondicionadores)>0)
                      @foreach($acondicionadores as $acond)
                      <tr>
                        @if($idTramite==10)
                        <td><input type="checkbox" class="chkPrese" checked name="idFab[]" value="{{$acond->ID_FABRICANTE}}" onclick="return false;"></td>
                        @endif
                        <td>{{$acond->NOMBRE_FABRICANTE}}</td>
                        <td>{{$acond->TIPO_FABRICANTE}}</td>
                        <td>{{$acond->NOMBRE_PAIS}}</td>
                      </tr>
                      @endforeach
                    @else
                      <tr class="table-warning"><td class="table-warning">ESTE PRODUCTO NO POSEÉ ACONDICIONADORES</td></tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-success">
          <div class="panel-heading" role="tab" id="headingFive">
            <h4 class="panel-title">
               @if($idTramite==9)
                AGREGRE EL NUEVO ACONDICIONADOR :
                @elseif($idTramite==10)
                NUEVO ACONDICIONADOR:
              @endif
                
            </h4>
          </div>
          <div  class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
            <div class="panel-body">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="input-group">
                      <div class="input-group-addon"><b>NUEVO ACONDICIONADOR:</b></div>
                      <textarea class="form-control text-uppercase" id="descripcion" name="descripcion" value=""  readonly>{{$descripcion}}</textarea>
                    </div>
                  </div>
            </div>
          </div>
      </div>
      @endif
      @if($idTramite!=27)
      <div id="panel-mandamiento" class="panel panel-success">
                  <div class="panel-heading">
                    <h4 class="panel-title">NUMERO DE MANDAMIENTO</h4>
                  </div>
                  <div class="panel-body">
                    <table width="100%" class="table table-stripped table-hover">
                      
                      <tr>
                      <td>
                        <div class="checkbox">
                          <label>
                          <div class="input-group col-md-10 col-lg-8" >
                            <div class="input-group-addon">MANDAMIENTO CANCELADO POR DERECHOS DE TRÁMITE</div>
                              <input type="number" class="form-control" id="num_mandamiento" name="mandamiento" value="{{$mandamiento}}" required readonly>
                          </div>
                          </label>
                        </div>
                      </td>
                      </tr>
                
                    </table>
                    
                  </div>
            </div>  
        @endif
        </div>
        @endif

        @if($idTramite==28)
            <div class="panel panel-success">
                            
            <div class="panel-heading" role="tab" id="headingSix">
              <h4 class="panel-title">
                 BUSQUEDA DE PIM:
              </h4>
            </div>        
            <div class="panel-body">
              <div class="container-fluid the-box">
                <div class="form-group">
                  <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <div class="input-group">
                      <div class="input-group-addon"><b>PIM</b></div>
                      <input type="hidden" class="form-control" id="idsolicitud" name="idsolicitud" value="{{$solicitud->ID_SOLICITUD}}">
                      <input type="number" class="form-control" min="4"  max="5" id="pim" name="pim" value="{{$solicitud->PIM}}"  readonly>
                    </div>
                  </div>
                  <div class="col-xs-1 col-sm-1 col-md-3 col-lg-3">
                    
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"  >
                    <div class="input-group">
                      <div class="input-group-addon"><b>FECHA DE PRESENTACIÓN:</b></div>
                      <input type="text" class="form-control" id="fecha" name="fecha" value="{{$solicitud->FECHA_CREACION}}"  disabled>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                    <div class="input-group">
                      <div class="input-group-addon"><b>NOMBRE DEL INSUMO:</b></div>
                      <input type="text" class="form-control" id="nominsumo" name="nominsumo" value="{{$solicitud->NOMBRE_INSUMO}}"  required disabled>
                    </div>
                  </div>
                  
                </div>
                
                <div class="form-group">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="input-group">
                      <div class="input-group-addon"><b>SOLICITANTE:</b></div>
                      <input type="text" class="form-control" id="solicitante" name="solicitante" value="{{$solicitud->SOLICITANTE}}"  required disabled>
                    </div>
                  </div>
                    
                </div>

              <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
                    <div class="input-group">
                      <div class="input-group-addon"><b>NUM. MANDAMIENTO:</b></div>
                      <input type="text" class="form-control" id="mandamiento" name="mandamiento" value="{{$solicitud->NUMERO_MANDAMIENTO}}" required disabled>
                    </div>
                  </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
                    <div class="input-group">
                      <div class="input-group-addon"><b>CONTACTO:</b></div>
                      <input type="text" class="form-control" id="contacto" name="contacto" value="{{$solicitud->NOMBRE_CONTACTO}}" required disabled>
                    </div>
                  </div>
              </div>

              <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" >
                    <div class="input-group">
                      <div class="input-group-addon"><b>PROFESIONAL RESPONSABLE:</b></div>
                      <input type="text" class="form-control" id="profesional" name="profesional" value="{{$solicitud->PROFESIONAL}}" required disabled>
                    </div>
                  </div>
              </div>
              
              </div>                              
            </div>  
          </div>
      @endif
          <div class="panel panel-success">
            <div class="panel-heading" role="tab" id="headingFive">
              <h4 class="panel-title">
                  SELECCIONE LOS DOCUMENTOS A PRESENTAR EN EL TRAMITE:
              </h4>
            </div>
            
              <div class="panel-body">
                <div class="table-responsive">
                  <table width="100%" class="table table-hover table-striped" id="documentos">
                    <tbody>
                      @foreach($requisitos as $req)
                        
                        <tr><th colspan=""><u>{{$req->nombreRequisito}}<u></th></tr>
                          @foreach($documentos as $doc)
                            @if($req->requisitoId==$doc->idRequisito)
                              <tr width="100%" id="">

                              <td width="50%">{{$doc->descripcionDocumento}}</td>
                              @for($i=0;$i<count($files);$i++)
                                  @if($files[$i]['idDoc']==$doc->idRequisitoDocumento)
                                      <td><input type="hidden"  name="tipoDocumento[]" value="{{$files[$i]['idDoc']}}"></td>
                                      <td width="50%">{!!$files[$i]['uploadfile']!!}</td>
                                  @endif
                              @endfor
                              </tr>
                            @endif
                        @endforeach
                      @endforeach  
                      

          
                    </tbody>
                  </table>
                </div>
                
              </div>
            
          </div>
          
            

          
        

        <div class="panel panel-footer text-center" id="guardar">
          <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" />
          <button type="button" id="guardarSoli" name"guardar" class="btn btn-primary">Confirmar</button>

          <a class="btn btn-warning" href="{!! URL::previous() !!}">Cancelar</a>
          
        </div>
        <input type="hidden" name="img_val" id="img_val" value="" />
        <input type="hidden" name="idTramite" id="idTramite" value="{{$idTramite}}">
        <input type="hidden" name="idClasificacion" id="idClasificacion" value="{{$idClasificacion}}">
      </form>
    
  </div>


@endsection
@section('js')
{!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}
{!! Html::script('js/html2canvas.js') !!}
<script type="text/javascript">

  $('#guardarSoli').click(function() {
      alertify.confirm("Mensaje de sistema","Esta seguro que desea procesar este trámite?", function (asc) {
         if (asc) {
              html2canvas(document.body).then(function(canvas) {
              // Export the canvas to its data URI representation
              var base64image = canvas.toDataURL("image/png");
              $('#img_val').val(base64image);
              $('#guardarSolicitudSIM').submit(); 
            });
             //alertify.success("Solicitud Enviada.");
         } else {
             //alertify.error("Solicitud no enviada");
         }
        }, "Default Value").set('labels', {ok:'SI', cancel:'NO'});

  });
            
      
  
</script>
@endsection