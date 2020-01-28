<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Solicitud{{$solicitud->ID_SOLICITUD}} </title>
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

      #firma{
        height: auto;
        width: auto;
        max-width: 400px;
        max-height: 800px;

      }
      * {
        padding-top: 0;
        margin-top: 0;
      }
    </style>
  </head>
  <body>
    <header>        
      <div class="logo" style="text-align: center;">
        <img id="nuevologo" src="{{url('img/LOGO_HERRAMIENTA_URIM.png')}}">
      </div>      
    </header>

    <div align="center">
     <main>
      <p align="right">No. de referencia de solicitud: <b>{{$solicitud->ID_SOLICITUD}}</b></p>
      <p align="justify"  style="margin: 30px;">{!! $dictamen->resolucion_encabezado !!}</p>

      <p align="justify" style="margin: 30px;">{!! $dictamen->resolucion_parrafo_I !!}</p>
     <p align="justify" style="margin: 30px;">
      Esta Dirección <strong><u>RESUELVE:</u></strong>
     </p>
    <table id="noBorderTable" cellspacing="0" cellpadding="0" style="border: none !important;">
            <tr>
                <td>
                    <strong>a)</strong>
                </td>
                <td>
                  @if($fabricante!=null)
                    @if(date("Y-m-d",strtotime($solicitud->FECHA_CREACION)) >= "2018-03-14")
                       Que el <b>{{mb_strtolower($tramite->nombre)}}</b> del dispositivo médico antes referido, el cual es fabricado por {{$fabricante[0]->fabricantes}}, presenta las siguientes observaciones:
                    @else
                        Que el <b>{{mb_strtolower($tramite->nombre)}}</b> del dispositivo médico antes referido, el cual es fabricado por <b>{{$fabricante->NOMBRE_FABRICANTE}}</b>, del domicilio de <b>{{$fabricante->DIRECCION}}, {{$fabricante->NOMBRE_PAIS}}</b>, presenta las siguientes observaciones:
                    @endif
                  @else
                        Que el <b>{{mb_strtolower($tramite->nombre)}}</b> del dispositivo médico antes referido, el cual es fabricado por <b>N/A</b>, del domicilio de <b>N/A</b>, presenta las siguientes observaciones:
                  @endif
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                   <b> I. {!! $dictamen->observaciones !!}</b>

                </td>
            </tr>
            <tr>
                <td>
                    <strong>b)</strong>
                </td>
                <td>
                    Que es necesario subsanar las observaciones relacionadas al post registro solicitado, para que el mismo sea favorable.
                </td>
            </tr>
            <tr>
                <td>
                    <strong>
                        c)
                    </strong>
                </td>
                <td>
                    Notifíquese.

                </td>
            </tr>

        </table>

      <br>
      <br>
      <br>
      <br>
      <!-- <img id="firma" src="{{ resource_path('assets/images/firmas/firma-dra-ejecutiva.png') }}" /> -->



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