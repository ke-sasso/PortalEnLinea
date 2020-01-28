<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Solicitud #: {{$solicitud->idSolicitud}} </title>
    <style type="text/css">
      body{
        font-size: 13px;
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
        max-width: 300px; 
        max-height: 600px;
      
      }
    </style>
  </head>
  <body>


    
    <table  style="width:100%;">
      <tr>
        <td style="width:15%;">
          <center>
            <img id="escudo" src="{{ url('img/escudo.png') }}" />
          </center> 
        </td>
        <td style="width:70%;">
          <center>
             
            <br>

            <h2 style="margin:0;padding:0;">
            </h2>
          </center>
        </td>
        <td>
          <center>
            <img id="logo" src="{{ url('img/dnm.png') }}" />
          </center> 
        </td>
    </table>
    <br>
    <h3 style="margin:0;padding:0;">DIRECCIÓN NACIONAL DE MEDICAMENTOS </h3>
    <h3 style="margin:0;padding:0;">PRESENTE. </h3>
    
      <div class="row">
      
      <div align="right"><label>Fecha de Solicitud: {{ date_format(date_create($solicitud->fechaCreacion),"d/m/Y")}}</label></div>
      </div>
      <div align="left"><label>Numero de Solicitud: {{$solicitud->numeroSolicitud}}</label></div> 
  
    <p>Tipo de Tramite: {{$solicitud->nombreTramite}}</p>
    <p>Solicitante de la publicidad: {{$solicitud->establecimientos}}</p>

    <br>
    <p>Solicitamos autorización de publicidad para los producto:</p>
    <table  width="100" border="1" cellspacing="0" cellpadding="2" style="width:100%;">
      

        <thead>
          <tr align="center">
            <th width="5%" style="background-color:rgb(184,184,184)">N°</th>
            <th width="10%" style="background-color:rgb(184,184,184)">Nombre del Producto</th>
            <th width="60%" style="background-color:rgb(184,184,184)">N° de registro sanitario</th>
            <th width="20%" style="background-color:rgb(184,184,184)">Modalidad de venta</th>
          </tr>
        </thead>
     <br>
     <br>
     <br>
    
     <tbody>
        @for($i=0;$i<count($productos);$i++)
      <tr>
        <td colspan="" style="width:5%">
           
          <center>{{$i+1}}</center>
        </td>
        <td colspan="" style="width:10%">
         
            <center>{{$productos[$i]['num']}}</center>
         
        </td>
        <td colspan="" style="width:10%">{{$productos[$i]['nombre']}}</td>
        <td colspan="" >{{$productos[$i]['mod']}}</td>
      </tr>
      @endfor
    </tbody>
    </table>
    <p>Que sera publicado por medio de: {{$solicitud->nombreMedio}} </p>
    <p>Cuya version se denominara: {{$solicitud->version}}</p>
    <p>Numero de Mandamiento Cancelado: <b>{{$solicitud->numMandamiento}}</b></p>
    <p>Agencia publicitaria (cuando aplique): N/A</p>

    <p>Nit del Encargado: {{$solicitud->nitSolicitante}}</p>
    <p>Encargado para recibir notificación: {{$solicitud->nombres.' '.$solicitud->apellidos}}</p>
    <p>Numero del telefono del encargado para recibir notificación:
      @if(count($solicitud->tels)==2)
        {{$solicitud->tels[0].', '.$solicitud->tels[1]}}
      @else
        {{$solicitud->tels[0]->telefono1.', '.$solicitud->tels[0]->telefono2}}
      @endif
    </p>
    <p>Correo electrónico para recibir notificación: {{$solicitud->emailsContacto}}</p>
    <br>
    Santa Tecla, a los {{ date_format(date_create($solicitud->fechaCreacion),"d")}} del mes de {{ date_format(date_create($solicitud->fechaCreacion),"F")}} del {{ date_format(date_create($solicitud->fechaCreacion),"Y")}}
    <br>
    <br>

    
  </body>
</html>