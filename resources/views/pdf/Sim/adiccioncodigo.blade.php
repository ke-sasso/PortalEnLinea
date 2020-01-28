  <html>
<head>
  <title>Solicitud #:{{$solicitud->ID_SOLICITUD}}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
        float: right;
        position: absolute;
        left: 70%;
        top:-30%;
      }
    @font-face{
      font-family:"MyWebFont";
      src:url("{{asset('assets/fonts/EdwardianScriptITC.ttf')}}") format('');
    }

    div#headerinit{
      margin-top: -6.0em;
      @if(isset($fontsize))
          font-size: {{$fontsize}};
      @else
        font-size: {{'12px'}};
      @endif
    }



      #footer { position: fixed; left: 0px; bottom: -150px; right: 0px; height: 180px; opacity: 0.6;font-size: 12px; }
      #pageCount { position: fixed; left: 80%; bottom: -187px; right: 0px; height: 180px; opacity: 0.1;  }
      #pageCount:before{ content: "PAGINA " counter(page) ; }
      #mainbody{
        @if(isset($fontsize))
          font-size: {{$fontsize}};
        @else
          font-size: {{'14px'}};
        @endif
      }
      #encabezado{
        margin-top: -8em !important;
        @if(isset($fontsize))
          font-size: {{$fontsize}};
        @else
          font-size: {{'12px'}};
        @endif
      }
      #parrafo-I{
        @if(isset($fontsize))
          font-size: {{$fontsize}};
        @else
          font-size: {{'12px'}};
        @endif
      }#parrafo-II{
        @if(isset($fontsize))
          font-size: {{$fontsize}};
        @else
          font-size: {{'12px'}};
        @endif
      }#codmods{
        margin: 0 auto;
      }#pie{
        @if(isset($fontsize))
          font-size: {{$fontsize}};
        @else
          font-size: {{'12px'}};
        @endif
      }#pie-final{
        @if(isset($fontsize))
          font-size: {{$fontsize}};
        @else
          font-size: {{'14px'}};
        @endif
      }#firma{
          height: auto;
          width: auto;
          max-width: 400px;
          max-height: 800px;
      margin: 0 auto;
        }
      }#extra{
        @if(isset($fontsize))
          font-size: {{$fontsize}};
        @else
          font-size: {{'12px'}};
        @endif
      }
      table#tablecodmod{
        width: auto;
        min-width: 70%;
        max-width: 70%;
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
      table#tablecodmod tr td{
      border-left: .5px solid black;
      border-right: .5px solid black;
      border-top: .5px solid black;
      border-bottom: .5px solid black !important;
      padding-bottom: 0px  !important;
      text-align: center !important;
      width: auto !important;
        max-width: 70% !important;
      margin: 0 auto;

    }

    table.collapse-tbl {
          width: 80% !important;
          text-align: center;
          border-collapse: collapse !important;
          margin: 0 auto;
          page-break-before:auto;

        }
    table.collapse-tbl th,table.collapse-tbl td {
      border: 1px 1px solid black !important;
          width: 100px !important;
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
      #padding2{      
        position: absolute;
        padding-top: 10px;
        margin-top: 10px;
        padding-left: 570px;
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

  <div align="center" id="mainbody">
     <main>
      <p align="justify"  style="margin: 30px; line-height: 2.5;">
      {!!$resolucion->resolucion_encabezado!!}
      </p>

      <p align="justify"  style="margin: 30px; line-height: 2.5;">{!!$resolucion->resolucion_parrafo_I!!}</p>

      <p align="justify" style="margin: 30px;">
      Esta Dirección <strong><u>RESUELVE:</u></strong>
     </p>
     </main>
  </div>

  <p align="justify" style="margin: 30px;">
    <table id="noBorderTable" cellspacing="0" cellpadding="0" style="border: none !important;">
            <tr>
                <td>
                    <strong>a)</strong>
                </td>
                <td>
                    @if($fabricante!=null)
                        @if($solicitud->ID_SOLICITUD==8147)
                            Autorícese la <b>ADICIÓN DE CÓDIGOS</b> del dispositivo médico antes referido, el cual es
                            fabricado por <b>ARTHREX, INC. y HENKE-SASS WOLF, GMBH</b>, del domicilio de <b>1370 Creekside Blvd Naples, FL. 34108-
                                1945 ESTADOS UNIDOS DE AMÉRICA y KELTENSTRASSE 1 TUTTLINGEN, Baden-Wurttemberg ALEMANIA 78532 ALEMANIA</b> respectivamente.
                        @elseif($solicitud->ID_SOLICITUD==8260)
                            Autorícese la <b>ADICIÓN DE CÓDIGOS</b> del dispositivo médico antes referido, el cual es
                            fabricado por <b>ARTHREX, INC. y NEW DEANTRONICS TAIWAN, LTD.</b>, del domicilio de <b>1370 Creekside Blvd Naples, FL. 34108-
                                1945 ESTADOS UNIDOS DE AMÉRICA y 12f No. 51, sec.4, Chong Yang Road, Tu Cheng Dist., New Tapei City, Tapei District, 23675 TAIWÁN</b> respectivamente.
                        @else
                            @if(date("Y-m-d",strtotime($solicitud->FECHA_CREACION)) >= "2018-03-14")
                                Autorícese la <b>{{mb_strtolower($tramite->nombre,"UTF-8")}}</b> del dispositivo médico antes referido, el cual es fabricado por  {{$fabricante[0]->fabricantes}}.
                            @else
                                Autorícese la <b>{{mb_strtolower($tramite->nombre,"UTF-8")}}</b> del dispositivo médico antes referido, el cual es fabricado por <b>{{$fabricante->NOMBRE_FABRICANTE}}</b>, del domicilio de <b>{{$fabricante->DIRECCION}}, {{$fabricante->NOMBRE_PAIS}}</b>.
                            @endif

                        @endif
                  @else
                    Autorícese la <b>{{mb_strtolower($tramite->nombre,"UTF-8")}}</b> del dispositivo médico antes referido, el cual es fabricado por <b>N/A</b>, del domicilio de <b>N/A</b>.
                  @endif
                </td>
            </tr>
            <tr>
                <td>
                    <strong>b)</strong>
                </td>
                <td>
                  @if(count($solcodmod)==1)
                    Consígnense al registro el código detallado(s) en la siguiente tabla:
                  @else
                    Consígnense al registro los códigos detallado(s) en la siguiente tabla:
                  @endif
                  <br>
                </td>
            </tr>
    </table>
  </p>

  <div align="center" id="codmods">

          <strong>CODIGOS Y MODELOS:</strong>
          <br>
          <center>

              <table class="collapse-tbl">
                <tbody>
                    <tr>
                  <th>Códigos</th>
                  <th>Modelos</th>
                  <th>Descripcion</th>

                </tr>
                   @foreach($solcodmod as $solcod)
                  <tr>
                    <td >{{$solcod->codigos}}</td>
                    <td >{{$solcod->modelos}}</td>
                    <td >{!!$solcod->descripcion!!}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>



          </center>

      </div>

  <p align="justify" style="margin: 30px;">
    <table id="noBorderTable" cellspacing="0" cellpadding="0" style="border: none !important;">
            <tr>
                <td>
                    <strong>
                        c)
                    </strong>
                </td>
                <td>
                   Margínese lo anterior al número de registro correspondiente.
                </td>
            </tr>
            <tr>
                <td>
                    <strong>
                        d)
                    </strong>
                </td>
                <td>
                    Notifíquese.

                </td>
            </tr>
    </table>
  </p>

  <div align="center" id="firma">
    <p align="justify"  style="margin: 10px;">
        @if(date("Y-m-d",strtotime($resolucion->fecha_creacion)) >= "2019-07-11")
            <img id="firma" src="{{ resource_path('assets/images/firmas/firma-dra-ayala.png') }}" />
        @else
            <img id="firma" src="{{ resource_path('assets/images/firmas/firma-pena-de.png') }}" />
        @endif
    <p>
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
