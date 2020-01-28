@extends('master')

@section('css')
    {!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal.css') !!}
    {!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') !!}
    <style type="text/css">

        body {

            overflow-x: hidden;
            overflow-y: scroll !important;
        }

        .dlgwait {
            display: inline;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .3) url("{{ asset('/img/ajax-loader.gif') }}") 50% 50% no-repeat;
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

        .text-uppercase {
            text-transform: uppercase;
        }

        @media screen and (min-width: 768px) {

            #modal-id .modal-dialog {
                width: 900px;
            }

        }

        #dlgProductos {
            width: 0px;
            height: 0px;
            position: center;
            top: 0%;
            left: 0%;
            margin-top: -0px;
            margin-left: 300px;
            padding: 0px;

        }

        #dlgExportacion {
            width: 0px;
            height: 0px;
            position: center;
            top: 0%;
            left: 0%;
            margin-top: -0px;
            margin-left: 300px;
            padding: 0px;

        }

        #imprimirModal {
            width: 0px;
            height: 0px;
            position: center;
            top: 5%;
            left: 50%;
            margin-top: -0px;
            margin-left: -200px;
            padding: 0px;
        }


    </style>

@endsection

@section('contenido')
    {{-- */
        $permisos = App\UserOptions::getAutUserOptions();
    /*--}}
    {{-- MENSAJE ERROR VALIDACIONES --}}
    @if($errors->any())
        <div class="alert alert-warning square fade in alert-dismissable">
            <button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
            <strong>Oops!</strong>
            Debes corregir los siguientes errores para poder continuar
            <ul class="inline-popups">
                @foreach ($errors->all() as $error)
                    <li class="alert-link">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- MENSAJE DE EXITO --}}
    @if(Session::has('msnExito'))
        <div class="modal fade" id="imprimirModal" tabindex="-1" role="dialog" aria-labelledby="myModalImprimir"
             aria-hidden="false" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="js-title-step">{!! Session::get('msnExito') !!}</h4>
                    </div>
                    <div class="modal-body">
                        <div align="center">


                            @if(Session::get('idTramite')==44)
                                <label>Esta solicitud entrara a sesión</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;
                            @else
                                <label>Imprimir Resolucion de la Solicitud</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;
                                <a href="{{route('imprimir.rv',['idSolicitud' => Session::get('idSolicitud'), 'idTramite' => Session::get('idTramite')])}}"
                                   target="_blank" title="Imprimir Solicitud"><i
                                            class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>
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
            <form method="POST" id='frmSolicitudRV' enctype="multipart/form-data"
                  action="{{ route('guardar.solicitud.rv')}}" class="form-horizontal" role="form">
                <div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true">

                    @if($idTramite==46 || $idTramite==45)
                        <div class="panel-body">
                            <label>Número de poder que corresponde al profesional :</label>
                            <div class="input-group">
                                <div class="input-group-addon"><b>NÚMERO DE PODER:</b></div>
                                <input type="text" name="numPoder" readonly class="form-control" id="numPoder"
                                       value="{{$numPoder}}">
                            </div>

                        </div>
                    @endif

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Seleccione el tipo de t&iacute;tulo para este tr&aacute;mite:</label>
                        <div class="input-group">
                            <div class="input-group-addon"><b>T&Iacute;TULO:</b></div>
                            <select name="perfil" id="perfil" class="form-control">
                                @if($perfil==='APODERADO')
                                    <option value="APODERADO" selected readonly>APODERADO</option>
                                @elseif($perfil==='PROFESIONAL')
                                    <option value="PROFESIONAL RESPONSABLE" selected readonly>PROFESIONAL RESPONSABLE
                                    </option>
                                @elseif($perfil==='PROPIETARIO')
                                    <option value="PROPIETARIO" selected readonly>PROPIETARIO</option>
                                @elseif($perfil==='REPRESENTATE LEGAL')
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
                                @if($idTramite==48)
                                    DATOS DEL PRODUCTO QUE SE MOSTRARAN EN LA CONSTANCIA:
                                @else
                                    PRODUCTO SELECCIONADO:
                                @endif
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="container-fluid the-box">
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="input-group">
                                            <div class="input-group-addon"><b>NUM. REGISTRO</b></div>
                                            <input type="text" class="form-control" id="txtregistro" name="txtregistro"
                                                   value="{{$txtregistro}}" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">

                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="tipo">
                                        <div class="input-group">
                                            <div class="input-group-addon"><b>TIPO:</b></div>
                                            <input type="text" class="form-control" id="txttipo" name="txttipo"
                                                   value="{{$txttipo}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="nombre">
                                        <div class="input-group">
                                            <div class="input-group-addon"><b>NOMBRE:</b></div>
                                            <input type="text" class="form-control" id="txtnombreprod"
                                                   name="txtnombreprod" value="{{$txtnombreprod}}" required readonly>
                                        </div>
                                    </div>

                                </div>
                            <!--
								<div class="form-group">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="renovacion" >
										<div class="input-group">
											<div class="input-group-addon"><b>RENOVACION</b></div>
											<input type="text" class="form-control" id="txtrenovacion" name="txtrenovacion" value="{{$txtrenovacion}}"  required readonly>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="vigencia" >
										<div class="input-group">
											<div class="input-group-addon"><b>VIGENCIA</b></div>
											<input type="text" class="form-control" id="txtvigencia" name="txtvigencia" value="{{$txtvigencia}}" required readonly>
										</div>
									</div>	
								</div>-->
                                @if($idTramite==48)
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="input-group">
                                                <div class="input-group-addon"><b>VÍA DE AMDNISTRACION:</b></div>
                                                <input type="text" class="form-control" id="txtrenovacion"
                                                       name="txtrenovacion"
                                                       value="{{$producto->NOMBRE_VIA_ADMINISTRACION}}" required
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="input-group">
                                                <div class="input-group-addon"><b>FORMA FARMACÉUTICA:</b></div>
                                                <input type="text" class="form-control" id="txtvigencia"
                                                       name="txtvigencia"
                                                       value="{{$formafarm->nombre_forma_farmaceutica}}" required
                                                       readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="input-group">
                                                <div class="input-group-addon"><b>CONCENTRACÍON POR UNIDAD
                                                        POSOLÓGICA:</b></div>
                                                <textarea name="posologica"
                                                          class="form-control">{!!$concentracion!!}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="input-group">
                                                <div class="input-group-addon"><b>PRESENTACIÓNES DEL PRODUCTO:</b></div>
                                                <textarea name="posologica"
                                                          class="form-control">{!!$presentaciones[0]->presentaciones!!}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="input-group">
                                                <div class="input-group-addon"><b>VIDA ÚTIL APROBADA:</b></div>
                                                <input type="text" class="form-control" id="txtrenovacion"
                                                       name="txtrenovacion" value="{{$producto->VIDA_UTIL}}" required
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><b>CONIDICONES DE ALMACENAMIENTO:</b>
                                                </div>
                                                <input type="text" class="form-control" id="txtvigencia"
                                                       name="txtvigencia"
                                                       value="{{$producto->CONDICIONES_ALMACENAMIENTO}}" required
                                                       readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><b>NOMBRE DEL TITULAR DEL REGISTRO:</b>
                                                </div>
                                                <input type="text" class="form-control" id="txtrenovacion"
                                                       name="txtrenovacion" value="{{$titular->NOMBRE_PROPIETARIO}}"
                                                       required readonly>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="input-group">
                                                <div class="input-group-addon"><b>PAÍS DEL TITULAR:</b></div>
                                                <input type="text" class="form-control" id="txtvigencia"
                                                       name="txtvigencia" value="{{$titular->NOMBRE_PAIS}}" required
                                                       readonly>
                                            </div>
                                        </div>
                                    </div>
                                    @if($fabricante!=null)
                                        @if(count($fabricante)>1)
                                            <div class="form-group">
                                                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><b>NOMBRE DEL FABRICANTE
                                                                PRINCIPAL:</b></div>
                                                        <input type="text" class="form-control" id="txtrenovacion"
                                                               name="txtrenovacion" value="{{$fabricante[0]->nombre}}"
                                                               required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><b>PAÍS DEL FAB. PRINCIPAL:</b>
                                                        </div>
                                                        <input type="text" class="form-control" id="txtvigencia"
                                                               name="txtvigencia"
                                                               value="{{$fabricante[0]->nombre_pais}}" required
                                                               readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><b>NOMBRE DEL FABRICANTE
                                                                ALTERNO:</b></div>
                                                        <input type="text" class="form-control" id="txtrenovacion"
                                                               name="txtrenovacion" value="{{$fabricante[1]->nombre}}"
                                                               required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><b>PAÍS DEL FAB. ALTERNO:</b>
                                                        </div>
                                                        <input type="text" class="form-control" id="txtvigencia"
                                                               name="txtvigencia"
                                                               value="{{$fabricante[1]->nombre_pais}}" required
                                                               readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><b>NOMBRE DEL FABRICANTE
                                                                PRINCIPAL:</b></div>
                                                        <input type="text" class="form-control" id="txtrenovacion"
                                                               name="txtrenovacion" value="{{$fabricante[0]->nombre}}"
                                                               required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><b>PAÍS DEL FAB.PRINCIPAL:</b>
                                                        </div>
                                                        <input type="text" class="form-control" id="txtvigencia"
                                                               name="txtvigencia"
                                                               value="{{$fabricante[0]->nombre_pais}}" required
                                                               readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><b>NOMBRE DEL FABRICANTE
                                                            PRINCIPAL:</b></div>
                                                    <input type="text" class="form-control" id="txtrenovacion"
                                                           name="txtrenovacion" value="N/A" required readonly>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><b>PAÍS DEL FAB. PRINCIPAL:</b></div>
                                                    <input type="text" class="form-control" id="txtvigencia"
                                                           name="txtvigencia" value="N/A" required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><b>MODALIDAD DE VENTA:</b></div>
                                                <input type="text" class="form-control" id="txtrenovacion"
                                                       name="txtrenovacion" value="{{$modV->NOMBRE_MODALIDAD_VENTA}}"
                                                       required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    @if(count($acondicionador)< 1)
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><b>NOMBRE DEL ACONDICIONADOR:</b>
                                                    </div>
                                                    <input type="text" class="form-control" id="txtrenovacion"
                                                           name="txtrenovacion" value="N/A" required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><b>NOMBRE DEL ACONDICIONADOR:</b>
                                                    </div>
                                                    <input type="text" class="form-control" id="txtrenovacion"
                                                           name="txtrenovacion"
                                                           value="{{$acondicionador[0]->nombreComercial}}" required
                                                           readonly>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($idTramite==66)
                        <div id="tra66" class="panel panel-success">
                            <div class="panel-heading">
                                <h4 class="panel-title">JUSTIFICACI&Oacute;N</h4>
                            </div>
                            <div class="panel-body">

                                <table width="100%" class="table table-bordered table-hover table-responsive">
                                    <thead class="thead-inverse">
                                    <tr>
                                        <th width="12%">N° LOTE</th>
                                        <th width="12%">UNIDADES</th>
                                        <th width="15%">FECHA</th>
                                        <th width="60%">PRESENTACION</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @for($i=0;$i<count($presentaciones);$i++)
                                        <tr>
                                            <td><input type="hidden" name="lotes[]"
                                                       value="{{strtoupper($lote[$i])}}">{{strtoupper($lote[$i])}}</td>
                                            <td><input type="hidden" name="unidades[]"
                                                       value="{{$unidades[$i]}}">{{$unidades[$i]}}</td>
                                            <td><input type="hidden" name="fechas[]"
                                                       value="{{$fecha[$i]}}">{{$fecha[$i]}}</td>
                                            <td><input type="hidden" name="presentaciones[]"
                                                       value="{{$presentaciones[$i]['idpresentacion']}}">{{$presentaciones[$i]['nompresentacion']}}
                                            </td>
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>


                            </div>
                        </div>
                    @elseif($idTramite==61)
                        <div id="tra61" class="panel panel-success">
                            <div class="panel panel-success">
                                <div class="panel-heading" role="tab" id="headingFive">
                                    <h4 class="panel-title">
                                        SELECCIONE LOS FABRICANTES A DESCONTINUAR:
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped" id="fabricantes">
                                                <thead>
                                                <th>NOMBRE</th>
                                                <th>PA&Iacute;S</th>
                                                <th>TIPO</th>
                                                </thead>
                                                <tbody>
                                                @foreach($fabricantes as $fabs)
                                                    <tr>
                                                        <td>{!!$fabs->nombre!!}</td>
                                                        <td>{!!$fabs->nombre_pais!!}</td>
                                                        @if($fabs->descontinuar==1)
                                                            <input type="hidden" name="fabDes[]"
                                                                   value="{{$fabs->id_fabricante}}">
                                                            <td>
                                                                <span class="label label-danger">{!!$fabs->nuevotipo!!}</span>
                                                            </td>
                                                        @else
                                                            <td><span class="label label-info">{!!$fabs->tipo!!}</span>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($idTramite==29 || $idTramite==27)
                        <div id="tra29" class="panel panel-success">
                            <div class="panel-heading">
                                <h4 class="panel-title">VERSION Y LA FECHA DE LA ACTUALIZACION:</h4>
                            </div>
                            <div class="panel-body">
                                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" id="existen">
                                    <div class="input-group">
                                        <div class="input-group-addon"><b>VERSION:</b></div>
                                        <input type="text" class="form-control text-uppercase" id="version"
                                               name="version" value="{{$version}}" readonly>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" id="existen">
                                    <div class="input-group">
                                        <div class="input-group-addon"><b>FECHA:</b></div>
                                        <input type="text" class="form-control date_masking_g" id="fecha" name="fecha"
                                               placeholder="00-00-0000" value="{{$fecha}}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($idTramite==36)
                        <div id="tra36" class="panel panel-success">
                            <div class="panel-heading" role="tab" id="headingFive">
                                <h4 class="panel-title">
                                    NOMBRES DE EXPORTACION A AGREGAR:
                                </h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped" id="fabricantes">
                                    <thead>
                                    <th>NOMBRE</th>
                                    <th>PA&Iacute;S</th>
                                    </thead>
                                    <tbody>
                                    @foreach($pais as $pa)
                                        <tr>
                                            <td><input type="hidden" name="nomexport"
                                                       value="{{$nomexport}}">{!!$nomexport!!}</td>
                                            <td><input type="hidden" name="idPais[]"
                                                       value="{{$pa->idPais}}">{!!$pa->nombre!!}</td>


                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    @elseif($idTramite==67)
                        <div id="tra67" class="panel panel-success">
                            <div class="panel panel-success">
                                <div class="panel-heading" role="tab" id="headingFive">
                                    <h4 class="panel-title">
                                        SELECCIONES LOS LABORATORIOS ACONDICIONADORES A DESCONTINUAR:
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped" id="labs">
                                                <thead>
                                                <th>NOMBRE</th>
                                                <th>PA&Iacute;S</th>
                                                <th>-</th>
                                                </thead>
                                                <tbody>
                                                @foreach($laboratorios as $labs)
                                                    <tr>
                                                        <td>{!!$labs->nombreComercial!!}</td>
                                                        <td>{!!$labs->nombre_pais!!}</td>
                                                        @if($labs->descontinuar==1)
                                                            <input type="hidden" name="labDescon[]"
                                                                   value="{{$labs->ID_LABORATORIO}}">
                                                            <td><span class="label label-danger">A DESCONTINUAR</span>
                                                            </td>
                                                        @else
                                                            <td><span class="label label-info">VIGENTE</span></td>
                                                        @endif
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($idTramite==64 || $idTramite==54)
                        <div id="tra54" class="panel panel-success">
                            <div class="panel-heading">
                                <h4 class="panel-title">TOMAR NOTA DE:</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group the-box">
                                    <label for="textarea" class="col-sm-2 control-label">TOMAR NOTA DE:</label>
                                    <div class="col-sm-10">
                                        <textarea name="observaciones" id="textarea" class="form-control text-uppercase"
                                                  readonly>{{strtoupper($txtobservaciones)}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($idTramite==21 || $idTramite==37 || $idTramite==38 || $idTramite==39 || $idTramite==51)
                        <div id="tra21" class="panel panel-success">
                            <div class="panel-heading">
                                <h4 class="panel-title">PRESENTACIONES</h4>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    @if($idTramite==21)
                                        <caption>PRESENTACIONES ACTUALES:</caption>
                                    @elseif($idTramite==51)
                                    @else
                                        <caption>PRESENTACIONES SELECCIONADAS:</caption>

                                    @endif

                                    <table class="table table-hover table-striped dt-presentaciones"
                                           id="dt-presentaciones">

                                        <tbody>
                                        @foreach($presentaciones as $pre)
                                            @if($idTramite==21)
                                                <tr>
                                                    <td>{!!$pre->PRESENTACION_COMPLETA!!}</td>
                                                    <td>{!!$pre->ACCESORIOS!!}</td>
                                                </tr>
                                            @elseif($idTramite==37 || $idTramite==38 || $idTramite==39)
                                                @if($pre->seleccionado==1)
                                                    <tr>
                                                        <td><input type="checkbox" name="idPresentacion[]"
                                                                   id="idPresentacion" value="{{$pre->ID_PRESENTACION}}"
                                                                   onclick="return false;" checked></td>
                                                        <td>{!!$pre->PRESENTACION_COMPLETA!!}</td>
                                                        <td>{!!$pre->ACCESORIOS!!}</td>
                                                    </tr>
                                                @endif
                                            @elseif($idTramite==51)
                                                @if($pre->descontinuar==1)
                                                    <tr>
                                                        <td>{!!$pre->PRESENTACION_COMPLETA!!}</td>
                                                        <td>{!!$pre->ACCESORIOS!!}</td>
                                                        <td><input type="hidden" name="idPre[]"
                                                                   value="{{$pre->ID_PRESENTACION}}">
                                                            <span class="label label-danger">A DESCONTINUAR</span></td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{!!$pre->PRESENTACION_COMPLETA!!}</td>
                                                        <td>{!!$pre->ACCESORIOS!!}</td>
                                                        <td><span class="label label-info">VIGENTE</span></td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                        </tbody>

                                    </table>

                                </div>

                            </div>
                        </div>
                        @if($idTramite==21)
                            <div id="tramite21" class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">NUEVA PRESENTACION A AGREGAR:</h4>
                                </div>
                                <div class="panel-body">

                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="table-responsive">
                                            <table id="presentacion" class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>NOMBRE</th>
                                                    <th>TIPO</th>
                                                    <th>TIPO MATERIAL</th>
                                                    <th>COLOR</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                @for($i=0;$i<count($present);$i++)
                                                    <tr>
                                                        <td>{!!$present[$i]!!}</td>
                                                        <td>{!!$tipo[$i]!!}</td>
                                                        <td>{!!$nomMaterial!!}</td>
                                                        <td>{!!$nomColor!!}</td>
                                                    </tr>
                                                @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="newPresent">
                                @for($i=0;$i<count($emp1);$i++)
                                    <input type="hidden" name="emp[{{$i}}]" value="{{$emp1[$i]}}">
                                    <input type="hidden" name="cont1[{{$i}}]" value="{{$cont1[$i]}}">
                                    <input type="hidden" name="cant1[{{$i}}]" value="{{$cant1[$i]}}">
                                @endfor


                                @if($emp2!=null)
                                    @for($j=0;$j<count($emp2);$j++)
                                        <input type="hidden" name="emp2[{{$j}}]" value="{{$emp2[$j]}}">
                                        @if($cont2!=null)
                                            <input type="hidden" name="cont2[{{$j}}]" value="{{$cont2[$j]}}">
                                        @endif
                                        @if($cant2!=null)
                                            <input type="hidden" name="cant2[{{$j}}]" value="{{$cant2[$j]}}">
                                        @endif
                                    @endfor
                                @endif


                                @if($emp3!=null)
                                    @for($w=0;$w<count($emp3);$w++)
                                        <input type="hidden" name="emp3[{{$w}}]" value="{{$emp3[$w]}}">
                                        @if($cont3!=null)
                                            <input type="hidden" name="cont3[{{$w}}]" value="{{$cont3[$w]}}">
                                        @endif
                                        @if($cant3!=null)
                                            <input type="hidden" name="cant3[{{$w}}]" value="{{$cant3[$w]}}">
                                        @endif
                                    @endfor
                                @endif
                                <input type="hidden" name="idmat" value="{{$idMaterial}}">
                                <input type="hidden" name="idcolor" value="{{$idColor}}">
                                <input type="hidden" name="accesorios" value="{{$accesorios}}">
                                <input type="hidden" name="tipo" value="{{$tipos}}">
                            </div>
                        @endif
                        @if($idTramite==37 || $idTramite==38 || $idTramite==39)
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">TIPO DE INFORMACION A CAMBIAR:</h4>
                                </div>
                                <div class="panel-body" style="font-size:90%;">
                                    <div class="row">

                                        <div class="col-sm-3">
                                            <label>CAMBIO DE TITULAR:</label>
                                        </div>
                                        <div class="col-sm-1">
                                            @if($titular==0)
                                                <input type="checkbox" name="titular" id="titular"
                                                       onclick="return false;" value="0">
                                            @else
                                                <input type="checkbox" name="titular" id="titular"
                                                       onclick="return false;" value="1" checked>
                                            @endif
                                        </div>

                                        <div class="col-sm-4">
                                            <label>CAMBIO DE NOMBRE DEL PRODUCTO:</label>
                                        </div>
                                        <div class="col-sm-1">
                                            @if($nomprod==0)
                                                <input type="checkbox" name="nomprod" id="nomprod"
                                                       onclick="return false;" value="0">
                                            @else
                                                <input type="checkbox" name="nomprod" onclick="return false;"
                                                       id="nomprod" checked value="3">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-sm-3">
                                            <label for="textarea">CAMBIO DE FABRICANTE:</label>
                                        </div>
                                        <div class="col-sm-1">
                                            @if($fabricante==0)
                                                <input type="checkbox" name="fabricante" id="fabricante"
                                                       onclick="return false;" value="0">
                                            @else
                                                <input type="checkbox" name="fabricante" id="fabricante"
                                                       onclick="return false;" checked value="2">
                                            @endif

                                        </div>


                                        <div class="col-sm-4">
                                            <label for="textarea">CAMBIO EN LA CONDICIONES DE ALMACENAMIENTO:</label>
                                        </div>
                                        <div class="col-sm-1">
                                            @if($condiciones==0)
                                                <input type="checkbox" name="condiciones" id="condiciones"
                                                       onclick="return false;" value="0">
                                            @else
                                                <input type="checkbox" name="condiciones" onclick="return false;"
                                                       id="condiciones" checked value="4">
                                            @endif
                                        </div>

                                    </div>
                                    <div class="row">
                                            <div class="col-sm-3">
                                                <label for="textarea">CAMBIO DE ACONDICIONADOR:</label>
                                            </div>
                                            <div class="col-sm-1">
                                                @if($acondicionador==0)
                                                    <input type="checkbox" name="acondicionador" onclick="return false;" id="acondicionador" value="0">
                                                @else
                                                    <input type="checkbox" name="acondicionador"  onclick="return false;" checked id="acondicionador" value="5">
                                                @endif
                                            </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @endif
                    @if($idTramite==57)
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">FORMA FARMACEUTICA ACTUAL</h3>
                            </div>
                            <div class="panel-body">
                                <div class="the-box full no-border">
                                    <table width="100%" class="table table-striped table-hover" id="dt-formafarm">
                                        <thead>
                                        <tr>
                                            <th>CODIGO</th>
                                            <th>NOMBRE</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{$idForma}}</td>
                                            <td>{{$nomForma}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-heading">
                                <h3 class="panel-title">FORMA FARMACEUTICA A CAMBIAR</h3>
                            </div>
                            <div class="panel-body">
                                <div class="the-box full no-border">
                                    <table width="100%" class="table table-striped table-hover" id="dt-formafarm">
                                        <thead>
                                        <tr>
                                            <th>CODIGO</th>
                                            <th>NOMBRE</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><input type="hidden" name="idFormaN" id="idFormaN"
                                                       value="{{$formanueva->ID_FORMA_FARMACEUTICA}}">{{$formanueva->ID_FORMA_FARMACEUTICA}}
                                            </td>
                                            <td>{{$formanueva->NOMBRE_FORMA_FARMACEUTICA}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    @endif
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
                                                <div class="input-group col-md-10 col-lg-8">
                                                    <div class="input-group-addon">MANDAMIENTO CANCELADO POR DERECHOS DE
                                                        TRÁMITE
                                                    </div>
                                                    <input type="number" class="form-control" id="num_mandamiento"
                                                           name="num_mandamiento" value="{{$mandamiento}}" readonly
                                                           required>
                                                </div>
                                            </label>
                                        </div>
                                    </td>
                                </tr>

                            </table>

                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading" role="tab" id="headingFive">
                            <h4 class="panel-title">
                                SELECCIONE LOS DOCUMENTOS A PRESENTAR EN EL TRAMITE
                            </h4>
                        </div>
                        <div class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table width="100%" class="table table-hover table-striped" id="documentos">

                                        <tbody>
                                        @for($i=0;$i<count($files);$i++)

                                            <tr>
                                                <td><input type="hidden" name="tipoDocumento[]"
                                                           value="{{$files[$i]['idDoc']}}"></td>
                                                <td>{!!$files[$i]['doc']!!}</td>
                                                <td>{!!$files[$i]['uploadfile']!!}</td>
                                            </tr>

                                        @endfor
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>

                <div class="panel panel-footer text-center" id="guardar">
                    <input type="hidden" name="_token" id="token" value="{{csrf_token()}}"/>
                    <button type="button" id="guardarSoli" name
                    "guardar" class="btn btn-primary">Confirmar</button>

                    <a class="btn btn-warning" href="{!! URL::previous() !!}">Cancelar</a>

                </div>
                <input type="hidden" name="img_val" id="img_val" value=""/>
                <input type="hidden" name="idArea" id="idArea" value="{{$idArea}}">
                <input type="hidden" name="idTramite" id="idTramite" value="{{$idTramite}}">
            </form>

        </div>
    </div>

@endsection
@section('js')
    {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}
    {!! Html::script('js/html2canvas.js') !!}
    <script type="text/javascript">


        var mandamiento ={!!$mandamiento!!};

        var documentos ={!!$documentos!!};


        if (mandamiento == -1) {
            $('#panel-mandamiento').hide();
        }
        else {
            $('#panel-mandamiento').show();
        }


        $('#guardarSoli').click(function () {
            alertify.confirm("Mensaje de sistema", "Esta seguro que desea procesar este trámite?", function (asc) {
                if (asc) {
                    html2canvas(document.body).then(function (canvas) {
                        // Export the canvas to its data URI representation
                        var base64image = canvas.toDataURL("image/png");
                        $('#img_val').val(base64image);
                        $('#frmSolicitudRV').submit();
                    });
                    //alertify.success("Solicitud Enviada.");

                } else {
                    //alertify.error("Solicitud no enviada");
                }
            }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});

        });


    </script>
@endsection	