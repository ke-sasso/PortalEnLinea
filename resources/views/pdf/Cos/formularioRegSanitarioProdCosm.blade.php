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
                <td style="font-weight: bold;">@if($solicitud->tipoSolicitud==2) FORMULARIO DE REGISTRO SANITARIO DE PRODUCTOS COSMETICOS @else RECONOCIMIENTO MUTUO DEL REGISTRO SANITARIO DE PRODUCTOS COSMETICOS. @endif
                </td>
            </tr>
        </thead>
    </table>
    <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td colspan="12">&nbsp;</td>
            </tr>
            <tr class="titulos"> 
                <td colspan="12" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>I. GENERALIDADES</b></td>
            </tr>
            <tr>
                <td colspan="8"><b>NOMBRE COMERCIAL:</b> {{$solicitud->solicitudesDetalle->nombreComercial}}</td>
                <td colspan="4"><b>MARCA:</b> {{$info->marca}}</td>
            </tr>
            <tr>
                <td colspan="8"><b>FORMA COSMETICA:</b> {{$info->formacos}}</td>
                <td colspan="4"><b>No MANDAMIENTO DE PAGO:</b> {{$solicitud->idMandamiento}}</td>
            </tr>
            @if($solicitud->tipoSolicitud==3)
            <tr>
                <td colspan="8"><b>No REGISTRO DE PAIS DE ORIGEN:</b> {{$solicitud->solicitudesDetalle->numeroRegistroExtr}}</td>
                <td colspan="4"><b>VENCE EN EL PAIS DE ORIGEN:</b> {{date('d-m-Y',strtotime($solicitud->solicitudesDetalle->fechaVencimiento))}}</td>
            </tr>
            @endif
            <tr>
                <td colspan="12"><b>PRESENTACION EN SISTEMA INTERNACIONAL: </b>
                    @if(count($solicitud->presentaciones)>0)
                        @foreach($solicitud->presentaciones as $pre)
                            {{$loop->iteration}} - : {{$pre->textoPresentacion}}<br>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr class="titulos">
                <td colspan="12" style="padding-left: 1.8em; padding-bottom: 0.1em;">
                    <b>II. TITULAR</b>
                </td>
            </tr>
            <!--SECCION TITULAR-->
            @if(isset($info->titular))
                <tr>
                    <td colspan="12"><b>NOMBRE:</b> @if(isset($info->titular->nombre)) {{$info->titular->nombre}} @endif</td>
                </tr>
                <tr>
                    <td colspan="7"><b>PAIS:</b> @if(isset($info->titular->PAIS)) {{$info->titular->PAIS}} @endif</td>
                    <td colspan="5"><b>TELEFONO:</b> @if(isset($info->titular->telefonosContacto)) {{str_replace(array("[","]","\""),"",$info->titular->telefonosContacto)}} @endif</td>
                </tr>
                <tr>
                    <td colspan="7"><b>DIRECCION:</b> @if(isset($info->titular->direccion)) {{$info->titular->direccion}} @endif</td>
                    <td colspan="5"><b>CORREO ELECTRONICO:</b> {{$info->titular->emailsContacto}} </td>
                </tr>
            @else
                <tr>
                    <td colspan="12"><b>NOMBRE:</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>PAIS:</b></td>
                    <td colspan="5"><b>TELEFONO:</b></td>
                </tr>
                <tr>
                    <td colspan="7"><b>DIRECCION:</b></td>
                    <td colspan="5"><b>CORREO ELECTRONICO: </b></td>
                </tr>
            @endif

            <!--SECCION FABRICANTE-->
            <tr class="titulos">
                <td colspan="12" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>III. FABRICANTE/S</b></td>
            </tr>
            @if(count($info->fabricantes)>0)
                @foreach($info->fabricantes as $fab)
                    <tr>
                        <td colspan="12"><b>{{$loop->iteration}} - FABRICANTE</b></td>
                    </tr>
                    <tr>
                        <td colspan="6"><b>NOMBRE:</b> {{$fab->nombreComercial}}</td>
                        <td colspan="6"><b>PAIS:</b> {{$fab->pais}}</td>
                    </tr>
                    <tr>
                        <td colspan="6"><b>TELEFONO:</b> {{str_replace(array("[","]","\""),"",$fab->telefonosContacto)}}</td>
                        <td colspan="6"><b>DIRECCION:</b> {{$fab->direccion}}</td>
                    </tr>
                    <tr>
                        <td colspan="6"><b>CONTRATO DE MAQUILA:</b></td>
                        <td colspan="6"><b>CORREO ELECTRONICO:</b> {{$fab->emailContacto}}</td>
                    </tr>
                @endforeach
            @else
                    <tr>
                        <td colspan="6"><b>NOMBRE:</b></td>
                        <td colspan="6"><b>PAIS:</b></td>
                    </tr>
                    <tr>
                        <td colspan="6"><b>TELEFONO: </b></td>
                        <td colspan="6"><b>DIRECCION:</b></td>
                    </tr>
                    <tr>
                        <td colspan="6"><b>CONTRATO DE MAQUILA:</b></td>
                        <td colspan="6"><b>CORREO ELECTRONICO:</b></td>
                    </tr>
            @endif
            
            <!--SECCION IMPORTADORES-->
            <tr class="titulos">
                <td colspan="12" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>IV. IMPORTADORES</b></td>
            </tr>
            @if(count($info->importadores)>0)
                @foreach($info->importadores as $imp)
                    <tr>
                        <td colspan="12"><b>{{$loop->iteration}} - IMPORTADOR</b></td>
                    </tr>
                    <tr>
                        <td colspan="6"><b>NOMBRE:</b> {{$imp->nombreComercial}}</td>
                        <td colspan="6"><b>DIRECCION:</b> {{$imp->direccion}}</td>
                    </tr>
                    <tr>
                        <td colspan="4"><b>No INSCRIPCION IMPORTADOR:</b> {{$imp->idEstablecimiento}}</td>
                        <td colspan="4">><b>CORREO ELECTRONICO:</b> {{$imp->emailContacto}}</td>
                        <td colspan="4"><b>TELEFONO:</b> {{str_replace(array("[","]","\""),"",$imp->telefonosContacto)}}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6"><b>NOMBRE:</b></td>
                    <td colspan="6"><b>DIRECCION:</b></td>
                </tr>
                 <tr>
                     <td colspan="4"><b>No INSCRIPCION IMPORTADOR:</b></td>
                     <td colspan="4"><b>CORREO ELECTRONICO:</b></td>
                     <td colspan="4"><b>TELEFONO: </b></td>
                </tr>
            @endif

            <!--SECCION DISTRIBUIDORES-->
            <tr class="titulos">
                <td colspan="12" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>V. DISTRIBUIDORES</b></td>
            </tr>
            @if($solicitud->distribuidorTitular==1)
                <tr>
                    <td colspan="6"><b>NOMBRE:</b> @if(isset($info->titular->nombre)) {{$info->titular->nombre}} @endif </td>
                    <td colspan="6"><b>DIRECCION:</b> @if(isset($info->titular->direccion)) {{$info->titular->direccion}} @endif</td>
                </tr>
                <tr>
                    <td colspan="4"><b>PD:</b></td>
                    <td colspan="4"><b>CORREO ELECTRONICO:</b> {{$info->titular->emailsContacto}}</td>
                    <td colspan="4"><b>TELEFONO: </b> @if(isset($info->titular->telefonosContacto)) {{str_replace(array("[","]","\""),"",$info->titular->telefonosContacto)}} @endif</td>
                </tr>
            @else
                @if(count($info->distribuidor)>0)
                    @foreach($info->distribuidor as $dist)
                        <tr>
                            <td colspan="12"><b>{{$loop->iteration}} - DISTRIBUIDOR</b></td>
                        </tr>
                        <tr>
                            <td colspan="6"><b>NOMBRE:</b> {{$dist->NOMBRE_COMERCIAL}}</td>
                            <td colspan="6"><b>DIRECCION:</b> {{$dist->DIRECCION}}</td>
                        </tr>
                        <tr>
                            <td colspan="4"><b>PD:</b> {{$dist->ID_PODER}}</td>
                            <td colspan="4"><b>CORREO ELECTRONICO:</b> {{$dist->emailContacto}}</td>
                            <td colspan="4"><b>TELEFONO:</b> {{str_replace(array("[","]","\""),"",$dist->telefonosContacto)}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6"><b>NOMBRE:</b></td>
                        <td colspan="6"><b>DIRECCION:</b></td>
                    </tr>
                    <tr>
                        <td colspan="4"><b>PD:</b></td>
                        <td colspan="4"><b>CORREO ELECTRONICO:</b></td>
                        <td colspan="4"><b>TELEFONO: </b></td>
                    </tr>
                @endif
            @endif

            <!--SECCION REPRESENTANTE LEGAL-->
            <!-- */*/*/*/*/*/*  Esta seccion esta pendiente */*/*/*/*/*/*-->
            <tr class="titulos">
                <td colspan="12" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>VI. REPRESENTANTE LEGAL</b></td>
            </tr> 
            @if(isset($info->titular)&&isset($info->titular->tipoPersona))
                @if($info->titular->tipoPersona == 'J' && $info->titular->represetante!=null)

                    <tr>
                        <td colspan="6"><b>NOMBRE:</b> {{$info->titular->represetante->nombres.' '.$info->titular->represetante->apellidos}}</td>
                        <td colspan="6"><b>DIRECCION:</b> {{$info->titular->represetante->direccion}}</td>
                    </tr>
                    <tr>
                        <td colspan="4"><b>PRL:</b></td>
                        <td colspan="4"><b>CORREO ELECTRONICO:</b> {{$info->titular->represetante->emailsContacto}}</td>
                        <td colspan="4"><b>TELEFONO: </b>{{$info->titular->represetante->emailsContacto}}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="6"><b>NOMBRE:</b></td>
                        <td colspan="6"><b>DIRECCION:</b></td>
                    </tr>
                    <tr>
                        <td colspan="4"><b>PRL:</b></td>
                        <td colspan="4"><b>CORREO ELECTRONICO:</b></td>
                        <td colspan="4"><b>TELEFONO: </b></td>
                    </tr>
                @endif
            @endif

            <!--SECCION PROFESIONAL RESPONSABLE-->
            <tr class="titulos">
                <td colspan="12" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>VII. PROFESIONAL RESPONSABLE</b></td>
            </tr>
            @if(isset($info->profesional))
                <tr>
                    <td colspan="6"><b>NOMBRE:</b> {{$info->profesional->NOMBRES}} {{$info->profesional->APELLIDOS}}</td>
                    <td colspan="6"><b>PR:</b> {{$solicitud->solicitudesDetalle->idPoderProfesional}}</td>
                </tr>
                <tr>
                    <td colspan="6"><b>TELEFONO:</b> {{str_replace(array("[","]","\""),"",$info->profesional->TELEFONO_1)}}</td>
                    <td colspan="6"><b>CORREO ELECTRONICO:</b> {{$info->profesional->EMAIL}}</td>
                </tr>
                <tr>
                    <td colspan="12"><b>DIRECCION:</b> {{$info->profesional->DIRECCION}}</td>
                </tr>
            @endif

            <tr>
                <td colspan="12">
                    <table border="0">
                        <tbody>
                            <tr>
                                <td style="width: 50%; padding-left: 1.8em;"><b>FIRMA:</b></td>
                                <td style="width: 50%; padding-left: 1.8em;"><b>SELLO:</b></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: bottom; padding-left: 1.8em;"><b>_____________________________</b></td>
                                <td><br><br><br></td>
                            </tr>
                            <tr>
                                <td colspan="2">Declaro bajo juramento que la información adjunta cumple con los requisitos establecidos en los Reglamentos Técnicos Centroamericanos y es conforme al producto comercializado, asimismo, el uso racional del producto no perjudica la salud humana.</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr class="titulos">
                <td colspan="12" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>VIII. CONTACTO PARA NOTIFICACIONES</b></td>
            </tr>
            <tr>
                <td colspan="6"><b>CORREO ELECTRONICO:</b> @if(isset($persona)) {{$persona->emailsContacto}} @endif</td>
                <td colspan="6"><b>TELEFONO:</b> @if(isset($persona->tels[0])) {{$persona->tels[0]}},@endif @if(isset($persona->tels[1])) {{$persona->tels[1]}} @endif</td>
            </tr>
        </tbody>
    </table>

    <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
            <tr class="titulos">
                <td colspan="12" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>IX. LISTA DE CHEQUEO- EN CUMPLIMIENTO AL ANEXO 2 Y 5 DE LA RESOLUCION No.231-2008 (COMIECO-L).</b></td>
            </tr>
            @if($solicitud->tipoSolicitud==2)
                <tr>
                    <td colspan="12" style="text-align: center;"><b>REGISTRO SANITARIO DE PRODUCTOS COSMETICOS</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">FORMULARIO (FIRMADO Y SELLADO POR PR).</td>
                    <td></td>
                    <td colspan="5">ESPECIFICACIONES DE PRODUCTO TERMINADO.</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">PODER A FAVOR DEL PROFESIONAL RESPONSABLE.</td>
                    <td></td>
                    <td colspan="5">EMPAQUES O PROYECTO DE EMPAQUE LEGIBLE.</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">BPM O DOCUMENTO DE AUTORIZACION LEGALIZADO.</td>
                    <td></td>
                    <td colspan="5">NUMERO DE COMPROBANTE DE PAGO.</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">FORMULA CUALITATIVA (FIRMADA Y SELLADA).</td>
                    <td colspan="6"></td>
                </tr>
            @else
                <tr>
                     <td colspan="12" style="text-align: center;"><b>RECONOCIMIENTO MUTUO DE PRODUCTOS COSMETICOS</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">FORMULARIO (FIRMADO Y SELLADO POR PR).</td>
                    <td></td>
                    <td colspan="5">FORMULA CUALITATIVA (FIRMADA Y SELLADA).</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">PODER A FAVOR DEL PROFESIONAL RESPONSABLE.</td>
                    <td></td>
                    <td colspan="5">ESPECIFICACIONES DE PRODUCTO TERMINADO.</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="5">CERTIFICADO DE REGISTRO SANITARIO EMITIDO POR LA AUTORIDAD SANITARIA DEL PRIMER PAÍS LEGALIZADO.</td>
                    <td></td>
                    <td colspan="5">NUMERO DE COMPROBANTE DE PAGO</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<footer id="footer">
    <hr>
    Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.<br>PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
</footer>

</body>

</html>