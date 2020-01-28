

@extends('master')
{{-- CSS ESPECIFICOS --}}
@section('css')

<style>
.alert-receta {
    color: #1a1e1f;
    background-color: #f8ffbc;
    border-color: #06f32e;
}
</style>
@endsection

{{-- CONTENIDO PRINCIPAL --}}
@section('contenido')
{{-- ERRORES DE VALIDACIÓN --}}
@if($errors->any())
	<div class="alert alert-warning square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<strong>Atención!: </strong>
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
		<strong>Atención!: </strong>
		{{ Session::get('msnError') }}
	</div>
@endif
<div class="alert alert-receta">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">11-Oct-2017</button>
  <strong>Mensaje del Sistema: </strong><br><br>&nbsp;&nbsp;&nbsp;&nbsp;Estimados Doctores,<br><br><p>&nbsp;&nbsp;&nbsp;&nbsp;Muy agradecidos por el apoyo que nos brindan para la implementación del Sistema de Prescripción Digital, con el objeto de facilitar la dispensación del producto RIVOTRIL 2 mg Comprimidos, deberá colocarse en el espacio de cantidad prescrito, 1 cuando corresponda la prescripción de 30 tabletas, 2 cuando la prescripción corresponda a 60 tabletas.</p>
</div>
<div class="panel panel-success">
<div class="panel-heading">
    <h3 class="panel-title">NUEVA RECETA</h3>
    <input type="hidden" name="idSolicitud" id="idSolicitud" class="form-control" value="">
  </div>
		<form id="infoGeneralReceta" method="post" action="{{route('guardar.receta')}}" >
	
				<div class="panel-body">
        
				

                <div class="form-group">
                <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Receta #</b></div>
                      <input type="text" class="form-control" id="dd" name="dd" value="{{$idReceta}}" autocomplete="off" disabled>
                      <input type="hidden" class="form-control" id="idReceta" name="idReceta" value="{{$idReceta}}">
                      <input type="hidden" name="ipRemote" id="ipRemote">
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Fecha</b></div>
                      <input type="text" class="form-control" id="datepicker1" name="fecha" placeholder="Formato mm-dd-yyyy. Ejemplo (01/31/2017)" autocomplete="off" value="{{date('d-m-Y')}}" required readonly>
                    </div>
                    </div>
                      
                    
                </div>
                </div>

                <div class="form-group">
                     <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>JVPM médico</b></div>
                     <input type="text" class="form-control" id="jvpm" name="jvpm" autocomplete="off" disabled value="{{Session::get('idProfesional')}}">
                     <input type="hidden" class="form-control" id="idProfesional" name="idProfesional" value="{{Session::get('idProfesional')}}">
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nombre Médico</b></div>
                     <input type="text" class="form-control" id="nombresSolicitante" name="nombresSolicitante" value="{{Session::get('name').' '.Session::get('lastname')}}" autocomplete="off" disabled>
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
                         <input type="text"  class="form-control" id="numIngreso" name="numIngreso" autocomplete="off" required placeholder="Escribir el número de documento a buscar">
                      <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="btnBuscarSolicitante"><i class="fa fa-search" ></i></button></span>  
                    </div>
                    </div>
                  
                
                </div>
              
                </div>
                <div class="form-group">
                 <div class="row">
                       <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nombres</b></div>
                      <input type="text" class="form-control" id="nombres" name="nombres" value="" disabled autocomplete="off">
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Apellidos</b></div>
                      <input type="text" class="form-control" id="apellidos" name="apellidos" value="" disabled autocomplete="off">
                    </div>
                    </div>
                    
                     
                </div>
                  </div>
                   <div class="form-group">
                 <div class="row">
                                   <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Dirección</b></div>
                      <textarea  class="form-control" id="direccion" name="direccion" autocomplete="off" disabled></textarea>
                    </div>
                    </div>
                 </div>
                  </div>
                  <hr>
                  <div class="form-group">
                 <div class="row">
                      <div class="col-sm-12 col-md-5">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Producto controlado</b></div>
                         <input type="text" class="form-control" required id="idProducto" name="idProducto" autocomplete="off" readonly>
                      <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="btnBuscarProducto"><i class="fa fa-search" ></i></button></span>  
                    </div>
                    </div>

                     <div class="col-sm-12 col-md-7">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nombre comercial</b></div>
                      <input type="text" class="form-control" id="nombreComercial" name="nombreComercial" value="" autocomplete="off" disabled>
                    </div>
                    </div>
 
                </div>
                  </div>
                     <div class="form-group">
                 <div class="row">
                     
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Total dosis prescrita</b></div>
                      <input type="number" min="1" class="form-control" required id="totalDosis" name="totalDosis" value="" autocomplete="off">
                    </div>
                    </div>

                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Tipo de uso</b></div>
                        <select class="form-control" id="idUso" name="idUso">
                        <option value="1" selected>Prescipción Médica</option>
                        <option value="2">Uso Profesional</option>
                        <option value="3">Menor de Edad</option>
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
                      <input type="text" class="form-control" id="dosisReceta" name="dosisReceta" value="" autocomplete="off" required>
                    </div>
                    </div>
                     
     </div>
        </div>
         <div class="form-group">
      <div class="row">
                     
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Total de tomas por ciclo</b></div>
                      <input type="number" min="1" class="form-control" id="totalTomas" name="totalTomas" value="" autocomplete="off" required>
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Ciclo de dosis</b></div>
                        <select class="form-control" id="cicloDosis" name="cicloDosis">
                        <option value="24" selected>24 Horas</option>
                        <option value="12">12 Horas</option>
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
                      <input type="number" class="form-control" id="duracionTratamiento" name="duracionTratamiento" value="" autocomplete="off" required>
                       <div class="input-group-addon"><b>Días</b></div>
                    </div>
                    </div>
 
                     
     </div>
        </div>
</div>
  </div>
                

               <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
               <input type="hidden" name="nit" value="{{Session::get('user')}}" />
               <div class="panel-footer text-center" id="id-guardar">
               <button type="button" id="enviar" name="enviar" class="btn btn-primary">GUARDAR <i class="fa fa-check"></i></button>
               </div>

              </form>
  
</div>

  @include('recetas.panel.persona')
  @include('recetas.panel.productos')
  @include('recetas.panel.productosRecetados')


	
@endsection

{{-- JS ESPECIFICOS --}}
@section('js')


 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 
{{-- Bootstrap Modal --}}

<script  type="text/javascript">
  var dataAddProds = [];
  var data = [];

$(document).ready(function(){
      $.get("https://ipinfo.io", function(response) {                
        $('#ipRemote').val(response.ip);
      }, "jsonp");
      $('#id-guardar').hide();
      $('.dui_masking').mask('00000000-0');

      var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
         
        var checkin = $('#datepicker1').datepicker({
          format: "dd-mm-yyyy",
          onRender: function(date) {
          return date.valueOf() < now.valueOf() ? 'disabled' : '';
          }
        });
       
      
      $('#btnBuscarSolicitante').click(function(event){
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
                          alertify.success('¡DATOS ENCONTRADOS CON EXITO!');

                          $('#nombres').val(r.message[0].nombres);
                          $('#apellidos').val(r.message[0].apellidos);
                          $('#direccion').val(r.message[0].domicilio);
                          $('#id-guardar').show();
                          $('#formPersona').modal('hide');
                        
                        }
                        else if (r.status == 404){
                            alertify.alert("Mensaje de sistema",'¡DATOS NO ENCONTRADOS, PRESIONAR OK PARA INGRESAR NUEVO PACIENTE!', function(){
                                $('#formPersona').modal('toggle');
                            });
                        }else if(r.status == 400){
                              alertify.alert("Mensaje de sistema",'¡DATOS NO ENCONTRADOS, PRESIONAR OK PARA INGRESAR NUEVO PACIENTE!', function(){
                                 $('#formPersona').modal('toggle');
                            });
                        }else{
                          alertify.alert("Mensaje de sistema",'¡DATOS NO ENCONTRADOS, PRESIONAR OK PARA INGRESAR NUEVO PACIENTE!' );
                        }
                    }
                  });
          }else{
            alertify.alert("Mensaje de sistema",'Debe de ingresar un número de documento para realizar la búsqueda');
          }
      });

      $('#guardarPaciente').click(function(e){
        var form=$("#nuevoPaciente");

        $.ajax({
            data: form.serialize(),
            url: "{{route('store.paciente')}}",
            type: 'post',
            beforeSend: function() {
              $('body').modalmanager('loading');
            },
            success:  function (r){
              $('body').modalmanager('loading');
              if(r.status == 200){
                alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Paciente registrado de forma exitosa!</p></strong>",function(){
                         $('#nombres').val(r.data.nombres);
                         $('#apellidos').val(r.data.apellidos);
                         $('#numIngreso').val(r.data.numero_docto);
                         $('#direccion').val(r.data.domicilio);
                         
                         $('#formPersona').modal('hide');
                         $('#nombresP').val('');
                         $('#apellidosP').val('');
                         $('#edad').val('');
                         $('#numDocumentoP').val('');
                         $('#numDocumento2').val('');
                         $('#domicilio').val('');
                         $('#id-guardar').show();
                });
              }
              else if(r.status == 400){
                alertify.alert('Mensaje del Sistema',r.message);
                $('#formPersona').modal('toggle');
             }      
          }
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


    $('#enviar').click(function(event) {
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
                success:  function (r){
                    if(r.status == 200){
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
                    }
                    else if (r.status == 404){
                      enviarDatosPost.call();
                    } 
                    else if (r.status == 400){
                      
                    }
                    else{
                      
                    }  
                     
                }
            });
        }
    
    });//fin del evento click

});
  
  function enviarDatosPost(){
    $('#infoGeneralReceta').submit();
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

  function habilitarInput(){
      tipo = document.getElementById("tipo").value;

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
</script>
@endsection
