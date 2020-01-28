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
        left: -10%;
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
     <main>
      <p align="right">No. de referencia de solicitud: <b>{{$solicitud->ID_SOLICITUD}}</b></p>



      <p align="justify"  style="margin: 10px;">
      El infrascrito Jefe de la Unidad de Registro de Insumos Médicos de la Dirección Nacional de Medicamentos, de conformidad a lo establecido en la Ley de Medicamentos, HACE CONSTAR:
      Que en esta Dirección se encuentra registrado como dispositivo médico el producto denominado {{$solicitud->NOMBRE_INSUMO}}, inscrito al número IM {{$solicitud->IM}} y cuyo Titular del Registro Sanitario es {{$producto->NOMBRE_PROPIETARIO}}, según se detalla a continuación:
      </p>

      <p align="justify"  style="margin: 10px;">Fabricante:<b>{{$fabricante->NOMBRE_FABRICANTE}}</b></p>
      <p align="justify"  style="margin: 10px;">Domicilio: <b>{{$fabricante->NOMBRE_PAIS}}</b></p>


      <table width="100%" class="table table-hover">
          <thead>
              <th>NOMBRE DEL INSUMO MÉDICO</th>
              <th>CÓDIGO</th>
              <th>MODELO</th>
              <th>DESCRIPCIÓN</th>
          </thead>
      </table>

      @foreach($modelos as $modelo)
      <table width="100%" class="table table-hover">
        <tbody>
          <tr>{{$modelo->NOMBRE_COMERCIAL}}</tr>
          <tr>{{$modelo->codigos}}</tr>
          <tr>{{$modelo->modelos}}</tr>
          <tr>{{$modelo->descripcion}}</tr>
        </tbody>
      </table>
      @endforeach


      <p align="justify"  style="margin: 10px;">Presentación Comercial: <b>{{$producto->PRESENTACIONES  }}</b></p>
      <p align="justify"  style="margin: 10px;">La presente no sustituye en ningún caso la licencia de registro sanitario como dispositivo médico.</p>
      <br>
      <p align="justify"  style="margin: 10px;">Por lo que extiendo, firmo y sello la presente a petición de (solicitante), {{$dia}}</p>
      <br>
      <br>
      <div align="center">
         @if(date("Y-m-d") >= "2018-01-11")
             <!--<img id="firma" src="{{ resource_path('assets/images/firmas/firma-pena-coordinador.png') }}" /> -->
         @else
           <!--   <img id="firma" src="{{ resource_path('assets/images/firmas/firma-dra-ejecutiva.png') }}" /> -->
         @endif
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