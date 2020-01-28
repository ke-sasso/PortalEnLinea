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
<!--
<div class="panel panel-success">
    <div class="panel-heading" >
        <h3 class="panel-title">
            <a class="block-collapse collapsed" id='colp' data-toggle="collapse" href="#collapse-filter">
            B&uacute;squeda de Solicitudes
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
               		<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
						          <label>N° Solicitud:</label>
	               		<input type="text" class="form-control" name="nsolicitud" id="nsolicitud">
	               	</div>
	               
               		<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <label>IM:</label>
                    <input type="text" name="nregistro" id="nregistro" class="form-control" >         
                  </div>
               </div>
               <div class="row">
               		
                    <div class="form-group col-sm-9 col-xs-12">
                        <label>Nombre Comercial:</label>
                        <input type="text" name="nomComercial" id="nomComercial" class="form-control" >        
                    </div>

	               
               </div>

               <div class="row">
               		   <div class="form-group col-sm-6 col-xs-12">
                       <label>Tipo de Tramite:</label>
                       <select name="tipo" id="tipo" class="form-control">
                           
                           <option value="" selected></option>
                           
                      </select>      
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
        </div>
    </div>
</div>
-->
<div class="modal fade" id="modal-id">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Fecha de ingreso al Pa&iacute;s</h4>
      </div>
      <div class="modal-body">
        <form id="frmFechaIngreso">
        
        
          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control datepicker" id="fechaIngreso" name="fechaIngreso" placeholder="Fecha de ingreso">              
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="idSolicitud" id="idSolicitud" class="form-control" value="">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnFechaIngreso">Guardar</button>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-success">	

	<div class="panel-heading">
		<h3 class="panel-title">TRÁMITES POST-REGISTRO</h3>
	</div>
	<div class="panel-body">	
    
		<div class="table-responsive">		
			<table width="100%" style="font-size: 12px;" id="dt-tramitessim" class="table table-hover table-striped" width="100%">
				<thead>
					<tr>
						<th>Id Solicitud</th>
						<th>No. Registro</th>
						<th>Nombre Comercial</th>
            <th>No. Lote</th>
            <th>Estado Solicitud</th>
            <th width="20%">Fecha Ingreso de Lote</th>
            
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
     
    $(function () {
                $('.datepicker').datepicker({format: 'dd-mm-yyyy'}).on('changeDate',function(){
                  $(this).datepicker('hide');
                });
            });

    var table = $('#dt-tramitessim').DataTable({

        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('dt.data.vacunas.solicitudes') }}",
        },
        columns: [
            {data: 'id', name: 'id',orderable:false},
            {data: 'noregistro', name: 'noregistro',orderable:false},
            {data: 'nombre_producto', name: 'nombre_producto',orderable:false},
            {data: 'numero_lote', name: 'numero_lote',orderable:false},
            {data: 'estado', name: 'estado',searchable:false,orderable:false},
            {data: 'fechaEnPais', name: 'fechaEnPais',searchable:false,orderable:false}
            
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

    $('#dt-tramitessim tbody').on('click', 'div.input-group span.input-group-btn', function(event) {
      event.preventDefault();
      
      var btn = $(this).parent().parent().parent();      

      var row  = table.row(btn).data();

      $('#fechaIngreso').val(row.fechaIngresoLote);
      $('#idSolicitud').val(row.id);
      $('#modal-id').modal('show');

    });


    $('#btnFechaIngreso').on('click', function(event) {
      event.preventDefault();
      $.post('{{ route('fecha.ingreso.lote') }}', $('#frmFechaIngreso').serialize(), function(data, textStatus, xhr) {
        try
        {
          alertify.alert(data.message,function(){
            $('#modal-id').modal('hide');
            table.ajax.reload();
          });
        }
        catch(e)
        {
          console.log(e)
        }
      }).fail(function(response){
        try
        {
          var obj = JSON.parse(response.responseText);
          alertify.alert(obj.message);
        }
        catch(e)
        {
          console.log(e);
        }
      });
    });

});



</script>

@endsection