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
									@if(count($soli->documentos)>0)
											@if(in_array($doc->requisito_documento->idItem,$itemsDoc))

												   <a class="btn btn-info" href="{{route('registrovisado.showdocumento',['idDocumento' => Crypt::encrypt($soli->documentos()->where('idItemRequisitoDoc',$doc->requisito_documento->idItem)->first()->idDoc)])}}" target="_blank">Ver documento del requisito<i class="fa fa-download" aria-hidden="true"></i></a>
												   <button onclick="cambiar(this);" name="btnEliminarDoc" id="btnEliminarDoc" title="Eliminar Documento" data-nomdoc="{{$doc->nomDocumento}}" data-id="{{$doc->requisito_documento->idItem}}" data-obligatorio="{{$doc->requisito_documento->requerido}}"  data-sintesis="{{$doc->requisito_documento->sintesis}}" data-biologico="{{$doc->requisito_documento->biologico}}" data-biotecnologico="{{$doc->requisito_documento->biotecnologico}}" data-suplementos="{{$doc->requisito_documento->suplementos}}" data-naturales="{{$doc->requisito_documento->naturales}}" data-homeopatico="{{$doc->requisito_documento->homeopatico}}" data-radiofarmaco="{{$doc->requisito_documento->radiofarmaco}}" data-gasesmedicinales="{{$doc->requisito_documento->gasesmedicinales}}" data-innovador="{{$doc->requisito_documento->innovador}}" data-recoextranjero="{{$doc->requisito_documento->recoextranjero}}" data-generico="{{$doc->requisito_documento->generico}}" data-vacuna="{{$doc->requisito_documento->vacuna}}" class="btn btn-danger btnEliminarDoc"><i class="fa fa-trash" aria-hidden="true"></i></button>
												   <input type="hidden" value="{{$soli->documentos()->where('idItemRequisitoDoc',$doc->requisito_documento->idItem)->first()->idDoc}}" name="docGuardado[]" form="RegistroPreStep10">

										   @else
														@if($doc->requisito_documento->requerido==1)
														<div class="file-loading">
														 <input id="{{'file'.$doc->requisito_documento->idItem}}" name="file-es[{{$doc->requisito_documento->idItem}}]"  required="true" data-obligatorio="{{$doc->requisito_documento->requerido}}" data-sintesis="{{$doc->requisito_documento->sintesis}}" data-biologico="{{$doc->requisito_documento->biologico}}" data-biotecnologico="{{$doc->requisito_documento->biotecnologico}}" data-suplementos="{{$doc->requisito_documento->suplementos}}" data-naturales="{{$doc->requisito_documento->naturales}}" data-homeopatico="{{$doc->requisito_documento->homeopatico}}" data-radiofarmaco="{{$doc->requisito_documento->radiofarmaco}}" data-gasesmedicinales="{{$doc->requisito_documento->gasesmedicinales}}" data-innovador="{{$doc->requisito_documento->innovador}}" data-recoextranjero="{{$doc->requisito_documento->recoextranjero}}" data-generico="{{$doc->requisito_documento->generico}}" data-vacuna="{{$doc->requisito_documento->vacuna}}" form="RegistroPreStep10" type="file" accept="application/pdf">
														 </div>
														    <span class="label label-warning">Documento obligatorio @if($doc->requisito_documento->idItem==10 || $doc->requisito_documento->idItem==21) <b>Excepto para productos Naturales, Homeopáticos, Suplementos Nutricionales, Vacunas @if($doc->requisito_documento->idItem==10)y Gases medicinales.@endif</b> @endif  @if($doc->requisito_documento->idItem==7 || $doc->requisito_documento->idItem==18) Excepto para productos de origen Reconocimiento Mutuo Centroamericano @endif </span>
														@else
															<div class="file-loading">
															 <input id="{{'file'.$doc->requisito_documento->idItem}}" name="file-es[{{$doc->requisito_documento->idItem}}]"  data-obligatorio="{{$doc->requisito_documento->requerido}}" data-sintesis="{{$doc->requisito_documento->sintesis}}" data-biologico="{{$doc->requisito_documento->biologico}}" data-biotecnologico="{{$doc->requisito_documento->biotecnologico}}" data-suplementos="{{$doc->requisito_documento->suplementos}}" data-naturales="{{$doc->requisito_documento->naturales}}" data-homeopatico="{{$doc->requisito_documento->homeopatico}}" data-radiofarmaco="{{$doc->requisito_documento->radiofarmaco}}" data-gasesmedicinales="{{$doc->requisito_documento->gasesmedicinales}}" data-innovador="{{$doc->requisito_documento->innovador}}" data-recoextranjero="{{$doc->requisito_documento->recoextranjero}}"  data-generico="{{$doc->requisito_documento->generico}}" data-vacuna="{{$doc->requisito_documento->vacuna}}"  form="RegistroPreStep10" type="file" accept="application/pdf">
															 </div>

														@endif
														<div id="kv-success-{{'file'.$doc->requisito_documento->idItem}}"  style="display:none"></div>
														<div id="kv-error-{{'file'.$doc->requisito_documento->idItem}}"  style="display:none"></div>

										  @endif
									@else

														@if($doc->requisito_documento->requerido==1)
														 <div class="file-loading">
														 <input id="{{'file'.$doc->requisito_documento->idItem}}" name="file-es[{{$doc->requisito_documento->idItem}}]"  required="true" data-obligatorio="{{$doc->requisito_documento->requerido}}" data-sintesis="{{$doc->requisito_documento->sintesis}}" data-biologico="{{$doc->requisito_documento->biologico}}" data-biotecnologico="{{$doc->requisito_documento->biotecnologico}}" data-suplementos="{{$doc->requisito_documento->suplementos}}" data-naturales="{{$doc->requisito_documento->naturales}}" data-homeopatico="{{$doc->requisito_documento->homeopatico}}" data-radiofarmaco="{{$doc->requisito_documento->radiofarmaco}}" data-gasesmedicinales="{{$doc->requisito_documento->gasesmedicinales}}" data-innovador="{{$doc->requisito_documento->innovador}}" data-recoextranjero="{{$doc->requisito_documento->recoextranjero}}"  data-generico="{{$doc->requisito_documento->generico}}"  data-vacuna="{{$doc->requisito_documento->vacuna}}" form="RegistroPreStep10" type="file" accept="application/pdf">
														 </div>
														    <span class="label label-warning">Documento obligatorio @if($doc->requisito_documento->idItem==10 || $doc->requisito_documento->idItem==21) <b>Excepto para productos Naturales, Homeopáticos, Suplementos Nutricionales, Vacunas @if($doc->requisito_documento->idItem==10)y Gases medicinales.@endif</b> @endif @if($doc->requisito_documento->idItem==7 || $doc->requisito_documento->idItem==18) Excepto para productos de origen Reconocimiento Mutuo Centroamericano @endif</span>
														@else
														 <div class="file-loading">
															 <input id="{{'file'.$doc->requisito_documento->idItem}}" name="file-es[{{$doc->requisito_documento->idItem}}]"  data-obligatorio="{{$doc->requisito_documento->requerido}}"  data-sintesis="{{$doc->requisito_documento->sintesis}}" data-biologico="{{$doc->requisito_documento->biologico}}" data-biotecnologico="{{$doc->requisito_documento->biotecnologico}}" data-suplementos="{{$doc->requisito_documento->suplementos}}" data-naturales="{{$doc->requisito_documento->naturales}}" data-homeopatico="{{$doc->requisito_documento->homeopatico}}" data-radiofarmaco="{{$doc->requisito_documento->radiofarmaco}}" data-gasesmedicinales="{{$doc->requisito_documento->gasesmedicinales}}" data-innovador="{{$doc->requisito_documento->innovador}}" data-recoextranjero="{{$doc->requisito_documento->recoextranjero}}" data-generico="{{$doc->requisito_documento->generico}}"  data-vacuna="{{$doc->requisito_documento->vacuna}}"  form="RegistroPreStep10" type="file" accept="application/pdf">
														  </div>

														@endif
														<div id="kv-success-{{'file'.$doc->requisito_documento->idItem}}"  style="display:none"></div>
														<div id="kv-error-{{'file'.$doc->requisito_documento->idItem}}"  style="display:none"></div>

									@endif
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