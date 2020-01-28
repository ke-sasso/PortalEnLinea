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

    .observacionCenter{
      text-align: center;
    }


</style>
@endsection

@section('contenido')
<div class="alert alert-info alert-block fade in alert-dismissable">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
 <strong>MENSAJE DEL SISTEMA:</strong> Estimado usuario, solo para los siguientes trámites CAMBIO DE NOMBRE PARA EXPORTACION, CONSTANCIAS, DESCONTINUACION DE ACONDICIONADOR, DESCONTINUACION DE FABRICANTE y DESCONTINUACIÓN DE PRESENTACIONES REGISTRADAS cuando su solicitud se encuentre con estado <b>FINALIZADA</b> le solicitamos atentamente se presente a nuestras ventanillas en la DNM para retirar su resolución firmada.
</div>
<div class="panel panel-success">
    <div class="panel-heading" >
        <h3 class="panel-title">
            <a class="block-collapse collapsed" id='colp' data-toggle="collapse" href="#collapse-filter">
            B&uacute;squeda Avanzada de Tramites Post-Registro
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
                  <div class="form-group col-xs-12 col-sm-12 col-md-2 col-lg-2">
                      <label>N° Solicitud:</label>
                    <input type="text" class="form-control" name="nsolicitud" id="nsolicitud">
                  </div>
                  <div class="form-group col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <label>N° Registro:</label>
                    <input type="text" name="nregistro" id="nregistro" class="form-control" >
                  </div>
                  <div class="form-group col-sm-8 col-xs-12">
                       <label>Tipo de Tramite:</label>
                       <select name="tipo" id="tipo" class="form-control">

                           <option value="0">Seleccione</option>
                           @foreach($tramites as $tra)
                            @if($tra->ID_TRAMITE!='N1' && $tra->ID_TRAMITE!='N2')
                              <option value="{{$tra->ID_TRAMITE}}">{{$tra->NOMBRE_TRAMITE}}</option>
                            @endif
                           @endforeach

                      </select>
                    </div>
               </div>
               <div class="row">

                    <div class="form-group col-sm-9 col-xs-12">
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
    <h3 class="panel-title">TRÁMITES POST-REGISTRO</h3>
  </div>
  <div class="panel-body">

    <div class="table-responsive">
      <table style="font-size: 12px;" id="dt-tramitesrv" class="table table-hover table-striped" role="group" width="100%">
        <thead>
          <tr>
            <th>ID SOLICITUD</th>
            <th># REGISTRO</th>
            <th>NOMBRE COMERCIAL</th>
            <th>TIPO DE TRAMITE</th>
            <th>ESTADO SOLICITUD</th>
            <th>PRESENTADO</th>
            <th>OBSERVACIONES</th>
            <th>FECHA CREACIÓN</th>
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



    var table = $('#dt-tramitesrv').DataTable({

        processing: true,
        serverSide: true,
        "searching": false,
        ajax: {
            url: "{{ route('dt.data.solicitudes.usuario') }}",
            data: function (d) {
                d.nsolicitud= $('#nsolicitud').val();
                d.nregistro= $('#nregistro').val();
                d.nomComercial= $('#nomComercial').val();
                d.tipo= $('#tipo').val();
                d.solicitante= $('#solicitante').val();
            }
        },
        columns: [
            {data: 'ID_SOLICITUD', name: 'sol.ID_SOLICITUD',orderable:false},
            {data: 'NO_REGISTRO', name: 'sol.NO_REGISTRO',orderable:false},
            {data: 'NOMBRE_COMERCIAL', name: 'prod.NOMBRE_COMERCIAL',orderable:false},
            {data: 'NOMBRE_TRAMITE', name: 'tra.NOMBRE_TRAMITE',orderable:false},
            {data: 'estado', name: 'estado',searchable:false,orderable:false},
            {data: 'ventanilla', name: 'ventanilla',searchable:false,orderable:false},
            {data: 'observaciones', name: 'observaciones',searchable:false,orderable:false, "sClass": "observacionCenter"},
            {data: 'FECHA_CREACION', name: 'FECHA_CREACION',searchable:false,orderable:false},
            {data: 'resolucion', name: 'resolucion',searchable:false,orderable:false}


        ],
        language: {
            "sProcessing": '<div class=\"dlgwait\"></div>',
            "url": "{{ asset('plugins/datatable/lang/es.json') }}"


        },
        columnDefs: [
            {

                "visible": false
            }
        ]

    }); //en Datatable

    $('#search-form').on('submit', function(e) {

        table.draw();
        e.preventDefault();
        $("#colp").attr("class", "block-collapse collapsed");
        $("#collapse-filter").attr("class", "collapse");
    });

    table.rows().remove();
});


function confirmDesistir(idSol){
      var token ='{{csrf_token()}}';
      alertify.confirm("Mensaje del Sistema","Esta seguro de desistir la solicitud "+idSol+" de cancelación de registro?",
        function(){
             $.ajax({
                data:  'idSolicitud='+idSol+'&_token='+token,
                url:   "{{route('solrv.desistimiento')}}",
                type:  'post',
                success:  function (r){
                    table.draw();
                }
              });
         }, function(){ }).set('labels', {ok:'SI', cancel:'NO'});
  }



</script>

@endsection