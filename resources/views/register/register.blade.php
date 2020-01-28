<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="Portal en linea">
  <meta name="keywords" content="Portal en linea">
  <meta name="author" content="Unidad de Informática, DNM">
  <link href="{{{ asset('img/favicon.ico') }}}" rel="shortcut icon">
  <title>PORTAL EN LINEA</title>

  <!-- Bootstrap -->
  {{ Html::style('css/bootstrap.min.css') }}

  {!! Html::style('plugins/font-awesome/css/font-awesome.min.css') !!} 
  {!! Html::style('css/style.css') !!} 
  {!! Html::style('css/style-responsive.css') !!} 
  {!! Html::style('css/custom.min.css') !!} 
  {!! Html::style('plugins/alertifyjs/css/alertify.min.css') !!} 
  {!! Html::style('plugins/alertifyjs/css/themes/default.min.css') !!} 
  <!-- Custom Theme Style -->
  {!! Html::style('plugins/smartwizard/css/smart_wizard.min.css') !!}
  {!! Html::style('plugins/smartwizard/css/smart_wizard_theme_circles.min.css') !!}
  {!! Html::style('plugins/smartwizard/css/smart_wizard_theme_arrows.min.css') !!}
  <style media="screen" type="text/css">
  body{
    background: url({{asset('img/bg.png')}}) repeat !important;
  }
  .login_content{
    border-radius: 10px;
    background: #f3f3f3 !important;
    padding: 0 5px;
    margin: 0 auto;
  }
  .text-uppercase
  { text-transform: uppercase; }

  .button{
    max-width: 140px;
    position: absolute;
    top: 50%; 
    left: 50%;
    margin: auto;
    width: 50%;
    padding: 10px;
  }


  .disabledBtn{
    border: none;
    box-shadow: none;
    background: #74AD3B;
  }

</style>
</head>

<body class="login" >
  <div>

    <div class="login_wrapper" style="max-width: 650px">
      <div class="animate form login_form">
        <form role="form" data-toggle="validator" action="{{ route('postRegisterForm') }}" method="POST" id="frmRegister" enctype="multipart/form-data">
          <section class="login_content text-center" align="center">
           <input type="hidden" name="access" value="{{ $access }}" id="access">
           {{ csrf_field() }}
           <input type="hidden" name="ipRemote" id="ipRemote">
           
           <img src="{{asset('img/logo.png')}}" alt="" />
           @if($errors->any())
          <div class="alert alert-danger alert-bold-border fade in alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

            <ul class="inline-popups">
              @foreach ($errors->all() as $error)
              <li  class="alert-link">{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <div class="alert alert-danger alert-bold-border fade in alert-dismissable" style="display: none;" id="errorPanel">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <ul class="inline-popups">
              <li  class="alert-link" id="infoError">-</li>
            </ul>
          </div>
           <div id="smartwizard" style="text-shadow: none; border-left: none; border-bottom: none; border-right: none">
            <ul>
              <li><a href="#step-1">Paso 1<br /><small>Información Personal</small></a></li>
              <li><a href="#step-2">Paso 2<br /><small>Información de contacto</small></a></li>
              <li><a href="#step-3">Paso 3<br /><small>Profesional Responsable</small></a></li>
              <li><a href="#step-4">Paso 4<br /><small>Finalizar Registro</small></a></li>
            </ul>

            <div>
              <div id="step-1" class="" style="background: none">
                <div class="row" style="padding: 15px; padding-bottom: 0px">
                  <p class="text-center">Completa tu información personal</p>
                  @if($enabled && $access == md5('create'))

                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="contentRegister">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto;">
                        <div class="form-group"> 
                          <label>Nombres</label>
                          <input type="text" name="nombres" id="nombres" class="form-control text-center  text-uppercase" placeholder="Nombres" required value="{{ old('nombres')}}" />
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto;">
                      <div class="form-group"> 
                        <label>Apellidos</label>
                        <input type="text" name="apellidos" id="apellidos" class="form-control text-center  text-uppercase" placeholder="Apellidos" required value="{{ old('apellidos')}}" />
                       </div>
                      </div>

                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto;">
                        <label>NIT</label>
                        <input type="hidden" name="nit" id="nit" value="{{ $nit or old('nit')}}"  />
                        <input type="text" class="form-control text-center  text-uppercase" placeholder="NIT" required value="{{ $nit or old('nit')}}" disabled />
                      </div>

                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto;">
                         <div class="form-group"> 
                          <label>DUI</label>
                          <input type="tel" name="dui" id="dui" class="form-control text-center  text-uppercase" placeholder="DUI" required value="{{ old('dui')}}" />
                        </div>
                      </div>
                   <!-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto; padding-bottom: 10px;">
                      <label>Fecha de Nacimiento</label>
                      <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control text-center  text-uppercase" placeholder="DUI" required value="{{ old('fecha_nacimiento')}}" />
                    </div> -->
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; padding-bottom: 20px; margin-top: 10px;">
                      <label>Título</label>
                      {{Form::select('id_tipo_tratamiento', $listaTratamientos, old('id_tipo_tratamiento'),['class' => 'form-control', 'id' => 'id_tipo_tratamiento'])}}
                    </div>
                    @php
                    $genero = old('fecha_nacimiento') ? old('fecha_nacimiento') : "M";
                    @endphp
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; padding-bottom: 20px; margin-top: 10px;">
                      <label>Género</label>
                      <select type="text" name="genero" id="genero" class="form-control text-center" required>
                       <option value="M" @if($genero == "M") selected @endif>Masculino</option>
                       <option value="F" @if($genero == "F") selected @endif>Femenino</option>
                     </select>
                   </div>
                   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto;">
                    <label>Adjuna una fotografia de tu NIT</label>
                    <input type="file" name="nit_photo" id="nit_photo" class="form-control text-center  text-uppercase" placeholder="DUI" required value="{{ old('dui')}}" />
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto;">
                    <label>Adjuna una fotografia de tu DUI</label>
                    <input type="file" name="dui_photo" id="dui_photo" class="form-control text-center  text-uppercase" placeholder="DUI" required value="{{ old('dui')}}" />
                  </div>
                </div>
              </div>
              @else
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="contentRegister">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; ">
                    <label>Nombres</label>
                    <input type="hidden" name="nombres" id="nombres" required value="{{ $info->nombres or old('nombres')}}" />
                    <input type="text" class="form-control text-center  text-uppercase" placeholder="Nombres" required value="{{ $info->nombres or old('nombres')}}" disabled />
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto;">
                    <label>Apellidos</label>
                    <input type="hidden"  name="apellidos" id="apellidos" required value="{{ $info->nombres or old('nombres')}}" />
                    <input type="text" class="form-control text-center  text-uppercase" placeholder="Apellidos" required value="{{ $info->apellidos or old('apellidos')}}" disabled />
                  </div>

                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; margin-top: 10px;">
                    <label>NIT</label>
                    <input type="hidden" name="nit" id="nit" value="{{ $info->nitNatural or old('nit')}}"  />
                    <input type="text" class="form-control text-center  text-uppercase" placeholder="NIT" required value="{{ $info->nitNatural or old('nit')}}" disabled />
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; margin-top: 10px;">
                    <label>DUI</label>
                    <input type="hidden" name="dui" id="dui" placeholder="DUI" required value="{{ $info->numeroDocumento or old('dui')}}"/>
                    <input type="text" class="form-control text-center text-uppercase" placeholder="DUI" value="{{ $info->numeroDocumento or old('dui')}}" disabled/>
                  </div>
                  <!-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto; padding-bottom: 10px; margin-top: 10px;">
                    <label>Fecha de Nacimiento</label>
                    <input type="hidden" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control text-center" required value="{{ $info->fechaNacimiento or old('fecha_nacimiento')}}"/>
                    <input type="date" class="form-control text-center  text-uppercase" placeholder="DUI" required value="{{ $info->fechaNacimiento or old('fecha_nacimiento')}}" disabled/>
                  </div> -->

                  @php
                  if(isset($info->telefonosContacto)){
                    $telefonos = json_decode($info->telefonosContacto);
                    $telefono_fijo = $telefonos[0] or old('telefono_fijo');
                    $telefono_celular = $telefonos[1] or old('telefono_celular');
                  }else{
                    $telefono_fijo = old('telefono_fijo');
                    $telefono_celular = old('telefono_celular');
                  }

                  if(isset($info->idMunicipio)){
                    $idMunicipio = $info->idMunicipio;
                  }else if(old('id_municipio')){
                    $idMunicipio = old('id_municipio');
                  }else{
                    $idMunicipio = 1;
                  }

                  if(isset($info->idTipoTratamiento)){
                    $idTipoTratamiento = $info->idTipoTratamiento;
                  }else if(old('id_tipo_tratamiento')){
                    $idTipoTratamiento = old('id_tipo_tratamiento');
                  }else{
                    $idTipoTratamiento = 1;
                  }

                  @endphp

                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; padding-bottom: 20px; margin-top: 10px;">
                    <label>Título</label>
                    {{Form::select('id_tipo_tratamiento', $listaTratamientos, $idTipoTratamiento, ['class' => 'form-control', 'id' => 'id_tipo_tratamiento'])}}
                  </div>
                  @php
                  $genero = isset($info->sexo) ? $info->sexo : old('genero');
                  @endphp
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; padding-bottom: 20px; margin-top: 10px;">
                    <label>Género</label>
                    <select type="text" name="genero" id="genero" class="form-control text-center" required>
                     <option value="M" @if($genero == "M") selected @endif>Masculino</option>
                     <option value="F" @if($genero == "F") selected @endif>Femenino</option>
                   </select>
                 </div>
               </div>  
             </div>
             @endif
           </div>
         </div>
         @php
         if(isset($info->telefonosContacto)){
          $telefonos = json_decode($info->telefonosContacto);
          $telefono_fijo = $telefonos[0] or old('telefono_fijo');
          $telefono_celular = $telefonos[1] or old('telefono_celular');
        }else{
          $telefono_fijo = old('telefono_fijo');
          $telefono_celular = old('telefono_celular');
        }

        if(isset($info->idMunicipio)){
          $idMunicipio = $info->idMunicipio;
        }else if(old('id_municipio')){
          $idMunicipio = old('id_municipio');
        }else{
          $idMunicipio = 1;
        }

        if(isset($info->idTipoTratamiento)){
          $idTipoTratamiento = $info->idTipoTratamiento;
        }else if(old('id_tipo_tratamiento')){
          $idTipoTratamiento = old('id_tipo_tratamiento');
        }else{
          $idTipoTratamiento = 1;
        }
        @endphp
        <div id="step-2" class="" style="background:none">
         <div class="row" style="padding: 15px; padding-bottom: 0px">
          <p class="text-center">Esta es tu información para recibir correspondencia</p>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto;">
            <label>Teléfono Fijo</label>
            <input type="tel" name="telefono_fijo" id="telefono_fijo" class="form-control text-center" placeholder="Telefono Fijo" required value="{{ $telefono_fijo }}"  />
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto;">
            <label>Teléfono Celular</label>
            <input type="tel" name="telefono_celular" id="telefono_celular" class="form-control text-center" placeholder="Telefono Celular" value="{{ $telefono_celular }}" />
          </div>

          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto; margin-top: 10px;">
            <label>Correo electrónico</label>
            <input type="email" name="mail" id="email" class="form-control text-center" placeholder="correo@mail.com" required value="{{ $info->emailsContacto or old('mail')}}" />
          </div>

          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; padding-bottom: 15px; margin-top: 10px;">
            <label>Departamento</label>
            {{Form::select('txtDepartamento', $listaDept, $idDepartamento, ['class' => 'form-control', 'id' => 'txtDepartamento'])}}
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; padding-bottom: 15px; margin-top: 10px;">
            <label>Municipio</label>
            {{Form::select('id_municipio', $listaMun, $idMunicipio, ['class' => 'form-control', 'id' => 'id_municipio'])}}
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto;">
            <label>Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control text-center" placeholder="Dirección para recibir correspondencia" required value="{{ $info->direccion or old('direccion')}}" />
          </div>
        </div>
      </div>
      <div id="step-3" class="" style="background:none">
        @if(isset($info->profesional->idProfesional))
          <br>
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto; padding-bottom: 15px;">
            <label>Junta de vigilancia</label>
            {{Form::select('idJunta', $listJuntas, $info->profesional->idJunta, ['class' => 'form-control', 'disabled' => true, 'id' => 'idJunta'])}}
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; ">
            <label>Rama</label>
            {{Form::select('idRama', $listRamas, $info->profesional->idRama, ['class' => 'form-control', 'disabled' => true, 'id' => 'idRama'])}}
          </div>
          <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="margin:0px auto;">
            <label>-</label>
            <input name="rama" id="rama" class="form-control text-center" value="P0101" disabled/>
          </div>
          <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin:0px auto;">
            <label>JVP</label>
            @php 
               $idProf = substr($info->profesional->idProfesional, 5);
            @endphp
            <input type="text" name="jvp" id="jvp" class="form-control text-center" placeholder="0000" disabled value="{{ $idProf or old('jvp')}}" required />
          </div>
        @else
          <div id="askPR">
          <hr>
          <h5 class="text-center">¿Está inscrito como profesional ante CSSP?</h5>
          <p class="text-center">
            Si no posees relación alguna con la Junta de Vigilancia de Salud puede omitir esta información y continuar con el registro.
          </p>
          <div class="text-center">
            <a class="btn btn-primary" id="btnEsPr">Si</a>
            <a class="btn btn-danger" ID="btnNotPr">No, omitir</a>
          </div>

        </div>

        <div id="esPR" style="display: none;">
         <div class="row" style="padding: 15px; padding-bottom: 0px">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto; padding-bottom: 15px;">
            <label>Junta de vigilancia</label>
            {{Form::select('id_junta', $listJuntas, $idDepartamento, ['class' => 'form-control', 'disabled' => false, 'id' => 'idJunta'])}}
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin:0px auto; ">
            <label>Rama</label>
            {{Form::select('id_rama', $listRamas, $idDepartamento, ['class' => 'form-control', 'disabled' => false, 'id' => 'idRama'])}}
          </div>
          <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="margin:0px auto;">
            <label>-</label>
            <input id="rama" class="form-control text-center" value="P0101" disabled/>
          </div>
          <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin:0px auto;">
            <div class="form-group"> 
              <label>JVP</label>
              <input tpye="tel" name="jvp" id="jvp" class="form-control text-center" placeholder="0000" value="{{ old('jvp')}}" />
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto; margin-top: 10px;">
            <label>Adjuna una fotografia de tu carnet</label>
            <input type="file" name="jvp_photo" id="jvp_photo" class="form-control text-center  text-uppercase" placeholder="jvp_photo" value="{{ old('jvp_photo')}}" />
          </div>
        </div> </div>
        @endif
        
      </div>
      <div id="step-4" style="background:none">
       <div class="row" style="padding: 15px; padding-bottom: 0px: font-size: 12px;" id="step4Info">
        <p>Por favor confirma que has ingresado correctamente tu información.</p>
        <p>Recibirás un correo a la direccion <b id="mailInfo"></b> con un código de confirmación, razón por la cual te solicitamos verificar que lo hayas escrito correctamente.</p>
        <p>Si continuas aceptas que la información expuesta antes en el formulario es totalmente personal y pertenece a ti (<b id="nombreCompletoInfo"></b>) como persona natural.</p>
        <hr style="margin: 5px auto">
        <div class="text-center">
          <button type="submit" id="button" class="btn btn-primary">Haz click aquí para confirmar el registro <i class="fa fa-check"></i></button>
        </div>
      </div>
      <div id="loadingMsg" class="text-center">
        <div class="row" style="padding: 15px; padding-bottom: 0px: font-size: 12px;">
          <br>
           <h5>
             Espera un momento...
           </h5>
           <p id="loading"><i style="color: #1BBC9B" class="fa fa-refresh fa-3x fa-spin"></i></p>
           <br>
           <p>
             Estamos verificando tu información... <br> No cierres esta pestaña hasta que finalice el registro.
           </p>
         </div>
      </div>
    </div>
  </div>
</div>
<hr style="margin: 0 auto">
 <a href="{{ route('doLogin') }}" >
                    <h5><i class="fa fa-arrow-left"></i> Cancelar registro</h5>
              </a>
@include('layouts.footer')
<br>
</section>
</form>

</div>
{!! Html::script('js/jquery.min.js') !!}
{!! Html::script('js/bootstrap.min.js') !!}
{!! Html::script('plugins/alertifyjs/alertify.min.js') !!}
{!! Html::script('js/jquery.mask.js') !!}
{!! Html::script('js/tools.js') !!}
{!! Html::script('plugins/smartwizard/js/jquery.smartWizard.min.js') !!}
{!! Html::script('plugins/1000hz-bootstrap-validator/validator.min.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/js/fileinput.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/js/locales/es.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/themes/explorer-fa/theme.js') !!}
</div>
</div>

<script type="text/javascript">
  $("#alertDUI").hide();
  $('#loading').hide();
  $('#loadingMsg').hide();
  let confirmar = true;
  let pr = false;

  $(document).ready(function(){

/*
    $("#nit_photo").fileinput({
      showUpload: false,
      showCancel: false,
      showRemove: false,
    });
    //$("#dui_photo").fileinput();
*/
    $('#smartwizard').smartWizard({
      selected: 0,
      theme: 'arrows',
      transitionEffect:'fade',
      showStepURLhash: true,
      keyNavigation: false,
            //toolbarSettings: {toolbarPosition: 'both'}
          });

    $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
            var step =stepNumber + 1;
            if(step==2){
            //    alert("xd")
            }
        });

    $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
        var step = stepNumber + 1;
          var elmForm = $("#frmRegister");
            if(stepDirection === 'forward' && elmForm ){
              
                  elmForm.validator('validate');
          
                var elmErr = elmForm.children('.has-error');
                if(elmErr && elmErr.length > 0){
                    // Form validation failed
                    return false;
                }

            } 
        console.log(elmErr);
        var action = $("#access").val();
        if(stepDirection == "forward"){
          if(step == 1){
            // Si la accion es create, obligamos a subir los documentos DUI, NIT
            if(action == "76ea0bebb3c22822b4f0dd9c9fd021c5"){
               if($("#dui_photo")[0].files.length === 0){
                  $("#errorPanel").show();
                  $("#infoError").html("Debes adjuntar una fotografía de su DUI");
                  return false;
               }
               if($("#nit_photo")[0].files.length === 0){
                  $("#errorPanel").show();
                  $("#infoError").html("Debes adjuntar una fotografía de su NIT");
                  return false;
               }
               if($("#nombres").val() == ""){
                  $("#errorPanel").show();
                  $("#infoError").html("Debes ingresar tus nombres");
                  return false;
               }
               if($("#apellidos").val() == ""){
                  $("#errorPanel").show();
                  $("#infoError").html("Debes ingresar tus apellidos");
                  return false;
               }
               if($("#dui").val() == ""){
                  $("#errorPanel").show();
                  $("#infoError").html("Debes ingresar tu número de DUI");
                  return false;
               }
            }
          }
          if(step == 2){
            if($("#telefono_fijo").val() == ""){
              $("#errorPanel").show();
              $("#infoError").html("Completa el número de teléfono fijo");
              return false;
            }
            
            if($("#email").val() == ""){
              $("#errorPanel").show();
              $("#infoError").html("Ingresa tu correo electrónico, es obligatorio.");
              return false;
            }

            if($("#direccion").val() == ""){
              $("#errorPanel").show();
              $("#infoError").html("Por favor ingresa tu dirección");
              return false;
            }
          }
          if(step == 3){
            $("#mailInfo").html($("#email").val());
            $("#nombreCompletoInfo").html($("#nombres").val().toUpperCase() + " " + $("#apellidos").val().toUpperCase());
            if(pr){
              if($("#jvp_photo")[0].files.length === 0){
                  $("#errorPanel").show();
                  $("#infoError").html("Debes adjuntar una fotografía de tu carnet de profesional");
                  return false;
               }
               if($("#jvp").val() == ""){
                  $("#errorPanel").show();
                  $("#infoError").html("Por favor ingresa el correlativo de tu JVP");
                  return false;
                }
            }
          }
          if(step == 4){
            $("#step4Info").show();
            $("#loadingMsg").hide();
            $("#loading").hide();
          }
        }
        $("#errorPanel").hide();
        return true;
    });

    $('#dui').mask('00000000-0');
    $('#telefono_fijo').mask('0000-0000');
    $('#telefono_celular').mask('0000-0000');
  });

  $( "#btnEsPr" ).click(function() {
    pr = true;
    $("#askPR").hide();
    $("#esPR").show();
  });

  $( "#button" ).click(function() {
    $("#step4Info").hide();
    $("#loadingMsg").show();
    $("#loading").show();
  });

  $( "#btnNotPr" ).click(function() {
    pr = false;
    $("#jvp").val("");
    $("#smartwizard").smartWizard("next");
  });

  $("#frmRegister1").submit(function( event ) {
    if(validarDUI($("#dui").val())){
      $("#mailInfo").html($("#email").val());
      $("#nombreCompletoInfo").html($("#nombres").val() + " " + $("#apellidos").val());
      if(!confirmar){
        event.preventDefault();
        $("#confirmarDatos").modal('show');

      }else{
        $("#contentRegister").hide();
        $("#loadingMsg").show();
        $("#loading").show();
      }
    }else{
      event.preventDefault();
      $("html, body").animate({scrollTop: 0}, 1000);
      $("#contentRegister").show();
      $("#alertDUI").show();
      $("#loadingMsg").hide();
      $("#loading").hide();
    }
  });
  $("#txtDepartamento").on('change',function(){
    $.ajax({
      data: {_token:'{{ csrf_token() }}', deparamento: this.value},
      url: "{{ url('/getMunicipios') }}",
      type: 'post',
      beforeSend: function() {
        $("#id_municipio").prop("disabled",true);
      },
      success:  function (response){
        $("#id_municipio").html(response);
        $("#id_municipio").prop("disabled",false);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("#id_municipio").prop("disabled",false);
        console.log("Error en peticion AJAX!");  
      }
    });
  });
  $("#idJunta").on('change',function(){
    $.ajax({
      data: {_token:'{{ csrf_token() }}', deparamento: this.value},
      url: "{{ url('getRamas') }}",
      type: 'post',
      beforeSend: function() {
        $("#idRama").prop("disabled",true);
      },
      success:  function (response){
        $("#idRama").html(response);
        $("#idRama").prop("disabled",false);
        $("#rama").val($("#idRama").val());
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("#idRama").prop("disabled",false);
        console.log("Error en peticion AJAX!");  
      }
    });
  });
  $("#idRama").on('change',function(){
    $("#rama").val(this.value);
  });

</script>

</body>
</html>

