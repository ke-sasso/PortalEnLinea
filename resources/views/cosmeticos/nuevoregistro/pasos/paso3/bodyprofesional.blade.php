
<input type="hidden" value="{!! $profesional->ID_PROFESIONAL !!}" name="idProfesional" required form="CosPreStep3">
<div class="form-group row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="input-group">
            <div class="input-group-addon"><b>NIT:</b></div>
            <label class="form-control">{!! $profesional->NIT !!}</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="input-group">
            <div class="input-group-addon"><b>DUI:</b></div>
            <label class="form-control">{!! $profesional->DUI !!}</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="input-group">
            <div class="input-group-addon"><b>J.V.P.Q.F:</b></div>
            <label class="form-control">{!! $profesional->CORRELATIVO !!}</label>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="input-group">
            <div class="input-group-addon"><b>Nombres:</b></div>
            <label class="form-control">{!! $profesional->NOMBRES !!}</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="input-group">
            <div class="input-group-addon"><b>Apellidos:</b></div>
            <label class="form-control" >{!! $profesional->APELLIDOS !!}</label>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4 col-md-6">
            <div class="input-group ">
                <div class="input-group-addon" for="telefonoProp"><b>Telefono:</b></div>
                <label class="form-control">{!! $profesional->TELEFONO_1 !!}</label>
            </div>
        </div>
        <div class="col-sm-4 col-md-6">
            <div class="input-group ">
                <div class="input-group-addon" for="correoProp"><b>Correo:</b></div>
                <label class="form-control">{!! $profesional->EMAIL !!}</label>
            </div>
        </div>
    </div>
</div>