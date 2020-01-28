<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>COMPROBANTE DE INGRESO: {{$solicitud->ID_SOLICITUD}}</title>
    <style type="text/css">

      body{
        font-size: 14px;

      }

       #wrap {
      float: center;
      position: relative;
      left: 35%;
    }

    #content {
        float: center;
        position: relative;
      }
      div#header{
        width: 74%;
        display: inline-block;
        margin: 0 auto;
        border:1px solid black;
      }
      div#header img#escudo{
        height: 60px;
        width: auto;
        max-width: 20%;
        display: inline-block;
        margin: 0.5em;
      }
      div#header img#logo{
        height: 40px;
        width: auto;
        max-width: 20%;
        display: inline-block;
        margin: 0.5em;
      }
      div#header div#mainTitle{
        width: 65%;
        display: inline-block;
        margin: 0.5em;
        margin-right: 1em;
        text-align: center;
      }
      #footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        padding: 0;
        text-align: center;
      }
      div#subTitle{
        width: 300px;
        height: 50px;
        margin: 0 auto;
        bottom: 200px;
        border: 2px solid black;
      }

      table#comprobante{
        width: auto;
        min-width: 95%;
        max-width: 95%;
        margin: 0 auto;
        border-collapse: collapse !important;
        width: auto !important;
        max-width: 70% !important;
        margin: 0 auto;
        font-style: 'times new roman' !important;
        width: auto !important;
        max-width: 70% !important;
        margin: 0 auto;

      }
      table#comprobante tr td{
      border-left: .5px solid black;
      border-right: .5px solid black;
      border-top: .5px solid black;
      border-bottom: .5px solid black !important;
      padding-bottom: 0px  !important;
      text-align: center !important;
      max-width: 70% !important;
      margin: 0 auto;

    }

      #firma{
        height: auto;
        width: auto;
        max-width: 400px;
        max-height: 800px;

      }
    </style>
  </head>
  <body>


    <header>
       <center><img  src="{{ url('img/LOGO_HERRAMIENTA_URIM.png') }}" /></center>
    </header>
    <div align="center">
     <main>
        <h3>COMPROBANTE DE INGRESO DE TRÁMITE POST REGISTRO -VENTANILLA VIRTUAL</h3>
        <table id="comprobante" style="width:100%;">
          <tbody>
            <tr>
                <td width="10" height="50"><b>FECHA DE INGRESO DE LA SOLICITUD:</b></td>
                <td height="50">{{date('d-m-Y',strtotime($solicitud->FECHA_CREACION))}}</td>
                <td height="50"><b>NOMBRE DEL PRODUCTO:</b></td>
                <td height="50">{{$producto->NOMBRE_COMERCIAL}}</td>
            </tr>
            <tr>
                <td><b>N° DE REGISTRO SANITARIO:</b></td>
                <td>{{$solicitud->NO_REGISTRO}}</td>
                <td><b>TITULAR DEL REGISTRO:</b></td>
                <td>{{$solicitud->PROPIETARIOS_FABRICANTES}}</td>
            </tr>
            <tr>
                <td><b>POST REGISTRO SOLICITADO:</b></td>
                <td>{{$tramite->NOMBRE_TRAMITE}}</td>
                <td><b>N° DE MANDAMIENTO:</b></td>
                <td>{{$solicitud->MANDAMIENTO}}</td>
            </tr>
            <tr>
                <td><b>{{$solicitud->TITULO}} SOLICITANTE:<b></td>
                <td colspan="3">{{$solicitud->PERSONA}}</td>
            </tr>
          </tbody>
        </table>

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