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
        </div><!-- /.panel-body -->
    </div><!-- /.collapse in -->
</div>
<div class="panel panel-success">	

	<div class="panel-heading">
		<h3 class="panel-title">TRÁMITES POST-REGISTRO</h3>
	</div>
	<div class="panel-body">	
    
		<div class="table-responsive">		
			<table style="font-size: 12px;" id="dt-tramitessim" class="table table-hover table-striped" width="100%">
				<thead>
					<tr>
						<th>ID SOLICITUD</th>
						<th>IM</th>
						<th>NOMBRE COMERCIAL</th>
            <th>TIPO DE TRAMITE</th>
            <th>ESTADO SOLICITUD</th>
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
     
     

    var table = $('#dt-tramitessim').DataTable({

        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('dt.data.solicitudes.c.sim') }}",
        },
        columns: [
            {data: 'ID_SOLICITUD', name: 'ID_SOLICITUD',orderable:false},
            {data: 'IM', name: 'IM',orderable:false},
            {data: 'NOMBRE_INSUMO', name: 'NOMBRE_INSUMO',orderable:false},
            {data: 'nombre', name: 'nombre',orderable:false},
            {data: 'nombre_estado_post', name: 'nombre_estado_post',searchable:false,orderable:false},
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
});



</script>

@endsection