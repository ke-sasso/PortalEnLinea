<!DOCTYPE html>
<html>
<head>
  <title></title>
  <style type="text/css">
  	*{
  		font-family: 'Arial';

  	}
    p{
  		font-size: 13px;
  	}
  	.table{
  		width: 100%;
  		font-size: 12px;
  	}
  	.table tr{
  		margin: 0;
  		padding: 0;
  	}
  	.table tr td{
		text-transform: uppercase;
  		border-bottom: 1/10px solid #CCC;
  		padding-left: 5px;
		padding: 3px
  	}
  	.table tr td.td_title{
  		background: #212121;
  		color: #FFF;
  		padding-left: 7px;
  		border: none;
  	}
  	.table tr td.td_head{
  		background: #000;
  		color: #FFF;
  		border: none;
  		text-align: center;
  	}
  </style>
</head>
<body>
	<img src="{{ asset('/') }}img/LOGO_HERRAMIENTA_URIM.png" style="width: 100%">
<h3 style="color:#212121">Formulario de inscripción › Portal en Línea</h3>
<p>
	Yo <b>{{ $inscripcion->nombres }} {{ $inscripcion->apellidos }}</b>, mayor de edad con número de DUI <b>{{ $inscripcion->dui }}</b>, y número de NIT <b>{{ $inscripcion->nit }}</b> 
	@if($inscripcion->pf_profesional == 1), inscrito ante la Junta de Vigilancia bajo el número <b>{{ $inscripcion->pf_correlativo }}</b> @endif
	por medio del presente formulario solicito que me sea proporcionado un usuario y contraseña para generar mandamientos de pago mediante Portal en Línea.
</p>
<p>Estoy enterado que el acceso a Portal en Línea es único e instransferible y que cualquier uso dentro del portal en línea de la Dirección Nacional de Medicamentos es de mi entera responsabilidad.</p>

<hr>

<table class="table">
	<tbody>
		<tr>
			<td colspan="6" class="td_head">
				Datos de inscripción
			</td>
		</tr>
		<tr>
			<td colspan="6"></td>
		</tr>
		<tr>
			<td colspan="1" class="td_title">Referencia N°</td>
			<td colspan="1">{{ $inscripcion->idInscripcion }}</td>

			<td colspan="2" class="td_title">Fecha de registro</td>
			<td colspan="2">{{ $inscripcion->fechaCreacion->date }}</td>
		</tr>
		<tr>
			<td colspan="6">
				<br>
			</td>
		</tr>
		<tr>
			<td class="td_title" colspan="2">Nombre completo</td>
			<td colspan="4">{{ $inscripcion->tratamiento->abreviaturaTratamiento }} {{ $inscripcion->nombres }} {{ $inscripcion->apellidos }}</td>
		</tr>
		<tr>
			<td class="td_title">DUI</td>
			<td>{{ $inscripcion->dui }}</td>

			<td class="td_title">NIT</td>
			<td>{{ $inscripcion->nit }}</td>

			<td class="td_title">Género</td>
			<td>{{ $inscripcion->sexo }}</td>
		</tr>
		<tr>
			<td colspan="6">
				<br>
			</td>
		</tr>
		@php
			$telefonos = json_decode($inscripcion->telefonosContacto);
			$telefono_fijo = $telefonos[0] or '-';
			$telefono_celular = $telefonos[1] or '-';
        @endphp
		<tr>
			<td colspan="2" class="td_title">Teléfono Fijo</td>
			<td colspan="1">{{ $telefono_fijo }}</td>

			<td colspan="2" class="td_title">Teléfono Celular</td>
			<td colspan="1">{{ $telefono_celular }}</td>
		</tr>
		<tr>
			<td colspan="2" class="td_title">Correo electrónico</td>
			<td colspan="4">{{ $inscripcion->emailsContacto }}</td>
		</tr>
		<tr>
			<td colspan="6" class="td_title">Dirección para recibir correspondencia</td>
		</tr>
		<tr>	
			<td colspan="6">{{ $inscripcion->direccion }}</td>
		</tr>
		@if($inscripcion->pf_profesional == 1)
		<tr>
			<td colspan="6">
				<br>
			</td>
		</tr>
		<tr>
			<td colspan="1" class="td_title">JUNTA</td>
			<td colspan="5">{{ $inscripcion->junta->nombreJunta }}</td>
		</tr>
		<tr>
			<td colspan="1" class="td_title">RAMA</td>
			<td colspan="2">{{ $inscripcion->rama->nombreRama }}</td>

			<td colspan="1" class="td_title">CORRELATIVO</td>
			<td colspan="2">{{ $inscripcion->pf_correlativo }}</td>
		</tr>
		@endif
	</tbody>
</table>
<br><br><br><br><br><br>
<br><br><br><br><br><br>

<p style="text-align: center;">________________________________________________
<br><br>
{{ $inscripcion->nombres }} {{ $inscripcion->apellidos }}
</p>

</body>
</html>
