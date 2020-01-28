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
                <td style="width:15%;" rowspan="4"><img id="logo" src="{{ url('img/dnm.png') }}"/></td>
                <td style="width:60%; font-weight: bold;">ASEGURAMIENTO SANITARIO</td>
                <td style="font-size: 12px; vertical-align: top;text-align: center;" rowspan="2">
                  <br>
                  Código:<br>
                  C02-RS-01-DRS_ME.HER01
                </td>
            </tr>
            <tr style="text-align: center;">
                <td style="font-weight: bold;">REGISTROS SANITARIOS Y TRAMITES ASOCIADOS</td>
            </tr>
            <tr style="text-align: center;">
                <td style="font-weight: bold;">AUTORIZACIONES</td>
                <td style="font-weight: bold;">Versión No. 06</td>

            </tr>
            <tr style="text-align: center;">
                <td style="font-weight: bold;">FORMULARIO NUEVO REGISTRO SANITARIO DE PRODUCTOS FARMACÉUTICOS
        </td>
        <td style="font-weight: bold;">Página 1 de 9</td>

            </tr>
        </thead>
    </table>
    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important; margin: 55px;">
    Señores<br>
    Dirección Nacional de Medicamentos.<br>
    Presente.<br>

      Yo, <b>{{Session::get('name')}} {{Session::get('lastname')}}</b>, actuando como @if(!empty($perfil)){{$perfil}} @else ______________________ @endif de @if($info->titular!=null) <b>{{$info->titular->nombre}},</b> @else _______________________, @endif solicito la inscripción de NUEVO REGISTRO SANITARIO, con las siguientes características:
    </div>
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tr class="titulos">
          <td style="width:60% !important;text-align:center!important;" colspan="8"><b>NOMBRE DEL PRODUCTO (como se comercializará en El Salvador)</b></td>
          <td style="width:20% !important;text-align:center!important;font-size:10px !important;" colspan="8"><b>N° DE SOLICITUD</b></td>
          <td style="width:20% !important;text-align:center!important;font-size:10px !important;" colspan="8"><b>N° DE COMPROBANTE DE PAGO </b> </td>
      </tr>
      <tr>
        <td style="width:60% !important;height: 45px !important;text-align:center!important;" colspan="8">{{$solicitud->solicitudesDetalle->nombreComercial}}</td>
        <td style="width:20% !important;text-align:center!important;font-size:10px !important;" colspan="8">{{$solicitud->numeroSolicitud}}</td>
        <td style="width:20% !important;text-align:center!important;font-size:10px !important;" colspan="8">{{$solicitud->mandamiento}}</td>

      </tr>
    </table>

    <table class="formulario" cellpadding="0" cellspacing="0">
      <tr class="titulos">
          <td style="width:100% !important;text-align:center!important;" colspan="24"><b>INNOVADOR &nbsp;&nbsp;&nbsp; SI <div style="position:relative;height:25px;width:25px;border: 1px solid black;display:inline;font-size:9px;margin:0px;padding:0px;background:white !important;text-align:center;">{{$solicitud->solicitudesDetalle->innovador==1?'X':'&nbsp;&nbsp;&nbsp;'}}</div> &nbsp; &nbsp; &nbsp; NO&nbsp; &nbsp; &nbsp;<div style="position:relative;height:25px;width:25px;border: 1px solid black;display:inline;font-size:9px;margin:0px;padding:0px;background:white !important;text-align:center;">{{$solicitud->solicitudesDetalle->innovador==0?'X':'&nbsp;&nbsp;&nbsp;'}}</div></b></td>
      </tr>
      <tr class="titulos">
          <td style="width:60% !important;text-align:center!important;" colspan="12"><b>ORIGEN DEL  PRODUCTO</b></td>
          <td style="width:40% !important;text-align:center!important;font-size:10px !important;" colspan="12"><b>TIPO DE MEDICAMENTO</b></td>
      </tr>
      <tr>
        <td style="width:20% !important;height: 45px !important;text-align:center!important;" colspan="12">
          <table style="width:100%;">
              <tr>
                <td style="border:none;width:94%;text-align:left !important;">NACIONAL</td>
                <td>
                  {{$solicitud->solicitudesDetalle->origenProducto==1?'X':''}}
                </td>
              </tr>
              <tr>
                <td style="border:none;width:94%;text-align:left !important;">EXTRANJERO</td>
                <td>
                  {{$solicitud->solicitudesDetalle->origenProducto==2?'X':''}}
                </td>
              </tr>
              <tr>
                <td style="border:none;width:94%;text-align:left !important;">RECONOCIMIENTO EXTRANJERO</td>
                <td>
                  {{$solicitud->solicitudesDetalle->origenProducto==3?'X':''}}

                </td>
              </tr>
              <tr>
                <td style="border:none;width:94%;text-align:left !important;">RECONOCIMIENTO MUTUO CENTROAMERICANO</td>
                <td>
                  {{$solicitud->solicitudesDetalle->origenProducto==4?'X':''}}

                </td>
              </tr>
          </table>
        </td>
        <td style="width:80% !important;text-align:center!important;font-size:10px !important;" colspan="12">
          <table style="width:100%;">
            <tr>
                <td style="border:none;width:42%;text-align:left !important;">
                  &nbsp;&nbsp;&nbsp;BIOLOGICO
                </td>
                <td style="width:8%;">
                  {{$solicitud->solicitudesDetalle->tipoMedicamento==2?'X':''}}
                </td>
                  <td style="border:none;width:42%;text-align:left !important;">
                  &nbsp;&nbsp;&nbsp;PROBIOTICO
                </td>
                <td style="width:8%;">
                  {{$solicitud->solicitudesDetalle->tipoMedicamento==13?'X':''}}
                </td>
            </tr>
            <tr>
                <td style="border:none;width:42%;text-align:left !important;">
                  &nbsp;&nbsp;&nbsp;BIOTECNOLOGICO
                </td>
                <td style="width:8%;">
                  {{$solicitud->solicitudesDetalle->tipoMedicamento==3?'X':''}}
                </td>
                  <td style="border:none;width:42%;text-align:left !important;">
                  &nbsp;&nbsp;&nbsp;RADIOFARMACOS
                </td>
                <td style="width:8%;">
                  {{$solicitud->solicitudesDetalle->tipoMedicamento==11?'X':''}}
                </td>
            </tr>
            <tr>
                <td style="border:none;width:42%;text-align:left !important;">
                  &nbsp;&nbsp;&nbsp;GASES MEDICINALES
                </td>
                <td style="width:8%;">
                  {{$solicitud->solicitudesDetalle->tipoMedicamento==12?'X':''}}
                </td>
                <td style="border:none;width:42%;text-align:left !important;">
                  &nbsp;&nbsp;&nbsp;SINTESIS QUIMICA
                </td>
                <td style="width:8%;">
                  {{$solicitud->solicitudesDetalle->tipoMedicamento==1?'X':''}}
                </td>
            </tr>
            <tr>
                <td style="border:none;width:42%;text-align:left !important;">
                  &nbsp;&nbsp;&nbsp;GENERICO
                </td>
                <td style="width:8%;">
                  {{$solicitud->solicitudesDetalle->tipoMedicamento==9?'X':''}}
                </td>
                <td style="border:none;width:42%;text-align:left !important;">
                  &nbsp;&nbsp;&nbsp;SUPLEMENTOS NUTRICIONALES
                </td>
                <td style="width:8%;">
                  {{$solicitud->solicitudesDetalle->tipoMedicamento==6?'X':''}}
                </td>
            </tr>
            <tr>
                 <td style="border:none;width:42%;text-align:left !important;">
                  &nbsp;&nbsp;&nbsp;HOMEOPATICO
                </td>
                <td style="width:8%;">
                  {{$solicitud->solicitudesDetalle->tipoMedicamento==8?'X':''}}
                </td>
                 <td style="border:none;width:42%;text-align:left !important;">
                  &nbsp;&nbsp;&nbsp;NATURALES
                </td>
                <td style="width:8%;">
                  {{$solicitud->solicitudesDetalle->tipoMedicamento==7?'X':''}}
                </td>
            </tr>
            <tr>
                 <td style="border:none;width:42%;text-align:left !important;">
                   &nbsp;&nbsp;&nbsp;MULTIORIGEN
                 </td>
                <td style="width:8%;">
                   {{$solicitud->solicitudesDetalle->tipoMedicamento==10?'X':''}}
                 </td>
                  <td style="border:none;width:42%;text-align:left !important;">
                   &nbsp;&nbsp;&nbsp;VACUNA
                 </td>
                <td style="width:8%;">
                   {{$solicitud->solicitudesDetalle->tipoMedicamento==4?'X':''}}
                 </td>
            </tr>


          </table>
        </td>
      </tr>
    </table>
    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><b>I. INFORMACIÓN DE LOS SOLICITANTES</b></div>
    @if ($info->titular!=null)
      <!-- INFORMACION DEL PROPIETARIO-->
      <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="titulos">
              <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>1. NOMBRE, RAZON O DENOMINACION SOCIAL DEL TITULAR/PROPIETARIO DEL PRODUCTO</b></td>
          </tr>
          <tr>
            <td colspan="24" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$info->titular->nombre}}</td>
          </tr>
        </tbody>
      </table>
      <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
           <tr class="titulos">
              <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>DOMICILIO PRINCIPAL (Calle/Avenida/Número/Colonia /Municipio/Departamento/País)</b></td>
          </tr>
          <tr>
            <td colspan="24" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$info->titular->direccion}}</td>
          </tr>
          <tr class="titulos">
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>TELEFONO</b></td>
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FAX</b></td>
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>CORREO ELECTRONICO</b></td>
          </tr>
          <tr>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
             @php
                if ($info->titular->telefonosContacto!='' && $info->titular->telefonosContacto!=null) {
                  $tels = json_decode($info->titular->telefonosContacto);
                  $textTels = $tels[0];
                  if ($tels[1]!="") {
                    $textTels .= '-'.$tels[0];

                  }
                  echo $textTels;
                }
              @endphp
            </td>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$info->titular->fax}}</td>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
               {{$info->titular->emailsContacto}}
            </td>
          </tr>
         <!-- <tr class="titulos">
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>2. DUI  Nº (Si es a titulo como persona natural)</b></td>
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>3. CARNET DE RESIDENTE Nº (Si es a titulo como persona natural y es extranjero)</b></td>
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>4. NIT</b></td>
          </tr>
          <tr>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
            @if($info->titular->tipoTitular==1)
            @if(!empty($info->titular->numeroDocumento)){{ $info->titular->numeroDocumento }}@endif
            @endif
            </td>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"> @if($info->titular->PAIS!='EL SALVADOR') @endif</td>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
            @if(!empty($info->titular->nit)){{$info->titular->nit}}@endif</td>
          </tr>-->
        </tbody>
      </table>
    @else
      DEBE INGRESAR LA INFORMACIÓN DEL TITULAR
    @endif
    <!-- FIN DEL PROPIETARIO-->

    @if ($info->representante!=null || $info->apoderado!=null)
      <!-- INFORMACION DEL APODERADO / REPRESENTANTE LEGAL-->
        @if(!empty($info->apoderado))
          <table class="formulario" cellpadding="0" cellspacing="0">
            <tbody>
              <tr class="titulos">
                  <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>2. NOMBRE DEL REPRESENTANTE LEGAL/APODERADO</b>  <div style="width:100% !important;font-size: 10px;text-align:right !important;margin:0px !important;padding:0px !important;">[ ] REPRESENTANTE LEGAL     [X] APODERADO</div></td>
              </tr>
              <tr>
                <td colspan="24" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                  {{$info->apoderado[0]->NOMBRE_APODERADO}}
                </td>
              </tr>
            </tbody>
          </table>
          <table class="formulario" cellpadding="0" cellspacing="0">
            <tbody>
              <tr class="titulos">
                  <td colspan="12" style="width: 20.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>No DE  PODER  AP o RL   INSCRITO DNM</b></td>
                  <td colspan="12" style="width: 43.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>DOMICILIO PRINCIPAL (Calle/Avenida/Número/Colonia /Municipio/Departamento)</b></td>
               </tr>
              <tr>
                <td colspan="12" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$solicitud->apoderado->poderApoderado}}</td>
                <td colspan="12" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$info->apoderado[0]->DIRECCION}}</td>
               </tr>
            </tbody>
          </table>
          <table class="formulario" cellpadding="0" cellspacing="0">
            <tbody>
              <tr class="titulos">
                  <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>TELEFONO</b></td>
                  <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FAX</b></td>
                  <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>CORREO ELECTRONICO</b></td>
              </tr>
              <tr>
                <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                 {{$info->apoderado[0]->TELEFONO_1}}
                </td>
                <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$info->apoderado[0]->FAX}}</td>
                <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                  {{$info->apoderado[0]->EMAIL}}
                </td>
              </tr>
            </tbody>
          </table>
        @elseif(!empty($info->representante))
          <table class="formulario" cellpadding="0" cellspacing="0">
            <tbody>
              <tr class="titulos">
                  <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>2. NOMBRE DEL REPRESENTANTE LEGAL/APODERADO</b> <div style="width:100% !important;font-size: 10px;text-align:right !important;margin:0px !important;padding:0px !important;">[X] REPRESENTANTE LEGAL     [] APODERADO</div></td>
              </tr>
              <tr>
                <td colspan="24" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                  {{$info->representante->NOMBRES}} {{$info->representante->APELLIDOS}}
                </td>
              </tr>
            </tbody>
          </table>
          <table class="formulario" cellpadding="0" cellspacing="0">
            <tbody>
              <tr class="titulos">
                  <td colspan="12" style="width: 20.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>No DE  PODER  AP o RL   INSCRITO DNM</b></td>
                  <td colspan="12" style="width: 43.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>DOMICILIO PRINCIPAL (Calle/Avenida/Número/Colonia /Municipio/Departamento)</b></td>
               </tr>
              <tr>
                <td colspan="12" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$solicitud->representante->poderRepresentante}}</td>
                <td colspan="12" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$info->representante->DIRECCION}}</td>
               </tr>
            </tbody>
          </table>
          <table class="formulario" cellpadding="0" cellspacing="0">
            <tbody>
              <tr class="titulos">
                  <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>TELEFONO</b></td>
                  <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FAX</b></td>
                  <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>CORREO ELECTRONICO</b></td>
              </tr>
              <tr>
                <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                  {{$info->representante->TELEFONO_1}}
                </td>
                <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"> {{$info->representante->FAX}}</td>
                <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                  {{$info->representante->EMAIL}}
                </td>
              </tr>
            </tbody>
          </table>
        @endif
    @else
                <table class="formulario" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr class="titulos">
                        <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>2. NOMBRE DEL REPRESENTANTE LEGAL/APODERADO</b><div style="width:100% !important;font-size: 10px;text-align:right !important;margin:0px !important;padding:0px !important;">[ ] REPRESENTANTE LEGAL     [ ] APODERADO</div></td>
                    </tr>
                    <tr>
                      <td colspan="24" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="formulario" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr class="titulos">
                        <td colspan="12" style="width: 20.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>No DE  PODER  AP o RL   INSCRITO DNM</b></td>
                        <td colspan="12" style="width: 43.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>DOMICILIO PRINCIPAL (Calle/Avenida/Número/Colonia /Municipio/Departamento)</b></td>
                     </tr>
                    <tr>
                      <td colspan="12" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"> </td>
                      <td colspan="12" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"> </td>
                     </tr>
                  </tbody>
                </table>
                <table class="formulario" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr class="titulos">
                        <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>TELEFONO</b></td>
                        <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FAX</b></td>
                        <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>CORREO ELECTRONICO</b></td>
                    </tr>
                    <tr>
                      <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"></td>
                      <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"></td>
                      <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"></td>
                    </tr>
                  </tbody>
                </table>
    @endif
    <!-- FIN DEL REPRESENTANTE LEGAL / APODERADO-->
    <!--informacion de PROFESIONAL RESPONSABLE -->
    @if (!empty($info->profesional))
      <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="titulos">
              <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>3. NOMBRE DEL PROFESIONAL RESPONSABLE QUIMICO FARMACEUTICO</b></td>
          </tr>
          <tr>
            <td colspan="24" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$info->profesional->NOMBRES.' '.$info->profesional->APELLIDOS}}</td>
          </tr>
        </tbody>
      </table>
      <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="titulos">
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>No. INSCRIPCION  J.V.P.Q.F. </b></td>
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>No. DE PODER PR INSCRITO DNM</b></td>
          </tr>
          <tr>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$info->profesional->CORRELATIVO}}</td>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$solicitud->profesional->poderProfesional}}</td>
          </tr>
        </tbody>
      </table>
      <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="titulos">
              <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>DOMICILIO PRINCIPAL (Calle/Avenida/Número/Colonia /Municipio/Departamento)</b></td>
          </tr>
          <tr>
            <td colspan="24" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$info->profesional->DIRECCION}}</td>
          </tr>
        </tbody>
      </table>
      <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="titulos">
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>TELEFONO</b></td>
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FAX</b></td>
              <td colspan="8" style="width: 33.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>CORREO ELECTRONICO</b></td>
          </tr>
          <tr>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
               {{$info->profesional->TELEFONO_1}}
            </td>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"></td>
            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                   {{$info->profesional->EMAIL}}

            </td>
          </tr>
        </tbody>
      </table>
    @else
      DEBE INGRESAR LA INFORMACIÓN DEL PROFESIONAL
    @endif
    <!-- FIN de información de profesional RESPONSABLE -->
     <br>
     <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="titulos">
              <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>4. SEÑALO PARA OIR NOTIFICACIONES <div style="width:100% !important;font-size: 10px;text-align:right !important;margin:0px !important;padding:0px !important;">VIA CORREO ELECTRONICO  [ @if($solicitud->solicitudesDetalle->idOirNotificaciones==1) X @endif ]<br>INSTALACIONES DE LA DNM [ @if($solicitud->solicitudesDetalle->idOirNotificaciones==2) X @endif ]</div>
          </b>
           @if($solicitud->solicitudesDetalle->idOirNotificaciones==1) <br>Correo: {{$persona->emailsContacto}} @endif
        </td>
          </tr>
        </tbody>
      </table>
    <br>
     <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="titulos">
              <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>5. TERCEROS INTERESADOS  <div style="width:100% !important;font-size: 10px;text-align:right !important;margin:0px !important;padding:0px !important;">@if($solicitud->solicitudesDetalle->tercerInteresado==0) [ X  ]  @else  [   ] @endif NO ES DE MI CONOCIMIENTO</div>
                <br>Nota: Se entenderá como terceros interesados las personas que sin haber iniciado el procedimiento, tengan derechos que puedan resultar afectados por la decisión que en el mismo se adopte.
          </b></td>
          </tr>
        </tbody>
      </table>
      <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
              <td colspan="8" style="width: 20.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>NOMBRE (PERSONA NATURAL O JURIDICA  Y  NOMBRE DE APODERADO O REPRESENTANTE LEGAL (CUANDO APLIQUE)</b></td>
              <td colspan="8" style="width: 43.33%;padding-left: 1.8em; padding-bottom: 0.1em;">{{$solicitud->tercero!=null?$solicitud->tercero->nombres:''}}</td>
          </tr>
          <tr class="titulos">
            <td colspan="16" style="width: 66.66%;padding-left: 1.8em; padding-bottom: 0.1em;"><center>MEDIO PARA NOTIFICAR</center></td>
          </tr>
            <tr>
              <td colspan="8" style="width: 20.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>CORREO ELECTRÓNICO</b></td>
              <td colspan="8" style="width: 43.33%;padding-left: 1.8em; padding-bottom: 0.1em;">{{$solicitud->tercero!=null?$solicitud->tercero->email:''}}</td>
          </tr>
            <tr>
              <td colspan="8" style="width: 20.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>DOMICILIO</b></td>
              <td colspan="8" style="width: 43.33%;padding-left: 1.8em; padding-bottom: 0.1em;">{{$solicitud->tercero!=null?$solicitud->tercero->direccion:''}}</td>
          </tr>
          <tr>
              <td colspan="8" style="width: 20.33%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>TELÉFONO (S)</b></td>
              <td colspan="8" style="width: 43.33%;padding-left: 1.8em; padding-bottom: 0.1em;">{{$solicitud->tercero!=null?$solicitud->tercero->telefono1:''}} <br>{{$solicitud->tercero!=null?$solicitud->tercero->telefono2:''}} </td>
          </tr>
        </tbody>
      </table>
      <!-- FIN DE TERCERAS PERSONAS-->
    <br>
    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><b>II. INFORMACIÓN GENERAL DEL PRODUCTO</b></div><br>
    <br>
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>6. NOMBRE DEL PRODUCTO (como se comercializará en El Salvador) Y PAIS DE PROCEDENCIA DEL PRODUCTO</b></td>
        </tr>
        <tr>
          <td colspan="24" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$solicitud->solicitudesDetalle->nombreComercial}}</td>
        </tr>
      </tbody>
    </table>
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="24" style="padding-left: 1.8em; padding-bottom: 0.1em;"><b>7. NOMBRE Y CONCENTRACION DE(LOS) PRINCIPIO(S) ACTIVO(S) POR UNIDAD DE DOSIS</b>
             <div style="width:100% !important;font-size: 10px;text-align:right !important;margin:0px !important;padding:0px !important;">POSEE PANTENTES
             SI  [{{$solicitud->solicitudesDetalle->poseePatentes==1?'X':''}}]     NO  [{{$solicitud->solicitudesDetalle->poseePatentes==0?'X':''}}] </div>
            </td>
        </tr>
        <tr>
          <td colspan="24" style="">
            @if ($solicitud->principiosActivos!=null)
              @foreach ($solicitud->principiosActivos as $principio)
                {{$principio->nombreMateriaPrima.' X '.$principio->concentracion.' '.$principio->nombreUnidadMedida}}
              @endforeach
            @endif
          </td>
        </tr>
      </tbody>
    </table>
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="12" style="width: 50%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>8. FORMA FARMACEUTICA</b></td>
            <td colspan="12" style="width: 50%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>9. VIA DE ADMINISTRACION</b></td>
         </tr>
        <tr>
          <td colspan="12" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
            {{$info->formaFarma!=null?$info->formaFarma->NOMBRE_FORMA_FARMACEUTICA:''}}
          </td>
          <td colspan="12" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
            {{$info->viaAdmon!=null?$info->viaAdmon->NOMBRE_VIA_ADMINISTRACION:''}}

          </td>
        </tr>
      </tbody>
    </table>
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="12" style="width: 50%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>10. CONDICIONES DE ALMACENAMIENTO</b></td>
         </tr>
        <tr>
          <td colspan="12" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
            {{$solicitud->solicitudesDetalle->condicionesAlmacenaje}}
          </td>
        </tr>
      </tbody>
    </table>
     <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="12" style="width: 50%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>11. VIDA UTIL PROPUESTA</b></td>
         </tr>
          <tr class="titulos">
                   <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">EMPAQUE PRIMARIO</td>
                   <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">MATERIAL/COLOR</td>
                   <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">VIDA UTIL </td>
                   <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">OBSERVACIÓN</td>
            </tr>
            @if($solicitud->vidaUtil!=null)
                 @foreach($solicitud->vidaUtil as $vida)
                    <tr>
                       <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$vida->nombrePrimario}}</td>
                       <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"> {{$vida->nombreMaterial}} <br> {{$vida->nombreColor}}</td>
                       <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$vida->vidaUtil}} @if($vida->idPeriodo==1) (MESES) @elseif($vida->idPeriodo==2)  (DÍAS)  @endif</td>
                       <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{{$vida->observacion}}</td>
                     </tr>
                  @endforeach
             @else
                <tr>
                   <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"></td>
                   <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"></td>
                   <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"></td>
                   <td colspan="3" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"></td>
                </tr>
            @endif
      </tbody>
    </table>
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="12" style="width: 50%;padding-left: 1.8em; padding-bottom: 0.1em;"><b>12.PRESENTACIONES   </b></td>
         </tr>
        <tr>
          <td colspan="12" style="">
            @if ($solicitud->empaquesPresentacion!=null)
                @foreach ($solicitud->empaquesPresentacion as $presentacion)
                    <b>{{$loop->iteration}})</b> {{$presentacion->textoPresentacion}} @if($presentacion->tipoPresentacion==2) (PRESENTACIÓN COMERCIAL) @elseif($presentacion->tipoPresentacion==1) (PRESENTACION DE MUESTRA MEDICA) @elseif($presentacion->tipoPresentacion==3)  (PRESENTACIÓN HOSPITALARIA) @elseif($presentacion->tipoPresentacion==4) PRESENTACION INSTITUCIONAL @endif
                    <br>
                @endforeach
            @endif
          </td>
        </tr>
      </tbody>
    </table>
      <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="32" style="width: 100% !important;padding-left: 1.8em; padding-bottom: 0.1em;text-align:center;"><b>13. INFORMACION DEL LABORATORIO FABRICANTE PRINCIPAL </b></td>
        </tr>
        <tr class="titulos">
            <td colspan="8" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>NOMBRE </b></td>
            <td colspan="8" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>DIRECCIÓN</b></td>
            <td colspan="8" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>PAÍS</b></td>
            <td colspan="8" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>No. CONTRATO DE FABRICACION A TERCEROS (MAQUILA)</b></td>
        </tr>
            <tr>
              <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                @if(!empty($info->fabricantePrincipalInfo))
                  {{$info->fabricantePrincipalInfo->nombreComercial}}
                @endif
              </td>
              <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                @if(!empty($info->fabricantePrincipalInfo))
                  {{$info->fabricantePrincipalInfo->direccion}}
                @endif
              </td>
              <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                @if(!empty($info->fabricantePrincipalInfo))
                  {{$info->fabricantePrincipalInfo->pais}}
                @endif
              </td>
              <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                @if(!empty($info->fabricantePrincipalInfo))
                  {{$solicitud->fabricantePrincipal->noPoderMaquila}}
                @endif
              </td>
            </tr>

      </tbody>
    </table>
    <!-- Fabricantes alternos-->
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="32" style="width: 100% !important;padding-left: 1.8em; padding-bottom: 0.1em;text-align:center;"><b>14. INFORMACION DEL LABORATORIO FABRICANTE ALTERNO (Cuando aplique)</b></td>
        </tr>
        <tr class="titulos">
            <td colspan="8" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>NOMBRE </b></td>
            <td colspan="8" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>DIRECCIÓN</b></td>
            <td colspan="8" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>PAÍS</b></td>
            <td colspan="8" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>No. CONTRATO DE FABRICACION A TERCEROS (MAQUILA)</b></td>
        </tr>
         @if($info->fabricantesAlternosInfo!=null)
              @foreach ($info->fabricantesAlternosInfo as $alterno)
                     @foreach($solicitud->fabricantesAlternos as $labalt)
                          @if($alterno->idEstablecimiento==$labalt->idFabAlterno)
                              <tr>
                                <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                                  {{$alterno->nombreComercial}}
                                </td>
                                <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                                  {{$alterno->direccion}}

                                </td>
                                <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                                   {{$alterno->pais}}
                                </td>
                                <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                                  {{$labalt->noPoderMaquila}}
                                </td>
                              </tr>
                          @endif
                     @endforeach
                @endforeach
          @else
             <tr>
              <td colspan="8" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="8" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="8" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="8" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
             </tr>
          @endif
      </tbody>
    </table>
    <!-- Fin info alternos-->

    <!-- acondicionador-->
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="32" style="width: 100% !important;padding-left: 1.8em; padding-bottom: 0.1em;text-align:center;"><b>15. INFORMACION DEL LABORATORIO ACONDICIONADOR (Cuando aplique)</b></td>
        </tr>
        <tr class="titulos">
            <td colspan="8" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>NOMBRE </b></td>
            <td colspan="8" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>DIRECCIÓN</b></td>
            <td colspan="8" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>PAÍS</b></td>
            <td colspan="8" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>No. CONTRATO DE FABRICACION A TERCEROS (MAQUILA)</b></td>
        </tr>
         @if($info->fabricantesAcondicionadoresInfo!=null)
             @foreach($info->fabricantesAcondicionadoresInfo as $acondicionador)
                 @foreach($solicitud->laboratorioAcondicionador as $labacon)
                      @if($acondicionador->idEstablecimiento==$labacon->idLabAcondicionador)
                          <tr>
                            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$acondicionador->nombreComercial}}
                                   @if($labacon->tipo==1)
                                                   (PRIMARIO)
                                   @elseif($labacon->tipo==2)
                                                   (SECUNDARIO)
                                   @endif
                            </td>
                            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$acondicionador->direccion}}

                            </td>
                            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                               {{$acondicionador->pais}}
                            </td>
                            <td colspan="8" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$labacon->noPoderMaquila}}
                            </td>
                          </tr>
                      @endif
                 @endforeach
              @endforeach
          @else
             <tr>
              <td colspan="8" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="8" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="8" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="8" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
             </tr>
          @endif
      </tbody>
    </table>
    <!-- FIN acondicionador-->
    <!-- RELACIONADO-->
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="30" style="width: 100% !important;padding-left: 1.8em; padding-bottom: 0.1em;text-align:center;"><b>16. INFORMACION DEL LABORATORIO RELACIONADO (Cuando aplique)</b></td>
        </tr>
        <tr class="titulos">
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>NOMBRE </b></td>
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>DIRECCIÓN</b></td>
            <td colspan="10" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>PAÍS</b></td>
        </tr>
          @if($info->fabricantesRelacional!=null)
             @foreach($info->fabricantesRelacional as $relacionado)
                          <tr>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$relacionado->nombreComercial}}
                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$relacionado->direccion}}

                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                               {{$relacionado->pais}}
                            </td>
                          </tr>
              @endforeach
          @else
             <tr>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
             </tr>
          @endif
      </tbody>
    </table>
    <!-- FIN RELACIONADO-->
    <!-- FAB PRINCIPIO ACTIVO-->
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="30" style="width: 100% !important;padding-left: 1.8em; padding-bottom: 0.1em;text-align:center;"><b>17. INFORMACION DEL LABORATORIO FABRICANTE DE PRINCIPIO ACTIVO  (Cuando aplique)</b></td>
        </tr>
        <tr class="titulos">
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>NOMBRE </b></td>
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>DIRECCIÓN</b></td>
            <td colspan="10" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>PAÍS</b></td>
        </tr>
        @if($info->fabricantePrincipioActivo!=null)
              @foreach($info->fabricantePrincipioActivo as $fabprinacti)
                          <tr>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$fabprinacti->nombreComercial}}
                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$fabprinacti->direccion}}

                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                               {{$fabprinacti->pais}}
                            </td>
                          </tr>
              @endforeach
          @else
             <tr>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
             </tr>
          @endif
      </tbody>
    </table>
    <!-- FIN FAB PRINCIPIO ACTIVO-->

    <br>
    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><b>III. INFORMACIÓN GENERAL DEL CERTIFICADO DE BUENAS PRÁCTICAS DE MANUFACTURA (Cuando aplique)</b></div><br>
    <br>
    <!--bpm-->
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>18. PAIS DE LA AUTORIDAD EMISORA DEL CERTIFICADO DE BUENAS PRACTICAS DE MANUFACTURA DEL LABORATORIO FABRICANTE PRINCIPAL </b></td>
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FECHA DE EMISIÓN</b></td>
            <td colspan="10" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FECHA DE VENCIMIENTO</b></td>
        </tr>
         <tr>
               <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                          {{$solicitud->bpmPrincipal!=null?$solicitud->bpmPrincipal->nombreEmisor:''}}
               </td>
                <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                           @if($solicitud->bpmPrincipal!=null)
                                @if($solicitud->bpmPrincipal->fechaEmision!='1900-01-01 00:00:00' && $solicitud->bpmPrincipal->fechaEmision!='')
                                  {{date('d-m-Y',strtotime($solicitud->bpmPrincipal->fechaEmision))}}
                                @endif
                           @endif
                </td>
                <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                            @if($solicitud->bpmPrincipal!=null)
                                @if($solicitud->bpmPrincipal->fechaVencimiento!='1900-01-01 00:00:00' && $solicitud->bpmPrincipal->fechaVencimiento!='')
                                  {{date('d-m-Y',strtotime($solicitud->bpmPrincipal->fechaVencimiento))}}
                                @endif
                           @endif
                </td>
           </tr>
      </tbody>
    </table>

    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>19. PAIS DE LA AUTORIDAD EMISORA DEL CERTIFICADO DE BUENAS PRACTICAS DE MANUFACTURA DEL FABRICANTE ALTERNO (Cuando aplique) </b></td>
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FECHA DE EMISIÓN</b></td>
            <td colspan="10" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FECHA DE VENCIMIENTO</b></td>
        </tr>
        @if(count($solicitud->bpmAlternos)>0)
            @foreach($solicitud->bpmAlternos as $bpmalterno)
                          <tr>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$bpmalterno->nombreEmisor}}
                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              @if($bpmalterno->fechaEmision!='1900-01-01 00:00:00' && $bpmalterno->fechaEmision!='')
                                      {{date('d-m-Y',strtotime($bpmalterno->fechaEmision))}}
                               @endif
                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                               @if($bpmalterno->fechaVencimiento!='1900-01-01 00:00:00' && $bpmalterno->fechaVencimiento!='')
                                      {{date('d-m-Y',strtotime($bpmalterno->fechaVencimiento))}}
                               @endif
                            </td>
                          </tr>
              @endforeach
          @else
             <tr>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
             </tr>
          @endif
      </tbody>
    </table>


     <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>20. PAIS  DE LA AUTORIDAD EMISORA DEL CERTIFICADO DE BUENAS PRACTICAS DE MANUFACTURA DEL LABORATORIO ACONDICIONADOR DEL PRODUCTO (Cuando aplique) </b></td>
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FECHA DE EMISIÓN</b></td>
            <td colspan="10" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FECHA DE VENCIMIENTO</b></td>
        </tr>
        @if(count($solicitud->bpmAcondicionadores)>0)
            @foreach($solicitud->bpmAcondicionadores as $bmpacon)
                          <tr>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$bmpacon->nombreEmisor}}
                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              @if($bmpacon->fechaEmision!='1900-01-01 00:00:00' && $bmpacon->fechaEmision!='')
                                      {{date('d-m-Y',strtotime($bmpacon->fechaEmision))}}
                               @endif
                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                               @if($bmpacon->fechaVencimiento!='1900-01-01 00:00:00' && $bmpacon->fechaVencimiento!='')
                                      {{date('d-m-Y',strtotime($bmpacon->fechaVencimiento))}}
                               @endif
                            </td>
                          </tr>
              @endforeach
          @else
             <tr>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
             </tr>
          @endif
      </tbody>
    </table>

      <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>21. PAIS DE LA AUTORIDAD EMISORA DEL CERTIFICADO DE BUENAS PRACTICAS DE MANUFACTURA DEL FABRICANTE RELACIONADO (Cuando aplique) </b></td>
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FECHA DE EMISIÓN</b></td>
            <td colspan="10" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FECHA DE VENCIMIENTO</b></td>
        </tr>
        @if(count($solicitud->bpmRelacionados)>0)
            @foreach($solicitud->bpmRelacionados as $bpmrelaci)
                          <tr>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$bpmrelaci->nombreEmisor}}
                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              @if($bpmrelaci->fechaEmision!='1900-01-01 00:00:00' && $bpmrelaci->fechaEmision!='')
                                      {{date('d-m-Y',strtotime($bpmrelaci->fechaEmision))}}
                               @endif
                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                               @if($bpmrelaci->fechaVencimiento!='1900-01-01 00:00:00' && $bpmrelaci->fechaVencimiento!='')
                                      {{date('d-m-Y',strtotime($bpmrelaci->fechaVencimiento))}}
                               @endif
                            </td>
                          </tr>
              @endforeach
          @else
             <tr>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
             </tr>
          @endif
      </tbody>
    </table>

          <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>22. PAIS DE LA AUTORIDAD EMISORA DEL CERTIFICADO DE BUENAS PRACTICAS DE MANUFACTURA DEL FABRICANTE  DE PRINCIPIO ACTIVO  (Cuando aplique) </b></td>
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FECHA DE EMISIÓN</b></td>
            <td colspan="10" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>FECHA DE VENCIMIENTO</b></td>
        </tr>
        @if(count($solicitud->bpmFabPrinActivo)>0)
            @foreach($solicitud->bpmFabPrinActivo as $bmpfabprin)
                          <tr>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              {{$bmpfabprin->nombreEmisor}}
                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                              @if($bmpfabprin->fechaEmision!='1900-01-01 00:00:00' && $bmpfabprin->fechaEmision!='')
                                      {{date('d-m-Y',strtotime($bmpfabprin->fechaEmision))}}
                               @endif
                            </td>
                            <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                               @if($bmpfabprin->fechaVencimiento!='1900-01-01 00:00:00' && $bmpfabprin->fechaVencimiento!='')
                                      {{date('d-m-Y',strtotime($bmpfabprin->fechaVencimiento))}}
                               @endif
                            </td>
                          </tr>
              @endforeach
          @else
             <tr>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;"></td>
             </tr>
          @endif
      </tbody>
    </table>
    <!--FIN BPM-->
    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><b>IV. INFORMACIÓN GENERAL DEL(LOS) DISTRIBUIDOR(ES) NACIONAL(ES) (Opcional)</b></div><br><br>
    <!--DISTRIBUIDORES-->
    <!-- Fabricantes alternos-->
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tbody>
        <tr class="titulos">
            <td colspan="30" style="width: 100% !important;padding-left: 1.8em; padding-bottom: 0.1em;text-align:center;"><b>23. INFORMACION DEL DISTRIBUIDOR</b></td>
        </tr>
        <tr class="titulos">
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>NOMBRE </b></td>
            <td colspan="10" style="width: 35% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>DIRECCIÓN</b></td>
            <td colspan="10" style="width: 15% !important;padding-left: 1.8em; padding-bottom: 0.1em;"><b>CORREO ELECTRÓNICO</b></td>
        </tr>

        @if ($info->distribuidores!=null)
          @foreach ($info->distribuidores as $distribuidor)
            <tr>
              <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                {{$distribuidor->nombreComercial}}
              </td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                {{$distribuidor->direccion}}
              </td>
              <td colspan="10" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">
                {{$distribuidor->emailContacto}}
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;">
            </td>
            <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;">
            </td>
            <td colspan="10" style="text-align:center;padding-bottom: 0.9em;padding-top: 0.9em;">
            </td>
          </tr>
        @endif
      </tbody>
    </table>
    <!--FIN DISTRIBUIDORES-->
    <br>
    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><b>V. PRESENTACION DE LA DOCUMENTACION (Anexo requisitos)</b><br><br>Observaciones:</div>
     <br>
    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><hr style="width:100% !important;border-top: 1px solid #8c8b8b;"></div>

    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><hr style="width:100% !important;border-top: 1px solid #8c8b8b;"></div>

    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><hr style="width:100% !important;border-top: 1px solid #8c8b8b;"></div>

    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><hr style="width:100% !important;border-top: 1px solid #8c8b8b;"></div>

    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><hr style="width:100% !important;border-top: 1px solid #8c8b8b;"></div>
    <br>
    <!-- DECLARACION-->
    <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><b>VI. DECLARACION JURADA </b></div>
    <div style="width:100% !important;text-align:justify !important;margin:0px !important;padding:0px !important; border:1px solid black;">
      El suscrito apoderado (o representante legal)  @if ($info->representante!=null || $info->apoderado!=null)  @if ($info->apoderado!=null) <b>{{$info->apoderado[0]->NOMBRE_APODERADO}}</b> @else  <b>{{$info->representante->NOMBRES}} {{$info->representante->APELLIDOS}}</b> @endif  @else _____________________________________________,@endif y el profesional responsable   @if($info->profesional!=null) <b>{{$info->profesional->NOMBRES.' '.$info->profesional->APELLIDOS}}</b> @else ______________________________, @endif
       declaramos que la información ingresada en los pasos 01 al 09 de la solicitud de Nuevo Registro del Portal en línea,   corresponde  a los requisitos de Ley  exigidos para dicho proceso, siendo fiel y conforme con los requisitos requeridos para el trámite y con sus originales adjuntados en el paso 10;  los cuales son veraces y garantizan la calidad, seguridad y eficacia del  producto  <b>{{$solicitud->solicitudesDetalle->nombreComercial}}</b>. Por tanto, asumimos las responsabilidades administrativas y penales que correspondan.
    </div>
    <br>
<br><br>
    <table style="width:100%" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
                <td style="width:50%;border:none;text-align:center;"></td>
                <td style="width:50%;border:none;text-align:center;"></td>
          </tr>
          <tr>
            <td style="width:50%;border:none;text-align:center;">
              <br><br><br>
              <hr style="width:70%;border:1px solid black;">
               Nombre y Firma del propietario apoderado <br>
               o representante legal
            </td>
            <td style="width:50%;border:none;text-align:center;">
                <br><br><br>
              <hr style="width:70%;border:1px solid black;">
              Nombre, Firma y Sello del Profesional Químico Farmacéutico
              <br>
               Responsable Sello (cuando aplique)
            </td>
          </tr>
        </tbody>
    </table>
<br><br>
    <!-- FIN DECLARACION-->
        <!-- USO NOTARIAL-->
        <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important;"><b>VII. PARA USO NOTARIAL (LEGALIZACIÓN DE FIRMAS)</b> </div>
        <div style="width:100% !important;border:1px solid black;text-align:justify;">
          DOY FE QUE LA (S) FIRMA (S) QUE CALZA (N) EL ANTERIOR ESCRITO QUE SE LEE (N) <br>
            <center>_______________________________________________</center>
        <br><center>_______________________________________________</center>
        <br>ES (SON) AUTENTICA (S) POR HABER SIDO PUESTA (S) DE SU PUÑO Y LETRA ANTE MI PRESENCIA POR EL (LOS) (LA)SEÑOR (A)(ES)
          <br><br><br><center>_________________________________________________________</center>
           <center>Nombre del propietario/apoderado o representante legal</center>
          <br><br><br><center>_________________________________________________________</center>
         <center>Químico farmacéutico responsable</center>
          <br>DE _______________________________________ AÑOS DE EDAD (RESPECTIVAMENTE), DEL DOMICILIO DE _____________________________________________ A QUIEN (ES) CONOZCO POR SU(S) _______________________________________________________________ NÚMERO(S) ____________________________________________________ (RESPECTIVAMENTE), SAN SALVADOR  A LOS__________________________________________________________
          <br><br><br><br><br><br><center>_________________________________________________________.</center>
          <br><center>Firma y sello del Notario</center>

        </div>
        <!-- FIN USO NOTARIAL-->
     <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important; margin: 55px;">
      Notas: <br><b>
        <ol>
          <li>La presente no tiene validez sin el nombre y la firma exigidos.</li>
          <li>Los documentos ingresados y en trámite no serán devueltos.</li>
          <li>Las traducciones deben hacerse de forma íntegra vertiendo al castellano todo lo que se encuentre en idioma extranjero. según art. 62 de la Constitución de la República y el Art. 103 del Reglamento General de la Ley de Medicamentos</li>
        </ol></b>

    </div>
    {{--<div style="page-break-after:always;"></div>
     <div style="width:100% !important;text-align:left !important;margin:0px !important;padding:0px !important; text-align: center;"><b>ANEXO</b></div><br>
      <table class="formulario" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="titulos">
            <td colspan="20" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"><b>REQUISITOS NUEVO REGISTRO </b></td>
            <td colspan="20" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"><b>NUMERACION DE FOLIO</b></td>
          </tr>
          <tr>
            <td colspan="20" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">SOLICITUD DE INGRESO DE NUEVO REGISTRO SANITARIO DE MEDICAMENTO</td>
            <td colspan="20" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"></td>
          </tr>
          @php $i=1; @endphp
          @foreach($documentos as $sub)
            @if($sub->idSubExpediente!=1)
                    <tr class="titulos">
                        <td colspan="40" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;">{!!$sub->nomSubExpediente!!}</td>
                    </tr>
                    @foreach($sub->docs as $doc)
                    <tr>
                        <td colspan="20" style="text-align:left;padding-bottom: 0.8em;padding-top: 0.8em;">{{$i}}. {!!$doc->nomDocumento!!}</td>
                        <td colspan="20" style="text-align:center;padding-bottom: 0.8em;padding-top: 0.8em;"></td>
                    </tr>
                    @php $i++; @endphp
                    @endforeach
            @endif
          @endforeach
        </tbody>
      </table>--}}


</div>

<!--<footer id="footer">
    <hr>
    Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.<br>PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
</footer>-->

</body>

</html>
