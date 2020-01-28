<div class="tab-pane fade in active" id="panel-dtsolicitud">
      
                  <div class="panel panel-success">
                    <div class="panel-heading" >
                      <h3 class="panel-title">ESTABLECIMIENTO</h3>
                    </div>
                    <div class="panel-body">
                       
                        <div class="input-group col-xs-12 col-sm-12 col-md-6 col-lg-8">
                          <div> 
                            <label>Seleccione el Solicitante:</label>
                            <select class="form-control" name="establecimiento" id="establecimiento">
                              <option value="0"></option>
                              @if($establecimientos!=null)
                              @foreach($establecimientos as $est)
                                <option value="{{$est->idEstablecimiento}}" required>{{$est->nombreComercial}}</option>
                              @endforeach
                              @else
                                <option value="{{$tramitespub->idEstablecimiento}}" selected>{{$tramitespub->nombreEstablecimiento}}</option>
                              @endif
                            </select>

                          </div>  
                        </div> 
                        <br>
                      
                      <div class="form-group">
                        <div class="input-group col-sm-12 col-md-12 col-lg-6">
                          <div class="input-group-addon">N° Registro:</div>
                          @if($estado==11)
                              <input type="text" class="form-control text-uppercase" id="numregistro" name="numregistro" value="{{$tramitespub->idEstablecimiento}}" readonly>
                          @else  
                              <input type="text" class="form-control text-uppercase" id="numregistro" name="numregistro" value="{{old('numregistro')}}" readonly>
                          @endif
                        </div>
                      </div>
                     
                     <div class="form-group">
                        <div class="input-group col-sm-12 col-md-12 col-lg-11">
                          <div class="input-group-addon">Nombre Comercial:</div>
                          @if($estado==11)
                              <input type="text" class="form-control text-uppercase" id="nomComercial" name="nomComercial" value="{{$tramitespub->nombreEstablecimiento}}" readonly>
                          @else
                              <input type="text" class="form-control text-uppercase" id="nomComercial" name="nomComercial" value="{{old('nomComercial')}}" readonly>
                          @endif
                          
                        </div>
                    </div>


                    </div>
              </div>
              <br>
              <div class="from-group" id="btn-productos">
                   
                    <button type="button" id="btnBuscarEstablecimiento" class="btn btn-primary btn-perspective" data-toggle="modal">Seleccionar Productos<i class="fa fa-plus"></i></button>
              </div>

                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h3 class="panel-title">PRODUCTOS</h3>
                  </div>
                  <div class="panel-body">
                    <div class="table-responsive">
                      <table id="vwProductos" style="font-size: 12px;" class="table table-hover table-fixed">
                        <thead>
                          <tr>
                            <th width="15"># Registro</th>
                            <th width="500">Nombre Comercial</th>
                            <th>Modalidad Venta</th>
                            <th>Vigencia Hasta</th>
                            <th>Ultima</th>
                            
                          </tr>
                        </thead>
                        
                        <tbody>
                            @if(count($productos)>=1)
                              @foreach($productos as $prod)
                                  <tr>
                                      <td><input type='hidden' name='pTableData[]' value="{{$prod->numRegistro}}"/>{{$prod->numRegistro}}</td>
                                      <td>{{$prod->nombreComercial}}</td>
                                      <td>{{$prod->nombre_modalidad_venta}}</td>
                                      <td>{{$prod->vigenciaProducto}}</td>
                                      <td>{{$prod->renovacionProducto}}</td>
                                      <td><button type="button" data="{{$prod->numRegistro}}" id="removeprod" class="removebutton btn btn-danger"><i class="fa fa-trash-o"></i></button></td>
                                  </tr>
                              @endforeach

                            @endif

                        </tbody>
                      </table>
                    </div>
                  </div>
              </div>

              <br>
                  
          
</div>