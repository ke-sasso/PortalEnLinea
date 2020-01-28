<div class="the-box full no-border">
			<table class="table table-hover" id="dt-poderes">
				<thead>
				<tr>
					<th>NIT</th>
					<th>NOMBRE</th>
					<th>DIRECCIÓN</th>
					<th>TELÉFONO</th>
					<th>CORREO</th>
					<th>FAX</th>
				</tr>
				</thead>
				<tbody>
				  @if(!empty($apoderados))

					@foreach($apoderados as $apo)
					 <input type="hidden" name="idApoderado" id="idApoderado" value="{{ $apo->ID_APODERADO}}" form="RegistroPreStep3">
					<tr>
						<td>{!!$apo->NIT !!}</td>
						<td>{!!$apo->NOMBRE_APODERADO !!}</td>
						<td>{!!$apo->DIRECCION !!}</td>
						<td>{!!$apo->TELEFONO_1 !!}</td>
						<td>{!!$apo->EMAIL !!}</td>
						<td>{!!$apo->FAX !!}</td>
					</tr>

					@endforeach
				@endif
				</tbody>
			</table>
</div>