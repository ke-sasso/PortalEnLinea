<div class="container-fluid the-box">
    <h4>
			<span class="label label-info">
				<strong>PROFESIONAL RESPONSABLE</strong>
			</span>
    </h4>
    <div class="form-group row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="input-group">
                <div class="input-group-addon"><b>Num. Poder Profesional:</b></div>
                @if(isset($solicitud))
                    @if(!empty($solicitud->solicitudesDetalle))
                        @if(!is_null($solicitud->solicitudesDetalle->idPoderProfesional))
                            <input type="text" class="form-control" id="poderProf" placeholder="Digite el número de poder" name="poderProf" value="{{$solicitud->solicitudesDetalle->idPoderProfesional}}" autocomplete="off" required form="CosPreStep3">
                        @else
                            <input type="text" class="form-control" id="poderProf" placeholder="Digite el número de poder" name="poderProf" value="" autocomplete="off" required form="CosPreStep3">
                        @endif
                    @else
                        <input type="text" class="form-control" id="poderProf" placeholder="Digite el número de poder" name="poderProf" value="" autocomplete="off" required form="CosPreStep3">
                    @endif
                @else
                    <input type="text" class="form-control" id="poderProf" placeholder="Digite el número de poder" name="poderProf" value="" autocomplete="off" required form="CosPreStep3">
                @endif
                <span class="input-group-btn">
					<button class="btn btn-primary" id="validarPoderP" type="button">Buscar <i class="fa fa-search" aria-hidden="true"></i></button>
				</span>
            </div>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div id="bodyProfesional">
        @if(isset($soldata))
            @if($soldata->profesional!=null)
                @include('cosmeticos.nuevoregistro.pasos.paso3.bodyprofesional', ['profesional' => $soldata->profesional])
            @endif
        @endif
    </div>

</div>