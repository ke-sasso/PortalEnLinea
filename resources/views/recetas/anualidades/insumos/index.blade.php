
@extends('master')
{{-- CSS ESPECIFICOS --}}
@section('css')
{!! Html::style('plugins/bootstrap-tour/css/bootstrap-tour.css') !!}
{!! Html::style('plugins/select2/css/select2.min.css') !!}
<style type="text/css">
.select2-container .select2-selection--single {
    height: 34px !important;
    width: 100%;
    border-radius: 0px !important;
}
</style>
@endsection

{{-- CONTENIDO PRINCIPAL --}}
@section('contenido')
{{-- ERRORES DE VALIDACIÓN --}}
@if($errors->any())
  <div class="alert alert-info alert-block fade in alert-dismissable">
    <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
      <strong>Debes corregir los siguientes errores para poder continuar:</strong>
    <ul class="inline-popups">
      @foreach ($errors->all() as $error)
        <strong>{!! $error !!}</strong>
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
    <strong>¡Mensajes del sistema!</strong>
    ADVERTENCIA {!! Session::get('msnError') !!}
  </div>
@endif
<div align="left">
			<button type="button" id="starTourPaso" class="btn btn-info btn-perspective"><b>INSTRUCCIONES GENERALES</b><span class="fa  fa-info-circle"></span></button>
</div>
<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title">PAGO DE ANUALIDADES DE INSUMOS MÉDICOS </h3>
</div>

				<div class="panel-body">

        <div class="form-group">
                <div class="row">
                     <div class="col-sm-12 col-md-12" id="paso1">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Por cuenta de:</b></div>
                      <input type="text" class="form-control" id="nombre" name="nombre" value="" autocomplete="off">

                    </div>
                    </div>
      </div>
      </div>
        <div class="form-group">
                <div class="row">
                     <div class="col-sm-12 col-md-12" id="paso2">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Propietario:</b></div>
                      <select class="form-control"  name="propietario" id="propietario">
                        <option value="" selected>SELECCIONAR PROPIETARIO...</option>
                         @if(!empty($propietarios))
                         @foreach($propietarios as $prop)
                        <option value="{{$prop->ID_PROPIETARIO}}">{{$prop->NOMBRE_PROPIETARIO}} ({{$prop->ID_PROPIETARIO}})</option>
                         @endforeach
                         @endif
                      </select>

                    </div>
                    </div>
      </div>
      </div>

      <div class="form-group">
           <div class="row" id="paso3">
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
        <div class="form-group" id="paso4">
                <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Por total de productos:</b></div>
                      <input type="text" class="form-control" id="idTotal" name="idTotal" value="" autocomplete="off" disabled>

                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Total mandamiento:</b></div>
                      <input type="text" class="form-control" id="idTotalMan" name="idTotalMan" value="" autocomplete="off" disabled>

                    </div>
                    </div>
      </div>
      </div>
        <div class="modal-footer" >
                    <div align="center" id="paso5">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}" class="btn btn-primary btn-perspective"/>
                  <button type="submit"  id="btnConsultar" class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Consultar</button>
                  <button class="btn btn-warning btn-perspective" id="btnLimpiar" type="button" onclick="limpiarFormulario()"><i class="fa fa-eraser"></i> Limpiar</button>
                           </div>
        </div>


  </div></div>



          <form id="infoGeneral2" method="post"  action="{{route('store.mandamiento.insumos')}}">
                        <div class="table-responsive the-box  no-border" id="lista-datos">
                        <table class="table table-th-block table-primary table-hover" id="tr-pagos">
                            <thead class="the-box dark full">
                                <tr>
                                    <th><input type="checkbox" name="checkTodos" id="checkTodos"></th>
                                    <th>Nombre Comercial</th>
                                    <th>Anualidad</th>
                                    <th>Renovación</th>
                                    <th>Valor</th>


                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <br>

                        <center><a class="btn btn-xs btn-success btn-perspective" onclick="generarMandamiento();" ><i class="fa fa-check-square-o"></i>Generar mandamiento</a> <a class="btn btn-xs btn-success btn-perspective" onclick="imprimirProductos();" ><i class="fa fa-check-square-o"></i>Imprimir lista de productos</a></center>

                    </div><!-- /.table-responsive -->
                       <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                       <input type="hidden" name="propietarioval" id="propietarioval">
                       <input type="hidden" name="cuentade" id="cuentade">
          </form>


<form id="infoGeneral3" method="post"  action="{{route('lista.productos.insumos')}}">
<input type="hidden" name="pro1" id="pro1"/>
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
</form>







@endsection

{{-- JS ESPECIFICOS --}}
@section('js')
{!! Html::script('plugins/bootstrap-tour/js/bootstrap-tour.js') !!}  
{!! Html::script('js/mandamientos/anualidades/tourpasos.js') !!}
{!! Html::script('plugins/select2/js/select2.min.js') !!}
{{-- Bootstrap Modal --}}

<script>
var table;
var totalCheck = 0;
var total=0;
var t1=0;
var t2=0;
$(document).ready(function(){
  stepInsumos();
    $('#propietario').select2({});
  $("#checkTodos").change(function () {

      $("input:checkbox").prop('checked', $(this).prop("checked"));
      var totalCheck  = $('input:checkbox:checked').size();
      if(totalCheck >0){ $('#idTotal').val(totalCheck-1); }else{$('#idTotal').val(0);}
      total=0;t1=0;t2=0;
      $('input[type=checkbox]:checked').each(function() {
          t1 = $(this).data("val");
          t2=isNaN(t1) ? 0 : t1;
          total+=t2;
       //alert("Checkbox " + $(this).data("val") +  " (" + $(this).val() + ") Seleccionado");
      });
       $('#idTotalMan').val("$"+total);
  });

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
});
 $('#lista-datos').hide();

   $("#btnConsultar").on("click",function(){
                    var num=document.getElementById("propietario").value;

            if(num.length==0){
                             alertify.alert('Mensaje del sistema',"Debes ingresar un propietario para realizar la b&uacute;squeda.", function(){
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
        table = $('#tr-pagos').DataTable({
        serverSide: false,
         filter:true,
         processing: true,
         destroy: true,
         paging: false,
        ajax: {
            url: "{{ route('get.rows.anualidades.insumos') }}",
             data: function (d) {
              d.nombre=$('#nombre').val();
              d.propietario=$('#propietario').val();
            },
            error: function (xhr, error, thrown) {
            alertify.alert('Mensaje del sistema',"¡Conflicto al realizar la b&uacute;squeda, ingresa de nuevo los datos!", function(){ location.reload();});
            }

        },
        columns: [
            {data:'combobox',name:'combobox'},
            {data: 'NOMBRE_COMERCIAL', name: 'NOMBRE_COMERCIAL'},
            {data: 'VIGENTE_HASTA', name: 'VIGENTE_HASTA'},
            {data: 'ULTIMA_RENOVACION', name: 'ULTIMA_RENOVACION'},
            {data: 'valor', name: 'valor'}

        ],
        language: {
            "sProcessing": '<div class=\"dlgwait\"></div>',
            "url": "{{ asset('plugins/datatable/lang/es.json') }}",
            "searchPlaceholder": ""


        },
           "columnDefs": [ {
            "searchable": true,
            "orderable": false,
             "targets": [0],
        }],

        "order": [[ 1, 'asc' ]]

          });

  }

function generarMandamiento(){
    if(document.getElementById("idPagos")){
     alertify.confirm('Mensaje de sistema','<P>Revise su mandamiento antes de guardar cambios y pagar en Banco; así también debe de asegurarse de cumplir con los requisitos para realizar su trámite.</P><br><P>Recuerde revisar la fecha de vencimiento en su Mandamiento</P><br><P>Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podra ser pagado en la red de las Agencias del Banco Agrícola, S.A.</P>',function(){
             $("#propietarioval").val($("#propietario").val());
             $("#cuentade").val($("#nombre").val());
             $('#infoGeneral2').submit();
        },null).set('labels', {ok:'ACEPTAR Y DESCARGAR PDF', cancel:'CERRAR'});
    }else{
      alertify.alert('Mensaje del sistema',"¡No se puede generar mandamientos!", function(){});
    }
}

function limpiarFormulario(){
  if(table){
      table.clear().draw();
  }
  $('#nombre').val("");
  $('#lista-datos').hide();
  $("#infoGeneral2")[0].reset();
}

 function cont(){
        document.getElementById("checkTodos").checked=false
       //$("#checkTodos input[type=checkbox]").prop('checked', false);
      var totalCheck  = $('input:checkbox:checked').size();
      if(totalCheck >0){ $('#idTotal').val(totalCheck); }else{$('#idTotal').val(0);}
      total=0;t1=0;t2=0;
      $('input[type=checkbox]:checked').each(function() {
          t1 = $(this).data("val");
          t2=isNaN(t1) ? 0 : t1;
          total+=t2;
       //alert("Checkbox " + $(this).data("val") +  " (" + $(this).val() + ") Seleccionado");
      });
       $('#idTotalMan').val("$"+total);

  };

  function imprimirProductos(){
      var pro = $('#propietario').val();
      if(pro.length==0){
              alertify.alert('Mensaje del sistema',"Debes ingresar un propietario para realizar la b&uacute;squeda.", function(){
                                        alertify.success('¡Vuelve a intentar!');
                                   });
      }else{
         alertify.confirm('Mensaje de sistema','<P>¿Está seguro que desea descargar lista de productos?</P>',function(){
              $('#pro1').val(pro);
              $('#infoGeneral3').submit();
      },null).set('labels', {ok:'ACEPTAR Y DESCARGAR PDF', cancel:'CERRAR'});

      }
  }
</script>
@endsection
