<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Resolucion Observada Post-Registro Unidad Jurídica</title>
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
                        <strong>UNIDAD JURIDICA</strong>
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
    <p align="justify"  style="margin: 30px;">La Libertad, Santa Tecla, {{$dia}}</p>

    <p align="justify" style="margin: 30px;">{!!$dictamen!!}</p>

    <br>
    <br>
    <br>
    <br>
    <div>
        <!--<img id="firma" src="{{ resource_path('assets/images/firmas/firma-herman-juridico.png') }}" />-->
        <p align="center">Licda. Claudia Díaz de Castillo</p><br>
        <p align="center">JEFE DE UNIDAD JURÍDICA</p>
    </div>
    <br>
    <br>
    <br>
    <br>


</div>

<footer id="footer">
    <hr>
    <center>
        Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.
        &nbsp;&nbsp;PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
    </center>
</footer>

</body>

</html>