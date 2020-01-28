
@extends('master')
{{-- CSS ESPECIFICOS --}}
@section('css')
{!! Html::style('plugins/full-calendar/css/fullcalendar.min.css') !!} 
{!! Html::style('plugins/timepicker/bootstrap-timepicker.min.css') !!} 
<style type="text/css">

#map {
    height: 300px;
    width: 100%;
}

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

    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="panel with-nav-tabs panel-success">
                                <div class="panel-heading">

                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#panel-generales" data-toggle="tab">DATOS GENERALES</a></li>
                                        <li><a href="#panel-propietarios" data-toggle="tab">PROPIETARIOS</a></li>
                                        <li><a href="#panel-regentes" data-toggle="tab">REGENTES</a></li>
                                    </ul>
                                </div>
                                <div id="panel-collapse-1" class="collapse in">
                                    <div class="panel-body">
                                        <div class="tab-content">
                            
                                                {{-- DATOS GENERALES --}}
                                            @include('recetas.anualidades.establecimientos.infoGeneral')
 
                                                {{-- PROPIETARIOS --}}
                                            @include('recetas.anualidades.establecimientos.infoPropietarios')
                                                
                                             {{-- REGENTES --}} 
                                             @include('recetas.anualidades.establecimientos.infoRegente')

                                              {{-- ENVIAR INFORMACIÓN --}} 
                                        
                                        </div><!-- /.tab-content -->
                                    </div><!-- /.panel-body -->                     
                                </div><!-- /.collapse in -->
                            </div><!-- /.panel .panel-success -->
                        </div><!-- /.col-sm-6 -->
                    </div><!-- /.row -->        
  <div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">                         
   <div class="panel panel-success">
                <div class="panel-heading">
                <h3 class="panel-title">ENVIAR INFORMACIÓN DEL ESTABLECIMIENTO</h3>
                </div>
                <div class="panel-body">
  
        
   <form id="enviarinfo" action="{{route('guardar.informacion.establecimiento')}}" role="form" method="POST">

            <div class="row">
              <div class="form-group">
              <div class="col-md-6 col-lg-6">
                <div class="input-group">
                  <div class="input-group-addon"><b>¿Está de acuerdo con la información que aparece?</b></div>  
                </div>
              </div>
              <div class="col-md-6 col-lg-6">
                <div class="row">
                  <div class="col-md-6 col-lg-6"> 
                  <input type="radio" class="" value="1" id="acuerdo" name="acuerdo" autocomplete="off">SI</div>
                  <div class="col-md-6 col-lg-6">
                  <input type="radio" class="" value="0" id="acuerdo" name="acuerdo" autocomplete="off">NO</div>
                </div>
                  
                
            
              </div>
            </div>  
          </div>
          <br>
          <div class="row">
            <div class="form-group">
              <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="input-group-addon"><b>Comentario</b></div>
              <textarea name="comentario" id="comentario" class="form-control text-uppercase" rows="2"></textarea>
              </div>
            </div>
            
          </div>
          <br>
          <div class="row">

              <div class="form-group">
              <div class="col-md-12 col-lg-12">
                <div class="input-group">
                  <div class="input-group-addon"><b>HORARIOS (Formato 24Horas)</b></div>  
                </div>
              </div>
              
            </div>  
          </div>
          <div class="row">

              <div class="form-group">
              <div class="col-md-6 col-lg-6">
                <div class="input-group">
                  <div class="input-group-addon"><b>Lunes a Viernes</b></div> 
                </div>
              </div>
              <div class="col-md-6 col-lg-6">
                <div class="row">
              <div class="col-md-6 col-lg-5"><input type="text" class="form-control tp" id="L1" name="L1"></div>
              <div class="col-md-6 col-lg-2">A</div>
              <div class="col-md-6 col-lg-5"><input type="text" class="form-control tp" id="L2" name="L2"></div>
                </div>
                  
                
            
              </div>
            </div>  
          </div>
      
          <div class="row">
              <div class="form-group">
              <div class="col-md-6 col-lg-6">
                <div class="input-group">
                  <div class="input-group-addon"><b>Sábados</b></div> 
                </div>
              </div>
              <div class="col-md-6 col-lg-6">
                <div class="row">
              <div class="col-md-6 col-lg-5"><input type="text" class="form-control tp" id="S1" name="S1"></div>
              <div class="col-md-6 col-lg-2">A</div>
              <div class="col-md-6 col-lg-5"><input type="text" class="form-control tp" id="S2" name="S2"></div>
                </div>
                  
                
            
              </div>
            </div>  
          </div>
          <div class="row">
              <div class="form-group">
              <div class="col-md-6 col-lg-6">
                <div class="input-group">
                  <div class="input-group-addon"><b>Domingos</b></div>  
                </div>
              </div>
              <div class="col-md-6 col-lg-6">
                <div class="row">
              <div class="col-md-6 col-lg-5"><input type="text" class="form-control tp" id="D1" name="D1"></div>
              <div class="col-md-6 col-lg-2">A</div>
              <div class="col-md-6 col-lg-5"><input type="text" class="form-control tp" id="D2" name="D2"></div>
                </div>
                  
                
            
              </div>
            </div>  
          </div>
        <br><br>
            <div class="row">
            <div class="col-md-12 col-lg-12">
              <div id="map"></div>
            </div><br>
            </div>
            <div class="row">
              <div class="form-group col-md-4 col-lg-4">
                <div class="input-group">
                      
                  <input type="hidden" id="txtLatEst" name="txtLatEst" class="form-control" readonly="true"  value="">
                </div>
              </div> 
              <div class="form-group col-md-4 col-lg-4">
                <div class="input-group">
                     
                  <input type="hidden" id="txtLngEst" name="txtLngEst" class="form-control" readonly="true"  value="">
                </div>
              </div>
            </div>
          
            <input type="hidden" name="id" id="id" value={{$id}}>
             <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class=" text-center">
                  <button type="submit" class="btn btn-success">Enviar información</button>
                </div>           
          </form>
</div><!-- /.panel-body -->
               
              </div><!-- /.panel panel-success -->


         </div><!-- /.col-sm-6 -->
         </div><!-- /.row -->    
@endsection

{{-- JS ESPECIFICOS --}}
@section('js')


        {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 
        {!!Html::script('plugins/full-calendar/js/jquery-ui.min.js')!!}
        {!! Html::script('plugins/full-calendar/js/moment.min.js') !!}
        {!! Html::script('plugins/full-calendar/js/fullcalendar.min.js') !!}
        {!! Html::script('plugins/full-calendar/lang/es.js') !!}
         {!! Html::script('plugins/timepicker/bootstrap-timepicker.js') !!}
         <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIyroCWk-krJpmPelqtAUSXHBHVtpvRS8" type="text/javascript"></script>
      {!! Html::script('plugins/hpneo-gmaps/gmaps.min.js') !!}
{{-- Bootstrap Modal --}}

<script>
 var table;
$(document).ready(function(){
      var id="{{$id}}";           
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
});    

  $('.tp').timepicker({ 
    defaultTime: '',
    minuteStep: 1,
    disableFocus: true,
    template: 'dropdown',
    showMeridian:false,
     });
               $('#calendar').fullCalendar('removeEvents', event.id);
               $('#calendar').fullCalendar( 'destroy' );  
               //$('#calen').hide();
          
                 $.get("{{route('dt.data.infoGeneral')}}?param="+id, function(data) {
                    try{
                     $('#idRegistro').val(""); 
                      $('#vigenteHasta').val("");
                      $('#nombreComercial').val("");
                      $('#direccion').val("");
                      $('#pais').val("");
                      $('#departamento').val("");
                      $('#municipio').val(""); 
                      $('#telefonosContacto1').val("");
                      $('#telefonosContacto2').val("");
                      $('#emailContacto').val("");
                      $('#estado').val("");

                       //console.log(data);
                       

                      $('#idRegistro').val(data.idEstablecimiento); 
                      $('#vigenteHasta').val(data.vigenteHasta);
                      $('#nombreComercial').val(data.nombreComercial);
                      $('#direccion').val(data.direccion);
                      $('#pais').val(data.pais);
                      $('#departamento').val(data.departamento);
                      $('#municipio').val(data.municipio); 
                      console.log(data.telefonosContacto.length);
                      if(data.telefonosContacto.length!=8){
                        var obj = JSON.parse(data.telefonosContacto);
                      $('#telefonosContacto1').val(obj[0]);
                      $('#telefonosContacto2').val(obj[1]);
                       }
                      $('#emailContacto').val(data.emailContacto);
                      $('#estado').val(data.nombreEstado);

                     
                    }
                    catch(e)
                    {
                      console.log(e);
                    }
                    
                  });

          $.get("{{route('dt.data.propietario')}}?param="+id, function(data) {
             //  console.log(data);
               $("#dt-propietarios tbody tr").remove(); 
               $.each(data, function(i, value) {
             //  console.log(value);
                 var tipo='';
                if(value.tipoPersona='J'){
                   tipo = 'Jurídica';
                }
                if(value.tipoPersona='N'){
                     tipo = 'Persona Natural';
                }
                $('#dt-propietarios tbody').append('<tr><td>'+value.nombre+'</td></tr>');
                 
                  });
          
                 });

             $.get("{{route('dt.data.regerentes')}}?param="+id, function(data) {
              //  $("#dt-horarios tbody tr").remove(); 
                 $("#dt-regentes tbody tr").remove(); 
               
                
            //console.log(data);
              $.each(data, function(i, value) {
                 
                // console.log(value.horarioRegente)
                   var i=0;
                    if(value.horarioRegente.length>0){
                                  var obj = JSON.parse(value.horarioRegente);
                                  for (var mes in obj){
                                    if(obj[mes].L1.length==0){
                                        i=i+1;
                                    }
                                    if(obj[mes].M1.length==0){
                                        i=i+1;
                                    }
                                    if(obj[mes].MI1.length==0){
                                         i=i+1;
                                    }
                                     if(obj[mes].J1.length==0){
                                        i=i+1;
                                    }
                                    if(obj[mes].V1.length==0){
                                        i=i+1;
                                    }
                                    if(obj[mes].S1.length==0){
                                        i=i+1;
                                    }
                                    if(obj[mes].D1.length==0){
                                         i=i+1;
                                    } 
                                    
                                   }
                                if(i==28){
                                    $('#dt-regentes tbody').append('<tr><td>'+value.nombre+'</td><td>'+value.idProfesional.replace('P0601','')+'</td><td></td></tr>');
                                }else{
                    $('#dt-regentes tbody').append('<tr><td>'+value.nombre+'</td><td>'+value.idProfesional.replace('P0601','')+'</td><td><a class="btn btn-xs btn-success btn-perspective" onclick="calendario(\''+id+'\',\''+value.idProfesional+'\');" >VER</a></td></tr>');
                           }
                     }else{
                      $('#dt-regentes tbody').append('<tr><td>'+value.nombre+'</td><td>'+value.idProfesional.replace('P0601','')+'</td><td></td></tr>');
                      }
                  
                 });
                   
                  

                  });
                 

});

 function calendario(id,idProfesional){

               $('#calendar').fullCalendar({
                    header: {
                        left: 'today',
                        center: 'title',
                        right: 'month' // buttons for switching between views
                    },
                     defaultView:'month',
                    events: {
                        url: '{{route("dt.data.horarios")}}?param='+id+'&idProfesional='+idProfesional,
                        type: 'get',
                        error: function() {
                            alert('El establecimiento todavía no tiene un horario definido.');
                        }
                    }
                    });
                
                // $('#calen').show();
}
  var map = new GMaps({
  el: '#map',
  lat: 13.691131334560277,
  lng: -89.22668041223142,
  zoomControl: true,
  mapTypeControl: false,
  streetViewControl: false,
  fullscreenControl: true
});


var markerPos = map.addMarker({
  lat: 13.691131334560277,
  lng: -89.22668041223142,
  draggable: true,
  dragend : function() {
    map.setCenter(this.getPosition().lat(),this.getPosition().lng());
    $("#txtLatEst").val(this.getPosition().lat());
    $("#txtLngEst").val(this.getPosition().lng());
    map.setZoom(15);
    //console.log(this.getPosition());
  }
});

map.addControl({
  position: 'top_left',
  content: '<i class="fa fa-map-marker" aria-hidden="true"></i>  Ubicación',
  style: {
    margin: '10px',
    padding: '5px 15px',
    border: 'solid 1px #F6BB42',
    background: '#F6BB42',
    color: '#fff'
  },
  events: {
    click: function(){
      getLocation();
    }
  }
});

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
      alert("Geolocation is not supported by this browser.");
        console.log("Geolocation is not supported by this browser.");
    }
}

function showPosition(position) {
  
  markerPos.setPosition(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
  map.setCenter(position.coords.latitude, position.coords.longitude);
    $("#txtLatEst").val(position.coords.latitude);
    $("#txtLngEst").val(position.coords.longitude);
    map.setZoom(15);
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
          alert("User denied the request for Geolocation.");
            console.log("User denied the request for Geolocation.");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("Location information is unavailable.");
            console.log("Location information is unavailable.");
            break;
        case error.TIMEOUT:
            alert("The request to get user location timed out.");
            console.log("The request to get user location timed out.");
            break;
        case error.UNKNOWN_ERROR:
            alert("An unknown error occurred.");
            console.log("An unknown error occurred.");
            break;
    }
}
function refreshMap(){
    //map.refresh();
    google.maps.event.trigger(map, "resize");
    $("#map").css("width", "100%").css("height", "300");
}

$('#enviarinfo').submit(function(e){
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
                
                 var obj =  JSON.parse(response);
                 if(obj.tipo==1){
                  alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Información registrado de forma exitosa!</p></strong>",function(){
                 window.location.href="{{route('ver.anualidades.establecimientos')}}";
                 window.open("{{route('ver.pdf.establecimiento',['idEstablecimiento'=>$id])}}");
                  });
                 }else{
                  alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ADVERTENCIA: La información del establecimiento ya fue enviada</p></strong>",function(){
                      window.location.href="{{route('ver.anualidades.establecimientos')}}";
                    });
                  
                 }
                
                
              
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo registrar la información!</p></strong>");
              console.log("Error en peticion AJAX!");  

          }
    });
    e.preventDefault(); //Prevent Default action. 

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
