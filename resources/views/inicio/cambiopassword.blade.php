<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Portal en linea">
    <meta name="keywords" content="Portal en linea">
    <meta name="author" content="Unidad de Informática">
    <link href="{{{ asset('img/favicon.ico') }}}" rel="shortcut icon">
    <title>PORTAL EN LINEA</title>

    <!-- Bootstrap -->
    {!! Html::style('css/bootstrap.min.css') !!} 
    
    
    {!! Html::style('plugins/font-awesome/css/font-awesome.min.css') !!} 
    {!! Html::style('css/style.css') !!} 
    {!! Html::style('css/style-responsive.css') !!} 
    {!! Html::style('css/custom.min.css') !!} 
  
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
  <body class="login">
    <div>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <img src="{{asset('img/logo.png')}}" alt="" />
            <h1>Cambio de contraseña</h1>
              
            <form role="form" action="{{route('cambiocontraseapost')}}" method="post">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto;">
                <label>Nombre de usuario:</label>
                  <input type="text" name ="idUsuario" class="form-control text-center" value="{{Session::get('user')}}"  readonly/>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:0px auto;">
                <label>Ingrese su nueva contraseña:</label>
                <input type="password" name ="newpassword" class="form-control text-center" placeholder="PASSWORD" required />
              </div>
             
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center style=margin:0px auto;">
             <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <button type="submit" class="btn btn-info btn-lg btn-perspective btn-block"  style="margin:0px auto;" align="center">Cambio de contraseña</button>
            </div>
            </div>
            </div>

              <div class="clearfix"></div>

              <div class="separator">

                <div class="clearfix"></div>
                <br />

                <div>
                  <p>©2016 V1.0 <a href="https://www.medicamentos.gob.sv">DNM</a></p>
                </div>
              </div>
            </form>
          </section>
        </div>
        {!! Html::script('js/jquery.min.js') !!}
        {!! Html::script('js/bootstrap.min.js') !!}
      </div>
    </div>
  </body>
</html>
