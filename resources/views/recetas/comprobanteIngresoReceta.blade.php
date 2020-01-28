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
            font-size: 12px;
            margin:5px;

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
        div.panel-body{
              border-width: 1px;
            border-style: solid;
             border-color: #323952;
        }
         div.panel-heading{
              border-width: 1px;
             border-style: solid;
             border-color: #323952;
            right: 0;
            bottom: 0;
            left: 0;
            padding: 0;
        }
        table.formulario thead, tr, td {
            border: 1px solid #ddd;
            padding: 5px !important;
        }
        table.formulario{
            width: 95%;
            margin-left: 20px;
            margin-top: 10px;
            border: 1px solid #ddd;
        }
        table.formulario2 thead, tr, td {
            border: 1px solid #ddd;
            padding: 5px !important;
        }
        table.formulario2{
            width: 98%;
            margin-left: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
        }
        hr{
            border: 1px solid #ddd;
            width: 92%;
            margin-left:28px;
        }
         div.panel-body2{
              border-width: 1px;
            border-style: solid;
             border-color: #323952;
               width: 92%;
             margin-left: 25px;
        }
         div.panel-heading2{
             width: 92%;
             margin-left: 25px;
             border-width: 1px;
             border-style: solid;
             border-color: #323952;
            right: 0;
            bottom: 0;
            left: 0;
            padding: 0;
        }




    </style>
</head>
<body>
<header>
    <table class="cabecera" style="width:100%; border-collapse: collapse;">
        <tbody>
            <tr>
                <th>{{date('d/m/Y')}}</th>
                <th> </th>
            </tr>
        <tr>
            <th style="width:15%;">

                    <img id="escudo" src="{{ url('img/logoReceta.png') }}"/>
            </th>
            <th>
                {{Session::get('name')}} {{Session::get('lastname')}}
            </th>
        </tr>
        </tbody>
    </table>
</header>
<div class="panel-heading" >
    <div style="padding:0px !important;">&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;RECETA</div>
 </div>
 <div class="panel-body">
      <table class="formulario" cellpadding="0" cellspacing="0"  cellspacing="">
      <tr>
          <td style="width:10% !important;text-align:left!important; background-color: #eee;"><b>Receta #</b></td>
          <td> {{$info[0]->ID_RECETA}}</td>
      </tr>
       <tr>
          <td style="width:10% !important;text-align:left!important; background-color: #eee;"><b>Fecha</b></td>
          <td>{{date('d-m-Y',strtotime($info[0]->fecha_emision))}}</td>
      </tr>
        <tr>
          <td style="width:20% !important;text-align:left!important; background-color: #eee;"><b>JVPM médico</b></td>
          <td>{{$info[0]->ID_MEDICO}}</td>
      </tr>
    </table><br>
    <hr><br>
    <table class="formulario" cellpadding="0" cellspacing="0">
      <caption>DATOS DEL PACIENTE</caption>
      <tr>
          <td style="width:15% !important;text-align:left!important; background-color: #eee;"><b># Documento <i class="fa fa-search"></i></b></td>
          <td>{{$info[0]->ID_PACIENTE}}</td>
      </tr>
       <tr>
          <td style="width:10% !important;text-align:left!important; background-color: #eee;"><b>Paciente</b></td>
          <td>{{$info[0]->nombrePaciente}}</td>
      </tr>
        <tr>
          <td style="width:10% !important;text-align:left!important; background-color: #eee;"><b>Dirección</b></td>
          <td>{{$info[0]->direccionPaciente}}</td>
      </tr>
    </table>
    <br>
    <hr><br>

    <table class="formulario" cellpadding="0" cellspacing="0">
      <tr>
          <td style="width:150px !important;text-align:left!important; background-color: #eee;"><b>Producto controlado</b></td>
          <td>{{$info[0]->ID_PRODUCTO_RECETADO}}</td>
      </tr>
       <tr>
          <td style="width:10% !important;text-align:left!important; background-color: #eee;"><b>Nombre comercial</b></td>
          <td>{{$info[0]->nombreComercial}}</td>
      </tr>
    </table>
    <br>
    <hr><br>
    <table class="formulario" cellpadding="0" cellspacing="0">
      <tr>
          <td style="width:140px !important;text-align:left!important; background-color: #eee;"><b>Total dosis prescrita</b></td>
          <td>{{$info[0]->cantidad_prescrita_magnitud}}</td>
      </tr>
       <tr>
          <td style="width:10% !important;text-align:left!important; background-color: #eee;"><b>Tipo de uso</b></td>
          <td> @if($info[0]->tipoUso==1) Prescipción Médica @elseif($info[0]->tipoUso==2) Uso Profesional  @else Menor de Edad @endif</td>
      </tr>
    </table>
    <br>
    <hr><br>
    <div class="panel-heading2" >
        <div style="padding:0px !important;">&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;DATOS SOBRE DOSIS</div>
     </div>
     <div class="panel-body2">
                <table  class="formulario2" cellpadding="0" cellspacing="0">
                   <tr>
                      <td style="width:140px !important;text-align:left!important; background-color: #eee;"><b>Dosis según receta</b></td>
                      <td>{{$info[0]->cantidad_prescrita_descripcion}}</td>
                  </tr>
                  <tr>
                      <td style="width:140px!important;text-align:left!important; background-color: #eee;"><b>Total de tomas por ciclo</b></td>
                      <td>{{$info[0]->dosis_ciclo}}</td>
                  </tr>
                    <tr>
                      <td style="width:140px!important;text-align:left!important; background-color: #eee;"><b>Ciclo de dosis</b></td>
                      <td> @if(trim($info[0]->ciclo)=='24 Horas' || trim($info[0]->ciclo)=='24') 24 Horas @else 12 Horas @endif</td>
                  </tr>
                    <tr>
                      <td style="width:140px !important;text-align:left!important; background-color: #eee; "><b>Duración tratamiento</b></td>
                      <td>{{$info[0]->dosis_duracion_trat}} Días</td>
                  </tr>
                </table>
                <br>
     </div>
    <br>
</div>

<footer id="footer">
   <!-- © {{date('Y')}} Dirección Nacional de Medicamentos (medicamentos.gob.sv) Diseñado por IT DNM-->
    <!--Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América
    Central.&nbsp;&nbsp; PBX 2522-5000 / web: www.medicamentos.gob.sv-->
</footer>

</body>

</html>