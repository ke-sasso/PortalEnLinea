<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Solicitud{{$solicitud->ID_SOLICITUD}} </title>
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
      * {
        padding-top: 0;
        margin-top: 0;
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
    </style>
  </head>
  <body>
    <header>
      <label class="padding2" id="padding2" style="opacity: 0.4;">{{$tramite->codHerramienta}}</label>    
      <div class="logo" style="text-align: center;">
        <img id="nuevologo" src="{{url('img/LOGO_HERRAMIENTA_URIM.png')}}">
      </div>      
    </header>

      <p align="justify"  style="margin: 30px; line-height: 2.5;">{!!$resolucion->resolucion_encabezado!!}</p>

      <p align="justify" style="margin: 30px; line-height: 2.5;">{!!$resolucion->resolucion_parrafo_I!!}</p>
     <p align="justify" style="margin: 30px;">
      Esta Dirección <strong><u>RESUELVE:</u></strong>
     </p>
    <table id="noBorderTable" cellspacing="0" cellpadding="0" style="border: none !important;">
            <tr>
                <td>
                    <strong>a)</strong>
                </td>
                <td align="justify">
                  @if($tramite->id==1 && $fabricantes!=null && date("Y-m-d",strtotime($solicitud->FECHA_CREACION)) <= "2018-07-18")
                      Autorícese la <b>{{mb_strtolower($tramite->nombre,"UTF-8")}}</b> del dispositivo médico antes referido, el cual es fabricado por <b>{{$fabricantes[0]->fabricantes}}</b>, del domicilio de <b>{{$fabricantes[0]->direcciones}}, respectivamente</b>.
                  @else
                      @if($solicitud->ID_SOLICITUD==5573)
                        Autorícese la <b>{{$tramite->nombre}}</b> del dispositivo médico antes referido, el cual es fabricado por <b>YIWU HYEGIIR MEDICAL SUPPLY CO., LTD.</b>, del domicilio de <b>CHINA</b>.
                      @elseif($solicitud->ID_SOLICITUD==5464)
                        Autorícese la <b>{{$tramite->nombre}}</b> del dispositivo médico antes referido, el cual es fabricado por <b>WELL LEAD MEDICAL CO LTD y CHANGZHOU WEITE MEDICAL EQUIPMENT CO. LTD</b>, del domicilio de <b>CHINA</b>.
                      @elseif($solicitud->ID_SOLICITUD==5470)
                        Autorícese el <b>{{$tramite->nombre}}</b> del dispositivo médico antes referido, el cual es fabricado por <b>JIANGSU YADA TECHNOTOGY GROUP CO.LTD. y  CHANGZHOU HUANKANG MEDICAL DEVICE, CO,LTD.</b>, del domicilio de <b>CHINA</b>.
                      @elseif($fabricante!=null)
                            @if(date("Y-m-d",strtotime($solicitud->FECHA_CREACION)) >= "2018-03-14")
                                Autorícese la <b>{{mb_strtolower($tramite->nombre,"UTF-8")}}</b> del dispositivo médico antes referido, el cual es fabricado por  {{$fabricante[0]->fabricantes}}.
                            @else
                                Autorícese la <b>{{mb_strtolower($tramite->nombre,"UTF-8")}}</b> del dispositivo médico antes referido, el cual es fabricado por <b>{{$fabricante->NOMBRE_FABRICANTE}}</b>, del domicilio de <b>{{$fabricante->DIRECCION}}, {{$fabricante->NOMBRE_PAIS}}</b>.
                            @endif
                      @else
                          Autorícese la <b>{{mb_strtolower($tramite->nombre,"UTF-8")}}</b> del dispositivo médico antes referido, el cual es fabricado por <b>N/A</b>, del domicilio de <b>N/A</b>.
                      @endif
                  @endif

                </td>
            </tr>
            <tr align="justify" >
                <td>
                    <strong>b)</strong>
                </td>
                <td align="justify" >
                @if($tramite->id==8)
                   Consígnense al registro el/la presentación(es) comercial: <b>{{$solicitud->DESCRIPCION_TRAMITE}}</b>
                @else
                  Margínese lo anterior al número de registro correspondiente.
                @endif
                </td>
            </tr>
            @if($tramite->id==8)
             <tr>
                <td>
                    <strong>
                        c)
                    </strong>
                </td>
                <td align="justify" >
                   Margínese lo anterior al número de registro correspondiente.

                </td>
            </tr>
            @endif
            <tr>
                <td>
                    <strong>
                      @if($tramite->id==8)
                        d)
                      @else
                        c)
                      @endif
                    </strong>
                </td>
                <td>
                    Notifíquese.
                </td>
            </tr>

        </table>

      <br>
      <div align="center">
        @if(date("Y-m-d",strtotime($resolucion->fecha_creacion)) >= "2019-07-11")
            <img id="firma" src="{{ resource_path('assets/images/firmas/firma-dra-ayala.png') }}" />
        @else
            <img id="firma" src="{{ resource_path('assets/images/firmas/firma-pena-de.png') }}" />
        @endif
      </div>


      @inject('barcode', 'Milon\Barcode\DNS1D')



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