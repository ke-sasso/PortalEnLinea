
@extends('master')
{{-- CSS ESPECIFICOS --}}
@section('css')
{!! Html::style('plugins/bootstrap-tour/css/bootstrap-tour.css') !!}
<style type="text/css">
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
<form id="infoGeneral" method="post"  action="{{route('store.mandamiento.pagoscosmeticos')}}">
<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title">Pago Inscripciones Cosméticos</h3>
</div>

	    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
				<div class="panel-body">

        <div class="form-group">
                <div class="row">
                     <div class="col-sm-12 col-md-12" id="paso1">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>A nombre de:</b></div>
                      <input type="text" class="form-control" id="nombre" name="nombre" value="{{$nombre}}" autocomplete="off" disabled>

                    </div>
                    </div>
                </div>
        </div>
        <div class="form-group">
                <div class="row">
                     <div class="col-sm-12 col-md-6" id="paso2">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Por cuenta de:</b></div>
                      <input type="text" class="form-control" id="cuenta" name="cuenta"  autocomplete="off">

                    </div>
                    </div>


                     <div class="col-sm-12 col-md-6" id="paso3">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Total a pagar:</b></div>
                      <input type="text" class="form-control" id="pagar" name="pagar" placeholder="0.00" value="0.00" autocomplete="off" disabled>

                    </div>
                    </div>
                </div>
        </div>
              <div class="form-group">
                <div class="row">
                     <div class="col-sm-12 col-md-6" id="paso4">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Comentario:</b></div>
                      <textarea class="form-control" id="comentario" name="comentario"  autocomplete="off" ></textarea>


                    </div>
                    </div>


                    <div class="col-sm-12 col-md-6" id="paso6">
                      <button type="button" id="enviar" name="enviar" class="btn btn-primary" onclick="generar();">Generar mandamiento <i class="fa fa-check"></i></button>
                        <p class="help-block text-primary">Nota: debe selecionar un tipo de pago para poder generar mandamiento.</p>
                    </div>
                </div>
        </div>

      </div>

</div>
                <div id="paso5">
                        <div class="table-responsive the-box  no-border" >
                        <table class="table table-th-block table-primary table-hover" id="tr-pagos">
                            <thead class="the-box dark full">
                                <tr>
                                    <th colspan ="2"> @if(count($p1)>0 && count($p2)>0) <input type="radio"  name="idPago" id="idPago" value="1" onclick="suma({{$p1->VALOR}},{{$p2->VALOR}});">@endif&nbsp;&nbsp;Registro o reconocimiento Nacional de Cosméticos</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($p1)>0 && count($p2)>0)
                             <tr>
                             <td><h4><span class="label label-success">${{$p1->VALOR}}</span></h4></td>
                             <td>{{$p1->CODIGO}} - {{$p1->NOMBRE_TIPO_PAGO}}</td>
                             </tr>
                             <tr>
                             <td><h4><span class="label label-success">${{$p2->VALOR}}</span></h4></td>
                             <td>{{$p2->CODIGO}} - {{$p2->NOMBRE_TIPO_PAGO}}</td>

                             </tr>
                             @else
                               <tr>
                               <td>SIN INFORMACIÓN</td>
                               <td>-</td>
                               </tr>
                             @endif

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                     <div class="table-responsive the-box  no-border">
                        <table class="table table-th-block table-primary table-hover" id="tr-pagos">
                            <thead class="the-box dark full">
                                <tr>
                                  <th colspan ="2"> @if(count($p1)>0 && count($p3)>0) <input type="radio"   name="idPago" id="idPago" value="2" onclick="suma({{$p1->VALOR}},{{$p3->VALOR}});">@endif&nbsp;&nbsp;Registro o reconocimiento Extranjero de Cosméticos</th>
                                </tr>
                            </thead>
                            <tbody>
                              @if(count($p1)>0 && count($p3)>0)
                                   <tr>
                                    <td><h4><span class="label label-success">${{$p1->VALOR}}</span></h4></td>
                                   <td>{{$p1->CODIGO}} - {{$p1->NOMBRE_TIPO_PAGO}}</td>
                                   </tr>
                                   <tr>
                                    <td><h4><span class="label label-success">${{$p3->VALOR}}</span></h4></td>
                                   <td>{{$p3->CODIGO}} - {{$p3->NOMBRE_TIPO_PAGO}}</td>

                                   </tr>
                              @else
                                  <tr>
                                   <td>SIN INFORMACIÓN</td>
                                   <td>-</td>
                                   </tr>
                              @endif

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
            </div>
  </form>







@endsection

{{-- JS ESPECIFICOS --}}
@section('js')
{!! Html::script('plugins/bootstrap-tour/js/bootstrap-tour.js') !!}  
{!! Html::script('js/mandamientos/pagosvarios/tourpasos.js') !!}
<script>

$(document).ready(function(){
pagosgenerales();

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
$('#enviar').attr("disabled", true);


});

function suma(valor1,valor2){
       var total = valor1+valor2;
      document.getElementById('pagar').value = '$'+total;
        $('#enviar').attr("disabled", false);



}
function generar(){
  if($('input[name="idPago"]').is(':checked')){
     alertify.confirm('Mensaje de sistema','<P>Revise su mandamiento antes de guardar cambios y pagar en Banco; así también debe de asegurarse de cumplir con los requisitos para realizar su trámite.</P><br><P>Recuerde revisar la fecha de vencimiento en su Mandamiento</P><br><P>Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podra ser pagado en la red de las Agencias del Banco Agrícola, S.A.</P>',function(){
             $('#infoGeneral').submit();
             $("#cuenta").val("");
             $("#comentario").val("");
             $("#idPago").removeAttr("checked");
             $("#enviar").attr("disabled", true);
        },null).set('labels', {ok:'ACEPTAR Y DESCARGAR PDF', cancel:'CERRAR'});
  }else{
     alertify.alert('Mensaje del sistema',"¡Debe seleccionar un tipo de pago para generar mandamiento", function(){
                alertify.success('¡Vuelve a intentar!');
      });
  }



}

</script>
@endsection
