  <html>
<head>
  <title>Constancia</title>
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


      #leyend { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 200px; opacity: 0.6;  }
      #footer { position: fixed; left: 0px; bottom: -175px; right: 0px; height: 200px; opacity: 0.6;  }
      #pageCount { position: fixed; left: 80%; bottom: -187px; right: 0px; height: 150px; opacity: 0.1;  }
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

        }
    table.collapse-tbl th,table.collapse-tbl td {
      border: 1px 1px solid black !important;
          width: 33% !important;
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

     #content {
        float: right;
        position: absolute;
        left: 70%;
        top:-30%;
      }      
    #padding2{      
      position: absolute;
      padding-top: 10px;
      margin-top: 10px;
      padding-left: 550px;
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
    <br>
     <main>

      <p align="justify"  style="margin: 10px; line-height: 2.5;">
      El infrascrito Jefe de la Unidad de Registro de Insumos Médicos de la Dirección Nacional de Medicamentos, de conformidad a lo establecido en la Ley de Medicamentos, HACE CONSTAR:
      Que en esta Dirección se encuentra registrado como dispositivo médico el producto denominado <b>{{$solicitud->NOMBRE_INSUMO}}</b>, inscrito al número IM: <b>{{$solicitud->IM}}</b> y cuyo Titular del Registro Sanitario es <b>{{$producto->NOMBRE_PROPIETARIO}}</b>, según se detalla a continuación:
      </p>

      <p align="justify"  style="margin: 10px;">Fabricante:<b>{{$fabricante->NOMBRE_FABRICANTE}}</b></p>
      <p align="justify"  style="margin: 10px;">Domicilio: <b>{{$fabricante->NOMBRE_PAIS}}</b></p>

      <p align="justify"  style="margin: 10px;">Presentación Comercial: <b>{{$producto->PRESENTACIONES  }}</b></p>
      <p align="justify"  style="margin: 10px;">La presente no sustituye en ningún caso la licencia de registro sanitario como dispositivo médico.</p>
      <br>

     </main>
  </div>

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
          </tbody>
        </table>

          @foreach($modelos as $modelo)
            <table class="collapse-tbl">
              <tbody>
                <tr>
                  <td >{{$modelo->codigos}}</td>
                  <td >{{$modelo->modelos}}</td>
                  <td >{{$modelo->descripcion}}</td>
                </tr>
              </tbody>
            </table>
          @endforeach
      </center>

  </div>

  <br>
  <br>
  <div align="center" id="pie-final">
     <p align="justify"  style="margin: 10px; line-height: 2.5;">Por lo que extiendo, firmo y sello la presente a petición de <b>{{$solicitante->nombres .' '}}{{$solicitante->apellidos}}</b>, {{$dia}}</p>
  </div>

  <div align="center" id="firma">

    <p align="justify"  style="margin: 10px;">
        @if(date("Y-m-d",strtotime($solicitud->FECHA_CREACION)) >= "2019-07-11")
            <img id="firma" src="{{ resource_path('assets/images/firmas/firma-dra-ayala.png') }}" />
        @else
            <img id="firma" src="{{ resource_path('assets/images/firmas/firma-pena-de.png') }}" />
        @endif
      <p>
  </div>
  <div id="leyend">
    <center><b>El presente documento hace referencia al estado actual del trámite o registro a la fecha de su emisión.</b></center>
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
