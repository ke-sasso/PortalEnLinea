<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title> </title>
    <style type="text/css">

      body{
        font-size: 14px;

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
        font-family: 'times new roman' !important;
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

    </style>
  </head>
  <body>


    <header>
    <table  style="width:100%;">
      <tr>
        <td style="width:15%;">
          <center>
            <img id="escudo" src="{{ url('img/escudo.png') }}" />
          </center>
        </td>
        <td style="width:70%;">
          <center>
            <h2 style="margin:0;padding:0;">
              &nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dirección Nacional de Medicamentos &nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <p>&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;República de El Salvador, América Central&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;</p>
              <strong>UNIDAD DE INSUMOS MÉDICOS</strong>
                <hr>
            </h2>
          </center>
        </td>
        <td>
          <center>
            <img id="logo" src="{{ url('img/dnm.png') }}" />
          </center>
        </td>
    </table>
    </header>
    <div align="center">
     <main>
        <h3>COMPROBANTE DE INGRESO DE CAMBIO POST REGISTRO -VENTANILLA VIRTUAL</h3>
        <table id="comprobante" style="width:100%;">
          <tbody>
            <tr>
              <td colspan="4"><b>NÚMERO DE SOLICITUD:</b>{{$solicitud->ID_SOLICITUD}}</td>
            </tr>
            <tr>
                <td width="10" height="50"><b>FECHA DE INGRESO DE LA SOLICITUD:</b></td>
                <td height="50">{{date('d-m-Y',strtotime($solicitud->FECHA_CREACION))}}</td>
                <td height="50"><b>NOMBRE DEL INSUMO MÉDICO:</b></td>
                <td height="50">{{$solicitud->NOMBRE_INSUMO}}</td>
            </tr>
            <tr>
                <td><b>N° DE REGISTRO SANITARIO:</b></td>
                <td>{{$solicitud->IM}}</td>
                <td><b>TITULAR DEL REGISTRO:</b></td>
                <td>{{$titular->NOMBRE_PROPIETARIO}}</td>
            </tr>
            <tr>
                <td><b>POST REGISTRO SOLICITADO:</b></td>
                <td>{{$tramite->nomtramite}}</td>
                <td><b>N° DE MANDAMIENTO:</b></td>
                <td>{{$solicitud->NUMERO_MANDAMIENTO}}</td>
            </tr>
            <tr>
              @if($solicitud->TIPO_PODER_AR==1)
                <td><b>PROFESIONAL RESPONSABLE SOLICITANTE:</b></td>
              @elseif($solicitud->TIPO_PODER_AR==2)
                <td><b>APODERADO SOLICITANTE:</b></td>
              @elseif($solicitud->TIPO_PODER_AR==3)
                <td><b>REPRESENTANTE LEGAL SOLICITANTE:</b></td>
              @endif
                <td colspan="3">{{Session::get('name')}} {{Session::get('lastname')}}</td>
            </tr>
          </tbody>
        </table>

     </main>
    </div>
    <footer id="footer">
        <hr>
   Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.
   &nbsp;&nbsp;
   PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
    </footer>

  </body>

</html>