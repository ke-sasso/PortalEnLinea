<div class="container-fluid the-box">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="presentacion" class="table table-hover">
                <caption><b><u>PRESENTACIONES</u></b></caption>
                <thead>
                <tr>
                    <th>N°</th>
                    <th>PRESENTACION COMPLETA</th>
                    <th width="10%">OPCIONES</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($solicitud))
                    @if(count($solicitud->presentaciones)>0)
                        @for($i=0;$i<count($solicitud->presentaciones);$i++)
                            <tr>
                                <input type="hidden" name="presentaciones[]" value="{{$solicitud->presentaciones[$i]->toJson()}}" form="CosPreStep1y2">
                                <td>{{$i+1}}</td>
                                @if($solicitud->presentaciones[$i]->nombrePresentacion!='')
                                    <td>{{$solicitud->presentaciones[$i]->textoPresentacion .'('.$solicitud->presentaciones[$i]->nombrePresentacion.')' }}</td>
                                @else
                                    <td>{{$solicitud->presentaciones[$i]->textoPresentacion}}</td>
                                @endif
                                <td><button class="btn btn-sm btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                            </tr>
                        @endfor
                    @endif
                @endif
                </tbody>
                <tfoot id="plusPresent">
                <tr>
                    <th colspan="4" class="text-right">
                        <span class="btn btn-primary" id="btnAddPresentacion"><i class="fa fa-plus"></i></span>
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>