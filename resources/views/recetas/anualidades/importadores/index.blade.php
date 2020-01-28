
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
    <h3 class="panel-title">PAGO DE ANUALIDADES DE  IMPORTADORES </h3>
</div>
     
				<div class="panel-body">

        <div class="form-group">
                <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Por cuenta de:</b></div>
                      <input type="text" class="form-control" id="nombre" name="nombre" value="" autocomplete="off">
                   
                    </div>
                    </div>
                
                <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Número:</b></div>
                      <input type="text" class="form-control" id="num" name="num" autocomplete="off">
                   
                    </div>
                    </div>
                </div>  
      </div>
      <div class="form-group">
           <div class="row">
                     <div class="col-sm-12 col-md-4">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>No ha pagado la Renovación</b>
                      <i class="fa fa-exclamation icon-circle icon-bordered icon-xs icon-danger"></i>
                      </div>
                   
                    </div>
                    </div>
                
                   <div class="col-sm-12 col-md-4">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Mandamiento Pagado</b>
                       <i class="fa fa-usd icon-circle icon-bordered icon-xs icon-primary"></i>
                      </div>
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Mandamiento Generado</b>
                     <i class="fa fa-file-pdf-o icon-circle icon-bordered icon-xs icon-primary"></i>
                    </div>
                    </div>
                    </div>
            </div>
      </div>
        <div class="modal-footer" >
                    <div align="center">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}" class="btn btn-primary btn-perspective"/>
                  <button type="submit"  id="btnConsultar" class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Consultar</button>
                  <button class="btn btn-warning btn-perspective" id="btnLimpiar" type="button" onclick="limpiarFormulario()"><i class="fa fa-eraser"></i> Limpiar</button>
                           </div>
        </div>

              
                <br>
        
				
          <form id="infoGeneral2" method="post"  target="_blank" action="{{route('store.mandamiento.importadores')}}">
                        <div class="table-responsive" id="lista-datos">
                        <table class="table table-th-block table-primary table-hover" id="tr-pagos">
                            <thead class="the-box dark full">
                                <tr>
                                    <th>-</th>
                                    <th>No.Registro</th>
                                    <th>Nombre Comercial</th> 
                                    <th>Propietario</th> 
                                    <th>Valor</th> 
                                    <th>Detalle</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                       <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
          </form>
               
  </div></div>







	
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
 $('#lista-datos').hide();

   $("#btnConsultar").on("click",function(){
                    var num=document.getElementById("num").value;

            if(num.length==0){
                             alertify.alert('Mensaje del sistema',"Debes ingresar un número para realizar la b&uacute;squeda.", function(){
                                    alertify.success('¡Vuelve a intentar!');
                               });
                                return false;
          }else{
             getTableData();
            $('#lista-datos').show();
          }

  });

  
});

function getTableData(){
 var table = $('#tr-pagos').DataTable({
        serverSide: false,
         filter:false,
         processing: true,
         destroy: true,
         paging: false,
        ajax: {
            url: "{{ route('get.rows.anualidades.impor') }}",
             data: function (d) {
              d.nombre=$('#nombre').val();
              d.num=$('#num').val(); 
              
            },
            error: function (xhr, error, thrown) {
            alertify.alert('Mensaje del sistema',"¡Conflicto al realizar la b&uacute;squeda, ingresa de nuevo los datos!", function(){ location.reload();});
            }

        },
        columns: [
            {data:'combobox',name:'combobox'},
            {data: 'imp_id', name: 'imp_id'},
            {data: 'imp_nombre', name: 'imp_nombre'},
            {data: 'NOMBRE_P', name: 'NOMBRE_P'},
            {data: 'valor', name: 'valor'},
            {data: 'detalle', name: 'detalle'}
        ],
        language: {
            "sProcessing": '<div class=\"dlgwait\"></div>',
            "url": "{{ asset('plugins/datatable/lang/es.json') }}",
            "searchPlaceholder": ""
                   
            
        },
           "columnDefs": [ {
            "searchable": true,
            "orderable": false,      
             "targets": [2],
        }],

        "order": [[ 2, 'asc' ]]

          });

  }

function generarMandamiento(){
 
   alertify.confirm('Mensaje de sistema','<P>Revise su mandamiento antes de guardar cambios y pagar en Banco; así también debe de asegurarse de cumplir con los requisitos para realizar su trámite.</P><br><P>Recuerde revisar la fecha de vencimiento en su Mandamiento</P><br><P>Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podra ser pagado en la red de las Agencias del Banco Agrícola, S.A.</P>',function(){
           $('#infoGeneral2').submit();
          setTimeout(function(){
            location.reload();
           }, 1000);
      },null).set('labels', {ok:'ACEPTAR', cancel:'CERRAR'}); 



}

function limpiarFormulario(){
  location.reload();
}

function eliminarHoja(idEnlace){

  alertify.confirm('Mensaje de sistema','¿Desea realmente eliminar la boleta?',function(){
    $.ajax({
      data : {_token:'{{ csrf_token() }}',txtEnlace: idEnlace,tipo:2},
      url: "{{ route('eliminar.boleta') }}",
      type: "post",
      cache: false,
      mimeType:"multipart/form-data",
       beforeSend: function() {
                //$('body').modalmanager('loading');
              },
          success:  function (response){
            $('body').modalmanager('loading');
                    if(isJson(response)){
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Boleta eliminada exitosamente!</p></strong>",function(){
                        var obj =  JSON.parse(response);
                        table.ajax.reload();
                      });
                      
                    }else{
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
                    }
                  },
          error: function(jqXHR, textStatus, errorThrown) {
                alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo eliminar la boleta!</p></strong>");
              console.log("Error en peticion AJAX!");  
          }
         });
      },null).set('labels', {ok:'SI', cancel:'NO'}); 


}

  function isJson(str) {
      try {
          JSON.parse(str);
      } catch (e) {
          return false;
      }
      return true;
  }
</script>
@endsection
