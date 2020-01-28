
@extends('master')
{{-- CSS ESPECIFICOS --}}
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

{{-- CONTENIDO PRINCIPAL --}}
@section('contenido')
{{-- ERRORES DE VALIDACIÓN --}}
@if($errors->any())
	<div class="alert alert-warning square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
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
	<div class="alert alert-danger square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<strong>Auchh!</strong>
		Algo ha salido mal.	{{ Session::get('msnError') }}
	</div>
@endif

<div class="panel panel-success">
<div class="panel-heading">
</div>
	
	
				<div class="panel-body">
        
				
                     <div class="the-box">
                        <div class="table-responsive">
                        <table class="table table-th-block table-primary table-hover" id="tr-establecimientos" width="100%">
                            <thead class="the-box dark full">
                                <tr>
                                    <th>No. Talonarios</th>
                                    <th>No. Receta</th>
                                    <th>Documento Paciente</th> 
                                    <th>Nombre Paciente</th> 
                                    <th>Producto controlado</th>
                                    <th>Estado</th>
                                    <th>Opciones</th> 
                                   
                                </tr>
                            </thead>
                            <tbody style="font-size:12px;">
                                
                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.the-box .default -->
  
			</div>

    </div>




	
@endsection

{{-- JS ESPECIFICOS --}}
@section('js')


 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 
{{-- Bootstrap Modal --}}

<script>

$(document).ready(function(){

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

 var table = $('#tr-establecimientos').DataTable({
                          serverSide: true,
                          destroy: true,
        ajax: {
            url: "{{ route('get.lista.rows.historial.recetas') }}",
            data: function (d) {
                d.txtNo= $('#txtNo').val();
                d.txtNombre= $('#txtNombre').val();
                d.txtTipo= $('#txtTipo').val();
                d.txtDepartamento= $('#txtDepartamento').val();
            },
               error: function(data){
                // Error...
                var errors = $.parseJSON(data.responseText);
                console.log(errors);
                $.each(errors, function(index, value) {
                    $.gritter.add({
                        title: 'Error',
                        text: value
                    });
                });
            }

        },
        columns: [
            {data: 'idTalonario', name: 'idTalonario'},
            {data: 'ID_RECETA', name: 'ID_RECETA'},
            {data: 'ID_PACIENTE', name: 'ID_PACIENTE'},
            {data: 'nombrePaciente', name: 'nombrePaciente'},
            {data: 'nombreComercial', name: 'nombreComercial'},
            {data: 'ubicacion_estado', name: 'ubicacion_estado'},
            {data: 'detalle', name: 'detalle',ordenable:false,searchable:false},

            
        ],
        language: {
           "sProcessing": '<div class=\"dlgwait\"></div>',
            "url": "{{ asset('plugins/datatable/lang/es.json') }}",
            "searchPlaceholder": ""
                   
            
        },
           "columnDefs": [ {
            "width": "20%",
            "searchable": false,
            "orderable": false,      
             "targets": [0],
        } ],

      "order": [[ 0, 'asc' ]]

              });


            $('#search-form').on('submit', function(e) {

                table.draw();
                e.preventDefault();
                $("#colp").attr("class", "block-collapse collapsed");
                $("#collapse-filter").attr("class", "collapse");
            });

        table.rows().remove();
    

});


</script>
@endsection
