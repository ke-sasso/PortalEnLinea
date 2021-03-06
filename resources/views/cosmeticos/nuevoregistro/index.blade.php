@extends('master')

@section('css')

    {!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal.css') !!}
    {!! Html::style('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') !!}
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


    {{-- MENSAJE DE EXITO --}}
    @if(Session::has('msnExito'))
        <div class="alert alert-success square fade in alert-dismissable">
            <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
            <strong>Enhorabuena!</strong>
            {{ Session::get('msnExito') }}
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

    <div class="panel panel-success">
        <div class="panel-heading" >
            <h3 class="panel-title">
                <a class="block-collapse collapsed" id='colp' data-toggle="collapse" href="#collapse-filter">
                    B&uacute;squeda Avanzada de Solicituds Nuevo-Registro
                    <span class="right-content">
                <span class="right-icon"><i class="fa fa-plus icon-collapse"></i></span>
            </span>
                </a>
            </h3>
        </div>



        <div id="collapse-filter" class="collapse" style="height: 0px;">
            <div class="panel-body">

                {{-- COLLAPSE CONTENT --}}
                <form role="form" id="search-form">
                    <div class="row">
                        <div class="form-group col-xs-6 col-sm-6 col-md-2 col-lg-2">
                            <label>N° Solicitud:</label>
                            <input type="text" class="form-control" name="nsolicitud" id="nsolicitud">
                        </div>
                        <div class="form-group col-xs-6 col-sm-6 col-md-2 col-lg-2">
                            <label>N° Registro:</label>
                            <input type="text" name="nregistro" id="nregistro" class="form-control" >
                        </div>
                        <div class="form-group col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <label>Tipo de Tramite:</label>
                            <select name="tipo" id="tipo" class="form-control">
                                <option value="0">Seleccione</option>
                                @if($tramites!=null)
                                    @foreach($tramites as $tra)
                                        <option value="{{$tra->idTramite}}">{{$tra->nombreTramite}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <label>Estado de Tramite:</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="sinseleccion">Seleccione</option>
                                @if($estados!=null)
                                    @foreach($estados as $est)
                                        @if($est->idEstado != 2 && $est->idEstado != 8 && $est->idEstado != 10)
                                        <option value="{{$est->idEstado}}">{{$est->estado}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-12 col-md-8 col-lg-8">
                            <label>Nombre Comercial:</label>
                            <input type="text" name="nomComercial" id="nomComercial" class="form-control" >
                        </div>
                    </div>

                    <div class="modal-footer" >
                        <div align="center">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" class="form-control"/>
                            <button type="submit" class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </form>
                {{-- /.COLLAPSE CONTENT --}}
            </div><!-- /.panel-body -->
        </div><!-- /.collapse in -->
    </div>

    <div class="panel panel-success">

        <div class="panel-heading">
            <h3 class="panel-title">SOLICITUDES NUEVO-REGISTRO</h3>
        </div>
        <div class="panel-body">
            <div class="btn-toolbar top-table" role="toolbar">
                <div class="btn-group">
                    <a href="{{route('get.cospreregistro.nuevasolicitud')}}" class="btn btn-success"><i class="fa fa-plus"></i>Agregar nueva solicitud</a>
                </div>
            </div><!-- /.btn-toolbar top-table -->
            <div class="table-responsive">
                <table style="font-size: 12px;" id="dt-nuevasolicitudes" class="table table-hover table-striped" role="group" width="100%">
                    <thead>
                    <tr>
                        <th></th>
                        <th>CORRELATIVO SOL.</th>
                        <th>N° REGISTRO</th>
                        <th>NOMBRE COMERCIAL</th>
                        <th>TIPO TRÁMITE</th>
                        <th>ESTADO</th>
                        <th>FECHA ENVIO</th>
                        <th>-</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">


        $( document ).ready(function() {

            var table = $('#dt-nuevasolicitudes').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('cospresolicitud.dtrows-sol') }}",
                    data: function (d) {
                        d.nsolicitud= $('#nsolicitud').val();
                        d.nregistro= $('#nregistro').val();
                        d.nomComercial= $('#nomComercial').val();
                        d.idTra= $('#tipo').val();
                        d.estado= $('#estado').val();
                    }
                },
                columns: [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    {data: 'numeroSolicitud', name: 'numeroSolicitud'},
                    {data: 'idProducto', name: 'idProducto'},
                    {data: 'nombreComercial', name: 'nombreComercial',orderable:false},
                    {data: 'nombreTramite', name: 'nombreTramite'},
                    {data: 'estado', name: 'estado'},
                    {data: 'fechaEnvio', name: 'fechaEnvio'},
                    {data: 'resolucion', name: 'resolucion',orderable:false},
                    {data: 'countObs', name: 'countObs',orderable:false},
                    {data: 'estadoDic', name: 'estadoDic',orderable:false},
                    {data: 'linkresol', name: 'linkresol',orderable:false},

                ],
                language: {
                    "sProcessing": '<div class=\"dlgwait\"></div>',
                    "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                },
                columnDefs: [
                    {
                        "targets": [8,9,10],
                        "visible": false
                    }
                ],
                order: [[ 1, "desc" ]]

            }); //en Datatable

            // Add event listener for opening and closing details
            $('#dt-nuevasolicitudes tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }
            });

            $('#search-form').on('submit', function(e) {

                table.draw();
                e.preventDefault();
                $("#colp").attr("class", "block-collapse collapsed");
                $("#collapse-filter").attr("class", "collapse");
            });

            table.rows().remove();

            $('.table').on("click", '.btnEliminarSolCos', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')     // SET TOKEN BEFORE DELETE
                    }
                });
                var elemento = $(this);
                var id = $(this).val();
                var deleteUrl = "{{url('pre-sol-cos/eliminar-solCosm')}}/" + id;
         
                console.log(deleteUrl);
                alertify.confirm("Mensaje de sistema", "Esta a punto de eliminar esta solicitud! no podrá recuperarla. ¿Está seguro que desea eliminarla?", function (asc) {
                if (asc) {
                    $.ajax({
                        type: "GET",
                        url: deleteUrl,
                        success: function (data) {
                            elemento.parent('td').parent('tr').remove();
                            alertify.alert("Mensaje de sistema","Se eliminó su solicitud correctamente!");
                            console.log(elemento.val() +'dsadsa');
                        },
                        error: function (data) {
                            console.log('Error:', "No se pudo eliminar la solicitud, contacte a DNM Informática!");
                        }
                    });
                } else {
                }
            }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});
                
            });

        });

        /* Formatting function for row details - modify as you need */
        function format (d) {

            // `d` is the original data object for the row
            if(d.estadoDic=='OBSERVADO'){
                return '<table cellpadding="3" cellspacing="0" border="0" style="padding-left:50px;" width="50%">'+
                    '<tr>'+
                    '<td><b>DICTAMEN:<b></td>'+
                    '<td>'+ d.estadoDic +'</td>'+
                    '<td width="10%"></td>'+
                    '<td><b>RESOLUCIÓN:<b></td>'+
                    '<td>'+ d.linkresol +'</td>'+
                    '<td width="10%"></td>'+
                    '<td><b>OBSERVACIONES:<b></td>'+
                    '<td>'+ d.countObs +'</td>'+
                    '</tr>'+
                    '</table>';
            }
            else{
                return '<table cellpadding="3" cellspacing="0" border="0" style="padding-left:30px;" width="30%">'+
                    '<tr>'+
                    '<td><b>DICTAMEN:<b></td>'+
                    '<td>'+ d.estadoDic +'</td>'+
                    '<td width="10%"></td>'+
                    '<td><b>OBSERVACIONES:<b></td>'+
                    '<td>'+ d.countObs +'</td>'+
                    '</tr>'+
                    '</table>';
            }


        }
    </script>

@endsection