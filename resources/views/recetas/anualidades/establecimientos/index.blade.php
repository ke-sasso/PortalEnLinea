
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
    <h3 class="panel-title">PAGO DE ANUALIDADES DE ESTABLECIMIENTOS E IMPORTADORES</h3>
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
                <div class="col-sm-6 col-md-6" id="paso2">
                    <div class="input-group ">
                      <div class="input-group-addon" for="innovador"><b>Tipo:</b></div>
                      <select class="form-control" id="tipo" name="tipo" style="width: 100%;" >
                       @if(isset($tipos))
                           @foreach($tipos as $t)
                           <option value="{{trim($t->ID_TIPO_ESTABLECIMIENTO)}}">{{trim($t->NOMBRE_TIPO_ESTABLECIMIENTO)}} ({{trim($t->ID_TIPO_ESTABLECIMIENTO)}})</option>
                           @endforeach
                        @endif
                       </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6" id="paso3">
                    <div class="input-group ">
                    <div class="input-group-addon"><b>ID establecimiento:</b></div>
                      <input type="text" class="form-control" id="num" name="num" autocomplete="off" placeholder="Escribir el ID de establecimiento completo...">
                    </div>

                </div>

            </div>
        </div>
          <div class="form-group">
           <div class="row" id="paso4">
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
                    <div align="center" id="paso5">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}" class="btn btn-primary btn-perspective"/>
                  <button type="submit"  id="btnConsultar" class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Consultar</button>
                  <button class="btn btn-warning btn-perspective" id="btnLimpiar" type="button" onclick="limpiarFormulario()"><i class="fa fa-eraser"></i> Limpiar</button>
                           </div>
        </div>

      </div>

</div>

				        <form id="infoGeneral2" method="post"  action="{{route('store.mandamiento.establecimientos')}}">
                     <div class="the-box  no-border" id="lista-datos">
                        <div class="table-responsive">
                        <table class="table table-th-block table-primary table-hover" id="tr-pagos">
                            <thead class="the-box dark full">
                                <tr>
                                    <th>-</th>
                                    <th>Valor</th>
                                    <th>Nombre Comercial</th>
                                    <th>Vigente hasta</th>
                                    <th>Propietario</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.the-box .default -->
                 <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                 <input type="hidden" name="cuentade" id="cuentade">
                 <input type="hidden" name="tipoval" id="tipoval">
              </form>

@endsection
{{-- JS ESPECIFICOS --}}
@section('js')
{!! Html::script('plugins/bootstrap-tour/js/bootstrap-tour.js') !!}  
{!! Html::script('js/mandamientos/anualidades/tourpasos.js') !!}
{!! Html::script('plugins/select2/js/select2.min.js') !!}
<script>
 var table;
  var map;
$(document).ready(function(){

  stepEstablecimiento();

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
});
    $('#tipo').select2({});
     $('#lista-datos').hide();
     $("#btnConsultar").on("click",function(){
          var nombre=document.getElementById("nombre").value;
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
        table = $('#tr-pagos').DataTable({
        serverSide: false,
        filter:false,
        processing: true,
        destroy: true,
        paging: false,
        ajax: {
            url: "{{ route('get.rows.establecimientos.anu') }}",
             data: function (d) {
              d.nombre=$('#nombre').val();
              d.num=$('#num').val();
              d.tipo=$('#tipo').val();
            }
        },
        columns: [
            {data:'combobox',name:'combobox'},
             {data: 'valor', name: 'valor'},
            {data: 'nombreComercial', name: 'nombreComercial'},
            {data: 'vigenteHasta', name: 'vigenteHasta'},
            {data: 'propietario', name: 'propietario'},
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
        $('#search-form').on('submit', function(e) {
                table.draw();
                e.preventDefault();
        });

        table.rows().remove();

}


function generarMandamiento(){
   alertify.confirm('Mensaje de sistema','<P>Revise su mandamiento antes de guardar cambios y pagar en Banco; así también debe de asegurarse de cumplir con los requisitos para realizar su trámite.</P><br><P>Recuerde revisar la fecha de vencimiento en su Mandamiento</P><br><P>Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podra ser pagado en la red de las Agencias del Banco Agrícola, S.A.</P>',function(){
          var nombre=document.getElementById("nombre").value;
          var tipo=document.getElementById("tipo").value;
            $("#cuentade").val(nombre);
            $("#tipoval").val(tipo);
            $('#infoGeneral2').submit();
            table.clear().draw();
            $('#nombre').val("");
            $("#num").val("");
            $('#lista-datos').hide();
            $("#infoGeneral2")[0].reset();
            
      },null).set('labels', {ok:'ACEPTAR Y DESCARGAR PDF', cancel:'CERRAR'});
}

function limpiarFormulario(){
  if(table){
      table.clear().draw();
  }
  $('#nombre').val("");
  $("#num").val("");
  $('#lista-datos').hide();
  $("#infoGeneral2")[0].reset();
}

</script>
@endsection
