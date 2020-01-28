<h4>
  <span class="label label-primary">
    <strong>Ingrese la información de la farmacologica del nuevo producto farmacéutico!</strong>
  </span>
</h4>
<!-- BEGIN FORM WIZARD -->

<input type="hidden" value="{{Crypt::encrypt('0')}}" name="idSolicitud8" id="idSolicitud8" form="RegistroPreStep9">
<div class="panel with-nav-tabs panel-default ">
  <div class="panel-heading">
	<ul class="nav nav-tabs nav-info">
		  <li class="active"><a href="#farmacologia" data-toggle="tab">Farmacocinética</a></li>
		  <li><a href="#mecanismo" data-toggle="tab">Mecanismo de Acción</a></li>
		  <li><a href="#indicaciones" data-toggle="tab">Indicaciones Terapéuticas</a></li>
		  <li><a href="#contradiciones" data-toggle="tab">Contraindicaciones</a></li>
		  <li><a href="#dosis" data-toggle="tab">Dosis</a></li>
		  <li><a href="#efectosas" data-toggle="tab">Efectos adversos o secundarios</a></li>
		  <li><a href="#advertencias" data-toggle="tab">Advertencias</a></li>
		  <li><a href="#interacciones" data-toggle="tab">Principales Interacciones</a></li>
		  <li><a href="#categoria" data-toggle="tab">Categoría terapéutica</a></li>
	</ul>
  </div>
	<div id="panel-collapse-1">
		<div class="tab-content">
			<div class="tab-pane fade in active" id="farmacologia">
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="farm" name="farm" placeholder="Digite la farmacología general...">{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->farmacocinetica):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="mecanismo">
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="mecaaccion" name="mecaaccion" placeholder="Digite los mecanismos de acción...">{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->mecanismoAccion):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="indicaciones">
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="indicacion" name="indicacion" placeholder="Digite las indicaciones...">{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->indicacionesTerapeuticas):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="contradiciones">
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="contrad" name="contrad" placeholder="Digite las contradiciones...">{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->contraindicaciones):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="dosis">
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="dos" name="dos" placeholder="Digite la dosis...">{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->regimenDosis):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="efectosas">
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="efectos" name="efectos" placeholder="Digite los efectos secundarios o adversos...">{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->efectosAdversos):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="advertencias">
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="adv" name="adv" placeholder="Digite las advertencias...">{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->precauciones):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="interacciones">
				<textarea form="RegistroPreStep9" class="form-control farmaco" id="interaccion" name="interaccion" placeholder="Digite las principales interacciones...">{{$solicitud->farmacologia!=null?strip_tags($solicitud->farmacologia->detalle->interacciones):''}}</textarea>
			</div>
			<div class="tab-pane fade" id="categoria">
				<div class="form-group">
                <div class="row">
                     <div class="col-sm-2 col-md-2"></div>
                    <div class="col-sm-8 col-md-8">
                         <br><br>
                        <div class="input-group ">

                          <div class="input-group-addon"><b>Grupo farmacológico (Código ATC)</b></div>
                          <select form="RegistroPreStep9"  class="form-control chosen" id="codigo-atc" name="codigoatc">
                          @if(!empty($solicitud->farmacologia))
		                          @if($solicitud->farmacologia->detalle->categoriaTerapeutica!='')
		                          	<option value="" >NO APLICA</option>
		                            <option selected value="{{$solicitud->farmacologia->detalle->categoriaTerapeutica}}">{{$solicitud->farmacologia->detalle->codigoAtc->codigoAtc}} {{$solicitud->farmacologia->detalle->codigoAtc->nombre}}</option>
		                          @else
		                          	<option value="" disabled selected hidden>Seleccione...</option>
		                          @endif
                          @else
                          	      <option value="" disabled selected hidden>Seleccione...</option>
                          @endif
						</select>
                        </div>
                        <br><br>
                    </div>
                    <div class="col-sm-2 col-md-2"></div>
                </div>
           	 </div>

			</div>
		</div><!-- /.tab-content -->
	</div><!-- /.collapse in -->
</div><!-- /.panel .panel-success -->
<!-- END FORM WIZARD -->

<!-- <a href="#" id="speak9" class="waves-effect waves-light btn btn-dark" disabled><i class="fa fa-play" aria-hidden="true"></i></a> -->



<div align="center">
	<button type="button" class="btn btn-primary" id="btnStep9">Guardar Paso 9</button>
</div>
