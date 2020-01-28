@if($propietario->tipoTitular==1 || $propietario->tipoTitular==2)
    <input type="hidden" name="titular" value="{{$propietario->nit}}" required form="CosPreStep3">
@elseif($propietario->tipoTitular==3)
    <input type="hidden" name="titular" value="{{$propietario->idPropietario}}" required form="CosPreStep3">
@endif


<div class="form-group">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="input-group ">
                <div class="input-group-addon" for="nomProp"><b>Nombre titular del producto:</b></div>
                <label class="form-control">{!! $propietario->nombre !!}</label>
            </div>
        </div>
    </div>
</div>
@if($propietario->tipoTitular==1 || $propietario->tipoTitular==2)
<div class="form-group">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="input-group ">
                <div class="input-group-addon" for="nitProp"><b>NIT:</b></div>
                @if(!empty($propietario->nit))
                    <label class="form-control">{!! $propietario->nit !!}</label>
                @else
                    <label class="form-control">N/A</label>
                @endif
            </div>
        </div>
        @if($propietario->tipoTitular==1)
            @if($propietario->numeroDocumento=!null)
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="input-group ">
                        <div class="input-group-addon" for="duiProp"><b>DUI:</b></div>
                        @if(!empty($dui))
                            <label class="form-control">{{ $dui }}</label>
                        @else
                            <label class="form-control">N/A</label>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endif
<div class="form-group">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="input-group ">
                <div class="input-group-addon" for="direccProp"><b>Domicilio principal:</b></div>
                @if(!empty($propietario->direccion))
                    <label class="form-control"><small>{!! $propietario->direccion !!}</small></label>
                @else
                    <label class="form-control">N/A</label>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        @if($propietario->tipoTitular==3)
            <div class="col-sm-4 col-md-4">
                <div class="input-group ">
                    <div class="input-group-addon" for="paisProp"><b>País:</b></div>
                    <label class="form-control">{!! $propietario->PAIS!!}</label>
                </div>
            </div>
        @endif
        <div class="col-sm-4 col-md-4">
            <div class="input-group ">
                <div class="input-group-addon" for="correoProp"><b>Correo:</b></div>
                @if(!empty($propietario->emailsContacto))
                    <label class="form-control">{!! $propietario->emailsContacto !!}</label>
                @else
                    <label class="form-control">N/A</label>
                @endif

            </div>
        </div>
         <div class="col-sm-4 col-md-4">
            <div class="input-group ">
                <div class="input-group-addon" for="correoProp"><b>Telefonos:</b></div>
                @if(!empty($propietario->telefonosContacto))
                    <label class="form-control">{{str_replace(array("[","]","\""),"",$propietario->telefonosContacto)}}</label>
                @else
                    <label class="form-control">N/A</label>
                @endif

            </div>
        </div>
    </div>
</div>