<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title> </title>
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

      #firma{
        height: auto;
        width: auto;
        max-width: 400px;
        max-height: 800px;

      }
      p.padding2 {
      padding-left: 15cm;
      margin: 0px;
      }

      table.collapse-tbl {
        width: 90% !important;
        text-align: center;
        border-collapse: collapse !important;
        margin: 0 auto;
        font-family: 'times new roman' !important;
        font-size: 10px;

      }
      table.collapse-tbl th,table.collapse-tbl td {
        border: 1px solid black !important;
        width: 33% !important;
      }

    </style>
  </head>
  <body>


    <header>
    <p class="padding2">{{$codHerramienta}}</p>
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
      <p align="right">No. de referencia de solicitud: <b>{{$idSolicitud}}</b></p>
      <p align="justify"  style="margin: 30px;">La Libertad, Santa Tecla, {{$dia}}</p>
      <p align="justify" style="margin: 30px;">{!!$head!!}</p>
      <p align="justify" style="margin: 30px;">Admitido el presente escrito y de conformidad al dictamen médico de carácter favorable, esta Dirección resuelve <b>AUTORIZAR el Agotamiento de Empaque </b>:</p>

      <div align="center" id="codmods">
          <center>
            <table class="collapse-tbl">
              <tbody>
                <tr>
                  <th>Presentación</th>
                  <th>Lote</th>
                  <th>Unidades</th>
                  <th>Fecha Vencimiento</th>
                </tr>
              </tbody>
            </table>

            @foreach($detallePresentaciones as $detP)
              <table  class="collapse-tbl">
                <tbody>
                  <tr>
                    <td >{{$detP->presentacion->PRESENTACION_COMPLETA}} {{$detP->presentacion->ACCESORIOS}}</td>
                    <td>{{$detP->ID_LOTE}}</td>
                    <td >{{$detP->UNIDADES}}</td>
                    <td >{!!date('d-m-Y',strtotime($detP->FECHA))!!}</td>
                  </tr>
                </tbody>
              </table>
            @endforeach

          </center>

      </div>
      <p align="justify" style="margin: 30px;">Margínese lo anterior a la inscripción: <strong>{{$foot}}</strong></p>
      <p align="center" style="margin: 30px;"><b>Se autoriza por un periodo no mayor a 12 meses a partir de la fecha de emisión de este documento. </b></p>
      <br>
      <div>
         @if(date("Y-m-d",strtotime($fechaSolicitud)) >= "2019-07-11")
            <img id="firma" src="{{ resource_path('assets/images/firmas/firma-dra-ayala.png') }}" />
        @else
            <img id="firma" src="{{ resource_path('assets/images/firmas/firma-pena-de.png') }}" />
        @endif
      </div>
      <br>
      <br>
      <br>
      <br>


       @inject('barcode', 'Milon\Barcode\DNS1D')
       <center>
       <div id="wrap">
        <div id="content" style="max-width: 250px; margin: 0 auto !important; ">
           {!! $barcode->getBarcodeHTML($idSolicitud, "C39");!!}
        </div>
      </div>
      </center>
    </div>

    <footer id="footer">
   <hr>
   Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.
   &nbsp;&nbsp;
   PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
    </footer>

  </body>

</html>