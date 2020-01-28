<div class="tab-pane fade" id="panel-dtpublicidad">  
          <div class="panel panel-success">
                  <div class="panel-heading">
                    <h3 class="panel-title">MANDAMIENTO</h3>
                  </div>
                  <div class="panel-body">                    
                    <table width="100%" class="table table-stripped table-hover">                      
                      <tr>
                      <td style="border-bottom: 1px solid #ddd;">
                        <div class="checkbox">
                          <label>
                          
                            <input type="checkbox"  checked name="tipoDocumento[]" value="" >
                          
                          <div class="input-group col-md-10 col-lg-8" >
                            <div class="input-group-addon">MANDAMIENTO CANCELADO POR DERECHOS DE TRÁMITE</div>
                            @if($estado==11)
                                <input type="number" class="form-control" id="num_mandamiento" name="num_mandamiento" value="{{$tramitespub->numMandamiento}}" readonly>
                            @else
                                <input type="number" class="form-control" id="num_mandamiento" name="num_mandamiento" value="" required >
                            @endif
                          </div>                          
                          <div align="right">
                            <button  type="button" name="validar" id="validar" class="btn btn-primary btn-perspective">Validar</button>
                          </div>                          
                          </label>
                        </div>
                      </td>
                      </tr>                       
                    </table>                                        
                    @if($estado!=11)
                      <h5 style="color: #EC2929;">*Debe validar el mandamiento de pago antes de ingresar los datos de publicidad.</h5>
                    @endif
                  </div>
          </div>  

          <div class="panel panel-success" id="divPublicidad">
                  <div class="panel-heading">
                    <h3 class="panel-title">PUBLICIDAD</h3>
                  </div>
                  
                  <div class="panel-body">
                    <div class="form-group">
                      <div class="input-group col-sm-12 col-md-6 col-lg-6">
                        <div class="input-group-addon">MEDIO: </div>
                        <select class="form-control" name="idMedio" id="idMedio">
                          @if($estado==11)
                            <option value="{{$detPub->idMedio}}" selected readonly>{{$detPub->nombreMedio}}</option>
                            @if($detPub->idMedio==6)
                              <option value="4">VALLA PUBLICITARIA</option>
                            @elseif($detPub->idMedio==4)
                              <option value="6">CARTELES Y SIMILARES</option>
                            @endif
                          @else
                            @foreach($medios as $medio)
                              <option value="{{$medio->idMedio}}" required>{{$medio->nombreMedio}}</option>
                            @endforeach
                          
                        </select>

                         <select class="form-control" name="idMedio1" id="idMedio1">
                            
                            <option value="6" required>CARTELES Y SIMILARES</option>
                          @endif
                        </select>
                        
                      </div>
                      
                    </div>
                    <div class="form-group">
                      <div class="input-group col-sm-12 col-md-12 col-lg-12">
                        <div class="input-group-addon">VERSI&Oacute;N:</div>
                        @if($estado==11)
                            <input type="text" class="form-control text-uppercase" id="txtVersion" name="version" value="{{strtoupper($detPub->version)}}" required>
                        @else
                            <input type="text" class="form-control text-uppercase" id="txtVersion" name="version" value="{{old('version')}}" required>
                        @endif
                      </div>
                    </div>
                 
                    
                      <div class="form-group">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>Arte Publicitario</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                <div class="form-group ">
                                     {!! Form::label('file1', 'Seleccione el Arte', ['class' => 'control-label']) !!}
                                     <input type="file" name="files[]" multiple required>
                                </div>
                              </td>
                            </tr>                 
                          </tbody>
                        </table>
                      </div>
                    
                    
                  </div>
                  
                </div>
                  
                
                <br>
                  
                


         <div class="panel-footer" id="footer">
                      <div class="from-group" align="center">
                      
                      <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" />
                      <button type="button" id="guardarSoli"  class="btn btn-primary btn-perspective">Enviar</button>
                      </div>

        </div>
</div>


