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

    #dictamenesModal{
    width:0px;
    height: 0px;
    position: center;
    top: 0%;
    left: 0%;
    margin-top: 150px;
    margin-left: 300px;
    padding: 0px;

    }



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
                    B&uacute;squeda Avanzada de Solicitudes Post-Registro
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
                        <div class="form-group col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <label>N° Solicitud:</label>
                            <input type="text" class="form-control" name="nsolicitud" id="nsolicitud" autocomplete="off">
                        </div>
                       <div class="form-group col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <label>Tipo de Tramite:</label>
                            <select name="idtipo" id="idtipo" class="form-control">
                                <option value="">Seleccione</option>
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
                                <option value="">Seleccione</option>
                                @if($estados!=null)
                                    @foreach($estados as $est)
                                        @if($est->idEstado != 0 && $est->idEstado != 10)
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
                            <input type="text" name="nomComercial" id="nomComercial" class="form-control" autocomplete="off" >
                        </div>
                           <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                           <label>Fecha creación:</label>
                           <input type="text" class="form-control datepicker date_masking2" name="fecha" id="fecha" autocomplete="off"> 

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
    <h3 class="panel-title">SOLICITUDES POST-REGISTRO COSMETICOS</h3>
  </div>
  <div class="panel-body">
    <div class="table-responsive">
      <table style="font-size: 12px;" id="dt-nuevasolicitudes" class="table table-hover table-striped" role="group" width="100%">
        <thead>
          <tr>
            <th># SOLICITUD</th>
            <th>NOMBRE COMERCIAL</th>
            <th>FECHA CREACI&Oacute;N</th>
            <th>TIPO TRAMITE</th>
            <th>TIPO PRODUCTO</th>
            <th>ESTADO</th>
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
          url: "{{ route('cosproregistro.get.rows.solicitudes')}}",
          data: function (d) {
                       
                        d.nsolicitud= $('#nsolicitud').val();
                        d.nomComercial= $('#nomComercial').val();
                        d.estado= $('#estado').val();
                        d.fecha = $('#fecha').val();
                        d.idtipo = $('#idtipo').val();

          }
      },
      columns: [
          {data: 'numeroSolicitud', name: 'numeroSolicitud',orderable:true},
          {data: 'nombreComercial', name: 'nombreComercial',orderable:false},
          {data: 'fechaCreacion', name: 'fechaCreacion',orderable:false},
          {data: 'nombreTramite', name: 'nombreTramite',orderable:false},
          {data: 'tipoProducto', name: 'tipoProducto',orderable:false},
          {data: 'estado', name: 'estado',orderable:false},
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

       $('.datepicker').datepicker({format: 'dd/mm/yyyy'});
       $('.date_masking2').mask('00/00/0000');

      

});


</script>

@endsection
