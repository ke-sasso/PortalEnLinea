
<div class="container-fluid the-box">
		<h4>
			<span class="label label-info">
				<strong>REPRESENTANTE LEGAL</strong>
			</span>
		</h4>
		<div class="form-group row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"> 
				<div class="input-group">
				 <div class="input-group-addon"><b>Num. Poder Representante Legal:</b></div>
				 <input type="text" class="form-control" id="poderRL" name="poderRL" placeholder="Digite el número de poder" value="{{$solicitud->representante!=null?$solicitud->representante->poderRepresentante:''}}" autocomplete="off" form="RegistroPreStep3">
				 <span class="input-group-btn">
					<button class="btn btn-primary" id="validarPresentanteL" type="button">Buscar <i class="fa fa-search" aria-hidden="true"></i></button>
				</span>
				</div>
				<div class="help-block with-errors"></div>

			</div>
		</div>
		<div id="bodyRepresentanteLegal">
			@if(isset($soldata))
					@if($soldata->representante!=null)
							@include('registro.nuevoregistro.pasosEdit.paso3.bodyRepresentante', ['representanteL' => $soldata->representante])
					@endif
			@endif
		</div>
</div>
