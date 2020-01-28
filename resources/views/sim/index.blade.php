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

        ::-webkit-file-upload-button {
            color: #fff;
            background-color: #29A0CB;
            border-color: #29A0CB

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

        #frmEst {
            width: 0px;
            height: 0px;
            position: center;
            top: 30%;
            left: 50%;
            margin-top: -0px;
            margin-left: -200px;
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

                            @if(Session::get('SinCerificar')==1)
                                <label>Imprimir comprobante de ingreso de cambio post-registro</label> &nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{route('comprobante.sim',['idSolicitud' => Crypt::encrypt(Session::get('idSolicitud'))])}}"
                                   target="_blank" title="Imprimir Comprobante"><i
                                            class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>
                            @elseif(Session::get('desestimiento')!=0)
                                <label>Se ha desistido su solicitud de Pre-Registro de insumos médicos
                                    exitosamente!</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{route('desistimiento.sim',['idSolicitud' => Session::get('desestimiento'), 'idTramite' => Session::get('idTramite')])}}"
                                   target="_blank" title="Imprimir Solicitud"><i
                                            class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>

                            @else
                                <label>Imprimir Resolución de la Solicitud</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;
                                <a href="{{route('imprimir.sim',['idSolicitud' => Crypt::encrypt(Session::get('idSolicitud')), 'idTramite' => Crypt::encrypt(Session::get('idTramite'))])}}"
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
            Algo ha salido mal {!! Session::get('msnError') !!}
        </div>
    @endif


    <div class="panel panel-success">
        <div class="panel-heading">
            <strong><h3 class="panel-title"></h3></strong>
        </div>
        <div class="panel-body">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    @if($sinPerfil==0)
                        <li role="presentation" class="active">
                            <a href="#panel-enlinea" aria-controls="panel-enlinea" role="tab" id="tabTramite"
                               data-toggle="tab">VENTANILLA VIRTUAL AUTOMATICA</a>
                        </li>
                    @endif
                    @if($sinPerfil==1)
                        <li role="presentation" class="active">
                    @else
                        <li role="presentation">
                            @endif
                            <a href="#panel-express" aria-controls="panel-express" role="tab" id="tabTramite"
                               data-toggle="tab">VENTANILLA VIRTUAL EXPRÉS</a>
                        </li>

                        <li role="presentation">
                            <a href="#generales" aria-controls="tab" class="hidden" role="tab" id="tabDetalleTramite"
                               data-toggle="tab">DETALLE DEL TRAMITE</a>
                        </li>
                </ul>

                
                <!-- Tab panes -->
                <div class="tab-content">
                    @if($sinPerfil==0)
                        <div role="tabpanel" class="tab-pane active" id="panel-enlinea">
                            <div class="table-responsive">
                                <br>
                                <div class="alert alert-info alert-block fade in alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>MENSAJE DEL SISTEMA:</strong> Estimado usuario, temporalmente este servicio está deshabilitado debido a que estamos optimizando el sistema, le pedimos disculpas por el inconveniente y le solicitamos atentamente se presente a nuestras ventanillas en la DNM para realizar el trámite.
                                </div>
                                <!--<table class="table table-hover" id="dt-tramites">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>NOMBRE DEL TRAMITE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tramites as $tra)
                                        @if($tra->idClasificacion==1)
                                            <tr>
                                                <td id="idTipoTramite">

                                                    <a class="btn btn-primary btn-sm"
                                                       onclick="getTabGeneral({{$tra->id}},{{$tra->idClasificacion}});">
                                                        <i id="tramite" class="fa fa-square-o"></i>
                                                    </a>
                                                </td>
                                                <td id="nomTramite"><b>{{$tra->nombre}}</b></td>
                                            </tr>
                                        @endif
                                        @if($tra->id==28)
                                            <tr>
                                                <td id="idTipoTramite">

                                                    <a class="btn btn-primary btn-sm"
                                                       onclick="getTabGeneral({{$tra->id}},{{$tra->idClasificacion}});">
                                                        <i id="tramite" class="fa fa-square-o"></i>
                                                    </a>
                                                </td>
                                                <td id="nomTramite"><b>{{$tra->nombre}}</b></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>-->
                            </div>
                        </div>
                    @endif
                    @if($sinPerfil==1)
                        <div role="tabpanel" class="tab-pane active" id="panel-express">
                            @else
                                <div role="tabpanel" class="tab-pane" id="panel-express">
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="dt-tramites">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>NOMBRE DEL TRAMITE</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($tramites as $tra)
                                                @if($tra->idClasificacion==2)
                                                    @if($tra->id!=28)
                                                        @if($sinPerfil==1)
                                                            @if($tra->id==19)
                                                                <tr>
                                                                    <td id="idTipoTramite">

                                                                        <a class="btn btn-primary btn-sm"
                                                                           onclick="getTabGeneral({{$tra->id}},{{$tra->idClasificacion}});">
                                                                            <i id="tramite" class="fa fa-square-o"></i>
                                                                        </a>
                                                                    </td>
                                                                    <td id="nomTramite"><b>{{$tra->nombre}}</b></td>
                                                                </tr>
                                                            @endif
                                                        @else
                                                            <tr>
                                                                <td id="idTipoTramite">

                                                                    <a class="btn btn-primary btn-sm"
                                                                       onclick="getTabGeneral({{$tra->id}},{{$tra->idClasificacion}});">
                                                                        <i id="tramite" class="fa fa-square-o"></i>
                                                                    </a>
                                                                </td>
                                                                <td id="nomTramite"><b>{{$tra->nombre}}</b></td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @include('sim.detalletramite')
                                @include('sim.tramites.modalcodigos')

                        </div> <!-- End tab panes -->
                </div>
            </div>
        </div>
        @endsection

        @section('js')
            {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}

            <script type="text/javascript">
                var tramites = {!!$tramites!!};
                var documentos = {!!$documentos!!};
                var requisitos = {!!$requisitos!!};
                var requisitoDoc = {!!$requisitoDoc!!};
                var dataAddProds = [];
                var data = [];
                var id_tramite;
                var nombretramite;
                var idProducto;
                var modelos = '';
                var descripcion6 = '';
                $(document).ready(function () {

                    $('#btnBuscarCod').click(function (event) {
                        //dataAddProds.length=0;
                        console.log($('#txtidproducto').val());
                        if ($('#txtidproducto').val().length == 0) {
                            alertify.alert('Mensaje del Sistema', 'Debe seleccionar un producto, antes de seleccionar los codigos o modelos!');
                        }
                        else {
                            dataAddProds.length = 0;
                            var dtproductos = $('#dt-codmods').DataTable({
                                processing: true,
                                serverSide: true,
                                destroy: true,
                                pageLength: 5,
                                ajax: {
                                    url: "{{route('get.cods.modelos.sim') }}",
                                    data: function (d) {
                                        d.idProducto = $('#txtidproducto').val();
                                        d.idTramite = id_tramite;
                                    }
                                },
                                columns: [
                                    {data: 'codigos', name: 'codigos'},
                                    {data: 'modelos', name: 'modelos'},
                                    {data: 'descripcion', name: 'descripcion'},
                                    {
                                        "mData": null,
                                        "bSortable": false,
                                        "mRender": function (data, type, full) {
                                            return '<input type=checkbox value="test your look ;)" name="idproducto" data-cod="' + data.codigos + '"  data-mod="' + data.modelos + '" data-descripcion="' + data.descripcion + '" data-id_producto_codmod="' + data.id_producto_codmod + '" class="ckb-check">'
                                        }
                                    },

                                ],
                                language: {
                                    "sProcessing": '<div class=\"dlgwait\"></div>',
                                    "url": "{{ asset('plugins/datatable/lang/es.json') }}"

                                },


                            });

                            $('#frmEst').modal('toggle');
                        }

                    });

                    $('#btnBuscarPim').click(function (event) {

                        if ($('#pim').val().length == 0) {
                            alertify.alert('Mensaje del Sistema', 'Debe digitar el numero del PIM, para poder buscar la solicitud pre-registro a desistimir!');
                        }
                        else {
                            var pim = $('#pim').val();
                            var token = $('#token').val();
                            //console.log(mandamiento);
                            $.ajax({
                                data: 'pim=' + pim + '&_token=' + token,
                                url: "{{route('get-pim')}}",
                                type: 'post',

                                beforeSend: function () {
                                    $('body').modalmanager('loading');
                                },
                                success: function (r) {
                                    $('body').modalmanager('loading');
                                    if (r.status == 200) {
                                        console.log(r.data);
                                        $('#fecha').val(r.data.FECHA_CREACION);
                                        $('#nominsumo').val(r.data.NOMBRE_INSUMO);
                                        $('#solicitante').val(r.data.SOLICITANTE);
                                        $('#mandamiento').val(r.data.NUMERO_MANDAMIENTO);
                                        $('#idsolicitud').val(r.data.ID_SOLICITUD);
                                        $('#contacto').val(r.data.NOMBRE_CONTACTO);
                                        $('#profesional').val(r.data.PROFESIONAL);


                                    }
                                    else if (r.status == 400) {
                                        alertify.alert("Mensaje de sistema - Error", r.message);
                                    } else if (r.status == 401) {
                                        alertify.alert("Mensaje de sistema", r.message, function () {
                                            window.location.href = r.redirect;
                                        });
                                    } else {//Unknown
                                        alertify.alert("Mensaje de sistema", "Este PIM no se ha encontrado en nuestra base de datos!");
                                        //console.log(r);
                                    }
                                },
                                error: function (data) {
                                    // Error...
                                    var errors = $.parseJSON(data.responseText);
                                    // console.log(errors);
                                    $.each(errors, function (index, value) {
                                        $.gritter.add({
                                            title: 'Error',
                                            text: value
                                        });
                                    });
                                }
                            });
                        }

                    });

                    $(document).on('click', '.ckb-check', function (e) {
                        if (this.checked) {
                            dataAddProds.push([$(this).data("cod"), $(this).data("mod"), $(this).data("descripcion"), $(this).data("id_producto_codmod")]);
                            console.info(dataAddProds);
                        } else {

                        }
                        data = dataAddProds;
                    });

                    $('#vwCodMods tbody tr').each(function () {

                        var codigo = $(this).find("td").eq(0).html();
                        var modelo = $(this).find("td").eq(1).html();
                        var descripcion = $(this).find("td").eq(2).html();
                        //var vigencia = $(this).find("td").eq(3).html();
                        //console.log(nombre);
                    });
                    /* ADD DESTINATION */
                    $('.multi-field-wrapper').each(function () {
                        var $wrapper = $('.multi-fields', this);
                        var x = 1;
                        $(".add-field", $(this)).click(function (e) {
                            x++;
                            $($wrapper).append('<br><div><div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><div class="input-group"><div class="input-group-addon"><b>NUEVO CÓDIGO:</b></div><input type="text" class="form-control text-uppercase" name="codigos[]" id="modelo" value="" required></div></div><br><br><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="input-group"><div class="input-group-addon"><b>NUEVO MÓDELO:</b></div><input type="text" class="form-control text-uppercase" name="modelo[] "id="modelo" value="" required></input></div></div><br><br><div class="col-xs-12 col-sm-12 col-md-11 col-lg-11"><div class="input-group"><div class="input-group-addon"><b>DESCRIPCIÓN:</b></div><textarea class="form-control text-uppercase" id="descripcion5" name="descripcion5[]" value=""></textarea></div></div><button type="button" id="removeprod" class="remove_field btn btn-danger"><i class="fa fa-trash-o"></i></button><br><br></div>');
                        });

                        $($wrapper).on("click", ".remove_field", function (e) { //user click on remove text
                            e.preventDefault();
                            $(this).parent('div').remove();
                            x--;
                        })
                    });


                    $('#guardarSoli').hide();
                    exito ={!! (Session::has('msnExito')!=null)?Session::has('msnExito'):0!!};
                    error ={!! (Session::has('msnError')!=null)?Session::has('msnError'):0!!};

                    if (error == 1) {

                        $("#imprimirModal").modal('hide');

                    }

                    if (exito == 1) {

                        $("#imprimirModal").modal('show');
                    }
                    else if (exito == 0) {

                        $("#imprimirModal").modal('hide');
                    }

                    $('#cerrar1').click(function (event) {
                        window.location.href = '{{route("indexsim")}}';
                    });

                    $('#fabs').on('click', 'input[type="checkbox"]', function () {
                        if ($(this).is(':checked')) {
                            if ($(this).data().tipo == 'SIN ASIGNAR') {
                                $(this).prop('checked', false);
                                alertify.alert('Mensaje del Sistema', '¡EL FABRICANTE NO SE PUEDE SELECCIONAR YA QUE NO TIENE ASIGNADO EL TIPO DE FABRICANTE, COMUNÍQUESE CON LA UNIDAD DE INSUMOS MÉDICOS PARA SOLVENTAR ESTE INCOVENIENTE!');
                            }
                        }
                    });


                    $('#fabs').on('click', 'input[type="checkbox"]', function () {
                        if ($(this).is(':checked')) {
                            idFab = $(this).val();
                            var checked = $(this);
                            $('#fabs tbody tr td input[type="checkbox"]').each(function () {
                                $(this).not(checked).prop('disabled', true);
                            });
                        }
                        else {
                            $('#fabs tbody tr td input[type="checkbox"]').each(function () {
                                $(this).not(checked).prop('disabled', false);
                            });
                        }
                    });

                    $('#btnBuscarProducto').click(function (event) {

                        perfil = $("#perfil option:selected").val();
                        if (perfil === '0') {
                            alertify.alert('Mensaje del Sistema', 'Seleccione un titulo para este trámite.');
                        }
                        else {
                            var dtproductos = $('#dt-producto').DataTable({
                                processing: true,
                                filter: true,
                                serverSide: true,
                                destroy: true,
                                pageLength: 5,
                                ajax: {
                                    url: "{{route('dt.data.prod.sim')}}",
                                    data: function (d) {
                                        d.perfil = perfil;
                                        d.idTramite = id_tramite;
                                    }

                                },
                                columns: [
                                    {data: 'ID_PRODUCTO', name: 'ID_PRODUCTO'},
                                    {data: 'NOMBRE_COMERCIAL', name: 'NOMBRE_COMERCIAL'},
                                    {data: 'VIGENTE_HASTA', name: 'VIGENTE_HASTA'},
                                    {data: 'ULTIMA_RENOVACION', name: 'ULTIMA_RENOVACION'},
                                    {
                                        searchable: false,
                                        "mData": null,
                                        "bSortable": false,
                                        "mRender": function (data, type, full) {
                                            if (data.alerta == 1) {
                                                return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="alertaProducto();" >' + '<i class="fa fa-check-square-o"></i>' + '</a>';
                                            }
                                            else {

                                                return '<a class="btn btn-primary btn-sm" data-dismiss="modal" onclick="selectProducto(\'' + data.CORRELATIVO + '\',\'' + data.ID_PRODUCTO + '\',\'' + data.NOMBRE_COMERCIAL + '\',\'' + data.VIGENTE_HASTA + '\',\'' + data.ULTIMA_RENOVACION + '\');" >' + '<i class="fa fa-check-square-o"></i>' + '</a>';
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

                    // funcion para validar el mandamiento
                    $('#validar').click(function (event) {
                        var mandamiento = $('#num_mandamiento').val();
                        var token = $('#token').val();
                        //console.log(mandamiento);
                        $.ajax({
                            data: 'mandamiento=' + mandamiento + '&idTipoTramite=' + id_tramite + '&_token=' + token,
                            url: "{{route('verificar-mandamiento-sim')}}",
                            type: 'post',

                            beforeSend: function () {
                                $('body').modalmanager('loading');
                            },
                            success: function (r) {
                                $('body').modalmanager('loading');
                                if (r.status == 200) {
                                    alertify.alert("Mensaje de sistema", r.message);
                                    //$('#guardar').show();
                                    $('#guardarSoli').show();
                                    validado = 1;

                                    //console.log(validado);
                                    document.getElementById("num_mandamiento").readOnly = true;

                                }
                                else if (r.status == 400) {
                                    alertify.alert("Mensaje de sistema - Error", r.message);
                                } else if (r.status == 404) {
                                    alertify.alert("Mensaje de sistema", r.message, function () {
                                        window.location.href = r.redirect;
                                    });
                                } else {//Unknown
                                    alertify.alert("Mensaje de sistema", "Error al validar el mandamiento, contacte al adminsitrador!");
                                    //console.log(r);
                                }
                            },
                            error: function (data) {
                                // Error...
                                var errors = $.parseJSON(data.responseText);
                                // console.log(errors);
                                $.each(errors, function (index, value) {
                                    $.gritter.add({
                                        title: 'Error',
                                        text: value
                                    });
                                });
                            }
                        });

                    });

                    $('#fabricantes').on('click', 'input[type="checkbox"]', function () {
                        if ($(this).is(':checked')) {
                            idFab = $(this).val();
                            var checked = $(this);
                            $('#fabricantes tbody tr td input[type="checkbox"]').each(function () {
                                $(this).not(checked).prop('disabled', true);
                            });
                        }
                        else {
                            $('#fabricantes tbody tr td input[type="checkbox"]').each(function () {
                                $(this).not(checked).prop('disabled', false);
                            });
                        }
                    });

                    $('#fabris').on('click', 'input[type="checkbox"]', function () {
                        if ($(this).is(':checked')) {
                            idFab = $(this).val();
                            var checked = $(this);
                            $('#fabris tbody tr td input[type="checkbox"]').each(function () {
                                $(this).not(checked).prop('disabled', true);
                            });
                        }
                        else {
                            $('#fabris tbody tr td input[type="checkbox"]').each(function () {
                                $(this).not(checked).prop('disabled', false);
                            });
                        }
                    });

                });

                $('#numcodigos').on('change', function () {
                    if (this.value == 0) {
                        //alertify.alert("Mensaje de sistema","Debe seleccionar un numero de cuantos codigos adiccionar");
                        $('#codigos').empty();
                    }
                    else {
                        if (!$('#txtidproducto').val()) {
                            alertify.alert("Mensaje de sistema", "Debe seleccionar un producto para realizar el tramite");
                            $("#numcodigos").val("0").change();
                        }
                        else {
                            $('#codigos').empty();
                            numcod = $("#numcodigos option:selected").val();

                            if (numcod < 10) {
                                //console.log(numcod);
                                $('#tablecodmod').hide();
                                $("#tablecodmod tbody tr").remove();
                                for (j = 0; j < numcod; j++) {
                                    $('#tablecodmod').show();
                                    $('#tablecodmod tbody').append('<tr><td><input type="text"  class="form-control text-uppercase" name="cods[]" value=""></td><td><select name="modelos[' + j + ']" class="form-control mods" id="modelos"><option value="-1" selected>Seleccione...</option><option value="0">NO APLICA</option>' + modelos + '</select></td><td><select name="descrip6[' + j + ']" class="form-control descrip" id="descripcion6"><option value="-1" selected>Seleccione...</option><option value="0">NO APLICA</option>' + descripcion6 + '</select><input type="text" class="form-control" name="descripcion6[]" placeholder="Nueva descripcion" value=""></td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button</td></tr>');
                                }

                            }
                            else {
                                $("#tablecodmod tbody tr").remove();
                                $('#tablecodmod').show();

                                $('#codigos').append('<br><br><div><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="input-group"><div class="input-group-addon"><b>CARGAR ARCHIVO:</b></div><input type="file" id="excelcod" class="form-control" name="excelcod" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  required><span class="input-group-btn"><span id="btnCargar" onclick="cargarExcelCod();" class="btn btn-info"><i class="fa fa-upload"></i></span></span></div></div></div>');
                            }
                        }
                    }
                });


                $('#guardarSoli').click(function () {
                    var modelo;
                    modelo = 0;
                    //console.log(id_tramite);
                    if (id_tramite == 28) {
                        guardar.call();
                    }
                    else {
                        if (!$('#txtidproducto').val()) {
                            alertify.alert("Mensaje de sistema", "Debe seleccionar un producto para realizar el tramite");
                        }
                        else if (id_tramite == 9) {
                            //console.log('adiccion de acondicionador');
                            if (!$('#descripcion2').val()) {
                                alertify.alert("Mensaje de sistema", "Debe digitar el nuevo acondicionador para realizar el tramite!");
                            }
                            else {
                                guardar.call();
                            }
                        }
                        else if (id_tramite == 6) {
                            //console.log('adiccion de acondicionador');
                            if (!$('#descripcion4').val()) {
                                alertify.alert("Mensaje de sistema", "Debe digitar el nuevo fabricante  para realizar el tramite!");
                            }
                            else {
                                guardar.call();
                            }
                        }
                        else if (id_tramite == 8 || id_tramite == 16) {
                            //console.log('adiccion de acondicionador');
                            if (!$('#descripcion3').val()) {
                                if (id_tramite == 8) {
                                    alertify.alert("Mensaje de sistema", "Debe digitar el la nueva presentación para realizar el tramite!");
                                }
                                else if (id_tramite == 16) {
                                    alertify.alert("Mensaje de sistema", "Debe digitar el la presentación eliminar para realizar el tramite!");
                                }
                            }
                            else {
                                guardar.call();
                            }
                        }
                        else if (id_tramite == 13) {
                            //console.log('adiccion de acondicionador');
                            if (!$('#descripcion1').val()) {
                                alertify.alert("Mensaje de sistema", "Debe digitar el nuevo periodo de vida útil para realizar el tramite!");
                            }
                            else {
                                guardar.call();
                            }
                        }
                        else if (id_tramite == 17 || id_tramite == 18) {
                            //console.log('adiccion de acondicionador');
                            if (!$('input.chkPrese').is(':checked')) {
                                if (id_tramite == 17) {
                                    alertify.alert("Mensaje de sistema", "Debe seleccionar un acondicionador a eliminar para realizar el tramite");
                                }
                                else if (id_tramite == 18) {
                                    // console.log($(this).val());
                                    alertify.alert("Mensaje de sistema", "Debe seleccionar un fabricante  a eliminar para realizar el tramite");
                                }
                            }
                            else {
                                guardar.call();
                            }
                        }
                        else if (id_tramite == 12) {
                            if (!$('input.chkPrese').is(':checked')) {
                                alertify.alert("Mensaje de sistema", "Debe seleccionar un fabricante para realizar el tramite");
                            }
                            else {
                                guardar.call();
                            }
                        }
                        else if (id_tramite == 11) {
                            if (!$('input.chkPrese').is(':checked')) {
                                alertify.alert("Mensaje de sistema", "Debe seleccionar un fabricante para realizar el tramite");
                            }
                            else if ($('#descripcion4').val() == "") {
                                alertify.alert("Mensaje de sistema", "Debe digitar el nombre del nuevo fabricante que sustituirá al fabricante seleccionado!");
                            }
                            else {
                                guardar.call();
                            }
                        }
                        else if (id_tramite == 5) {
                            var selecmod = 0;
                            var selecdesc = 0;
                            $('select.mods option:selected').each(function () {
                                if ($(this).val() == '-1') {
                                    selecmod++;
                                }
                            });

                            $('select.descrip option:selected').each(function () {
                                if ($(this).val() == '-1') {
                                    selecdesc++;
                                }
                            });

                            if (selecmod != 0) {
                                alertify.alert("Mensaje de sistema", "Debe seleccionar NO APLICA en los modelos, en caso que no seleccione un modelo existente!");
                            }
                            else if (selecdesc != 0) {
                                alertify.alert("Mensaje de sistema", "Debe seleccionar NO APLICA en las descripciones, en caso que no seleccione una descripcion existente!");
                            }
                            else if (selecdesc == 0 && selecmod == 0) {
                                guardar.call();
                            }
                        }
                        else {
                            guardar.call();
                        }
                    }
                });

                $('#noAplicaModelos').click(function () {
                    $('select.mods').each(function () {
                        $(this).val(0);
                    });
                });

                $('#noAplicaDescrips').click(function () {
                    $('select.descrip').each(function () {
                        $(this).val(0);
                    });
                });

                function guardar() {
                    alertify.confirm("Mensaje de sistema", "Esta seguro que desea procesar este trámite?", function (asc) {
                        if (asc) {
                            $('#frmSolicitudSIM').submit();
                            //alertify.success("Solicitud Enviada.");

                        } else {
                            //alertify.error("Solicitud no enviada");
                        }
                    }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});
                }

                function selectProducto(correlativo, id_producto, nombre_comercial, vigencia, renovacion) {
                    console.log(correlativo);
                    idProducto = id_producto;
                    console.log(idProducto);
                    var token = $('#token').val();
                    document.getElementById('correlativo').value = correlativo;
                    document.getElementById('txtidproducto').value = id_producto;
                    document.getElementById('nomcomercial').value = nombre_comercial;
                    document.getElementById("nombre").style.display = "block";
                    document.getElementById('txtvigencia').value = vigencia;
                    document.getElementById("vigencia").style.display = "block";
                    document.getElementById('txtrenovacion').value = renovacion;
                    document.getElementById("renovacion").style.display = "block";

                    if (id_tramite == 13 || id_tramite == 8 || id_tramite == 16) {
                        $("textarea").each(function () {
                            $(this).val("");
                        });

                        $.ajax({
                            data: 'idProducto=' + idProducto + '&_token=' + token,
                            url: "{{ route('get.producto.sim') }}",
                            type: 'post',
                            success: function (r) {
                                if (r.status == 200) {
                                    console.log(r.data);
                                    if (id_tramite == 8 || id_tramite == 16) {
                                        document.getElementById('presentacion').value = r.data.PRESENTACIONES;
                                    }
                                }
                                else if (r.status == 400) {
                                    alertify.alert("Mensaje de sistema - Error", r.message);
                                } else if (r.status == 401) {
                                    alertify.alert("Mensaje de sistema", r.message, function () {
                                        window.location.href = r.redirect;
                                    });
                                } else {//Unknown
                                    alertify.alert("Mensaje de sistema - Error", "Oops!. Algo ha salido mal, contactar con el adminsitrador del sistema para poder continuar!");
                                    //console.log(r);
                                }
                            }
                        });
                    }
                    //
                    else if (id_tramite == 6 || id_tramite == 9 || id_tramite == 10 || id_tramite == 11 || id_tramite == 18 || id_tramite == 17 || id_tramite == 12) {

                        if (id_tramite == 6 || id_tramite == 11 || id_tramite == 12) {

                            if (id_tramite == 12) {
                                var idtipo = "0";
                                var table = document.getElementById("fabris");
                                //or use :  var table = document.all.tableid;
                                for (var i = table.rows.length - 1; i >= 1; i--) {
                                    table.deleteRow(i);
                                }
                            }
                            else if (id_tramite == 6 || id_tramite == 11) {
                                var idtipo = "4";
                                var table = document.getElementById("fabricantes");
                                //or use :  var table = document.all.tableid;
                                for (var i = table.rows.length - 1; i >= 1; i--) {
                                    table.deleteRow(i);
                                }

                            }
                        }
                        else if (id_tramite == 9 || id_tramite == 10) {
                            var idtipo = "5";

                            var table = document.getElementById("acondicionador");
                            //or use :  var table = document.all.tableid;
                            for (var i = table.rows.length - 1; i >= 1; i--) {
                                table.deleteRow(i);
                            }
                        }
                        else if (id_tramite == 17 || id_tramite == 18) {
                            if (id_tramite == 17) {
                                var idtipo = "5";
                            }
                            else if (id_tramite == 18) {
                                var idtipo = "4";
                            }
                            var table = document.getElementById("fabs");
                            //or use :  var table = document.all.tableid;
                            for (var i = table.rows.length - 1; i >= 1; i--) {
                                table.deleteRow(i);
                            }

                        }

                        $.ajax({
                            data: 'idProducto=' + idProducto + '&idtipo=' + idtipo + '&_token=' + token,
                            url: "{{ route('get.fabricantes.sim.prod') }}",
                            type: 'post',
                            success: function (r) {
                                if (r.status == 200) {
                                    console.log(r.data);
                                    if (r.data.length != 0) {
                                        for (j = 0; j < r.data.length; j++) {
                                            if (id_tramite == 6) {
                                                $('#fabricantes').append('<tr><td></td><td>' + r.data[j].NOMBRE_FABRICANTE + '</td><td>' + r.data[j].TIPO_FABRICANTE + '</td><td>' + r.data[j].NOMBRE_PAIS + '</td></tr>');
                                            }
                                            if (id_tramite == 11) {
                                                console.log(id_tramite);
                                                $('#validar').show();
                                                $('#fabricantes').append('<tr><td><input type="checkbox" class="chkPrese" name="idFab[]" value="' + r.data[j].ID_FABRICANTE + '"></td><td>' + r.data[j].NOMBRE_FABRICANTE + '</td><td>' + r.data[j].TIPO_FABRICANTE + '</td><td>' + r.data[j].NOMBRE_PAIS + '</td></tr>');
                                            }
                                            if (id_tramite == 12) {
                                                console.log(id_tramite);
                                                $('#validar').show();
                                                $('#fabris').append('<tr><td><input type="checkbox" class="chkPrese" name="idFab[]" value="' + r.data[j].ID_FABRICANTE + '"></td><td>' + r.data[j].NOMBRE_FABRICANTE + '</td><td>' + r.data[j].TIPO_FABRICANTE + '</td><td>' + r.data[j].DIRECCION + '</td><td>' + r.data[j].NOMBRE_PAIS + '</td></tr>');
                                            }
                                            if (id_tramite == 18 || id_tramite == 17) {
                                                $('#validar').show();
                                                if (r.data[j].TIPO_FABRICANTE === "LEGAL") {
                                                    if (id_tramite != 1) {
                                                        $('#fabs').append('<tr><td><input type="checkbox" class="chkPrese" name="idFab[]" value="' + r.data[j].ID_FABRICANTE + '" disabled></td><td>' + r.data[j].NOMBRE_FABRICANTE + '</td><td>' + r.data[j].TIPO_FABRICANTE + '</td><td>' + r.data[j].NOMBRE_PAIS + '</td></tr>');
                                                    }
                                                    else {
                                                        $('#fabs').append('<tr><td><input type="checkbox" class="chkPrese" name="idFab[]" value="' + r.data[j].ID_FABRICANTE + '" data-tipo="' + r.data[j].TIPO_FABRICANTE + '"></td><td>' + r.data[j].NOMBRE_FABRICANTE + '</td><td>' + r.data[j].TIPO_FABRICANTE + '</td><td>' + r.data[j].NOMBRE_PAIS + '</td></tr>');

                                                    }
                                                }
                                                else {

                                                    $('#fabs').append('<tr><td><input type="checkbox" class="chkPrese" name="idFab[]" value="' + r.data[j].ID_FABRICANTE + '" data-tipo="' + r.data[j].TIPO_FABRICANTE + '"></td><td>' + r.data[j].NOMBRE_FABRICANTE + '</td><td>' + r.data[j].TIPO_FABRICANTE + '</td><td>' + r.data[j].NOMBRE_PAIS + '</td></tr>');
                                                }
                                            }
                                            if (id_tramite == 9) {
                                                $('#acondicionador').append('<tr><td></td><td>' + r.data[j].NOMBRE_FABRICANTE + '</td><td>' + r.data[j].TIPO_FABRICANTE + '</td><td>' + r.data[j].NOMBRE_PAIS + '</td></tr>');
                                            }
                                            if (id_tramite == 10) {
                                                $('#validar').show();
                                                $('#acondicionador').append('<tr><td><input type="checkbox" class="chkPrese" name="idFab[]" value="' + r.data[j].ID_FABRICANTE + '"></td><td>' + r.data[j].NOMBRE_FABRICANTE + '</td><td>' + r.data[j].TIPO_FABRICANTE + '</td><td>' + r.data[j].NOMBRE_PAIS + '</td></tr>');
                                            }

                                        }

                                    }
                                    else {
                                        if (id_tramite == 11 || id_tramite == 18) {
                                            alertify.alert("Mensaje de sistema - Error", "No puede realizar el tramite con el producto seleccionado no poseé fabricantes!");
                                            $('#guardarSoli').hide();
                                            $('#validar').hide();
                                        }
                                        else if (id_tramite == 10 || id_tramite == 17) {
                                            alertify.alert("Mensaje de sistema - Error", "No puede realizar el tramite con el producto seleccionado no posee acondicionadores!");
                                            $('#acondicionador').append('<tr class="table-warning"><td class="table-warning">ESTE PRODUCTO NO POSEÉ ACONDICIONADORES</td></tr>');
                                            $('#guardarSoli').hide();
                                            $('#validar').hide();
                                        }
                                        else if (id_tramite == 9) {
                                            $('#acondicionador').append('<tr class="table-warning"><td class="table-warning">ESTE PRODUCTO NO POSEÉ ACONDICIONADORES</td></tr>');
                                        }
                                        else if (id_tramite == 6) {
                                            $('#fabricantes').append('<tr class="table-warning"><td class="table-warning">ESTE PRODUCTO NO POSEÉ FABRICANTES </td></tr>');
                                        }

                                    }

                                }
                                else if (r.status == 400) {
                                    alertify.alert("Mensaje de sistema - Error", r.message);
                                } else if (r.status == 401) {
                                    alertify.alert("Mensaje de sistema", r.message, function () {
                                        window.location.href = r.redirect;
                                    });
                                } else {//Unknown
                                    alertify.alert("Mensaje de sistema - Error", "Oops!. Algo ha salido mal, contactar con el adminsitrador del sistema para poder continuar!");
                                    //console.log(r);
                                }
                            }
                        });
                    }
                    else if (id_tramite == 5) {

                        $('select.mods option').each(function () {
                            if ($(this).val() != '0') {
                                $(this).remove();
                            }
                        });

                        $('select.descrip option').each(function () {
                            if ($(this).val() != '0') {
                                $(this).remove();
                            }
                        });

                        modelos = '';
                        descripcion6 = '';
                        $.ajax({
                            data: 'idProducto=' + idProducto + '&_token=' + token,
                            url: "{{ route('get.modelos.sim') }}",
                            type: 'post',
                            success: function (r) {
                                if (r.status == 200) {
                                    console.log(r.data);
                                    //modelos=r;
                                    if (r.data.length != 0) {
                                        for (j = 0; j < r.data.length; j++) {
                                            modelos += '<option value="' + r.data[j].id_producto_codmod + '">' + r.data[j].modelos + '</option>';
                                            descripcion6 += '<option value="' + r.data[j].id_producto_codmod + '">' + r.data[j].descripcion + '</option>';
                                        }


                                        $("select.mods").each(function () {
                                            $(this).append(modelos);
                                        });
                                        $("select.descrip").each(function () {
                                            $(this).append(descripcion6);
                                        });
                                        //$('.mods').trigger('chosen:updated');
                                        //$('#descripcion6').trigger('chosen:updated');
                                    }
                                }
                                else if (r.status == 400) {
                                    alertify.alert("Mensaje de sistema - Error", r.message);
                                } else if (r.status == 401) {
                                    alertify.alert("Mensaje de sistema", r.message, function () {
                                        window.location.href = r.redirect;
                                    });
                                } else {//Unknown
                                    alertify.alert("Mensaje de sistema - Error", "Oops!. Algo ha salido mal, contactar con el adminsitrador del sistema para poder continuar!");
                                    //console.log(r);
                                }
                            }
                        });
                    }
                    else if (id_tramite == 15 || id_tramite == 14) {
                    }
                }

                /* ADD DESTINATION */
                $('.multi-field-wrapper-5').each(function () {
                    var $wrapper = $('.multi-fields-5', this);
                    var x = 0;
                    $(".add-field", $(this)).click(function (e) {
                        //console.log(modelos);
                        x++;
                        $($wrapper).append('<br><br><div><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="input-group"><div class="input-group-addon"><b>NUEVO MÓDELO:</b></div><input type="text" class="form-control text-uppercase" name="codigo[] "id="codigo" value="" required></input></div></div><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="input-group"><div class="input-group-addon"><b>MODELOS ACTUALES:</b></div><select name="modelos[' + x + ']" class="form-control" id="modelos"><option value="0">NO APLICA</option>' + modelos + '</select></div></div><br><br><div class="col-xs-12 col-sm-12 col-md-11 col-lg-11"><div class="input-group"><div class="input-group-addon"><b>DESCRIPCIÓN:</b></div><textarea class="form-control text-uppercase" id="descripcion6" name="descripcion6[]" value=""></textarea></div></div><button type="button" id="removeprod" class="remove_field btn btn-danger"><i class="fa fa-trash-o"></i></button></div>');
                    });

                    $($wrapper).on("click", ".remove_field", function (e) { //user click on remove text
                        e.preventDefault();
                        $(this).parent('div').remove();
                        x--;
                    })


                    $('#modelos').each(function () {

                        if (modelos) {
                            if (r.data.length != 0) {
                                for (j = 0; j < r.data.length; j++) {
                                    $('#modelos').append("<option value='" + r.data[j].id_producto_codmod + "'>" + r.data[j].modelos + "</option>");
                                }
                                $('#modelos').trigger('chosen:updated');
                            }
                        }
                        else {
                            console.log('al revesz');
                        }
                    });
                });


                function alertaProducto() {
                    alertify.alert("Mensaje de sistema", "Este producto no tiene vigente su anualidad o renovacion");
                }

                function getTabGeneral(idTramite, idClasificacion) {
                    // console.log(idTramite);
                    id_tramite = idTramite;
                    document.getElementById('correlativo').value = '';
                    document.getElementById('txtidproducto').value = '';
                    document.getElementById('txttipo').value = '';
                    document.getElementById('nomcomercial').value = '';
                    document.getElementById("nombre").style.display = "none";
                    document.getElementById('txtvigencia').value = '';
                    document.getElementById('txtrenovacion').value = '';
                    document.getElementById("renovacion").style.display = "none";
                    document.getElementById("vigencia").style.display = "none";
                    document.getElementById('idTramite').value = '';

                    $("textarea").each(function () {
                        $(this).val("");
                    });

                    $('#presentacion').empty();
                    document.getElementById('idClasificacion').value = '';
                    document.getElementById("num_mandamiento").readOnly = false;
                    document.getElementById('idTramite').value = idTramite;
                    document.getElementById('idClasificacion').value = idClasificacion;

                    var table = document.getElementById("fabricantes");
                    //or use :  var table = document.all.tableid;
                    for (var i = table.rows.length - 1; i >= 1; i--) {
                        table.deleteRow(i);
                    }
                    var table = document.getElementById("vwCodMods");
                    //or use :  var table = document.all.tableid;
                    for (var i = table.rows.length - 1; i >= 1; i--) {
                        table.deleteRow(i);
                    }

                    var table = document.getElementById("fabs");
                    //or use :  var table = document.all.tableid;
                    for (var i = table.rows.length - 1; i >= 1; i--) {
                        table.deleteRow(i);
                    }

                    var table = document.getElementById("acondicionador");
                    //or use :  var table = document.all.tableid;
                    for (var i = table.rows.length - 1; i >= 1; i--) {
                        table.deleteRow(i);
                    }
                    var table = document.getElementById("fabris");
                    //or use :  var table = document.all.tableid;
                    for (var i = table.rows.length - 1; i >= 1; i--) {
                        table.deleteRow(i);
                    }
                    $("#numcodigos").val("0").change();
                    $('#accordion1').show();
                    $('#tablecodmod').hide();
                    $('#top-table').hide();
                    $('#tragnral').hide();
                    $('#tram28').hide();
                    $('#tram8').hide();
                    $('#tram12').hide();
                    $('#tram15').hide();
                    $('#tram9').hide();
                    $('#tram6').hide();
                    $('#tram18').hide();
                    $('#tram7').hide();
                    $('#tram5').hide();
                    $('#num_mandamiento').val('');
                    // $('#descripcion').val('');
                    $('#guardarSoli').hide();

                    for (w = 0; w < tramites.length; w++) {
                        if (tramites[w].id == idTramite) {
                            nombretramite = tramites[w].nombre;
                            $("#leyendTramite").text('SOLICITUD DE: ' + tramites[w].nombre);
                        }
                    }


                    $("#documentos tr").remove();
                    for (i = 0; i < requisitos.length; i++) {
                        if (requisitos[i].tramiteTipoId == idTramite) {
                            //console.log(requisitos[i]);
                            $('#documentos').append('<tr><th width="100%" colspan=""><u>' + requisitos[i].nombreRequisito + '<u></th></tr>');
                            for (j = 0; j < requisitoDoc.length; j++) {
                                if (requisitos[i].requisitoId == requisitoDoc[j].requisitoId) {
                                    //console.log(requisitoDoc[j]);
                                    //console.log(documentos);
                                    for (w = 0; w < documentos.length; w++) {
                                        if (requisitoDoc[j].idRequisitoDocumento == documentos[w].idRequisitoDocumento) {
                                            //console.log(documentos[w]);
                                            if (documentos[w].documentoTramiteId == 1 || documentos[w].documentoTramiteId == 32) {
                                                var requisito = documentos[w].descripcionDocumento;
                                                var nuevonom = requisito.replace(/tramite/i, nombretramite.toLowerCase());
                                                //console.log(requisito);
                                                $('#documentos').append('<tr id="' + documentos[w].documentoTramiteId + '"><td>' + nuevonom + '</td><td><input type="file" id="docs" name="files[' + documentos[w].idRequisitoDocumento + ']" accept="application/pdf"  required></td></tr>');
                                            }
                                            else {
                                                $('#documentos').append('<tr id="' + documentos[w].documentoTramiteId + '"><td>' + documentos[w].descripcionDocumento + '</td><td><input type="file" id="docs" name="files[' + documentos[w].idRequisitoDocumento + ']" accept="application/pdf"  required></td></tr>');
                                            }
                                        }

                                    }
                                }
                            }

                        }
                    }

                    if (idTramite == 13) {
                        $('#tragnral').show();
                    }
                    else if (idTramite == 8 || idTramite == 16) {
                        $('#tram8').show();
                        //titlepresent
                        if (idTramite == 8) {
                            $("#titlepresent").text('ADICIÓN DE PRESENTACIONES:');
                            $("#titledespre").text('NUEVA PRESENTACIÓN:');
                        }
                        else if (idTramite == 16) {
                            $("#titlepresent").text('ELIMINACIÓN DE PRESENTACIONES:');
                            $("#titledespre").text('DIGITE LAS PRESENTACIÓNES A ELIMINAR:');
                        }
                    }
                    else if (idTramite == 7) {
                        $('#tram7').show();
                    }
                    else if (idTramite == 5) {
                        $('#tram5').show();
                    }

                    else if (idTramite == 27) {
                        $('#panel-mandamiento').hide();
                        $('#guardarSoli').show();

                    }
                    else if (idTramite == 15 || idTramite == 14) {
                        $('#tram15').show();
                        if (idTramite == 14) {
                            $("#frmModalLabel").text('SELECCIONES UNO O MÁS CÓDIGOS');
                            $("#btnBuscarCod").text('Seleccionar Códigos');
                            $("#btnBuscarCod").append('<i class="fa fa-plus"></i>');
                            $("#title1415").text('CÓDIGOS SELECCIONADOS');
                        }
                        else if (idTramite == 15) {
                            $("#frmModalLabel").text('SELECCIONES UNO O MÁS MODELOS');
                            $("#btnBuscarCod").text('Seleccionar Modelos');
                            $("#btnBuscarCod").append('<i class="fa fa-plus"></i>');
                            $("#title1415").text('MODELOS SELECCIONADOS');
                        }
                    }
                    else if (idTramite == 6 || idTramite == 11) {
                        $('#tram6').show();
                        // $('#descripcion').val('');
                        if (idTramite == 6) {
                            $("#title611").text('FABRICANTES ACTUALES DEL PRODUCTO:');
                            $("#tit611").text('AGREGRE EL NUEVO FABRICANTE:');
                        }
                        else if (idTramite == 11) {
                            $("#title611").text('SELECCIONE EL FABRICANTE A CAMBIAR:');
                            $("#tit611").text('AGREGRE EL NUEVO FABRICANTE:');
                        }
                    }
                    else if (idTramite == 12) {
                        $('#tram12').show();
                    }
                    else if (idTramite == 9 || idTramite == 10) {
                        $('#tram9').show();
                        if (idTramite == 9) {
                            $("#titletra910").text('ACONDICIONADORES ACTUALES DEL PRODUCTO:');
                            $("#tra910").text('AGREGRE EL NUEVO ACONDICIONADOR ::');
                        }
                        else {
                            $("#titletra910").text('SELECCIONE EL ACONDICIONADOR A CAMBIAR:');
                            $("#tra910").text('AGREGRE EL NUEVO ACONDICIONADOR:');
                        }
                    }
                    else if (idTramite == 18 || idTramite == 17) {
                        $('#tram18').show();
                        if (idTramite == 18) {
                            $("#title1817").text('SELECCIONE EL FABRICANTE A ELIMINAR:');
                        }
                        else if (idTramite == 17) {
                            $("#title1817").text('SELECCIONE EL ACONDICIONADOR A ELIMINAR:');
                        }

                    }
                    else if (idTramite == 28) {
                        $('#tram28').show();
                        $('#accordion1').hide();
                        $('#guardarSoli').show();
                    }
                    document.getElementById('idTramite').value = idTramite;
                    $('#tabDetalleTramite').removeClass('hidden');
                    $('.nav-tabs a[href="#generales"]').tab('show');
                }

                function guardarCodigos() {

                    for (var i = 0; i < dataAddProds.length; i++) {

                        $('#vwCodMods').append('<tr><td><input type="hidden" name="codmod[]" value="' + dataAddProds[i][3] + '">' + dataAddProds[i][0] + '</td><td>' + dataAddProds[i][1] + '</td><td>' + dataAddProds[i][2] + '</td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button</td></tr>');

                    }
                }

                $("#vwCodMods").on('click', '.btnEliminar', function () {
                    $(this).closest('tr').remove();
                });


                $("#tablecodmod").on('click', '.btnEliminar', function () {
                    $(this).closest('tr').remove();
                });

                function cargarExcelCod() {

                    //console.log('entro');
                    //var archDoc=$("#flArchivoDocumento").val();

                    $("#tablecodmod tbody tr").remove();

                    var token = $('#token').val();
                    var formData = new FormData($("#frmSolicitudSIM")[0]);
                    $.ajax({
                        data: formData,
                        url: "{{route('cargar.excel.codmods')}}",
                        type: 'post',
                        async: true,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            // $('body').modalmanager('loading');
                        },
                        success: function (r) {
                            if (r.status == 200) {
                                console.log(r.data);
                                $('#top-table').show();
                                for (j = 0; j < r.data.length; j++) {

                                    $('#tablecodmod tbody').append('<tr><td><input type="hidden" name="cods[]" value="' + r.data[j].codigo + '">' + r.data[j].codigo + '</td><td><select name="modelos[' + j + ']" class="form-control mods" id="modelos"><option value="-1" >Seleccione...</option><option value="0">NO APLICA</option>' + modelos + '</select></td><td><select name="descrip6[' + j + ']" class="form-control descrip" id="descripcion6"><option value="-1" >Seleccione...</option><option value="0">NO APLICA</option>' + descripcion6 + '</select><input type="text" class="form-control" name="descripcion6[]" placeholder="Nueva descripcion" value="' + r.data[j].descripcion + '"></td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button</td></tr>');
                                }
                            }
                            else if (r.status == 400) {
                                alertify.alert("Mensaje de sistema - Error", r.message);
                            } else if (r.status == 401) {
                                alertify.alert("Mensaje de sistema", r.message, function () {
                                    window.location.href = r.redirect;
                                });
                            } else {//Unknown
                                alertify.alert("Mensaje de sistema", "No se ha podido cargar el archivo de excel, por favor intentelo de nuevo, si este incoveniente persiste contactar a la unidad de insumos médicos!");
                                //console.log(r);
                            }
                        }
                    });

                }
            </script>
@endsection
