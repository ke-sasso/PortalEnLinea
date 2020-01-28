<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>DESISTIR SOLICITUD: {{$solicitud->numeroSolicitud}}</title>
    <style type="text/css">

      body{
        font-size: 14px;

      }
      #content {
        float: center;
        position: relative;
      }      
      #footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        padding: 0;
        text-align: center;
      }

      #informacion{
        min-width: 85%;
        max-width: 85%;
        margin-left: 55px;
        font-style: 'times new roman' !important;
        text-align: justify;
        line-height: 1.5;
      }

      #firma{
        height: auto;
        width: auto;
        max-width: 400px;
        max-height: 800px;

      }
      #padding2{      
        position: absolute;
        padding-top: 10px;
        margin-top: 10px;
        padding-left: 550px;
      }
      * {
        padding-top: 0;
        margin-top: 0;
      }
    </style>
  </head>
  <body>


    <header>
      <!-- <label class="padding2" id="padding2" style="opacity: 0.4;">C02-RS-04-URV.HER03</label> -->
      <label class="padding2" id="padding2" style="opacity: 0.4; font-size: 13px;">C02-RS-04-DRS_ME.HER01</label>
      <div class="logo" style="text-align: center;">
        <img id="escudo" src="{{ url('img/LOGO_HERRAMIENTA_URIM.png') }}" />
      </div>
    </header>
    <div align="center">
     <main>
        <div id="informacion">

        <br>
        <p>La Libertad, Santa Tecla, {{$dia}}.</p><br>
        <p>
          A sus antecedentes el escrito presentado, por el (la) <b>{{$tratm}} {{Session::get('name')}} {{Session::get('lastname')}}</b>, en su calidad de <b>{{$perfil}}</b> del producto denominado <b>{{$solicitud->solicitudesDetalle->nombreComercial}}</b>.<br><br>
          Admitido el expediente del producto en el cual solicitaron autorización de registro sanitario, con  número de referencia <b>{{$solicitud->numeroSolicitud}}</b>, y vista la solicitud de DESISTIMIENTO del trámite, recibido en fecha <b>{{$fechaEnvio}}</b>.<br><br>
          Con base a lo solicitado, esta Dirección AUTORIZA: EL DESISTIMIENTO DEL TRAMITE, de Nuevo Registro Sanitario por procedencia @if($solicitud->solicitudesDetalle->origenProducto==1) Nacional, @elseif($solicitud->solicitudesDetalle->origenProducto==2) Extranjero, @else Reconocimiento extranjero, @endif  para el producto <b>{{$solicitud->solicitudesDetalle->nombreComercial}}</b>.
      </p>
      </div>
      <div class="row">
        <br>
         -
          <!-- <p align="center"><img  src="{{ resource_path('assets/images/firmas/firma_DraClara.png') }}"></p> -->
      </div>

     </main>
    </div>
    <footer id="footer">
   ______________________________________________________________________________________________________
   Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.
   &nbsp;&nbsp;
   PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
    </footer>

  </body>

</html>