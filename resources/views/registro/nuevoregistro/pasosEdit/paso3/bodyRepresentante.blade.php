		<input type="hidden" value="{!! $representanteL->ID_APODERADO !!}" name="idPresentanteLegal" id="idPresentanteLegal" form="RegistroPreStep3">
		<div class="form-group row">
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<div class="input-group">
				 <div class="input-group-addon"><b>NIT:</b></div>
					<label class="form-control">{!! $representanteL->NIT !!}</label>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<div class="input-group">
					<div class="input-group-addon"><b>DUI:</b></div>
					<label class="form-control">{!! $representanteL->DUI !!}</label>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="input-group">
					<div class="input-group-addon"><b>Nombres:</b></div>
					<label class="form-control" >{!! $representanteL->NOMBRES !!}</label>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="input-group">
					<div class="input-group-addon"><b>Apellidos:</b></div>
					<label class="form-control" >{!! $representanteL->APELLIDOS !!}</label>
				</div>
			</div>
		</div>
			<div class="form-group row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="input-group">
					<div class="input-group-addon"><b>Dirección:</b></div>
					<label class="form-control" >{!! $representanteL->DIRECCION !!}</label>
				</div>
			</div>
		</div>
		<div class="form-group">
    	<div class="row">
            <div class="col-sm-4 col-md-4">
                <div class="input-group ">
                  <div class="input-group-addon" for="telefonoProp"><b>Telefono:</b></div>
                  <input type="text" class="form-control" id="telefonoProp" name="telefonoProp" value="{!! $representanteL->TELEFONO_1 !!}" autocomplete="off" readonly>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="input-group ">
                  <div class="input-group-addon" for="correoProp"><b>Correo:</b></div>
                  <input type="text" class="form-control" id="correoProp" name="correoProp" value="{!! $representanteL->EMAIL !!}" autocomplete="off" readonly>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <div class="input-group ">
                  <div class="input-group-addon" for="correoProp"><b>FAX:</b></div>
                  <input type="text" class="form-control" id="correoProp" name="correoProp" value="{!! $representanteL->FAX !!}" autocomplete="off" readonly>
                </div>
            </div>
        </div>
    </div>