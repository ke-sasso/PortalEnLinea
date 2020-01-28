<!--
 * User: oscar.merino
 * Date: 6/6/2018
 * Time: 5:09 PM
 -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title></title>
    <style type="text/css">

        body {
            font-size: 14px;

        }

        div#header {
            width: 74%;
            display: inline-block;
            margin: 0 auto;
            border: 1px solid black;
        }

        div#header img#escudo {
            height: 60px;
            width: auto;
            max-width: 20%;
            display: inline-block;
            margin: 0.5em;
        }

        div#header img#logo {
            height: 40px;
            width: auto;
            max-width: 20%;
            display: inline-block;
            margin: 0.5em;
        }

        div#header div#mainTitle {
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

        div#subTitle {
            width: 300px;
            height: 50px;
            margin: 0 auto;
            bottom: 200px;
            border: 2px solid black;
        }

        table.comprobante {
            width: auto;
            min-width: 95%;
            max-width: 95%;
            margin: 0 auto;
            border-collapse: collapse !important;
            width: auto !important;
            max-width: 70% !important;
            margin: 0 auto;
            font-family: 'times new roman' !important;
            width: auto !important;
            max-width: 70% !important;
            margin: 0 auto;
            padding: 1px;
            padding-left: 0.1em;
        }

        table.comprobante th, tr td {
            border: 1px solid #9D9D9D;
            padding-bottom: 0px !important;
            text-align: left !important;
            max-width: 70% !important;
            margin: 0 auto;
            padding-left: 0.1em;
        }
        table.comprobante th{
            border: 1px solid #9D9D9D;
            padding: 0px !important;
            text-align: center !important;
            padding-left: 0.1em;
        }
        table.cabecera th{
            padding: 0px !important;
            text-align: center !important;
            padding-left: 0.1em;
        }

    </style>
</head>
<body>


<header>
    <table class="cabecera" style="width:100%; border-collapse: collapse;">
        <tbody>
        <tr>
            <th style="width:15%;">
                    <img id="escudo" src="{{ url('img/escudo.png') }}"/>
            </th>
            <th style="width:70%;">                    
                <h2>Dirección Nacional de Medicamentos</h2>
                <p>República de El Salvador, América Central</p>
                <hr>
            </th>
            <th>
                    <img id="logo" src="{{ url('img/dnm.png') }}"/>
            </th>
        </tr>
        </tbody>
    </table>
</header>
<div align="center">
    <div>
        <h3>COMPROBANTE DE SOLICITUD<br>REGISTRO SANITARIO DE PRODUCTOS COSMETICOS E HIGIENICOS</h3>

        <table class="comprobante">
            <thead>
                <tr>
                    <th colspan="4"><h3>GENERALIDADES</h3></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td><b>No Solicitud:</b></td>
                <td class="center">{{$info->solicitud->numeroSolicitud}}</td>
                <td><b>Fecha y hora de envío de la solicitud:</b></td>
                <td>@if($info->solicitud->fechaEnvio){{$info->solicitud->fechaEnvio}} @else <b> - </b> @endif</td>
            </tr>
            <tr>
                <td><b>Trámite:</b></td>
                <td>@if($info->tramite) {{$info->tramite}} @else <b> - </b> @endif</td>
                <td><b>Fecha y hora de emisión de comprobante:</b></td>
                <td>{{$date}}</td>
            </tr>
            <tr>
                <td><b>Anualidad:</b></td>
                <td></td>
                <td><b>Renovación:</b></td>
                <td></td>
            </tr>
            </tbody>
        </table><br><br>
    </div>
    <div>
        <table class="comprobante">
            <thead>
                <tr>
                    <th colspan="2"><h3>DATOS DEL PRODUCTO</h3></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="40%"><b>No registro:</b></td>
                    <td>Sin Asignar</td>
                </tr>
                <tr>
                    <td><b>Nombre comercial:</b></td>
                    <td>@if($info->nombreComercial) {{$info->nombreComercial}} @else <b> - </b> @endif</td>
                </tr>
                <tr>
                    <td><b>Profesional responsable:</b></td>
                    <td>{{$info->profesional->NOMBRES}} {{$info->profesional->APELLIDOS}}</td>
                </tr>
                <tr>
                    <td><b>Titular:</b></td>
                    <td>@if($info->titular->nombre) {{$info->titular->nombre}} @else <b> - </b> @endif</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Fabricantes:</b></td>
                </tr>
                @if(count($info->fabricantes) > 0)
                    
                    <tr>
                        <td colspan="2">
                            @foreach($info->fabricantes as $fab)
                            {{$loop->iteration}}- {{$fab->nombreComercial}}<br>
                            @endforeach
                        </td>
                    </tr>
                    
                @endif
                <tr>
                    <td colspan="2"><b>Presentaciones:</b>
                </tr>
                @if(count($info->presentaciones) > 0)
                    @foreach($info->presentaciones as $pre)
                    <tr>
                        <td colspan="2">{{$loop->iteration}}- {{$pre->textoPresentacion}}</td>
                    </tr>
                    @endforeach
                @endif
                <tr>
                    <td><b>Estado del trámite:</b></td>
                    <td>@if($info->estado->estado) {{$info->estado->estado}} @else <b> - </b> @endif</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<footer id="footer">
    <hr>
    Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América
    Central.
    &nbsp;&nbsp;
    PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
</footer>

</body>

</html>