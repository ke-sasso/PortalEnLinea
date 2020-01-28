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

<div class="panel panel-success">
    <div class="panel-heading" >
        <h3 class="panel-title">
            <a class="block-collapse collapsed" id='colp' data-toggle="collapse" href="#collapse-filter">
            B&uacute;squeda Avanzada de Tramites Nuevo Registro
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
    <h3 class="panel-title">TRÁMITES NUEVO-REGISTRO</h3>
  </div>
  <div class="panel-body">  
    
    <div class="table-responsive">    
      <table style="font-size: 12px;" id="dt-tramitesrv" class="table table-hover table-striped" role="group" width="100%">
        <thead>
          <tr>
            <th></th>
            <th># SOLICITUD</th>
            <th># REGISTRO</th>
            <th>NOMBRE COMERCIAL</th>
            <th>ESTADO SOLICITUD</th>
            <th>DICTAMEN M&Eacute;DICO</th>
            <th>OBSERVACION</th>
            <th>DICTAMEN QU&Iacute;MICO</th>
            <th>OBSERVACION</th>
            <th>DICTAMEN LABORATORIO</th>
            <th>OBSERVACION</th>
            <th>FECHA CREACI&Oacute;N</th>
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
       "pageLength": 5,
        processing: true,
        serverSide: true,
        "scrollX": true,
        "searching": false,
        ajax: {
            url: "{{ route('dt.data.solicitudes.pre') }}",
            data: function (d) {
                d.nsolicitud= $('#nsolicitud').val();
                d.nregistro= $('#nregistro').val();
                d.nomComercial= $('#nomComercial').val();
            }
        },
        columns: [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            {data: 'ID_SOLICITUD', name: 'ID_SOLICITUD',orderable:false},
            {data: 'NO_REGISTRO', name: 'NO_REGISTRO',orderable:false},
            {data: 'NOMBRE_COMERCIAL', name: 'NOMBRE_COMERCIAL',orderable:false},
            {data: 'ESTADO', name: 'ESTADO',orderable:false},
            {data: 'ESTADOM', name: 'ESTADOM',orderable:false},
            {data: 'OBSM', name: 'OBSM',"sClass": "observacionCenter",orderable:false},
            {data: 'ESTADOQ', name: 'ESTADOQ',orderable:false},
            {data: 'OBSQ', name: 'OBSQ',"sClass": "observacionCenter",orderable:false},
            {data: 'ESTADOL', name: 'ESTADOL',orderable:false},
            {data: 'OBSL', name: 'OBSL',"sClass": "observacionCenter"},
            {data: 'FECHA_CREACION', name: 'FECHA_CREACION',orderable:false}
            
            
        ],
        language: {
            "sProcessing": '<div class=\"dlgwait\"></div>',
            "url": "{{ asset('plugins/datatable/lang/es.json') }}"
            
            
        },
        columnDefs: [
            {
                "targets": [5,6,7,8,9,10],
                "visible": false
            }
        ]
        
    }); //en Datatable

    
    // Add event listener for opening and closing details
    $('#dt-tramitesrv tbody').on('click', 'td.details-control', function () {
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

    
});
    /* Formatting function for row details - modify as you need */
    function format (d) {
         
        // `d` is the original data object for the row
        
        return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" width="50%">'+
            '<tr>'+
                '<td><b>DICTAMEN M&Eacute;DICO:<b>&nbsp;&nbsp;</td>'+
                '<td>'+ d.ESTADOM +'&nbsp;&nbsp;</td>'+
                '<td width="10%"></td>'+
                '<td><b>OBSERVACIONES M&Eacute;DICO:<b>&nbsp;&nbsp;</td>'+
                '<td>'+ d.OBSM +'&nbsp;&nbsp;</td>'+
                
            '</tr>'+
            '<tr>'+
                '<td><b>DICTAMEN QU&Iacute;MICO:<b>&nbsp;&nbsp;</td>'+
                '<td>'+  d.ESTADOQ +'&nbsp;&nbsp;</td>'+
                '<td width="10%"></td>'+
                '<td><b>OBSERVACIONES QU&Iacute;MICO:<b>&nbsp;&nbsp;</td>'+
                '<td>'+ d.OBSQ +'&nbsp;&nbsp;</td>'+
                
            '</tr>'+
            '<tr>'+
                '<td><b>DICTAMEN LABORATORIO:<b>&nbsp;&nbsp;</td>'+
                '<td>'+ d.ESTADOL +'&nbsp;&nbsp;</td>'+
                '<td width="10%"></td>'+
                '<td><b>OBSERVACIONES LABORATORIO:<b>&nbsp;&nbsp;</td>'+
                '<td>'+ d.OBSL +'&nbsp;&nbsp;</td>'+
                
            '</tr>'+
            
            
        '</table>';
}



</script>

@endsection