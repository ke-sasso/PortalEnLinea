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

    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content text-center" align="center">
          <img src="{{asset('img/logo.png')}}" alt="" />

          <h1> <i class="fa fa-check"></i> Información recibida</h1>
         <div class="row" style="padding: 0 20px">
           @if($errors->any())
          <div class="alert alert-danger alert-bold-border fade in alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

            <ul class="inline-popups">
              @foreach ($errors->all() as $error)
              <li  class="alert-link">{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @else
           <p>Para completar tu registro debes verificar tu correo electrónico. Se ha enviado un correo de confirmación a <b>{{ $correo }}</b></p>
          @endif
           <p>Revisa tu bandeja de entrada e ingresa el código de verificación recibido:</p>
          </div>
          <hr style="margin: 10px">  
          <form role="form" action="#" method="POST" align="center" id="frmRegister" enctype="multipart/form-data">
            {{ csrf_field() }}

          <div class="row">
            <div class="col-md-5 col-md-offset-1 col-xs-12" style="text-align: left">
              <label style="margin-top: 7px;"><i class="fa fa-lock"></i> CÓDIGO DE VERIFICACIÓN</label>
            </div>
            <div class="col-md-5 col-xs-12">
              <input type="tel" name="codigo" class="form-control" required>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-5 col-md-offset-1 col-xs-12"  style="text-align: left;">
              <label style="margin-top: 7px;"><i class="fa fa-file"></i> DOCUMENTO FIRMADO</label>
            </div>
            <div class="col-md-5 col-xs-12">
              <input type="file" name="file_pdf" class="form-control" required>
            </div>
          </div>
           <hr style="margin: 10px">  
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center style=margin:0px auto;">
                <button type="submit" id="button"  style="margin:4px auto;" align="center" class="btn btn-success btn-lg btn-perspective center-block">Continuar<i class="fa fa-arrow-right"></i></button>
              </div>  
          </div>
               </form>
               
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
</div>
<script type="text/javascript">
  $("#alertNIT").hide();
  $('#loading').hide();
  $('#loadingMsg').hide();
  $(document).ready(function(){

    $('#username').mask('0000-000000-000-0');

    $( "#frmRegister" ).submit(function( event ) {
      $("#contentRegister").hide();
      $("#loadingMsg").show();
      $("#loading").show();

    
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

