<script type="text/javascript">



    function dataFillStep2(){

        var idArea;
        var idClasi;

        $('body').modalmanager('loading');
        hide = function(){
            $('body').modalmanager('loading');
        };
        setTimeout(hide, 6000); // 5 seconds

        if(!$('#marca').val() || $('#marca option').length > 0){
            setTimeout(marca, 1500);
        }



        function marca(){

            @if(!is_null($solicitud->solicitudesDetalle->idMarca))
                var idMarca = '{{$solicitud->solicitudesDetalle->idMarca}}';
                $('#marca option').remove();
                //carga de marcas
                var fecthMarcas = function () {

                    $.ajax({
                        url: config.routes[0].marcas + '?idMarca=' + idMarca,
                        type: 'get',
                        success: function (r) {
                            if (r.status == 200) {
                                $('#marca').append(r.data);
                            }
                            else if (r.status == 400) {
                                //alertify.alert("Mensaje de sistema","Se presentan fallos con el sistema ");
                                console.warn(r.message);
                            } else {//Unknown
                                //alertify.alert("Mensaje de sistema",r.message);
                                console.warn("No se han podido cargar las marcas de cosmeticos");
                            }
                        },
                        error: function (data) {
                            console.error(data.responseJSON.message);
                            setTimeout(function () {
                                fecthMarcas();
                            }, 4000)
                        }
                    });
                }

                fecthMarcas();

            @endif
        }


        @if($solicitud->tipoSolicitud==2 || $solicitud->tipoSolicitud==3)
            if(!$('#areaApli').val()){
                setTimeout(area, 2000);
            }
            function area(){
                @if(!empty($solicitud->solicitudesDetalle->detallesCosmetico))
                    @if(!is_null($solicitud->solicitudesDetalle->detallesCosmetico->idClasificacion))
                        idArea='{{$solicitud->solicitudesDetalle->detallesCosmetico->clasificacion->idArea}}';
                        $('#areaApli option').remove();
                        var fecthAreas =function() {
                            $.ajax({
                                url: config.routes[0].areaaplicacion + '?idArea=' + idArea,
                                type: 'get',
                                success: function (r) {
                                    if (r.status == 200) {
                                        $('#areaApli').append(r.data);
                                    }
                                    else if (r.status == 400) {
                                        console.warn(r.message);
                                    } else {//Unknown
                                        console.warn("No se han podido cargar las areas de aplicación");
                                    }
                                },
                                error: function (data) {
                                    console.error(data.responseJSON.message);
                                    setTimeout(function () {
                                        fecthAreas();
                                    }, 3000)
                                }
                            });
                        }
                        fecthAreas();
                    @endif
                @endif
            }

            if(!$('#clasificacion').val()){
                setTimeout(clasi, 3000);
            }

            function clasi(){
                @if(!empty($solicitud->solicitudesDetalle->detallesCosmetico))
                    @if(!is_null($solicitud->solicitudesDetalle->detallesCosmetico->idClasificacion))

                            idClasi='{{$solicitud->solicitudesDetalle->detallesCosmetico->idClasificacion}}';
                            $('#clasificacion').empty();
                            var fecthClasificacion = function () {
                                $.ajax({
                                    url: config.routes[0].clasificaciones + '?idArea=' + idArea +'&idClasi='+idClasi,
                                    type: 'GET',
                                    success: function (r) {
                                        if (r.status == 200) {
                                            $('#clasificacion').append(r.data);
                                            //fragancia
                                            if($("#clasificacion option:selected").attr('data-fragancia')==1){
                                                $('#fragancias').show()
                                                $("input .fragancias").attr("required", "true");
                                            }
                                            //tono
                                            if($("#clasificacion option:selected").attr('data-tono')==1){
                                                $('#tonos').show();
                                                $('input[type=text][name=tonos]').prop("required",true);
                                            }

                                        }
                                        else if (r.status == 400) {
                                            console.warn(r.message);
                                            setTimeout(function () {
                                                fecthClasificacion();
                                            }, 2000)
                                        } else {//Unknown
                                            console.warn("No se han podido cargar las clasificaciones");
                                        }
                                    },
                                    error: function (data) {
                                        console.error(data.responseJSON.message);
                                        setTimeout(function () {
                                            fecthClasificacion();
                                        }, 2000)
                                    }
                                });
                            }

                            fecthClasificacion();
                    @endif
                @endif
            }

            if(!$('#formacos').val() && $('#formacos option').length > 0){
                    setTimeout(forma, 3000);
            }
            function forma(){
                @if(!empty($solicitud->solicitudesDetalle->detallesCosmetico))
                    @if(!is_null($solicitud->solicitudesDetalle->detallesCosmetico->idClasificacion))
                            var idForma='{{$solicitud->solicitudesDetalle->detallesCosmetico->idFormaCosmetica}}';
                            console.log(idForma);
                            $('#formacos').empty();

                            var fecthFormaCos = function () {

                                $.ajax({
                                    url: config.routes[0].formas + '?idClasificacion=' + idClasi+'&idForma='+idForma,
                                    type: 'GET',
                                    success: function (r) {
                                        if (r.status == 200) {

                                            $('#formacos').append(r.data);
                                        }
                                        else if (r.status == 400) {
                                            console.warn(r.message);
                                            setTimeout(function () {
                                                fecthFormaCos();
                                            }, 2000)
                                        } else {//Unknown
                                            console.warn("No se han podido cargar las clasificaciones");
                                        }
                                    },
                                    error: function (data) {
                                        console.error(data.responseJSON.message);
                                        setTimeout(function () {
                                            fecthFormaCos();
                                        }, 2000)
                                    }
                                });
                            }
                            if(!$('#formacos').val()){
                                fecthFormaCos();
                            }
                    @endif
                @endif
            }
        @endif


        @if(count($solicitud->fragancias)>0)
            $('#fraganciastb >tbody>tr').not($('#fraganciastb >tbody>tr:first')).remove();
            @for($i=0;$i<count($solicitud->fragancias);$i++)
                @if($i==0)
                    $("#fraganciastb >tbody>tr:first").find("input").val('{{$solicitud->fragancias[$i]->fragancia}}');
                @else
                    $('#fraganciastb tbody').append('<tr><td>'+ {{$i+1}} +'</td><td width="80%"><input  name="fragancias[]" type="text" class="form-control" value="{{$solicitud->fragancias[$i]->fragancia}}" form="CosPreStep1y2"/></td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
                @endif
            @endfor
        @endif


        @if(count($solicitud->tonos)>0)
            $('#tonostb >tbody>tr').not($('#tonostb >tbody>tr:first')).remove();
            @for($i=0;$i<count($solicitud->tonos);$i++)
                @if($i==0)
                    $("#tonostb >tbody>tr:first").find("input").val('{{$solicitud->tonos[$i]->tono}}');
                @else
                    $('#tonostb tbody').append('<tr><td>'+ {{$i+1}} +'</td><td width="80%"><input  name="tonos[]" type="text" class="form-control" value="{{$solicitud->tonos[$i]->tono}}" form="CosPreStep1y2"/></td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
                @endif
            @endfor
        @endif

        @if(isset($solicitud))
            @if($solicitud->tipoSolicitud==3 || $solicitud->tipoSolicitud==5)
                //$('#fechaVen').datepicker({format: 'dd-mm-yyyy'});
                @if(!is_null($solicitud->solicitudesDetalle->idPais) || $solicitud->solicitudesDetalle->idPais!='')
                    $('#paisOrigen').val({{$solicitud->solicitudesDetalle->idPais}}).change();
                @endif
            @endif

            @if($solicitud->tipoSolicitud==4 || $solicitud->tipoSolicitud==5)
                if(!$('#clasificacion').val()){
                    setTimeout(clasi, 3000);
                }
                function clasi(){
                    @if(!empty($solicitud->solicitudesDetalle->detallesHigienicos))
                        @if(!is_null($solicitud->solicitudesDetalle->detallesHigienicos->idClasificacion))
                            $('#clasificacion').val({{$solicitud->solicitudesDetalle->detallesHigienicos->idClasificacion}}).change();
                            $('#clasificacion').change();
                        @endif
                    @endif
                }

            @endif
        @endif


        //$('body').trigger('click');

    }

    function dataFillStep3(){
        @if(!empty($solicitud->solicitudesDetalle))
            @if(!is_null($solicitud->solicitudesDetalle->tipoTitular))
                @if($solicitud->solicitudesDetalle->tipoTitular==1)
                    $('input[name=tipoTitular][value="1"]').attr('checked', true);
                    $('input[name=tipoTitular][value="1"]').change();
                @elseif($solicitud->solicitudesDetalle->tipoTitular==2)
                    $("#inlineRadio2").attr('checked', true);
                    $('input[name=tipoTitular][value="2"]').attr('checked', true);
                    $('input[name=tipoTitular][value="2"]').change();
                @elseif($solicitud->solicitudesDetalle->tipoTitular==3)
                    $('input[name=tipoTitular][value="3"]').attr('checked', true);
                    $('input[name=tipoTitular][value="3"]').change();
                @endif
            @endif
            @if(!is_null($solicitud->solicitudesDetalle->idPoderProfesional) and $soldata->profesional!=null)
                config.flags[0].profesionalvalidado=true;
            @endif
        @endif


    }

    function dataFillStep4(){

        @if(count($solicitud->fabricantes)>0)
            //var value='{{substr($solicitud->fabricantes[0]->idFabricante,0,3)}}';
            var value = '{{$solicitud->fabricantes[0]->tipoFabricante}}';
            if(value==2)
            {
                $('input[name=origenFab][tipo="EXT"]').attr('checked', true);
                $('input[name=origenFab][tipo="EXT"]').change();
            }
            else
            {
                $('input[name=origenFab][tipo="NAC"]').attr('checked', true);
                $('input[name=origenFab][tipo="NAC"]').change();
            }
        @else
                $('input[name=origenFab][tipo="NAC"]').attr('checked', true);
                $('input[name=origenFab][tipo="NAC"]').change();
        @endif

        @if(isset($soldata))
            @if(count($soldata->fabricantes)>0)
                $('#fabricantes >tbody>tr').remove();
                @foreach($soldata->fabricantes as $fabs)
                    $('#fabricantes > tbody:last-child').append('<tr><input type="hidden" name="fabricantes[]" value="{!!$fabs->idEstablecimiento!!}" form="CosPreStep4"><td>{{trim($fabs->nombreComercial)}}</td> <td>{{trim($fabs->direccion)}}</td> <td>{!!$fabs->pais!!}</td><td><button class="btn btn-sm btn-danger btnEliminar" data-idest="{!!$fabs->idEstablecimiento!!}"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
                @endforeach
            @endif
                @if(count($soldata->importadores)>0)
                    $('#importadores >tbody>tr').remove();
                    @foreach($soldata->importadores as $imps)
                        $('#importadores > tbody:last-child').append('<tr><input type="hidden" name="importadores[]" value="{!!$imps->idEstablecimiento!!}" form="CosPreStep4"><td>{{trim($imps->nombreComercial)}}</td> <td>{{trim($imps->direccion)}}</td> <td>{!!$imps->pais!!}</td><td><button class="btn btn-sm btn-danger btnEliminar" data-idEst="{!!$imps->idEstablecimiento!!}"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
                    @endforeach
                @endif
        @endif
    }

    function dataFillStep5(){

        @if(isset($solicitud))
                @if($solicitud->distribuidorTitular==1)
                    $('input[name=distTitu][id=distTitu1]').attr('checked', true);
                    $('input[name=distTitu][id=distTitu1]').change();
                @elseif(count($soldata->distribuidor)>0)
                    $('input[name=distTitu][id=distTitu2]').attr('checked', true);
                    $('input[name=distTitu][id=distTitu2]').change();
                @endif
        @endif

        @if(isset($soldata))
            @if(count($soldata->distribuidor)>0)
                $('#distribuidores >tbody>tr').remove();
                @foreach($soldata->distribuidor as $dist)
                    $('#distribuidores > tbody:last-child').append('<tr><input type="hidden" name="dist[]" value="{!!$dist->ID_DISTRIBUIDOR_MAQUILA.','.$dist->ID_PODER!!}" form="CosPreStep5"><td>{!! $dist->ID_PODER!!}</td><td>{!!$dist->NOMBRE_COMERCIAL!!}</td> <td>{!!trim($dist->DIRECCION)!!}</td><td><button class="btn btn-sm btn-danger btnEliminar" data-idest="{!!$dist->ID_DISTRIBUIDOR_MAQUILA.','.$dist->ID_PODER!!}"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
                @endforeach
            @endif
        @endif
    }


    $('input[type=radio][name=coempaque]').change(function() {
        var coempaque = $(this).val();
        console.log(coempaque);
        if(coempaque==1) {
            $('#detCoemp').show();
            $('#coempRadio2').removeAttr('checked');
            $('#detcoempaque').attr('true');
        }
        else {
            $('#detCoemp').hide();
            $('#coempRadio1').removeAttr('checked');
            $('#detcoempaque').attr('false');
        }
    });

    function dataFillStep6() {


        $.each($('input[type=file]'), function () {
            $('#' + $(this).attr('id')).fileinput({
                theme: 'fa',
                language: 'es',
                allowedFileExtensions: ['pdf'],

            });

        });
    }

</script>
