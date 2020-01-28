 <div class="panel with-nav-tabs panel-default ">

			<div class="panel-heading">
				<ul class="nav nav-tabs nav-info">
				    @foreach($expDoc as $exp)
				      @if($exp->idSubExpediente==1)
					  <li class="active"><a href="#exp-{{ $exp->idSubExpediente }}" data-toggle="tab">{{ $exp->nomSubExpediente }}</a></li>
					  @else
					   <li><a href="#exp-{{ $exp->idSubExpediente }}" data-toggle="tab">{{ $exp->nomSubExpediente }}</a></li>
					  @endif
					 @endforeach
				</ul>
		  	</div>
		    <div id="panel-collapse-1">
		     <div class="tab-content">

		     		 @foreach($expDoc as $exp)
					    <div   @if($exp->idSubExpediente==1) class="tab-pane fade in active" @else class="tab-pane fade" @endif class="tab-pane fade" id="exp-{{ $exp->idSubExpediente }}">
									<div class="table-responsive">
									<table width="100%" class="table table-striped" id="documentos">
										<thead>
											<th>REQUISITOS:</th>
										</thead>
										<tbody>
										@foreach($exp->docs as $doc)
											<tr>
												<td width="50%">{!!$doc->nomDocumento!!}</td>
												<td>

												@if($doc->requisito_documento->requerido==1)
												<div class="file-loading">
												 <input id="{{'file'.$doc->requisito_documento->idItem}}" name="file-es[{{$doc->requisito_documento->idItem}}]"  required data-obligatorio="{{$doc->requisito_documento->requerido}}"  data-sintesis="{{$doc->requisito_documento->sintesis}}" data-biologico="{{$doc->requisito_documento->biologico}}" data-biotecnologico="{{$doc->requisito_documento->biotecnologico}}" data-suplementos="{{$doc->requisito_documento->suplementos}}" data-naturales="{{$doc->requisito_documento->naturales}}" data-homeopatico="{{$doc->requisito_documento->homeopatico}}" data-radiofarmaco="{{$doc->requisito_documento->radiofarmaco}}" data-gasesmedicinales="{{$doc->requisito_documento->gasesmedicinales}}" data-innovador="{{$doc->requisito_documento->innovador}}" data-recoextranjero="{{$doc->requisito_documento->recoextranjero}}" data-generico="{{$doc->requisito_documento->generico}}" data-vacuna="{{$doc->requisito_documento->vacuna}}" form="RegistroPreStep10" type="file" accept="application/pdf">
												 </div>
											      <span class="label label-warning">Documento obligatorio @if($doc->requisito_documento->idItem==10 || $doc->requisito_documento->idItem==21) <b>Excepto para productos Naturales, Homeopáticos, Suplementos Nutricionales, Vacunas @if($doc->requisito_documento->idItem==10)y Gases medicinales.@endif</b>  @endif @if($doc->requisito_documento->idItem==7 || $doc->requisito_documento->idItem==18) Excepto para productos de origen Reconocimiento Mutuo Centroamericano @endif</span>
											      <div id="kv-success-{{'file'.$doc->requisito_documento->idItem}}"  style="display:none"></div>
											      <div id="kv-error-{{'file'.$doc->requisito_documento->idItem}}"  style="display:none"></div>
												@else
													<div class="file-loading">
													 <input id="{{'file'.$doc->requisito_documento->idItem}}" name="file-es[{{$doc->requisito_documento->idItem}}]"  data-obligatorio="{{$doc->requisito_documento->requerido}}"   data-sintesis="{{$doc->requisito_documento->sintesis}}" data-biologico="{{$doc->requisito_documento->biologico}}" data-biotecnologico="{{$doc->requisito_documento->biotecnologico}}" data-suplementos="{{$doc->requisito_documento->suplementos}}" data-naturales="{{$doc->requisito_documento->naturales}}" data-homeopatico="{{$doc->requisito_documento->homeopatico}}" data-radiofarmaco="{{$doc->requisito_documento->radiofarmaco}}" data-gasesmedicinales="{{$doc->requisito_documento->gasesmedicinales}}"  data-innovador="{{$doc->requisito_documento->innovador}}" data-recoextranjero="{{$doc->requisito_documento->recoextranjero}}" data-generico="{{$doc->requisito_documento->generico}}" data-vacuna="{{$doc->requisito_documento->vacuna}}" form="RegistroPreStep10" type="file" accept="application/pdf">
													</div>
													<div id="kv-success-{{'file'.$doc->requisito_documento->idItem}}"  style="display:none"></div>
												    <div id="kv-error-{{'file'.$doc->requisito_documento->idItem}}"  style="display:none"></div>
												@endif
												</div>
												</td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
				      	</div>
					 @endforeach
			  </div><!-- /.tab-content -->
			</div><!-- /.collapse in -->
</div>