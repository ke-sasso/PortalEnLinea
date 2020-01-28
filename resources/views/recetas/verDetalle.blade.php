
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
		<strong>Auchh!</strong>
		Algo ha salido mal.	{{ Session::get('msnError') }}
	</div>
@endif

<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title">DETALLE RECETA</h3>
    <input type="hidden" name="idSolicitud" id="idSolicitud" class="form-control" value="">
  </div>
		<form id="infoGeneralReceta" method="post" action="{{route('store.editar.receta')}}" enctype="multipart/form-data">
	
				<div class="panel-body">
        
				

                <div class="form-group">
                <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Receta #</b></div>
                      <input type="text" class="form-control" id="dd" name="dd" value="{{$info[0]->ID_RECETA}}" autocomplete="off" disabled>
                    <input type="hidden" class="form-control" id="idReceta" name="idReceta" value="{{$info[0]->ID_RECETA}}">
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Fecha</b></div>
                      <input type="text" class="form-control" id="fecha" value="{{date('d-m-Y',strtotime($info[0]->fecha_emision))}}" name="fecha" autocomplete="off" disabled>
                    </div>
                    </div>
                      
                    
                </div>
                </div>

                <div class="form-group">
                     <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>JVPM médico</b></div>
                     <input type="text" class="form-control" id="jvpm" name="jvpm" autocomplete="off" disabled value="{{$info[0]->ID_MEDICO}}" >
                     <input type="hidden" class="form-control" id="idProfesional" name="idProfesional" value="{{$info[0]->ID_MEDICO}}">
                    </div>
                    </div>
                     
                </div>
                </div>
               <hr>
                <div class="form-group">
                     <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="input-group ">
                              <div class="input-group-addon"><b># Documento</b></div>
                              <input type="text"  class="form-control" id="numIngreso" disabled name="numIngreso" autocomplete="off" required placeholder="Escribir el número de documento a buscar" value="{{$info[0]->ID_PACIENTE}}">
                               
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                          <div class="input-group ">
                            <div class="input-group-addon"><b>Paciente:</b></div>
                            <input type="text" class="form-control" id="paciente" name="paciente" value="{{$info[0]->nombrePaciente}}" disabled autocomplete="off">
                          </div>
                        </div>
                      </div>
                </div>
                   <div class="form-group">
                  </div>
                  <hr>
                  <div class="form-group">
                 <div class="row">
                    <div class="col-sm-12 col-md-4">
                       <div class="input-group ">
                        <div class="input-group-addon"><b>Producto controlado</b></div>
                        <input type="text" class="form-control" required id="idProducto" name="idProducto" autocomplete="off" readonly value="{{$info[0]->ID_PRODUCTO_RECETADO}}" disabled>
                       
                       </div>
                    </div>
                    
                    <div class="col-sm-12 col-md-8">
                        <div class="input-group ">
                          <div class="input-group-addon"><b>Nombre comercial</b></div>
                          <input type="text" class="form-control" id="nombreComercial" name="nombreComercial" value="{{$info[0]->nombreComercial}}" autocomplete="off" disabled>
                        </div>
                    </div>
                </div>
                  </div>
                     <div class="form-group">
                 <div class="row">
                     
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Total dosis prescrita</b></div>
                      <input type="number" min="1" class="form-control" required id="totalDosis" name="totalDosis" value="{{$info[0]->cantidad_prescrita_magnitud}}" autocomplete="off" disabled>
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Tipo de uso</b></div>
                        <select class="form-control" id="idUso" name="idUso" disabled>  
                          @if($info[0]->tipoUso==1)
                            <option value="1" selected>Prescipción Médica</option>
                          @elseif($info[0]->tipoUso==2)
                            <option value="2" selected>Uso Profesional</option>
                          @elseif($info[0]->tipoUso==3)
                            <option value="3" selected>Menor de Edad</option>
                          @endif
                          
                        </select>
                    </div>
                    </div>
                       
                     
                </div>
                  </div>
                   <hr>
<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title">DATOS SOBRE DOSIS</h3>
  </div>
<div class="panel-body">
   <div class="form-group">
      <div class="row">
                     
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Dosis según receta</b></div>

                      <input type="text" class="form-control" id="dosisReceta" name="dosisReceta" value="{{$info[0]->cantidad_prescrita_descripcion}}" autocomplete="off" disabled>

                    </div>
                    </div>
                     
     </div>
        </div>
         <div class="form-group">
      <div class="row">
                     
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Total de tomas por ciclo</b></div>

                      <input type="number" min="1" class="form-control" id="totalTomas" name="totalTomas" value="{{$info[0]->dosis_ciclo}}" autocomplete="off" disabled>

                    </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Ciclo de dosis</b></div>
                          
                        <select class="form-control" id="cicloDosis" name="cicloDosis" disabled>
                        <option value="" selected>{{$info[0]->ciclo}}</option>
                        </select>
                    </div>
                    </div>
                     
     </div>
        </div>
          <div class="form-group">
      <div class="row">
                     
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Duración tratamiento</b></div>
                      <input type="number" class="form-control" id="duracionTratamiento" name="duracionTratamiento" value="{{$info[0]->dosis_duracion_trat}}" autocomplete="off" required disabled>
                       <div class="input-group-addon"><b>Días</b></div>
                    </div>
                    </div>
 
                     
     </div>
        </div>
</div>
  </div>
  
@if($info[0]->ID_ESTABLECIMIENTO!="" || $info[0]->ID_ESTABLECIMIENTO!=null)
  <div class="panel panel-success">
      <div class="panel-heading">
        <h3 class="panel-title">DISPENSADA EN:</h3>
      </div>
      <div class="panel-body">
          <div class="form-group">
            <div class="row">
                     
              <div class="col-sm-12 col-md-8">
                  <div class="input-group ">
                    <div class="input-group-addon"><b>Establecimiento:</b></div>
                    <input type="text" class="form-control" id="establecimiento" name="establecimiento" value="{{$info[0]->nombreEstablecimiento}}" autocomplete="off" required disabled>
                  </div>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                  <div class="input-group ">
                    <div class="input-group-addon"><b>Direccion Establecimiento:</b></div>
                    <input type="textarea"  rows="2" class="form-control" id="direccEst" name="direccEst" value="{{$info[0]->direccionEstablecimiento}}" autocomplete="off" required disabled>
                  </div>
              </div>

            </div>
          </div>                             
      </div>
  </div>
@endif
               <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                <input type="hidden" name="nit" value="" />
             

              </form>
  @include('recetas.panel.persona')
                    </div>
  @include('recetas.panel.productos')


	
@endsection

{{-- JS ESPECIFICOS --}}
@section('js')


 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 
{{-- Bootstrap Modal --}}

<script>
  var dataAddProds = [];
  var data = [];
$(document).ready(function(){
$('.dui_masking').mask('00000000-0');
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
//$('#id-guardar').hide();

});
$('#infoGeneralReceta').submit(function(e){
        var formObj = $(this);
        var formURL = formObj.attr("action");
      var formData = new FormData(this);
    $.ajax({
      data: formData,
      url: formURL,
      type: 'post',
      mimeType:"multipart/form-data",
        contentType: false,
          cache: false,
          processData:false,
      beforeSend: function() {
        $('body').modalmanager('loading');
      },
      success:  function (response){
            $('body').modalmanager('loading');
            if(isJson(response)){
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡La información editada con exito!</p></strong>",function(){
                var obj =  JSON.parse(response);
                location.reload();
              });
              
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>¡No se pudo registrar la informaci&oacute;n!</p></strong>");
              console.log("Error en peticion AJAX!");  
          }
    });
    e.preventDefault(); //Prevent Default action. 

    }); 
 $('#nuevoPaciente').submit(function(e){
        var formObj = $(this);
        var formURL = formObj.attr("action");
      var formData = new FormData(this);
    $.ajax({
      data: formData,
      url: formURL,
      type: 'post',
      mimeType:"multipart/form-data",
        contentType: false,
          cache: false,
          processData:false,
      beforeSend: function() {
        $('body').modalmanager('loading');
      },
          success:  function (response){
            $('body').modalmanager('loading');
            if(isJson(response)){
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Paciente registrado de forma exitosa!</p></strong>",function(){
                var obj =  JSON.parse(response);
                       var n = $('#nombresP').val();
                       $('#nombres').val(n);

                       var a = $('#apellidosP').val();
                        $('#apellidos').val(a);

                       var nd = $('#numDocumentoP').val();

                       if(nd.length>0){
                       $('#numIngreso').val(nd);
                       }
                       var nd2 = $('#numDocumento2').val();
                      
                       if(nd2.length>0){
                       $('#numIngreso').val(nd2);
                       }
                       var dd = $('#domicilio').val();
                        $('#direccion').val(dd);

                       $('#apellidosP').val();
                       $('#formPersona').modal('hide');
                       $('#nombresP').val('');
                       $('#apellidosP').val('');
                       $('#edad').val('');
                       $('#numDocumentoP').val('');
                       $('#numDocumento2').val('');
                       $('#domicilio').val('');
                       $('#id-guardar').show();
                
                 
              });
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>¡No se pudo registrar la información!</p></strong>");
              console.log("Error en peticion AJAX!");  
          }
    });
    e.preventDefault(); //Prevent Default action. 

    });
   $('#btnBuscarSolicitante').click(function(event) {
        
           var num=$('#numIngreso').val();
           var token =$('#token').val();
           if(num!=''){

          
            $.ajax({
            data:'num='+num+'&_token='+token,
            url:   "{{route('consultar.persona')}}",
            type:  'post',
           
            beforeSend: function() {
                $('body').modalmanager('loading');
            },
            success:  function (r){
                $('body').modalmanager('loading');
                
                if(r.status == 200){
                   //console.log(r.message[0].id_paciente);
                 // $('#nombres').val('');
                 // $('#apellidos').val('');
                 // $('#direccion').val('');

                  alertify.success('¡DATOS ENCONTRADOS CON EXITO!');


                     $('#nombres').val(r.message[0].nombres);
                  $('#apellidos').val(r.message[0].apellidos);
                  $('#direccion').val(r.message[0].domicilio);
                   $('#id-guardar').show();
                  $('#formPersona').modal('hide');
                
                }
                else if (r.status == 400){
                    alertify.alert("Mensaje de sistema",'¡DATOS NO ENCONTRADOS, PRESIONAR OK PARA INGRESAR NUEVO PACIENTE!', function(){
                        $('#formPersona').modal('toggle');
                    });

                    console.log(r);
                    //alertify.alert("Mensaje de sistema - Error",r.message);
                }else if(r.status == 401){
                      alertify.alert("Mensaje de sistema",'¡DATOS NO ENCONTRADOS, PRESIONAR OK PARA INGRESAR NUEVO PACIENTE!', function(){
                         $('#formPersona').modal('toggle');
                    });
                      console.log(r);
                }else{
                  alertify.alert("Mensaje de sistema",'¡DATOS NO ENCONTRADOS, PRESIONAR OK PARA INGRESAR NUEVO PACIENTE!', function(){
                         $('#formPersona').modal('toggle');
                    });
                    console.log(r);
                }
            },
            error: function(data){
                // Error...
                var errors = $.parseJSON(data.responseText);
                console.log(errors);
                $.each(errors, function(index, value) {
                    $.gritter.add({
                        title: 'Error',
                        text: value
                    });
                });
                   }
                  });


           }else{
            
            alertify.alert("Mensaje de sistema",'Debe de ingresar un número de documento para realizar la búsqueda');

           }
              
         
    



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
  function isJson(str) {
      try {
          JSON.parse(str);
      } catch (e) {
          return false;
      }
      return true;
  }

function habilitarInput(){
  tipo = document.getElementById("tipo").value;
 // alert(tipo);

  if(tipo!='DUI'){
    
    document.getElementById("numDocumentoP").value='';
    document.getElementById("numDocumento2").value='';
    document.getElementById('N1').style.display='none';
     document.getElementById('N2').style.display='block';
  }else{
    document.getElementById("numDocumentoP").value='';
     document.getElementById("numDocumento2").value='';
     document.getElementById('N2').style.display='none';
     document.getElementById('N1').style.display='block';
  }

  }
  function selectInfo(id, nombre){
    var a = id;
    var b = nombre;
   $('#idProducto').val('');
   $('#nombreComercial').val('');

   $('#idProducto').val(a);
   $('#nombreComercial').val(b);
   $('#frmEst').modal('hide');
  }

</script>
@endsection
