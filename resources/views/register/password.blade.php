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

  .login_wrapper{
    margin-top: 5%;
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
   
             <form role="form" action="#" method="POST" id="frmRegister">
    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content text-center" align="center">
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
      
            <h1> <i class="fa fa-lock"></i> El código es válido...</h1>
            <div class="row" style="padding: 0 20px">
              <p>Selecciona una contraseña segura para acceder a tu cuenta</p>
            </div>
            <hr style="margin: 10px">  
         
            {{ csrf_field() }}
            <input type="hidden" name="codigo" value="{{ $codigo }}" />
            <div class="row">
              <div class="col-md-5 col-xs-12">
                <label style="margin-top: 7px;"><i class="fa fa-lock"></i> CONTRASEÑA</label>
              </div>
              <div class="col-md-6 col-xs-12">
                <div class="input-group form-group">
                  <input type="password" name="password" id="pwdnew1" class="form-control">
                  <div class="input-group-addon" id="checkpwdnew1">
                   <i class="fa fa-eye-slash"></i></div>
                 </div>
               </div>
             </div>
             
             <div class="row">
              <div class="col-md-5 col-xs-12">
                <label style="margin-top: 7px;"><i class="fa fa-lock"></i> REPITE LA CONTRASEÑA</label>
              </div>
              <div class="col-md-6 col-xs-12">
                <div class="input-group form-group">
                  <input type="password" name="password_confirmation" id="pwdnew2" class="form-control">
                  <div class="input-group-addon" id="checkpwdnew2">
                   <i class="fa fa-eye-slash"></i></div>
                 </div>
               </div>
             </div>
             <div id="tooltip" class="panel-footer text-center">

             </div>
             <hr style="margin: 10px">  
             <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="margin:0px auto;">
                <button type="submit" id="button"  style="margin:4px auto;" align="center" class="btn btn-success btn-lg btn-perspective center-block">Completar registro<i class="fa fa-check"></i></button>
              </div>  
            </div>
       

          <hr style="margin: 10px">  

          <p>Si ya no deseas continuar con el registro puedes
           <a href="{{ route('doLogin') }}" >
             <b> cancelar tu registro</b> </p>
           </a>
           <hr style="margin: 10px">  
           @include('layouts.footer')
           <br>
         </section>
       </div>
       {!! Html::script('js/jquery.min.js') !!}
       {!! Html::script('js/bootstrap.min.js') !!}
       {!! Html::script('plugins/alertifyjs/alertify.min.js') !!}
       {!! Html::script('js/jquery.mask.js') !!}
     </div>
          </form>
   </div>
   <script type="text/javascript">
    $("#alertNIT").hide();
    $('#loading').hide();
    $('#loadingMsg').hide();
    $(document).ready(function(){

      $( "#frmRegister" ).submit(function( event ) {
        $("#contentRegister").hide();
        $("#loadingMsg").show();
        $("#loading").show();

      });

      function validatePwdStrength(string) {
        return /[A-Z]+/.test(string) && /[a-z]+/.test(string) &&
        /[\d\W]/.test(string) && /\S{7,}/.test(string)
      }

      $('#checkpwdnew1').on('mousemove',function(){
        setTimeout(function(){
          $('#pwdnew1').attr('type','text');
        },100);

      }).on('mouseup mouseleave',function(){
        setTimeout(function(){
          $('#pwdnew1').attr('type','password');
        },100);
      });

      $('#checkpwdnew2').on('mousemove',function(){
        setTimeout(function(){
          $('#pwdnew2').attr('type','text');
        },100);

      }).on('mouseup mouseleave',function(){
        setTimeout(function(){
          $('#pwdnew2').attr('type','password');
        },100);
      });

      $('#pwdnew1').keyup(function(event) {
        $(this).parent().removeClass('has-error');
        var test = validatePwdStrength($(this).val());

        var nova = $('#pwdnew1').val();

        if(!test)
        {
          $('#tooltip').html('<p class="text-left">La nueva contraseña debe cumplir las siguientes condiciones: </p><ul><li>Debe contener por lo menos 8 caracteres</li><li>Una Letra Mayúscula</li><li>Una Letra Minúscula</li><li>Un valor numérico (0-9)</li><li>Debe ser diferente a la contraseña anterior</li></ul></p>');
          $('#pwdnew1').parent().addClass('has-error');
                    //$('#pwdnew2').attr('readonly', 'readonly');
                    return;
                  }
                  else
                  {
                    $('checkpwdnew1').show();
                    $('#pwdnew1').parent().addClass('has-success');
                   // $('#pwdnew2').removeAttr('readonly');
                   $('#tooltip').html('');
                 }
               });

      $('#pwdnew2').keyup(function(event) {
        $(this).parent().removeClass('has-error');
        var nova = $('#pwdnew1').val();
        var conf = $(this).val();

        if(nova != conf)
        {
          $('#tooltip').html('<p class="text-left">La nueva contraseña debe complir las siguientes condiciones: </p><ul><li>La confirmación de la contraseña no coincide</li></ul>');
          $('#pwdnew2').parent().addClass('has-error');
          return;
        }
        else
        {
          $('checkpwdnew2').show();
          $('#pwdnew2').parent().addClass('has-success');
          $('#tooltip').html('');

        }
      });


      $.get("http://ip-api.com/json/",function (response) {
        if(response.status == 'success')
        {
          console.log(response.query + ' ' + response.regionName + ' ' + response.country);
          $('#ipRequest').text(response.query + ' ' + response.regionName + ' ' + response.country);
          $('#ipRemote').val(response.query);
        }
        else{
          $.get("https://ipinfo.io", function(response) {
            console.log(response.ip, response.country);
            $('#ipRequest').text(response.ip + ' ' + response.city + ' '+response.region);
            $('#ipRemote').val(response.ip);
          }, "jsonp");
        }
      });
      if((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) != -1 ) 
      {
        $("#navegador").append("<input type='hidden' name='navegador' value='Opera'/>");
      }
            else if(navigator.userAgent.indexOf("MSIE") != -1) //IF IE > 10
            {
              $("#navegador").append("<input type='hidden' name='navegador' value='IE'/>");
            }
            else if(navigator.userAgent.indexOf('Edge') >= 0) //IF IE > 10
            {
              $("#navegador").append("<input type='hidden' name='navegador' value='Edge'/>"); 
            }
            else if(navigator.userAgent.indexOf("Chrome") != -1 )
            {
              $("#navegador").append("<input type='hidden' name='navegador' value='Chrome'/>");
            }
            else if(navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1)
            {
              $("#navegador").append("<input type='hidden' name='navegador' value='Safari'/>");
            }
            else if(navigator.userAgent.indexOf("Firefox") > -1 ) 
            {
             $("#navegador").append("<input type='hidden' name='navegador' value='Firefox'/>");
           }  
           else 
           {
             $("#navegador").append("<input type='hidden' name='navegador' value='Desconocido'/>");
           }



         });


    //Metodo para validar nit de el salvador
    function validarNIT(cadena){

      let calculo = 0;
      let digitos = parseInt(cadena.substring(12, 15));
      let resultado;
      if ( digitos <= 100 ) {
        for ( let posicion = 0; posicion <= 14; posicion++ ) {
          if ( !( posicion == 4 || posicion == 11 ) ){
            calculo += ( 14 *  parseInt( cadena.charAt( posicion ) ) );
                    //calculo += ((15 - posicion) * parseInt(cadena.charAt(posicion)));
                  }
                  calculo = calculo % 11;
                }
              } else {
                let n = 1;
                for ( let posicion = 0; posicion <= 14; posicion++ ){
                  if ( !( posicion == 4 || posicion == 11 ) ){
                    calculo = parseInt( calculo + ( ( parseInt( cadena.charAt( posicion ) ) ) * ( ( 3 + 6 * Math.floor( Math.abs( ( n + 4) / 6 ) ) ) - n ) ) );
                    n++;
                  }
                }
                calculo = calculo % 11;
                if ( calculo > 1 ){
                  calculo = 11 - calculo;
                } else {
                  calculo = 0;
                }
              }
              resultado = (calculo == ( parseInt( cadena.charAt( 16 ) ) ) ); 
              return resultado;
            }

          </script>

        </body>
        </html>

