<h4>
  <span class="label label-primary">
    <strong>Ingrese el mandamiento de pago!</strong>
  </span>
</h4>
<div id="form-step-0" role="form" data-toggle="validator">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="input-group ">
                            <div class="input-group-addon" for="mandamiento"><b>Mandamiento de pago:</b></div>
                            <input type="number" readonly class="form-control" id="mandamiento" name="mandamiento" autocomplete="off" required form="RegistroStep1y2" value="{{$solicitud->mandamiento}}">
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <div class="input-group ">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-8">
                        <div class="input-group ">
                            <label> <smal>Recuerde que el monto del mandamiento de pago es diferente si el Fabricante principal del producto es nacional o extranjero, y que una vez haya pagado su mandamiento tendrá que esperar 24 horas para que sea valido en el sistema!</smal>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
</div>

<!-- <a href="#" id="speak" class="waves-effect waves-light btn btn-dark"><i class="fa fa-play" aria-hidden="true"></i></a> -->
