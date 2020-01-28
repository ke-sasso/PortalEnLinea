<!--
 * User: oscar.merino
 * Date: 7/6/2018
 * Time: 10:01 AM
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
            font-size: 12px;
            position: fixed;
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

        table.formulario {
            width: 100;
            min-width: 100%;
            max-width: 100%;
            margin: 0 auto;
            border-color: #B4C6E7 !important;
            border-collapse: collapse;
            width: auto !important;
            margin: 0 auto;
            font-family: "Arial Narrow", Arial;
            font-size: 12px;
            page-break-before:auto;
            position: relative;
        }

        table.formulario thead, tr, td {
            border: 1px solid #B4C6E7;
            padding: 1px;
        }

        .titulos{
            background-color: #B8B8B8;
        }

    </style>
</head>
<body>


<div align="center">
    <table class="formulario">
        <thead>
            <tr style="text-align: center;">
                <td style="width:15%;" rowspan="3"><img id="logo" src="{{ url('img/dnm.png') }}"/></td>
                <td style="width:70%; font-weight: bold;">DIRECCION NACIONAL DE MEDICAMENTOS.</td>
                <td style="font-size: 12px; vertical-align: top;" rowspan="3">FECHA DE SELLO DE RECIBIDO DNM</td>
            </tr>
            <tr style="text-align: center;">
                <td style="font-weight: bold;">REGISTRO SANITARIO DE PRODUCTOS COSMETICOS E HIGIENICOS.</td>
            </tr>
            <tr style="text-align: center;">
                <td style="font-weight: bold;">FORMULARIO DE REGISTRO SANITARIO DE PRODUCTOS COSMETICOS/RECONOCIMIENTO MUTUO DEL REGISTRO SANITARIO DE PRODUCTOS COSMETICOS.
                </td>
            </tr>
        </thead>
    </table>
    <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr class="titulos"> 
                <td colspan="3" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>I. GENERALIDADES</b></td>
            </tr>
            <tr>
                <td colspan="3" style="width: 50%; min-width: 50%; max-width: 50%;"><b>NOMBRE COMERCIAL:</b> {{$info->solicitudTipo->nombreTramite}}</td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%; min-width: 50%; max-width: 50%;"><b>MARCA:</b> {{$info->marca}}</td>
                <td style="width: 50%; min-width: 50%; max-width: 50%;"><b>AREA DE APLICACION:</b> {{$info->areaaplicacion}}</td>
            </tr>
            <tr>
                <td colspan="2"><b>CLASIFICACION:</b> {{$info->clasificacion}}</td>
                <td><b>FORMA COSMETICA:</b> {{$info->formacos}}</td>
            </tr>
             <tr>
                <td colspan="3"><b>NOMBRE DEL PRODUCTO QUE COMPONE EL COEMPAQUE:</b> @if($solicitud->poseeCoempaque == 0) NO POSEE COEMPAQUE @else {{$solicitud->descripcionCoempaque}} @endif</td>
            </tr>
            @if($solicitud->tipoSolicitud==3 || $solicitud->tipoSolicitud==5)
            <tr>
                <td colspan="2"><b>No REGISTRO PAIS DE ORIGEN:</b> {{$solicitud->solicitudesDetalle->numRegistroExtr}}</td>
                <td><b>VENCE EN EL PAIS DE ORIGEN:</b> {{$solicitud->solicitudesDetalle->fechaVencimiento}}</td>
            </tr>
            @endif

            <tr> 
                <td colspan="3"><b>PRESENTACIONES:</b></td>
            </tr>
            @if(count($solicitud->presentaciones)>0)
                @foreach($solicitud->presentaciones as $pre)
                    <tr>
                        <td colspan="3"><b>{{$loop->iteration}} - PRESENTACION</b></td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>NOMBRE:</b> {{$pre->nombrePresentacion}}</td>
                        <td><b>PRESENTACION EN SISTEMA INTERNACIONAL:</b> {{$pre->textoPresentacion}}</td>
                    </tr>
                @endforeach
            @endif

            <tr class="titulos">
                <td colspan="3" style="padding-left: 1.8em; padding-bottom: 0.1em;">

                    <b>II. TITULAR 
                        @if(isset($info->titular))
                            @if($info->titular->tipoPersona=='N') - PERSONA NATURAL
                            @elseIf($info->titular->tipoPersona=='J') - PERSONA JURIDICA
                            @else - EXTRANJERO @endIf
                        @endif
                    </b>
                </td>
            </tr>
            @if(isset($info->titular))
                <tr>
                    <td colspan="3"><b>NOMBRE:</b> @if(isset($info->titular->nombre)) {{$info->titular->nombre}} @endif</td>
                </tr>
                <tr>
                    <td colspan="2"><b>PAIS:</b> @if(isset($info->titular->PAIS)) {{$info->titular->PAIS}} @endif</td>
                    <td><b>TELEFONO:</b> @if(isset($info->titular->telefonosContacto)) {{str_replace(array("[","]","\""),"",$info->titular->telefonosContacto)}} @endif</td>
                </tr>
                <tr>
                    <td colspan="2"><b>DIRECCION:</b> @if(isset($info->titular->direccion)) {{$info->titular->direccion}} @endif</td>
                    <td><b>CORREO ELECTRONICO:</b> @if(isset($info->titular->emailsContacto)) {{str_replace(array("[","]","\""),"",$info->titular->emailsContacto)}} @endif</td>
                </tr>
            @endif
            <tr class="titulos">
                <td colspan="3" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>III. FABRICANTES</b></td>
            </tr>
            @if(count($info->fabricantes)>0)
                @foreach($info->fabricantes as $fab)
                    <tr>
                        <td colspan="3"><b>{{$loop->iteration}} - FABRICANTE</b></td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>NOMBRE:</b> {{$fab->nombreComercial}}</td>
                        <td><b>PAIS:</b> {{$fab->pais}}</td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>DIRECCION:</b> {{$fab->direccion}}</td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>CONTRATO DE MAQUILA:</b> - </td>
                    </tr>
                @endforeach
            @endif
            <tr class="titulos">
                <td colspan="3" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>IV. IMPORTADORES</b></td>
            </tr>
            @if(count($info->importadores)>0)
                @foreach($info->importadores as $imp)
                    <tr>
                        <td colspan="3"><b>{{$loop->iteration}} - IMPORTADOR</b></td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>NOMBRE:</b> {{$imp->nombreComercial}}</td>
                        <td><b>No INSCRIPCION IMPORTADOR:</b> {{$imp->idEstablecimiento}}</td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>DIRECCION:</b> {{$imp->direccion}}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">NO TIENE REGISTRADOS IMPORTADORES</td>
                </tr>
            @endif
            <tr class="titulos">
                <td colspan="3" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>V. DISTRIBUIDORES</b></td>
            </tr>

            @if(count($info->distribuidor)>0)
                @foreach($info->distribuidor as $dist)
                <tr>
                    <td colspan="3"><b>{{$loop->iteration}} - DISTRIBUIDOR</b></td>
                </tr>
                <tr>
                    <td colspan="2"><b>NOMBRE:</b> {{$dist->NOMBRE_COMERCIAL}}</td>
                    <td><b>PD:</b> {{$dist->ID_PODER}}</td>
                </tr>
                <tr>
                    <td colspan="3"><b>DIRECCION:</b> {{$dist->DIRECCION}}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">NO TIENE REGISTRADOS DISTRIBUIDORES</td>
                </tr>
            @endif

            <tr class="titulos">
                <td colspan="3" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>VI. REPRESENTANTE LEGAL</b></td>
            </tr>
            @if(isset($solicitud->solicitudesDetalle->idRepresentante))
                <tr>
                    <td colspan="2"><b>NOMBRE:</b> -</td>
                    <td><b>DIRECCION:</b> -</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table style="width: 100% !important; border-collapse: collapse; font-size: 12px;">
                            <tr>
                                <td style="width:  33% !important;"><b>PRL:</b> -</td>
                                <td style="width:  33% !important;"><b>CORREO ELECTRONICO:</b> -</td>
                                <td><b>TELEFONO:</b> -</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="3">NO TIENE REGISTRADO REPRESENTANTE LEGAL</td>
                </tr>
            @endif
            <tr class="titulos">
                <td colspan="3" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>VII. PROFESIONAL RESPONSABLE</b></td>
            </tr>
            @if(isset($info->profesional))
                <tr>
                    <td colspan="2"><b>NOMBRE:</b> {{$info->profesional->NOMBRES}} {{$info->profesional->APELLIDOS}}</td>
                    <td><b>J.V.P.Q.F:</b> {{$info->profesional->CORRELATIVO}}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>DUI:</b> {{$info->profesional->DUI}}</td>
                    <td><b>NIT:</b> {{$info->profesional->NIT}}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>TELEFONO:</b> {{str_replace(array("[","]","\""),"",$info->profesional->TELEFONO_1)}}</td>
                    <td><b>CORREO ELECTRONICO:</b> {{$info->profesional->EMAIL}}</td>
                </tr>
                <tr>
                    <td colspan="3"><b>DIRECCION:</b> {{$info->profesional->DIRECCION}}</td>
                </tr>
            @endif
            <tr>
                <td colspan="3">
                    <table border="0">
                        <tbody>
                            <tr>
                                <td style="width: 50%; padding-left: 1.8em;"><b>FIRMA:</b></td>
                                <td style="padding-left: 1.8em;"><b>SELLO:</b></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: bottom; padding-left: 1.8em;"><b>_____________________________</b></td>
                                <td><br><br></td>
                            </tr>
                            <tr>
                                <td colspan="2">Declaro que la documentación adjunta y los datos arriba indicados son verdaderos, quedando establecido en la ley en caso de que se compruebe la falsedad de los mismos, asumiendo la responsabilidad de dicho producto y su comercialización en el país.</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="titulos">
                <td colspan="3" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>VIII. CONTACTO PARA NOTIFICACIONES</b></td>
            </tr>
            <tr>
                <td colspan="2"><b>CORREO ELECTRONICO:</b> @if(isset($persona)) {{$persona->emailsContacto}} @endif</td>
                <td><b>TELEFONO:</b> @if(isset($persona->tels[0])) {{$persona->tels[0]}},@endif @if(isset($persona->tels[1])) {{$persona->tels[1]}} @endif</td>
            </tr>
            <tr class="titulos">
                <td colspan="3" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>IX. LISTA DE CHEQUEO- EN CUMPLIMIENTO AL ANEXO 2 Y 5 DE LA RESOLUCION No.231-2008 (COMIECO-L).</b></td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="width: 100% !important; border-collapse: collapse; font-size: 12px;">
                        <tr>
                            <td colspan="2">REGISTRO SANITARIO DE PRODUCTOS COSMETICOS</td>
                            <td colspan="2">RECONOCIMIENTO MUTUO DE PRODUCTOS COSMETICOS</td>
                        </tr>
                        <tr>
                            <td style="width: 5%;"></td>
                            <td style="width: 45%;">FORMULARIO (FIRMADO Y SELLADO POR PR).</td>
                            <td td style="width: 5%;"></td>
                            <td>FORMULARIO (FIRMADO Y SELLADO POR PR).</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>PODER A FAVOR DEL PROFESIONAL RESPONSABLE.</td>
                            <td></td>
                            <td>PODER A FAVOR DEL PROFESIONAL RESPONSABLE.</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>BPM O DOCUMENTO DE AUTORIZACION LEGALIZADO.</td>
                            <td rowspan="2"></td>
                            <td rowspan="2">CERTIFICADO DE REGISTRO SANITARIO EMITIDO POR LA AUTORIDAD SANITARIA DEL PRIMER PAÍS LEGALIZADO.</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>FORMULA CUALITATIVA (FIRMADA Y SELLADA).</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>ESPECIFICACIONES DE PRODUCTO TERMINADO.</td>
                            <td></td>
                            <td>FORMULA CUALITATIVA SELLADA</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>EMPAQUES O PROYECTO DE EMPAQUE LEGIBLE</td>
                            <td></td>
                            <td>ESPECIFICACIONES DE PRODUCTO TERMINADO.</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>NUMERO DE COMPROBANTE DE PAGO</td>
                            <td></td>
                            <td>NUMERO DE COMPROBANTE DE PAGO </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<footer id="footer">
    <hr>
    Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.<br>PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
</footer>

</body>

</html>