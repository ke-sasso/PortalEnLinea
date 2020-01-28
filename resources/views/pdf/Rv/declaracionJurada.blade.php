<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>DECLARACIÓN JURADA </title>
    <style type="text/css">
   
      body{
        font-size: 14px;

      }

      
    </style>
  </head>
  <body>

     <p align="justify" style="margin: 30px; line-height: 2.5;">
       Yo, {{$solicitud->PERSONA}}, {{$solicitud->TITULO}} doy fe que he verificado la información contenida en el registro sanitario del producto del cual estoy realizando  el trámite de {{$tramite->NOMBRE_TRAMITE}} y la información en el registro no ha variado desde la última solicitud de modificación presentada ante la Direccion Nacional de Medicamentos.
     </p>
     <br><br><br>
     <p align="center" style="margin: 30px; line-height: 2.5;">
       _________________________________________________________________
     </p>
     <p align="center" style="margin: 30px; line-height: 2.5;">
       FIRMA SOLICITANTE
     </p>
  </body>

</html>