<div class="container-fluid the-box">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			<table id="presentacionrv" class="table table-hover">
				<caption>
				 @if(!empty($campos)) @if(in_array('presentaciones', $campos))
                  <a class="btn btn-danger btn-perspective btn-xs vals" id="presentaciones-val"><i class="fa  fa-times"></i></a>
                  <div id="toke-presentaciones"></div>
                 @endif @endif<b>AGREGAR PRESENTACIONES</b></caption>
				<thead>
					<tr>
                        <th>N°</th>
						<th>NOMBRE</th>
						<th>TIPO</th>
						<th>MATERIAL</th>
                        <th>ACCESORIOS</th>
					@if(!empty($campos)) @if(in_array('presentaciones', $campos)) <th>OPCIONES</th> @endif	@endif
					</tr>
				</thead>
				<tbody>
					@if ($solicitud->empaquesPresentacion!=null)
						@php
							$i = 0;
						@endphp
						@foreach ($solicitud->empaquesPresentacion as $presentacion)
							@php
								$i += 1;
							@endphp
							<tr>
								<input form="RegistroStep1y2" type="hidden" name="presentaciones[]" value='{"empa1": "{{$presentacion->empaquePrimario}}","mat1": "{{$presentacion->contenidoPrimario}}","empa2": "{{$presentacion->empaqueSecunadrio}}","empa3": "{{$presentacion->empaqueTerciario}}","material": "{{$presentacion->idMateria}}","color": "{{$presentacion->idColor}}","tipoP": "{{$presentacion->tipoPresentacion}}","accesorios": "{{$presentacion->accesorio}}","presentacionespecial": "{{$presentacion->presentacionEspecial}}","checkempsec":"{{$presentacion->empaqueSecunadrio!=null?'1':'0'}}","checkempter":"{{$presentacion->empaqueTerciario!=null?'1':'0'}}","textPres":"{{$presentacion->textoPresentacion}}","por1":"{{$presentacion->cantidadPrimaria}}","por2":"{{$presentacion->cantidadSecundaria}}","mat2":"{{$presentacion->contenidoSecundario}}","por3":"{{$presentacion->cantidadTerciaria}}","mat3":"{{$presentacion->contenidoTerciario}}","nombreMaterial":"{{$presentacion->nombreMaterial}}","nombreColor":"{{$presentacion->nombreColor}}"}'>
								<td>{{$i}}</td>
								<td>{{$presentacion->textoPresentacion}}</td>

								<td>
									@if ($presentacion->tipoPresentacion==1)
										MUESTRA MÉDICA
									@elseif ($presentacion->tipoPresentacion==2)
										PRESENTACIÓN COMERCIAL
									@elseif ($presentacion->tipoPresentacion==3)
										PRESENTACIÓN HOSPITALARIA
									@elseif ($presentacion->tipoPresentacion==4)
										PRESENTACIÓN INSTITUCIONAL
									@endif
								</td>
								<td>{{$presentacion->nombreMaterial}}</td>
								<td>{{$presentacion->accesorio}}</td>
								@if(!empty($campos)) @if(in_array('presentaciones', $campos))
								<td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
								@endif	@endif
							</tr>
						@endforeach
					@endif

				</tbody>
				@if(!empty($campos)) @if(in_array('presentaciones', $campos))
				<tfoot id="plusPresent">
					<tr>
						<th colspan="6" class="text-right">
							<span class="btn btn-primary" id="btnAddPresentacion"><i class="fa fa-plus"></i></span>
						</th>
					</tr>
				</tfoot>
				@endif	@endif
			</table>
		</div>
	</div>
</div>




<div class="modal fade" id="dlgAddPresent" tabindex="-1" role="dialog" aria-labelledby="DefaultModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header bg-primary">
			<button type="button" class="close" style="opacity: 1 !important; color: #ffffff;" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 align="center" class="modal-title" id="DefaultModalLabel" >Presentaciones</h3>
		  </div>
		  <div class="modal-body" id="presentacionRvDiv">
			  <div class="form-group">
				  <div class="row">
					 <div class="col-xs-12 col-sm-4">
						  <label>¿Posee empaque secundario?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
						  <input type="radio" id="empesectrue" name="checkempsec" value="1" >&nbsp;&nbsp;Si
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  <input type="radio" id="empesecfalse"  name="checkempsec" checked="checked" value="0" >&nbsp;&nbsp;No
					  </div>

					  <div class="col-xs-12 col-sm-4">
						  <label>¿Posee empaque terciaria?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
						  <input type="radio" id="empetertrue" name="checkempter" value="1" >&nbsp;&nbsp;Si
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  <input type="radio" id="empeterfalse"  name="checkempter" checked="checked" value="0" >&nbsp;&nbsp;No
					  </div>
					   <div class="col-xs-12 col-sm-4">
						  <label>¿Es presentación especial?</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
						  <input type="radio" id="empetertrue" name="checkespecial" value="1" >&nbsp;&nbsp;Si
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  <input type="radio" id="empeterfalse"  name="checkespecial" checked="checked" value="0" >&nbsp;&nbsp;No
					  </div>

				  </div>
			  </div>

			  <div class="form-group" >
				  <div class="row">
					  <div class="col-xs-12 col-sm-12">
						  <h4>Su presentación se lee así:</h4>
						  <b><input type="text" class="form-control pres" style="border-bottom-color: black; background-color: #d5dad8;" id="textPres" name="textPres" readonly="true"/></b>
					  </div>
				  </div>
			  </div>

			  <div class="form-group">
				  <div class="row">
					  <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						  <div class="input-group">
							  <div class="input-group-addon"><b>Primaria:</b></div>
							  <select name="empa1" id="empa1" data-placeholder="Escoge un empaque..." class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true" required onchange="armarPresentacion();">
							  </select>
						  </div>
						  <div class="help-block with-errors"></div>
					  </div>
					  <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
						  <div class="input-group">
							  <div class="input-group-addon"><b>X</b></div>
							  <input type="number" class="form-control" id="por1" name="por1" value="0" min="1" required onkeyup="armarPresentacion();" onmousedown="armarPresentacion();" onmouseup="armarPresentacion();">
						  </div>
						  <div class="help-block with-errors"></div>
					  </div>
					  <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						  <div class="input-group">
                              <div class="input-group-addon"><b>-</b></div>
							  <select name="mat1" id="mat1" data-placeholder="Seleccionar un contenido..." class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true" required onchange="armarPresentacion();">
							  </select>
						  </div>
						  <div class="help-block with-errors"></div>
					  </div>
				  </div>
			  </div>
			  <div class="form-group" id="empaquesecundario">
				  <div class="row">
					  <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						  <div class="input-group">
							  <div class="input-group-addon"><b>Secundaria:</b></div>
							  <select name="empa2" id="empa2" data-placeholder="Escoge un empaque..." class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true" required onchange="armarPresentacion();">
							  </select>
						  </div>
						  <div class="help-block with-errors"></div>
					  </div>
					  <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
						  <div class="input-group">
							  <div class="input-group-addon"><b>X</b></div>
							  <input type="number" class="form-control" id="por2" name="por2" value="0" min="1" required onkeyup="armarPresentacion();" onmousedown="armarPresentacion();" onmouseup="armarPresentacion();">
						  </div>
						  <div class="help-block with-errors"></div>
					  </div>
					  <div class="col-xs-12 col-sm-12 col-md-5 col-lg5">
						  <div class="input-group">
							  <div class="input-group-addon"><b>-</b></div>
							  <input type="text" class="form-control" id="mat2" name="mat2" value="" readonly required>
						  </div>
					  </div>
				  </div>
			  </div>
			  <div class="form-group" id="empaqueterciario">
				  <div class="row">
					  <div class="col-xs-12 col-sm-12 col-md-4 col-lg-5">
						  <div class="input-group">
							  <div class="input-group-addon"><b>Terciaria:</b></div>
							  <select name="empa3" id="empa3" data-placeholder="Escoge un empaque..." class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true" required  onchange="armarPresentacion();">
							  </select>
						  </div>
						  <div class="help-block with-errors"></div>
					  </div>
					  <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
						  <div class="input-group">
							  <div class="input-group-addon"><b>X</b></div>
							  <input type="number" class="form-control" id="por3" name="por3" value="0" min="1" required onkeyup="armarPresentacion();" onmousedown="armarPresentacion();" onmouseup="armarPresentacion();"   >
						  </div>
						  <div class="help-block with-errors"></div>
					  </div>
					  <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						  <div class="input-group">
                              <div class="input-group-addon"><b>-</b></div>
							  <input type="text" class="form-control" id="mat3" name="mat3" value="" readonly required>
						  </div>
					  </div>
				  </div>
			  </div>
			  <div class="form-group">
				  <div class="row">
					  <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						  <div class="input-group">
							  <div class="input-group-addon"><b>Material:</b></div>
							  <select onchange="armarPresentacion();" id="material" name="material" class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true" required>
							  </select>
							  <input type="hidden" name="nombreMaterial" id="nombreMaterial" value="">
						  </div>
						  <div class="help-block with-errors"></div>
					  </div>

					  <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						  <div class="input-group">
							  <div class="input-group-addon"><b>Color:</b></div>
							  <select onchange="armarPresentacion();" id="color" name="color" class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true" required>
							  </select>
							   <input type="hidden" name="nombreColor" id="nombreColor" value="">
						  </div>
						  <div class="help-block with-errors"></div>
					  </div>
				  </div>
			  </div>
			  <div class="form-group">
				  <div class="row">
					  <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
						  <div class="input-group">
							  <div class="input-group-addon"><b>Accesorios:</b></div>
							  <textarea onchange="armarPresentacion();" name="accesorios" id="accesorios" class="form-control text-uppercase"></textarea>
						  </div>
					  </div>
				  </div>
			  </div>
			  <div class="form-group">
				  <div class="row">
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						  <div class="input-group">
							  <div class="input-group-addon"><b>Tipo presentación:</b></div>
							  <select name="tipoP" id="tipoP" class="form-control">
								  <option value="1" selected>MUESTRA MÉDICA</option>
								  <option value="2">PRESENTACIÓN COMERCIAL</option>
								  <option value="3">PRESENTACIÓN HOSPITALARÍA</option>
								  <option value="4">PRESENTACIÓN INSTITUCIONAL</option>
								  <option value="5">MUESTRO MÉDICA SIN VALOR COMERCIAL</option>
							  </select>
						  </div>
						  <div class="help-block with-errors"></div>
					  </div>
				  </div>
			  </div>
			  <div class="form-group" id="pre-especial" style="display: none;">
				  <div class="row">
					  <div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
						  <div class="input-group">
							  <div class="input-group-addon"><b>Presentación Especial:</b></div>
							  <textarea name="presentacionespecial" id="presentacionespecial" class="form-control text-uppercase" placeholder="Digite la información necesaria para la presentacion especial"></textarea>
						  </div>
					  </div>
				  </div>
			  </div>
		  </div>
		  <div class="modal-footer">
			<button type="button" id="btnAddPresentacionDt" class="btn btn-primary">Agregar</button>
		  </div><!-- /.modal-footer -->
		</div><!-- /.modal-content -->
	</div><!-- /.modal-doalog -->
</div><!-- /#DefaultModal -->
