<div class="container-fluid the-box">
		<h4>
			<span class="label label-info">
				<strong>PROFESIONAL RESPONSABLE</strong>
			</span>
			@if(!empty($campos))  @if(in_array('poderProfesional', $campos))<a class="btn btn-danger btn-perspective btn-xs vals" id="poderProfesional-val"><i class="fa  fa-times"></i></a>@endif @endif
		</h4>
		<div id="token-poderProfesional"></div>
		<div class="form-group row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"> 
				<div class="input-group">
				 <div class="input-group-addon"><b>Num. Poder Profesional:</b></div>
				 <input type="text" class="form-control" id="poderProf" placeholder="Digite el número de poder" name="poderProf" value="{{$solicitud->profesional!=null?$solicitud->profesional->poderProfesional:''}}" autocomplete="off" required form="RegistroPreStep3">
				@if(!empty($campos))  @if(in_array('poderProfesional', $campos))
				 <span class="input-group-btn">
					<button class="btn btn-primary" id="validarPoderP" type="button">Buscar <i class="fa fa-search" aria-hidden="true"></i></button>
				</span>
				@endif @endif
				</div>
				<div class="help-block with-errors"></div>
			</div>
		</div>
		

		<div id="bodyProfesional">
			@if(isset($soldata))
					@if($soldata->profesional!=null)
							@include('registro.nuevoregistro.subsanacion.pasosEdit.paso3.bodyprofesional', ['profesional' => $soldata->profesional])
					@endif
			@endif
		</div>
</div>
