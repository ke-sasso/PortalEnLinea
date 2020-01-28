
<div class="container-fluid the-box">
		<h4>
			<span class="label label-info">
				<strong>APODERADO/S</strong>
			</span>
			@if(!empty($campos))  @if(in_array('poderApoderado', $campos))<a class="btn btn-danger btn-perspective btn-xs vals" id="poderApoderado-val"><i class="fa  fa-times"></i></a>@endif @endif
		</h4>
		<div id="token-poderApoderado"></div>
		<div class="form-group row"> 
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="input-group">
				 <div class="input-group-addon"><b>Num. Poder Apoderado:</b></div>
				 <input type="text" class="form-control" id="poderApo" name="poderApo" placeholder="Digite el número de poder" value="{{$solicitud->apoderado!=null?$solicitud->apoderado->poderApoderado:''}}" autocomplete="off" form="RegistroPreStep3">
				 @if(!empty($campos))  
				   @if(in_array('poderRepresentante', $campos))
						<span class="input-group-btn" id="habilitar-poderApoderado">
								<button class="btn btn-primary" id="validarApoderado" type="button">Buscar <i class="fa fa-search" aria-hidden="true"></i></button>
					      </span>
				    @else
						<span class="input-group-btn" id="habilitar-poderApoderado" style="display: none;">
							<button class="btn btn-primary" id="validarApoderado" type="button">Buscar <i class="fa fa-search" aria-hidden="true"></i></button>
						</span>
				    @endif
			
				@endif
				</div>

			</div>
		</div>
		<div id="bodyApoderados">
			@if(isset($soldata))
					@if($soldata->apoderado!=null)
							@include('registro.nuevoregistro.subsanacion.pasosEdit.paso3.bodyApoderado', ['apoderados' => $soldata->apoderado])
					@endif
			@endif
		</div>
</div>
