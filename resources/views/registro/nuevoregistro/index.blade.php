@extends('master')

@section('css')
<style type="text/css">
</style>
@endsection

@section('contenido')

@if(Session::has('msnError'))
  <div class="alert alert-danger square fade in alert-dismissable">
    <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
    <strong>Auchh!</strong>
    Algo ha salido mal. {{ Session::get('msnError') }}
  </div>
@endif

    <div class="panel panel-success">
        <div class="panel-heading" >
            <h3 class="panel-title">
                <a class="block-collapse collapsed" id='colp' data-toggle="collapse" href="#collapse-filter">
                    B&uacute;squeda Avanzada de Solicitudes Nuevo-Registro
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
                    <div class="rows">
                        <div class="form-group col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <label>N° Solicitud:</label>
                            <input type="text" class="form-control" name="nsolicitud" id="nsolicitud" autocomplete="off">
                        </div>
                       {{--  <div class="form-group col-xs-6 col-sm-6 col-md-4 col-lg-4">
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
                        --}}
                        <div class="form-group col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <label>Estado de Tramite:</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="">Seleccione</option>
                                @if($estados!=null)
                                    @foreach($estados as $est)
                                        @if($est->idEstado != 8 && $est->idEstado != 9)
                                        <option value="{{$est->idEstado}}">{{$est->estadoPortal}}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                         <div class="form-group col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <label>Fecha Creación:</label>
                             <input type="text" name="fecha"  id="fecha" class="form-control  datepicker date_masking2" placeholder="dd-mm-yyyy"  data-date-format="dd-mm-yyyy"  autocomplete="off">
                        </div>
                    </div>
                    <div class="rows">
                        <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                           <label>Fecha subsanación:</label>
                           <input type="text" name="fechaSubsanacion"  id="fechaSubsanacion" class="form-control  datepicker date_masking2" placeholder="dd-mm-yyyy"  data-date-format="dd-mm-yyyy"  autocomplete="off">

                           </div>
                           <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                           <label>Fecha recepción:</label>
                           <input type="text" name="fechaRecep"  id="fechaRecep" class="form-control  datepicker date_masking2" placeholder="dd-mm-yyyy"  data-date-format="dd-mm-yyyy"  autocomplete="off">

                           </div>
                    </div>
                    <div class="rows">
                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Nombre Comercial:</label>
                            <input type="text" name="nomComercial" id="nomComercial" autocomplete="off" class="form-control" style="width:550px;">
                        </div>
                    </div>
                     <div class="rows">
                      <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="modal-footer" >
                            <div align="center">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" class="form-control"/>
                                <button type="submit" class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
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
          <a href="{{ route('get.preregistrorv.nuevosolicitud') }}" class="btn btn-success"><i class="fa fa-plus"></i>Agregar nueva solicitud</a>
        </div>
    </div><!-- /.btn-toolbar top-table -->
    <div class="table-responsive">
      <table style="font-size: 12px;" id="dt-nuevasolicitudes" class="table table-hover table-striped" role="group" width="100%">
        <thead>
          <tr>
            <th># SOLICITUD</th>
            <th>NOMBRE COMERCIAL</th>
            <th>FECHA CREACI&Oacute;N</th>
            <th>FECHA RECEPCI&Oacute;N</th>
            <th>FECHA SUBSANACIÓN</th>
            <th>ESTADO</th>
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

@include('registro.nuevoregistro.panel.modalDictamenes')

@section('js')
<script type="text/javascript">


$( document ).ready(function() {

    var table = $('#dt-nuevasolicitudes').DataTable({
      processing: true,
      serverSide: true,
      searching: false,
      ajax: {
          url: "{{ route('get.preregistrorv.index.getSolicitudes')}}",
          data: function (d) {
                        d.nsolicitud= $('#nsolicitud').val();
                        d.nregistro= $('#nregistro').val();
                        d.nomComercial= $('#nomComercial').val();
                        d.estado= $('#estado').val();
                        d.fecha = $('#fecha').val();
                        d.fechaRecep = $('#fechaRecep').val();
                        d.fechaSubsanacion = $('#fechaSubsanacion').val();
          }
      },
      columns: [
          {data: 'numeroSolicitud', name: 'numeroSolicitud',orderable:true},
          {data: 'nombreComercial', name: 'nombreComercial',orderable:false},
          {data: 'fechaCreacion', name: 'fechaCreacion',orderable:false},
          {data: 'fechaEnvio', name: 'fechaEnvio',orderable:false},
          {data: 'fechaSubsanacion', name: 'fechaSubsanacion',orderable:false},
          {data: 'estadoPortal', name: 'estadoPortal',orderable:false},
          {data: 'opciones', name: 'opciones',orderable:false}


      ],
      language: {
          "sProcessing": '<div class=\"dlgwait\"></div>',
          "url": "{{ asset('plugins/datatable/lang/es.json') }}"
      },
      order: [[ 0, "desc" ]]
    }); //en Datatable

      $('#search-form').on('submit', function(e) {

                table.draw();
                e.preventDefault();
                $("#colp").attr("class", "block-collapse collapsed");
                $("#collapse-filter").attr("class", "collapse");
       });

      table.rows().remove();

       $('.datepicker').datepicker({format: 'dd-mm-yyyy'});
       $('.date_masking2').mask('00-00-0000');

       $('.table').on("click", '.btnEliminarSolCos', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')     // SET TOKEN BEFORE DELETE
                    }
                });
                var elemento = $(this);
                var id = $(this).val();
                var deleteUrl = "{{url('registro-sol-registro/eliminar-sol')}}/" + id;

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


function verDictamenesModal(idSolicitud){

           $('#dt-dictamenes-estatus').dataTable().fnDestroy();
           var table = $('#dt-dictamenes-estatus').DataTable({
            processing: true,
            serverSide: false,
            searching: false,
                    ajax: {
                        url: "{{route('verlista.dictamenes.rv')}}",
                        data: function (d){
                        d.idSolicitud= idSolicitud;
                        }
                    },
                    columns: [
                        {data: 'dictamen', name: 'dictamen'},
                        {data: 'estado', name: 'estado',orderable:false},
                        {data: 'opcion', name: 'opcion',orderable:false},
                    ],
                    language: {
                        "sProcessing": '<div class=\"dlgwait\"></div>',
                        "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                    },
                    order: [[ 0, "desc" ]]
        });

      $('#dictamenesModal').modal('toggle');

}

$('#dt-nuevasolicitudes').on('click','.btnDesistirSol',function () {

    var idsol = $(this).data("id");
    alertify.confirm("Mensaje de sistema", "¡Esta a punto de desistir esta solicitud!. ¿Está seguro de realizar esta acción?", function (asc) {
                if (asc) {
                     var desistirUrl = "{{url('registro-sol-registro/solicitud-urv/desistir-sol')}}/" + idsol;
                     window.open(desistirUrl);

                } else {
                }
    }, "Default Value").set('labels', {ok: 'SI', cancel: 'NO'});

    //alert($(this).data("id"));
});


autoComplete();
function autoComplete(){
    var options = {
      url: function(search) {
        return "{{url('pre-registro-urv/search/solicitud/nombre')}}/"+search;
      },
      placeholder:"Escriba el nombre comercial del producto...",
      getValue: "nombreComercial",
      template:{
          type:"description",
          fields:{
             description: "numeroSolicitud"
          }
      },
        list: {
          maxNumberOfElements: 6,
          match: {
            enabled: true
          }
        }
    };
    $("#nomComercial").easyAutocomplete(options);
}


</script>

@endsection
