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
  </style>
</head>

<body class="login" >
<div>

  <div class="login_wrapper">
    <div class="animate form login_form">
      <section class="login_content text-center" align="center">
        <img src="{{asset('img/logo.png')}}" alt="" />
        <h4>Estimado Usuario: Se ha enviado un correo electrónico de notificación de reseteo de contraseña, temporalmente se le ha generado un código para el acceso al sistema, por favor copielo y peguelo en esta pantalla para continuar </h4>
        @if($errors->any())
          <div class="alert  alert-bold-border fade in alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

            <ul class="inline-popups">
              @foreach ($errors->all() as $error)
                <li  class="alert-link">{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <form role="form" action="{{route('loginpost')}}" method="post" align="center">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto;">
                <input type="text" name="username" id="username"class="form-control text-center  text-uppercase" readonly="readonly" placeholder="NIT DE USUARIO" required value="{{$nit}}" />
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto;" align="center">
                <input type="password" name ="password" class="form-control text-center" placeholder="CÓGIDO DE ACCESO" required />
              </div>

              <div class="row">
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                  {!!Captcha::img('flat');!!}

                </div>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5" style="margin:0px auto;" align="center">
                  <input type="text" name="captcha" placeholder="" class="form-control text-center"/>
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
              </div>

              <div class="form-group text-center col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                {{ csrf_field() }}
                <input type="hidden" name="ipRemote" id="ipRemote">
              </div>


              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center style=margin:0px auto;">
                <button type="submit" id="button"  style="margin:0px auto;" align="center" class="btn btn-success btn-lg btn-perspective center-block">CONTINUAR</button>
              </div>



            </div>
          </div>


          <div id="navegador" class="col-sm-10">

          </div>


          <div class="clearfix"></div>

          <div class="separator">

            <div class="clearfix"></div>

            <div>
              <p>©2016 V1.0 <a href="http://medicamentos.gob.sv">DNM</a></p>
              <p><label id="ipRequest"></label></p>
            </div>
          </div>
        </form>
      </section>
    </div>
    {!! Html::script('js/jquery.min.js') !!}
    {!! Html::script('js/bootstrap.min.js') !!}
    {!! Html::script('plugins/alertifyjs/alertify.min.js') !!}

  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $.get("https://ipinfo.io", function(response) {
      console.log(response.ip, response.country);
      $('#ipRequest').text(response.ip + ' ' + response.country);
      $('#ipRemote').val(response.ip);
    }, "jsonp");
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

</script>
</body>
</html>

