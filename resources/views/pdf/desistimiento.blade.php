<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>{{$tramite->nombre.$solicitud->ID_SOLICITUD}}</title>
    <style type="text/css">

      body{
        text-align: justify ;
        font-style: 'times new roman' !important;
        font-size: 12px;

      }
       #wrap {
      float: center;
      position: relative;
      left: 35%;
    }

      #content {
        float: right;
        position: absolute;
        left: 70%;
        top:-30%;
      }        
       #footer { position: fixed; left: 0px; bottom: -150px; right: 0px; height: 180px; opacity: 0.6;font-size: 12px; }
      div#subTitle{
        width: 300px;
        height: 50px;
        margin: 0 auto;
        bottom: 200px;
        border: 2px solid black;
      }

      table#tablecodmod{
        width: auto;
            min-width: 70%;
        max-width: 70%;
            margin: 0 auto;
        border-collapse: collapse !important;
    }
    table#tablecodmod tr td{
        border-left: .5px solid black;
        border-right: .5px solid black;
        border-top: .5px solid black;
        border-bottom: .5px solid black !important;
        padding-bottom: 0px  !important;
        text-align: center !important;
    }

    table.collapse-tbl {
                width: 80% !important;
                text-align: center;
                border-collapse: collapse !important;

            }
    table.collapse-tbl th,table.collapse-tbl td {

    }
    table#noBorderTable{
        border: none !important;
        width: 85% !important;
        margin: 0 auto !important;
    }
    table#noBorderTable tr, table#noBorderTable td{
        border: none !important;
        padding: 10px !important;
        margin-bottom: : 10px !important;
    }

    .opacityFont{
        opacity: 0.5;
        font-weight: bold;
        padding: 0px !important;
        margin: 0px !important;
    }

    .opacityFontTwo{
        opacity: 0.4;
        font-weight: bold;
        padding: 0px !important;
        margin: 0px !important;
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
      padding-left: 575px;
    }
    * {
      padding-top: 0;
      margin-top: 0;
    }
    </style>
  </head>
  <body>
    <header>
      <label class="padding2" id="padding2" style="opacity: 0.4;">{{$tramite->codHerramienta}}</label>    
      <div class="logo" style="text-align: center;">
        <img id="nuevologo" src="{{url('img/LOGO_HERRAMIENTA_URIM.png')}}">
      </div>      
    </header>
    <div align="center">

      <p align="justify"   style="margin: 30px; line-height: 2.5;">
        @if($idTramite==28)
          {!!$desistimiento->encabezado!!}
        @else
          {!!$resolucion->resolucion_encabezado!!}
        @endif
      </p>

      <p align="justify"   style="margin: 30px; line-height: 2.5;">
        <b>I. </b>&nbsp;@if($idTramite==28){!! $desistimiento->texto_I!!}@else {!! $resolucion->resolucion_parrafo_I!!}@endif
      </p>

    <p align="justify" style="margin: 30px;"><b>II.</b> Vista la anterior comunicación, esta Dirección <b>RESUELVE:</b></p>

      @if (View::exists('pdf.Sim.anexos.anexo_'.$tramite->id.''))
            @include('pdf.Sim.anexos.anexo_'.$tramite->id.'')
      @endif

      <br>
      <br>
      <br>
      <br>
      @if($idTramite==17 || $idTramite==18 || $idTramite==15)
            @if(date("Y-m-d",strtotime($resolucion->fecha_creacion)) >= "2019-07-11")
                <img id="firma" src="{{ resource_path('assets/images/firmas/firma-dra-ayala.png') }}" />
            @else
                <img id="firma" src="{{ resource_path('assets/images/firmas/firma-pena-de.png') }}" />
            @endif
      @else
            @if(date("Y-m-d",strtotime($desistimiento->fecha_creacion)) >= "2019-07-11")
                <img id="firma" src="{{ resource_path('assets/images/firmas/firma-dra-ayala.png') }}" />
            @else
                <img id="firma" src="{{ resource_path('assets/images/firmas/firma-pena-de.png') }}" />
            @endif
      @endif
     @inject('barcode', 'Milon\Barcode\DNS1D')
    </div>

    <footer id="footer">
        <div id="content">
        {!! $barcode->getBarcodeHTML($solicitud->ID_SOLICITUD, "C39");!!}
        </div>
        <p style="text-align: left; width:49%; display: inline-block; margin-top: 0em; line-height: 0;">No. de referencia de solicitud: <b>{{$solicitud->ID_SOLICITUD}}</p>
      <hr/>
       <center>Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.
       <br>PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv</center>
    </footer>

  </body>

</html>