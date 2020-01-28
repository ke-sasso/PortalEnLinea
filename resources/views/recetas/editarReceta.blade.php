
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
    <h3 class="panel-title">EDITAR RECETA</h3>
    <input type="hidden" name="idSolicitud" id="idSolicitud" class="form-control" value="">
  </div>
		<form id="infoGeneralReceta" method="post"  enctype="multipart/form-data">
	
				<div class="panel-body">
        
				

                <div class="form-group">
                <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Receta #</b></div>
                      <input type="text" class="form-control" id="dd" name="dd" value="{{$info[0]->ID_RECETA}}" autocomplete="off" disabled>
                    <input type="hidden" class="form-control" id="idReceta" name="idReceta" value="{{$info[0]->ID_RECETA}}">
                    <input type="hidden" name="ipRemote" id="ipRemote">
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Fecha</b></div>
                      <input type="text" class="form-control" id="datepicker1" value="{{date('d-m-Y',strtotime($info[0]->fecha_emision))}}" name="fecha" autocomplete="off" >
                    </div>
                    </div>
                      
                    
                </div>
                </div>

                <div class="form-group">
                     <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>JVPM médico</b></div>
                     <input type="text" class="form-control" id="jvpm" name="jvpm" autocomplete="off" disabled value="{{$info[0]->ID_MEDICO}}">
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
                      <input type="text"  class="form-control" id="numIngreso" name="numIngreso" autocomplete="off" required placeholder="Escribir el número de documento a buscar" value="{{$info[0]->ID_PACIENTE}}">
                      <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="btnBuscarSolicitante"><i class="fa fa-search" ></i></button></span>  
                    </div>
                    </div>
                  
                
                </div>
              
                </div>
                <div class="form-group">
                 <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Paciente:</b></div>
                      <input type="text" class="form-control" id="nombres" name="nombres" value="{{$info[0]->nombrePaciente}}" disabled autocomplete="off">
                      </div>
                    </div>
                </div>
                  </div>
                   <div class="form-group">
                     <div class="row">
                        <div class="col-sm-12 col-md-12">
                         <div class="input-group ">
                          <div class="input-group-addon"><b>Dirección:</b></div>
                          <textarea  class="form-control" id="direccion" name="direccion" autocomplete="off" disabled>{{$info[0]->direccionPaciente}}</textarea>
                        </div>
                        </div>
                     </div>
                  </div>
                  <hr>
                  <div class="form-group">
                 <div class="row">
                      <div class="col-sm-12 col-md-4">
                         <div class="input-group ">
                          <div class="input-group-addon"><b>Producto controlado</b></div>
                        <input type="text" class="form-control" required id="idProducto" name="idProducto" autocomplete="off" readonly value="{{$info[0]->ID_PRODUCTO_RECETADO}}">
                          <span class="input-group-btn">
                        <button type="button" class="btn btn-primary" id="btnBuscarProducto"><i class="fa fa-search" ></i></button></span>  
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
                      <input type="number" min="1" class="form-control" required id="totalDosis" name="totalDosis" value="{{$info[0]->cantidad_prescrita_magnitud}}" autocomplete="off">
                    </div>
                    </div>


                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Tipo de uso</b></div>
                        <select class="form-control" id="idUso" name="idUso">

                        @if($info[0]->tipoUso==1)
                        <option value="1" selected>Prescipción Médica</option>
                        <option value="2">Uso Profesional</option>
                        <option value="3">Menor de Edad</option>
                        @elseif($info[0]->tipoUso==2)
                        <option value="1">Prescipción Médica</option>
                        <option value="2" selected>Uso Profesional</option>
                        <option value="3">Menor de Edad</option>
                        @else
                        <option value="1">Prescipción Médica</option>
                        <option value="2">Uso Profesional</option>
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
                      <input type="text" class="form-control" id="dosisReceta" name="dosisReceta" value="{{$info[0]->cantidad_prescrita_descripcion}}" autocomplete="off" required>
                    </div>
                    </div>
                     
     </div>
        </div>
         <div class="form-group">
      <div class="row">
                     
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Total de tomas por ciclo</b></div>
                      <input type="number" min="1" class="form-control" id="totalTomas" name="totalTomas" value="{{$info[0]->dosis_ciclo}}" autocomplete="off" required>
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Ciclo de dosis</b></div>
                          
                        <select class="form-control" id="cicloDosis" name="cicloDosis">
                        @if(trim($info[0]->ciclo)=='24 Horas' || trim($info[0]->ciclo)=='24')
                        <option value="24 Horas" selected>24 Horas</option>
                        <option value="12 Horas">12 Horas</option>
                        @else
                        <option value="24 Horas">24 Horas</option>
                        <option value="12 Horas" selected>12 Horas</option>
                        @endif
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
                      <input type="number" class="form-control" id="duracionTratamiento" name="duracionTratamiento" value="{{$info[0]->dosis_duracion_trat}}" autocomplete="off" required>
                       <div class="input-group-addon"><b>Días</b></div>
                    </div>
                    </div>
 
                     
     </div>
        </div>
</div>
  </div>
                

               <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                <input type="hidden" name="nit" value="" />
               <div class="panel-footer text-center" id="id-guardar">
                <button type="button" id="enviar" name="enviar" class="btn btn-primary btn-perspective">EDITAR <i class="fa fa-check"></i></button>
               
                <button type="button" title="Anular Receta" id="anular-receta" class="btn btn-danger btn-perspective">ANULAR <i class="fa fa-times"></i></button>
               </div>

              </form>
  @include('recetas.panel.persona')
                    </div>
  @include('recetas.panel.productos')
    @include('recetas.panel.productosRecetados')

	
@endsection

{{-- JS ESPECIFICOS --}}
@section('js')


 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 
{{-- Bootstrap Modal --}}

<script type="text/javascript">
  var dataAddProds = [];
  var data = [];
$(document).ready(function(){
  $.get("https://ipinfo.io", function(response) {                
        $('#ipRemote').val(response.ip);
      }, "jsonp");

  $('.dui_masking').mask('00000000-0');

  var nowTemp = new Date();
  var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
         
  var checkin = $('#datepicker1').datepicker({
    format: "dd-mm-yyyy",
    onRender: function(date) {
    return date.valueOf() < now.valueOf() ? 'disabled' : '';
    }
  });

  $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
      });
  //$('#id-guardar').hide();

  });

// funcion para validar el mandamiento 
$('#anular-receta').click(function(event){
    
    alertify.confirm("Mensaje de sistema","¿Está seguro que desea anular esta receta? , Recuerde que una vez anulada la receta, esta no podrá ser dispensada en ninguna Farmacia.", function (asc) {
       if (asc) {
          var idReceta = $('#idReceta').val();
          var idMedico = $('#idProfesional').val();
          var token =$('#token').val();
        
          $.ajax({
              data:'idReceta='+idReceta+'&idMedico='+idMedico+'&_token='+token,
              url:   "{{route('receta.anular')}}",
              type:  'post',
             
              beforeSend: function() {
                  $('body').modalmanager('loading');
              },
              success:  function (r){
                  $('body').modalmanager('loading');
                  console.log(r);
                  if(r.status == 200){
                      window.location.href = "{{route('ver.historial.recetas')}}";
                  }
                  else if (r.status == 400){
                      alertify.alert("Mensaje de sistema - Error",r.message);
                  }else if(r.status == 401){
                      alertify.alert("Mensaje de sistema",r.message, function(){
                          window.location.href = r.redirect;
                      });
                  }else{//Unknown
                      alertify.alert("Mensaje de sistema","No se ha podido anular la receta, intentelo más tarde!");
                      //console.log(r);
                  }
              }
          });      

       } else {

       }
    }, "Default Value").set('labels', {ok:'SI', cancel:'NO'});    

});

$('#enviar').click(function() {
    var pro = document.getElementById("idProducto").value;
    var pac = document.getElementById("numIngreso").value;

    if(pro.length==0){
      alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>¡El campo Producto controlado es requerido!</p></strong>");
    }else if(pac.length==0){
       alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>¡El campo # Documento es requerido!</p></strong>");
    }else{

      $.ajax({
      data : {_token:'{{ csrf_token() }}',txt:pac},
      url: "{{route('verificar.producto.paciente')}}",
      type: "post",
      cache: false,
      mimeType:"multipart/form-data",
       beforeSend: function() {
                $('body').modalmanager('loading');
              },
       success:  function (response){
            $('body').modalmanager('loading');
            
                    if(isJson(response)){
                     
                           $('#dt-productosRecetados').DataTable({
                          scrollY:     "200px",
                          scrollCollapse: true,
                          paging: false,
                          filter:false,
                          processing: true,
                          serverSide: false,
                          destroy: true,
                          ajax: {
                              url: "{{route('get.rows.productos.paciente')}}?num="+pac,
                              data: function (d) {
                                  d.numIngreso= $('#numIngreso').val();
                              }
                          },
                          columns:[                        
                                  {data: 'fecha_creacion', name:'fecha_creacion'},
                                  {data: 'nombreComercial', name:'nombreComercial'},
                                  {data:'cantidad_prescrita_magnitud', name:'cantidad_prescrita_magnitud'},
                                  {data:'dias_restantes', name:'dias_restantes'},
                                  {data:'fecha_retiro',name:'fecha_retiro'}
                                  ],
                          language: {
                              "sProcessing": '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                              
                          },
                            "columnDefs": [ {
                               "width": "20%",
                               "orderable": false,
                               "targets": [0,1,2,3]
                            }],
                             "order": [[ 0, 'asc' ]]
                                  }); 
                            $('#frmProRecetados').modal('toggle'); 
                    }else{
                         enviar.call();
                    }
                  },
          error: function(jqXHR, textStatus, errorThrown) {
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo editar la receta!</p></strong>");
              console.log("Error en peticion AJAX!");  
          }
         });
         
     
  }//fin para validar input producto
    
});//fin del evento click

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


                     $('#nombres').val(r.message[0].nombres+' '+r.message[0].apellidos);
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


    function enviar(){
     var a1= $('#datepicker1').val();
     var a2= $('#idReceta').val();
     var a3= $('#idProfesional').val();
     var a4= $('#numIngreso').val();
     var a5= $('#idProducto').val();
     var a6= $('#totalDosis').val();
     var a7= $('#dosisReceta').val();
     var a8= $('#totalTomas').val();
     var a9= $('#cicloDosis').val();
     var a10= $('#duracionTratamiento').val();
     var a11= $('#idUso').val();
     var a12= $('#ipRemote').val();
    var token =$('#token').val();

    $.ajax({
      data: '_token='+token+'&fecha='+a1+'&idReceta='+a2+'&idProfesional='+a3+'&numIngreso='+a4+'&idProducto='+a5+'&totalDosis='+a6+'&dosisReceta='+a7+'&totalTomas='+a8+'&cicloDosis='+a9+'&duracionTratamiento='+a10+'&idUso='+a11+'&ipRemote='+a12,
      url:  "{{route('store.editar.receta')}}",
       type: "post",
      cache: false,
      mimeType:"multipart/form-data",
      beforeSend: function() {
        $('body').modalmanager('loading');
      },
      success:  function (response){
            $('body').modalmanager('loading');
            if(isJson(response)){
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡La información se edito con exito!</p></strong>",function(){
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

  }

  function enviarDatosPost(){
    enviar.call();
  }

</script>
@endsection
