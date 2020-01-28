
@extends('master')
{{-- CSS ESPECIFICOS --}}
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
		<strong>Algo ha salido mal!</strong>
			{{ Session::get('msnError') }}
	</div>
@endif

<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title">MIS RECETAS</h3>
  </div>


				<div class="panel-body">
  <div class="row">

 <a href="{{route('ingresar.receta')}}">
<div class="col-lg-4 col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">

                    <i class="fa fa-level-up fa-5x"></i>
                </div>
                <div class="col-xs-9 text-center">

                    <h4 style="color:#F5FFFA;">RECETAS  DISPONIBLES</h4>
                    <h2 style="color:#F5FFFA;">@if(!empty($data->recetasDisponibles)){{$data->recetasDisponibles}}@else 0 @endif</h2>
               <div class="progress no-rounded progress-xs">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                </div><!-- /.progress-bar .progress-bar-info -->
            </div>
            <div style="color:#F5FFFA;">CREAR NUEVA RECETA</div>
                </div>

            </div>
        </div>
    </div>
</div>
</a>
 <a href="{{route('index.talonario')}}">
<div class="col-lg-4 col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-2">

                    <i class="fa fa-level-up fa-5x"></i>
                </div>
                <div class="col-xs-10 text-center">

                    <h4 style="color:#F5FFFA;">TALONARIOS INSCRITOS</h4>
                    <h2 style="color:#F5FFFA;">@if(!empty($data->recetasDisponibles)){{$data->talonariosInscritos}}@else 0 @endif</h2>
               <div class="progress no-rounded progress-xs">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                </div><!-- /.progress-bar .progress-bar-info -->
            </div>
            <div style="color:#F5FFFA;">SOLICITAR TALONARIO</div>
                </div>

            </div>
        </div>
    </div>
</div>
</a>
 <a href="{{route('ver.historial.recetas')}}">
<div class="col-lg-4 col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">

                    <i class="fa  fa-level-down fa-5x"></i>
                </div>
                <div class="col-xs-9 text-center">

                    <h4 style="color:#F5FFFA;">RECETAS UTITLIZADAS</h4>
                    <h2 style="color:#F5FFFA;">@if(!empty($data->recetasDisponibles)){{$data->recetasNoDisponibles}}@else 0 @endif</h2>
               <div class="progress no-rounded progress-xs">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                </div><!-- /.progress-bar .progress-bar-info -->
            </div>
            <div style="color:#F5FFFA;">HISTORIAL DE RECETAS</div>
                </div>

            </div>
        </div>
    </div>
</div>
</a>
</div>

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


});
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
