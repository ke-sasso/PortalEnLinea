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
            B&uacute;squeda Avanzada de Publicidad
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
               		<div class="form-group col-sm-3 col-xs-12">
						<label>N° Solicitud:</label>
	               		<input type="text" class="form-control" name="nsolicitud" id="nsolicitud">
	               	</div>
	               	<div class="form-group col-sm-9 col-xs-12">
			            <label>N° Registro:</label>
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
                            @foreach($tramites as $tramite)
                                <option value="{{$tramite->nombreTramite}}">{{$tramite->nombreTramite}}</option>
                            @endforeach
                           
                      </select>      
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                                  <label>Estado de Solicitud:</label>
                          <select class="form-control" name="estado" id="estado" >
                            <option value="" selected>Seleccione un Estado</option>
                           
                            @foreach($estados as $estado)
                              <option value="{{$estado->idEstado}}">{{$estado->nombreEstado}}</option>
                           @endforeach
                            
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
		<h3 class="panel-title">TRÁMITES PUBLICITARIOS</h3>
	</div>
	<div class="panel-body">	
    
		<div class="table-responsive">		
			<table style="font-size: 12px;table-layout: fixed;" id="dt-tramitespub" class="table table-hover table-striped" width="100%">
				<thead>
					<tr>
            <th width="5%"></th>
						<th >ID SOLICITUD</th>
						<th >#SOLICITUD</th>
						<th >#REGISTRO</th>
						<th >NOMBRE COMERCIAL</th>
            <th >TIPO DE TRAMITE</th>
            <th >ESTADO SOLICITUD</th>
            <th >Dictamenes</th>
            <th >Imprimir Dictamen</th>
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
     
     

    var table = $('#dt-tramitespub').DataTable({

        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('dt.row.data.solicitudes') }}",
            data: function (d) {
                d.nsolicitud=$('#nsolicitud').val();
                d.nregistro= $('#nregistro').val();
                d.nomComercial=$('#nomComercial').val();
                d.estado=$('#estado').val();
                d.tipo=$('#tipo').val();
                
            }
        },
        columns: [
        	   {
                "className":      'details-control',
                "orderable":      false,
                "searchable":     false,
                "data":           null,
                "defaultContent": ''
            },
            {data: 'idSolicitud', name: 'sol.idSolicitud',orderable:false},
            {data: 'numeroSolicitud', name: 'sol.numeroSolicitud',orderable:false},
            {
                            "mData":null,
                            "bSortable": true,
                            "searchable":false,
                            "mRender" : function(data,type,full)
                            {
                                var est = data.idEstablecimiento;

                                var result = est.replace(/\"|\[|\]/gi,"");
                                return result;
                            }
            },
           
            {
                            "mData":null,
                            "bSortable": true,
                            "searchable":false,
                            "mRender" : function(data,type,full)
                            {
                                var est = data.nombreComercial;
                                if(est){
                                    var result = est.replace(/",\"/gi," y ");
                                    result = result.replace(/\"|\[|\]/gi,"");
                                    return result;  
                                }
                                else
                                {
                                    return '';
                                }
                                
                            }
                        },
            {data: 'nombreTramite', name: 'sol.nombreTramite',orderable:false},
            {data: 'nombreEstado', name: 'sol.nombreEstado',orderable:false},
            {data: 'dictamenes', name: 'dictamenes',searchable:false,orderable:false},
            {data: 'impDic', name: 'impDic',searchable:false,orderable:false},
            {data: 'subsanacion', name: 'subsanacion',searchable:false,orderable:false},

            
            
    
        ],
        language: {
            "sProcessing": '<div class=\"dlgwait\"></div>',
            "url": "{{ asset('plugins/datatable/lang/es.json') }}"
            
            
        },
        columnDefs: [
            {
                "targets": [7,8],
                "visible": false
            }
        ]
        
        
        
    }); //en Datatable
	
       // Add event listener for opening and closing details
    $('#dt-tramitespub tbody').on('click', 'td.details-control', function () {
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
    

    /* Formatting function for row details - modify as you need */
    function format (d) {
         
        // `d` is the original data object for the row
        
        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<tr>'+
                '<td><b>Dictamenes:<b>&nbsp;&nbsp;&nbsp;&nbsp;</td>'+
                '<td>'+
                '<td>'+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+ d.dictamenes +'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>'+
                '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
                '</td>'+
                '<td>'+d.impDic+'</td>'+
                
            '</tr>'+
            '<br>'+
 
            
        '</table>';
    }

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