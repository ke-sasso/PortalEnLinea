<div class="container-fluid the-box">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="table-responsive">
        <table class="table table-hover table-sm" id="vidautilempaque">
            <caption> @if(!empty($campos)) @if(in_array('vidaUtil', $campos))
                       <a class="btn btn-danger btn-perspective btn-xs vals" id="vidaUtil-val"><i class="fa  fa-times"></i></a>
                       <input form="RegistroStep1y2" type="hidden" class="form-control" id="vidaUtil2" name="vidaUtil2" value="{{Crypt::encrypt('vidaUtil2') }}" />
                      @endif @endif<b>AGREGAR VIDA UTIL</b></caption>
          <thead>
            <tr>
              <th>EMPAQUE PRIMARIO</th>
              <th>MATERIAL</th>
              <th>VIDA UTIL</th>
              <th>COLOR</th>
              <th>OBSERVACIÓN</th>
              @if(!empty($campos)) @if(in_array('vidaUtil', $campos))<th>OPCIONES</th> @endif @endif
            </tr>
          </thead>
          <tbody>
            @if($solicitud->vidaUtil!=null)
              @foreach($solicitud->vidaUtil as $vida)
              <tr>
                <input form="RegistroStep1y2" type="hidden" name="idEmpaquevida[]" value="{{$vida->empaquePrimario}}"/><input form="RegistroStep1y2" type="hidden" name="nombreEmpaquevida[]" value="{{$vida->nombrePrimario}}"/>
                <td>{{$vida->nombrePrimario}}</td>
                <td><input form="RegistroStep1y2" type="hidden" name="materialVidaId[]" value="{{$vida->idMaterial}}"><input form="RegistroStep1y2" type="hidden" name="materialnombreVidaId[]" value="{{$vida->nombreMaterial}}">{{$vida->nombreMaterial}}</td>
                <td><input form="RegistroStep1y2" type="hidden" name="utilvida[]" value="{{$vida->vidaUtil}}"/>
                    <input form="RegistroStep1y2" type="hidden" name="idperiodo[]" value="{{$vida->idPeriodo}}"/>
                  {{$vida->vidaUtil}} ({{$vida->tipoperiodo->periodo}})
                </td>
                <td><input name="idcolorvida[]" type="hidden" form="RegistroStep1y2"  value="{{$vida->idColor}}"/>
                    <input name="nombrecolorvida[]" type="hidden" form="RegistroStep1y2"  value="{{$vida->nombreColor}}"/>
                    {{$vida->nombreColor}}</td>
                <td><input form="RegistroStep1y2" type="hidden" name="observacionvida[]" value="{{$vida->observacion}}"/>{{$vida->observacion}}</td>
                @if(!empty($campos)) @if(in_array('vidaUtil', $campos)) <td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td> @endif @endif
              </tr>
              @endforeach
            @endif

          </tbody>
          <tfoot id="plusPresent">
            <tr>
              @if(!empty($campos)) @if(in_array('vidaUtil', $campos))
              <th colspan="6" class="text-right">
                <span class="btn btn-primary" id="btnAddVidaUtil"><i class="fa fa-plus"></i></span>
              </th>
              @endif @endif
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
</div>

<div class="modal fade" id="dlgAddVidaUtil"  role="dialog" aria-labelledby="DefaultModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close btn-dark" style="opacity: 1 !important; color: #ffffff;"  data-dismiss="modal" ><b>&times;</b></button>
        <h3 align="center" class="modal-title" id="DefaultModalLabel">Vida util</h3>
      </div>
      <div class="modal-body">
        <h5 align="center" ><b>Seleccione un empaque primario y  material, además digite  su vida util</b></h5>
        <form class="form-inline" id="VidaUtilForm" role="form">

          <div class="form-group">
            <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="input-group">
                      <div class="input-group-addon">Empaque primario</div>
                      <select name="empaquevida" id="empaquevida" class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true">
                      </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="input-group">
                      <div class="input-group-addon">Material</div>
                      <select name="materialvida" id="materialvida" class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true">
                      </select>
                    </div>
                </div>
            </div>
          </div>
          <br><br>
          <div class="form-group">
            <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                      <div class="input-group">
                        <div class="input-group-addon">Color</div>
                        <select name="colorvida" id="colorvida" class="form-control select2-single select2-hidden-accessible" style="width:100%;" aria-hidden="true">
                        </select>
                      </div>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                      <div class="input-group">
                        <span class="input-group-addon">Vida útil</span>
                        <input type="number" id="utilvida" name="utilvida"  class="form-control" value="0" min="0" step="1">
                         <select id="idperiodvida" name="idperiodvida" class="form-control">
                                <option value="1" selected>MESES</option>
                                <option value="2">DÍAS</option>
                        </select>
                      </div>
                  </div>
          </div>
        </div>
        <br><br>
        <div class="form-group">
            <div class="row">
                   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="input-group">
                        <div class="input-group-addon">Observación</div>
                         <textarea name="observacionvida" id="observacionvida" class="form-control text-uppercase" placeholder="Digite la observación si es necesaria para la vida util del empaque"></textarea>
                      </div>
                  </div>
          </div>
        </div>


        </form>
        <br>
      </div>
      <div class="modal-footer" align="center">
        <button type="button" id="btnAddVidaUtilTable" class="btn btn-primary">Agregar</button>
      </div><!-- /.modal-footer -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-doalog -->
</div><!-- /#DefaultModal -->