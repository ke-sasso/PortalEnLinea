<div class="container-fluid the-box">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="table-responsive">
        <table class="table table-hover table-sm" id="principiosactivos">
            <caption><b>AGREGAR PRINCIPIOS ACTIVOS</b></caption>
                        <caption>
                            <label class="radio-inline"> POSEE PANTENTES</label>
                            @if($solicitud->solicitudesDetalle->poseePatentes!=null || $solicitud->solicitudesDetalle->poseePatentes!='')
                                <label class="radio-inline">
                                  <input type="radio" name="patente" id="patente" value="1" @if($solicitud->solicitudesDetalle->poseePatentes==1) checked @endif  form="RegistroStep1y2">SI
                                </label>
                                <label class="radio-inline">
                                 <input type="radio" name="patente" id="patente" value="0" @if($solicitud->solicitudesDetalle->poseePatentes==0) checked @endif form="RegistroStep1y2">NO
                                </label>
                            @else
                                <label class="radio-inline">
                                  <input type="radio" name="patente" id="patente" value="1" form="RegistroStep1y2">SI
                                </label>
                                <label class="radio-inline">
                                 <input type="radio" name="patente" id="patente" value="0"  form="RegistroStep1y2">NO
                                </label>
                            @endif
            </caption>
          <thead>
            <tr>
              <th>NOMBRE</th>
              <th>CONCENTRACIÓN</th>
              <th>U/M</th>
              <th>OPCIONES</th>
            </tr>
          </thead>
          <tbody>
            @if ($solicitud->principiosactivos!=null)
              @foreach ($solicitud->principiosactivos as $principio)
                <tr>
                  <input form="RegistroStep1y2" type="hidden" name="idMateriasP[]" value="{{$principio->idMateriaPrima}}">
                  <input form="RegistroStep1y2" type="hidden" name="nombreUnidad[]" value="{{$principio->nombreUnidadMedida}}">
                  <input form="RegistroStep1y2" type="hidden" name="nombreMateria[]" value="{{$principio->nombreMateriaPrima}}">
                  <td>{{$principio->nombreMateriaPrima}}</td>
                  <td><input form="RegistroStep1y2" type="hidden" name="concentracion[]" value="{{$principio->concentracion}}">{{$principio->concentracion}}</td>
                  <td><input form="RegistroStep1y2" type="hidden" name="idUnidadesM[]" value="{{$principio->unidadMedida}}"> {{$principio->nombreUnidadMedida}}</td>
                  <td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                </tr>
              @endforeach
            @endif
          </tbody>
          <tfoot id="plusPresent">
            <tr>
              <th colspan="4" class="text-right">
                <span class="btn btn-primary" id="btnAddPrincipio"><i class="fa fa-plus"></i></span>
              </th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
</div>

<div class="modal fade" id="dlgAddPinicipio"  role="dialog" aria-labelledby="DefaultModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close btn-dark" style="opacity: 1 !important; color: #ffffff;"  data-dismiss="modal" ><b>&times;</b></button>
        <h3 align="center" class="modal-title" id="DefaultModalLabel">Principios activos</h3>
      </div>
      <div class="modal-body">
        <h5 align="center" ><b>Seleccione una materia prima y digite la concentración con su unidad de medida</b></h5>
        <form class="form-inline" id="PrincipiosForm" role="form" data-toggle="validator">

          <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">Principios activos</div>
                <select name="materiaPrima" id="materiaPrima" class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true" required>
                </select>
              </div>
              <div class="help-block with-errors"></div>
            </div>
          </div>
          <div class="col-xs-8 col-sm-8 col-md-5 col-lg-5">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><b>Concentración</b></span>
                <input type="number" id="concentracion" name="concentracion" required="required" class="form-control" value="0" step="0.01">
                <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                <select id="unidadmedida" name="unidadmedida" style="width:140px !important;" class="form-control select2-single" aria-hidden="true" required>
                </select>
              </div>
            </div>
            <div class="help-block with-errors"></div>
          </div>

        </form>
        <br>
      </div>
      <div class="modal-footer" align="center">
        <button type="button" id="btnAddPrincipioA" class="btn btn-primary">Agregar</button>
      </div><!-- /.modal-footer -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-doalog -->
</div><!-- /#DefaultModal -->
