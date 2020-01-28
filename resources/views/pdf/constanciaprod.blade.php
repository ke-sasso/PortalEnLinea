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
      #leyend { position: fixed; left: 0px; bottom: -125px; right: 0px; height: 200px; opacity: 0.6;  }
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

      <h4 align="justify" style="margin: 10px;">LA DIRECCION NACIONAL DE MEDICAMENTOS HACE CONSTAR LAS CONDICIONES DE AUTORIZACION DE REGISTRO SANITARIO DEL PRODUCTO: </h4>

    <p align="justify"  style="margin: 10px;">Número de registro sanitario: <b>{{$producto->ID_PRODUCTO}}</b></p>
      <p align="justify"  style="margin: 10px;">Nombre Comercial: <b>{{$producto->NOMBRE_COMERCIAL}}</b></p>
      <p align="justify"  style="margin: 10px;">Vía de administración: <b>{{$producto->NOMBRE_VIA_ADMINISTRACION}}</b></p>
      @if($formafarm!=null)
        <p align="justify"  style="margin: 10px;">Forma farmacéutica: <b>{{$formafarm->nombre_forma_farmaceutica}}</b></p>
      @else
        <p align="justify"  style="margin: 10px;">Forma farmacéutica: <b>N/A</b></p>
      @endif

      <p align="justify"  style="margin: 10px;">Concentración por unidad posológica: <b>{!!$concentracion!!}</b> </p>
      <p align="justify"  style="margin: 10px;">Presentación del producto: <b>{{$presentaciones[0]->presentaciones}}</b></p>
      <p align="justify"  style="margin: 10px;">Vida útil aprobada: <b>{{$producto->VIDA_UTIL}}</b></p>
      <p align="justify"  style="margin: 10px;">Condiciones de almacenamiento: <b>{{$producto->CONDICIONES_ALMACENAMIENTO}}</b> </p>
      <p align="justify"  style="margin: 10px;">Nombre del titular del registro: <b>{{$titular->NOMBRE_PROPIETARIO}}</b></p>
      <p  align="justify"  style="margin: 10px;">País del titular: <b>{{$titular->NOMBRE_PAIS}}</b></p>
    @if($fabricante!=null)
      @if(count($fabricante)>1)
      <p align="justify"  style="margin: 10px;">Nombre del fabricante principal: <b>{{$fabricante[0]->nombre}}</b> </p>
      <p align="justify"  style="margin: 10px;">País del fabricante principal: <b>{{$fabricante[0]->nombre_pais}}</b></p>
      <p align="justify"  style="margin: 10px;">Nombre del fabricante alterno: <b>{{$fabricante[1]->nombre}}</b> </p>
      <p align="justify"  style="margin: 10px;">País del fabricante alterno: <b>{{$fabricante[1]->nombre_pais}}</b></p>
      @else
      <p align="justify"  style="margin: 10px;">Nombre del fabricante principal: <b>{{$fabricante[0]->nombre}}</b> </p>
      <p align="justify"  style="margin: 10px;">País del fabricante principal: <b>{{$fabricante[0]->nombre_pais}}</b></p>
      @endif
    @else
      <p align="justify"  style="margin: 10px;">Nombre del fabricante principal: <b>N/A</b> </p>
      <p align="justify"  style="margin: 10px;">País del fabricante principal: <b>N/A</b></p>
    @endif
      <p align="justify"  style="margin: 10px;">Modalidad de venta: <b>{{$modV->NOMBRE_MODALIDAD_VENTA}}</b></p>
      <p align="justify"  style="margin: 10px;">Anualidad: <b>
        {{date('d/m/Y',strtotime($producto->VIGENTE_HASTA))}}</b></p>
      <p align="justify"  style="margin: 10px;">Renovación: <b>{{date('d/m/Y',strtotime($producto->ULTIMA_RENOVACION))}}</b></p>
    @if(count($acondicionador)< 1)
    <p align="justify"  style="margin: 10px;">Nombre del Acondicionador: <b>N/A</b></p>
    @else
    <p align="justify"  style="margin: 10px;">Nombre del Acondicionador: <b>{{$acondicionador[0]->nombreComercial}}</b></p>
    @endif

      <p align="justify"  style="margin: 10px;">Santa Tecla, La Libertad, {{$dia}}</p>

       @if(date("Y-m-d",strtotime($fechaCreacion)) >= "2019-07-11")
            <img id="firma" src="{{ resource_path('assets/images/firmas/firma-dra-ayala.png') }}" />
        @else
            <img id="firma" src="{{ resource_path('assets/images/firmas/firma-pena-de.png') }}" />
        @endif



    </div>

   <div id="leyend">
    <center><b>El presente documento hace referencia al estado actual del trámite o registro a la fecha de su emisión.</b></center>
  </div>
   <footer id="footer">
   <hr>
   Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.
   &nbsp;&nbsp;
   PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
    </footer>

  </body>

</html>