
@extends('master')
{{-- CSS ESPECIFICOS --}}
@section('css')

@endsection

{{-- CONTENIDO PRINCIPAL --}}
@section('contenido')
{{-- ERRORES DE VALIDACIÓN --}}
@if($errors->any())
	<div class="alert alert-info square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		Debes corregir los siguientes errores para poder continuar
			@foreach ($errors->all() as $error)
				{!!$error !!}
			@endforeach
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
		<strong>Algo ha salido mal!</strong>
			{{ Session::get('msnError') }}
	</div>
@endif


<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title">Mandamiento para talonarios</h3>
    <input type="hidden" name="idSolicitud" id="idSolicitud" class="form-control" value="">
  </div>

    <div class="panel-body">
      <form id="frm-recetas" target="_blank" method="post" action="{{route('recetas.generar.mandamiento.store')}}" >
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6">
                <div class="form-group">
                  <label for="txtAnombre">A nombre de:</label>
                  <input type="text" disabled class="form-control" name="txtAnombre" value="{{Session::get('name').' '.Session::get('lastname')}}">
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6">
                <div class="form-group">
                  <label for="txtPorCuenta">Por cuenta de:</label>
                  <input type="text" class="form-control" name="txtPorCuenta" value="">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                  <label for="txtComentario">Comentario</label>
                  <textarea type="text" rows="2" class="form-control" name="txtComentario"></textarea>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-4">
                <div class="form-group">
                  <label for="txtCantidad">Cantidad</label>
                  <input id="recetas-cantidad-talonario" name="txtCantidad" type="number" min="1" max="8" step="1" class="form-control">
                  <label class="text-primary text-justify">Nota: campo obligatorio y la cantidad máxima de talonario a inscribir es de 8</label>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
                <div class="form-group">
                  <label for="txtRecetas">Número de Recetas</label>
                  <input id="recetas-numero" disabled name="txtRecetas" type="text" class="form-control">
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
                <div class="form-group">
                  <label for="txtTotal">Total a pagar $</label>
                  <input id="recetas-total" disabled name="txtTotal" type="text" class="form-control">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="form-group" align="center">
                  <button class="btn btn-primary" type="button" id="btn-generar" name="button">Generar mandamiento <i class="fa fa-check"></i></button>
                </div>
            </div>
          </div>
      </form>


    </div>
</div>


@endsection

{{-- JS ESPECIFICOS --}}
@section('js')
<script type="text/javascript">
  function calcularRecetas(){
    var cantidad      =  parseInt($("#recetas-cantidad-talonario").val());
    var totalRecetas  =  cantidad*25;
    var totalPago     =  cantidad*10;
    $("#recetas-numero") .val(totalRecetas);
    $("#recetas-total").val(totalPago.toFixed(2));
  }
  $("#recetas-cantidad-talonario").bind('keyup mouseup', function (){
    if($.isNumeric($(this).val())){
      if($(this).val()>8 || $(this).val()<1 ){
        $("#recetas-cantidad-talonario").val('');
        $("#recetas-numero").val(0);
        $("#recetas-total").val(0.00);
        alertify.alert('Error!','Debe ingresar un número entre 1 y 8 talonarios');
      }
      else
        calcularRecetas();
    }
  });
  $('#btn-generar').on('click',function(){
    var cantidad= $('#recetas-cantidad-talonario').val();
    var msjError='';
    if (cantidad=='' || cantidad==0) {
        msjError += '-Debe ingresar la cantidad de talonarios \n';
    }
    if (msjError=='') {
      $('#frm-recetas').submit();
      setTimeout(function(){
        location.reload();
      }, 1000);
    }else{
      alertify.alert('¡Error!',msjError);
    }
  });
</script>
@endsection
