
<div class="container-fluid the-box">
		<h4>
			<span class="label label-info">
				<strong>APODERADO/S</strong>
			</span>
		</h4>
		<div class="form-group row"> 
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="input-group">
				 <div class="input-group-addon"><b>Num. Poder Apoderado:</b></div>
				 <input type="text" class="form-control" id="poderApo" name="poderApo" placeholder="Digite el número de poder" value="{{$solicitud->apoderado!=null?$solicitud->apoderado->poderApoderado:''}}" autocomplete="off" form="RegistroPreStep3">
				 <span class="input-group-btn">
					<button class="btn btn-primary" id="validarApoderado" type="button">Buscar <i class="fa fa-search" aria-hidden="true"></i></button>
				</span>
				</div>

			</div>
		</div>
		<div id="bodyApoderados">
			@if(isset($soldata))
					@if($soldata->apoderado!=null)
							@include('registro.nuevoregistro.pasosEdit.paso3.bodyApoderado', ['apoderados' => $soldata->apoderado])
					@endif
			@endif
		</div>
</div>
