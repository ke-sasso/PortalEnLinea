
@extends('master')
{{-- CSS ESPECIFICOS --}}
@section('css')


<style>
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
	<div class="alert alert-warning square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<strong>Atención!</strong>
		{{ Session::get('msnError') }}
	</div>

@endif
<?php 
  
 ?>
<form id="frmProducto" method="post">

  <div class="panel panel-success">
    <div class="panel-heading">
      <h3 class="panel-title">EXISTENCIAS EN FARMACIAS</h3>
    </div>
    <div class="panel-body">
      <div class="form-group row">
        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
          <div class="input-group">
            <div class="input-group-addon">Producto Controlado</div>
            <input type="hidden" name="idProducto" id="idProducto" class="form-control" value="<?php echo (isset($busqueda)) ? $busqueda['idProducto'] : '' ; ?>">
            <input type="text" id="nombreProducto" name="nombreProducto" class="form-control" value="<?php echo (isset($busqueda)) ? $busqueda['nombreProducto'] : '' ; ?>" readonly="readonly" placeholder="Seleccione un producto">
            <span class="input-group-btn">
              <button type="button" id="btnBuscarProducto" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
          <div class="input-group">
            <div class="input-group-addon">Departamento (Opcional)</div>
            <select name="cbDepartamentos" id="cbDepartamentos" class="form-control">
              <option value="0">-- Seleccione --</option>
              <?php
                  foreach ($departamentos as $key => $dep) {
                    echo '<option value="'.$dep->idDepartamento.'">'.$dep->nombreDepartamento.'</option>';
                  }
              ?>
            </select>
          </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
          <div class="input-group">
            <div class="input-group-addon">Municipio (Opcional)</div>
            <select name="cbMunicipios" id="cbMunicipios" class="form-control">
              <option value="0">-- Seleccione --</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="panel-footer">
        <div class="text-center">
          <button type="submit" name="submit" value="1" class="btn btn-success btn-perspective"><i class="fa fa-search"></i>Buscar en Farmacias</button>
        </div>
      </div>
  </div>
  <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
</form>
<?php 
  if(isset($busqueda)) 
  {
    
?>
<div id="panel-resultados" class="panel panel-success">
  <div class="panel-heading">
    <h3 id="title-result" class="panel-title">Resultados</h3>
  </div>
  <div class="panel-body"> 
  @if(isset($farmacias))    
    @include('recetas.panel.gmap',$farmacias)
  @endif
  @include('recetas.panel.resultadosProductos')
<?php 
  }
?>
@include('recetas.panel.productos')
@endsection

{{-- JS ESPECIFICOS --}}
@section('js')


 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 
{{-- Bootstrap Modal --}}
<script  type="text/javascript">
  $(document).ready(function() {

    <?php 
      if(isset($busqueda)) 
      {
        
    ?>
    $('#title-result').html('Farmacias con Existencia de '+$('#nombreProducto').val());
    
    var dtresults = $('#table-results').DataTable(
      {
          filter:false,
          processing: true,
          serverSide: false,
          pageLength: 1000,
          lengthChange: false,
          scrollY:        '325px',
          scrollCollapse: true,
          destroy: true,
          ajax: {
              url: "{{ route('get.data.info') }}",
              data: function (d) {
                  d.idProducto= '{{ $busqueda['idProducto'] }}';
                  d.idDepartamento = '{{ $busqueda['cbDepartamentos'] }}';
                  d.idMunicipio = '{{ $busqueda['cbMunicipios'] }}';
                }
             
          },
          columns: [
             
              {data: 'idEstablecimiento', name: 'idEstablecimiento'},
              {data: 'nombreComercial', name: 'nombreComercial',orderable:false},        
              {data: 'direccion', name: 'direccion',orderable:false},
              {data: 'nombreDepartamento', name: 'nombreDepartamento',orderable:false},
              {data: 'nombreMunicipio', name: 'nombreMunicipio',orderable:false},
              {
                "mData":null,
                "bSortable": true,
                "searchable":false,
                "mRender" : function(data,type,full)
                {
                    try
                    {
                       var telefonos = $.parseJSON(data.telefonosContacto);
                       return telefonos[0]+', '+telefonos[1];
                    }
                    catch(e)
                    {
                      return '';
                    }
                }
              }             
          ],
          language: {
              "sProcessing": '<center>Buscando...</center>',
              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
              
              
          },
           columnDefs: [
              {
                  
                  "visible": false
              }
          ]        
          
      });

    <?php 
      }
    ?>
    $('#cbDepartamentos').change(function(event) {
        var dep = this.value;

        var mun = $('#cbMunicipios');

        mun.empty();
        mun.append('<option value="0">-- Seleccione --</option>')
        $.get('{{url('Recetas/municipios')}}/'+dep, function(data) {
          try
          {
            console.log(data.get_municipios);
            $.each(data.get_municipios, function(index, val) {
               console.log(val.nombreMunicipio);
               mun.append('<option value="'+val.idMunicipio+'">'+val.nombreMunicipio+'</option>');
            });
          } 
          catch(e)
          {
            console.log(e);
          }
          
        });

    });  

  });

  $('#btnBuscarProducto').click(function(event) {
          var dtproductosext = $('#dt-productos').DataTable({
                          processing: true,
                          serverSide: false,
                          destroy: true,
                          pageLength: 5,
                          ajax: {
                              url: "{{route('dt.row.data.productos.recetas') }}",
                              data: function (d) {
                                  //d.tipoTramite= $('#tipoTramite1').val();
                              }
                          },
                          columns:[                        
                                  {data: 'idProducto', name:'idProducto'},
                                  {data: 'nombreComercial', name:'nombreComercial'},
                                  {data:'detalle', name:'detalle'}
                                  
                                  
                              ],
                          language: {
                              "sProcessing": '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                              
                          },
       "columnDefs": [ {
            "width": "10%",
            "orderable": false,
            "targets": [0,2]
        } ],

        "order": [[ 1, 'asc' ]]

                  });     
        
            $('#frmEst').modal('toggle');  
     

     
    });

  function selectInfo(id, nombre)
  {
    var a = id;
    var b = nombre;
    $('#idProducto').val('');
    $('#nombreProducto').val('');

    $('#idProducto').val(a);
    $('#nombreProducto').val(b);
    $('#frmEst').modal('hide');
  }


</script>
@endsection
