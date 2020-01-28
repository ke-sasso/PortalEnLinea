<div class="tab-pane fade in active" id="panel-dtsolicitud">

              <div class="from-group" id="producto">
                   
                    <button type="button" id="btnBuscarProducto" class="btn btn-primary btn-perspective" data-toggle="modal">Seleccionar Productos<i class="fa fa-plus"></i></button>
              </div>

                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h3 class="panel-title">PRODUCTOS</h3>
                  </div>
                  <div class="panel-body">
                    <div class="table-responsive">
                      <table id="vwProductos" style="font-size: 12px;" class="table table-hover">
                        <thead>
                          <tr>
                            <th># Registro</th>
                            <th>Nombre Comercial</th>
                            <th>Modalidad de Venta</th>
                            <th>Vigencia Hasta</th>
                            <th>Ultima Renovacion</th>
                            <th>Titular</th>
                            <th>Opciones</th>
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
                                      <td><input type='hidden' name='idEstablecimiento[]' value="{{$prod->idPropietario}}"/>{{$prod->titular}}</td></td>
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