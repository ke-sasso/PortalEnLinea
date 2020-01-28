<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>DOCUMENTACIÓN</title>
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
      max-width: 70% !important;
      margin: 0 auto;

    }

      #firma{
        height: auto;
        width: auto;
        max-width: 400px;
        max-height: 800px;

      }
       .titulos{
            background-color: #B8B8B8;
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
              <strong>UNIDAD DE REGISTRO Y VISADO</strong>
              ___________________________________________________________
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
        <h3>I. PRESENTACION DE LA DOCUMENTACIÓN</h3>

            <table id="comprobante" style="width:100%;">
          <tbody>
            <tr>
                <td><b>NOMBRE DEL PRODUCTO:</b></td>
                <td>{{$solicitud->solicitudesDetalle->nombreComercial}}</td>
            </tr>
            <tr>
                <td ><b>NÚMERO DE SOLICITUD:</b></td>
                <td >{{$solicitud->numeroSolicitud}}</td>
            </tr>

          </tbody>
        </table><br>
         <h3>REQUISITOS</h3>
         <table style="font-size: 13px;" id="comprobante"  width="100%">
        <tbody>
        @foreach ($expDoc as $exp)
          <tr  class="titulos">
            <td><center><b>{{ $exp->nomSubExpediente }}</b></center></td>
            <td ><center><b> @if($loop->first)Numeración de Folios @endif</b></center></td>
          </tr>
            @foreach($exp->docs as $doc)
                @if(in_array($doc->requisito_documento->idItem,$itemsDoc))
                <tr>
                 @php
                    $a='pag1_'.$doc->requisito_documento->idItem;
                    $b='pag2_'.$doc->requisito_documento->idItem;
                  @endphp
                  <td>{!!$doc->nomDocumento!!}</td>
                  <td>
                  <center>
                  {{ $num[$a] }}
                  @if(strlen($num[$b])>0) - {{ $num[$b] }}@endif Pág
                  </center>
                  </td>
                </tr>
                @endif

            @endforeach
        @endforeach
        </tbody>
      </table>
    </div>
    <footer id="footer">
   ______________________________________________________________________________________________________
   Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.
   &nbsp;&nbsp;
   PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
    </footer>

  </body>

</html>