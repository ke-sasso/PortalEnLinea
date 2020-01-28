<script type="text/javascript">
/**Carga tipo medicamento*/
  @if ($solicitud->solicitudesDetalle->tipoMedicamento!=null)
    var tipoMedicamento = '{{$solicitud->solicitudesDetalle->tipoMedicamento}}';
    var tipoSelect =   $('#tipoMedicamento').select2({
        ajax: {
            theme: "classic",
            url: config.routes[0].tiposmedicamentos,
            dataType: 'json',
            delay: 250,
            dropdownAutoWidth : false,
            width: 'resolve',
            processResults: function (data) {
                if(data.status==200){
                    return {
                        results:  $.map(data.data, function (item) {

                            return {
                                text: item.nomTipo,
                                id: item.tipoMed
                            }

                        })
                    };

                }
                else{
                    console.error(data.message);
                }
            },
            cache: true
        }
    });

    var fecthtiposmed = function () {
        $.ajax({
            type: 'GET',
            url: config.routes[0].tiposmedicamentos,
            error: function (data) {
                console.error(data.responseJSON.message);
                setTimeout(function () {
                    fecthtiposmed();
                }, 2000)
            }
        }).then(function (resp) {
            var option = '';
            $.each(resp.data, function (i, item) {
                if (item.tipoMed == tipoMedicamento) {
                    option = new Option(item.nomTipo, item.tipoMed, true, true);

                }
            })
            tipoSelect.append(option).trigger('change');
            tipoSelect.trigger({
                type: 'select2:select',
                params: {
                    data: resp.data
                }
            });
        });
    }

    fecthtiposmed();

  @endif
  /*Carga forma farmaceutica*/
  @if ($solicitud->solicitudesDetalle->formaFarmaceutica!=null)
  var formaFarmaceutica = '{{$solicitud->solicitudesDetalle->formaFarmaceutica}}';
  var formaSelect = $('#formaFarm').select2({
      ajax: {
          url: config.routes[0].formasfarmaceuticas,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
              if(data.status==200){
                  return {
                      results:  $.map(data.data, function (item) {
                          return {
                              text: item.formaFarmaceutica,
                              id: item.idFormaF
                          }
                      })
                  };
              }
              else{
                  console.error(data.message);
              }
          },
          cache: true
      }
  });

  var fecthformas = function () {
      $.ajax({
          type: 'GET',
          url: config.routes[0].formasfarmaceuticas,
          error: function (data) {
              console.error(data.responseJSON.message);
              setTimeout(function () {
                  fecthformas();
              }, 2000)
          }
      }).then(function (resp) {
          var option = '';
          $.each(resp.data, function (i, item) {
              if (item.idFormaF == formaFarmaceutica) {
                  option = new Option(item.formaFarmaceutica, item.idFormaF, true, true);

              }
          })
          formaSelect.append(option).trigger('change');
          formaSelect.trigger({
              type: 'select2:select',
              params: {
                  data: resp.data
              }
          });
      });
  }

  fecthformas();

  @endif

  @if ($solicitud->solicitudesDetalle->viaAdmon!=null)
  var via = '{{$solicitud->solicitudesDetalle->viaAdmon}}'
  var viaSelect = $('#viaAdmin').select2({
      ajax: {
          url: config.routes[0].viasadministracion,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
              if(data.status==200) {
                  return {
                      results: $.map(data.data, function (item) {
                          return {
                              text: item.viaAdministracion,
                              id: item.idViaAdmin
                          }
                      })
                  };
              }
              else{
                  console.error(data.message);
              }
          },
          cache: true
      }
  });

  var ajaxviadmini = function () {
  $.ajax({
        type: 'GET',
        url: config.routes[0].viasadministracion,
        error: function (data) {
              console.error(data.responseJSON.message);
              setTimeout(function () {
                  ajaxviadmini();
              }, 2000)
          }
    }).then(function (resp) {
      var option='';
      $.each(resp.data, function(i, item) {
          if (item.idViaAdmin==via) {
             option = new Option(item.viaAdministracion,item.idViaAdmin, true, true);

          }
        })
        viaSelect.append(option).trigger('change');
          viaSelect.trigger({
          type: 'select2:select',
          params: {
              data: resp.data
          }
        });
    });
  }


  ajaxviadmini();
  @endif
  @if ($solicitud->solicitudesDetalle->modalidadVenta!=null)
    var modalidad = '{{$solicitud->solicitudesDetalle->modalidadVenta}}';
    var modSelect = $('#modalidad').select2({
        ajax: {
            url: config.routes[0].getmodalidadventa,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                if (data.status == 200) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.nomModalidad,
                                id: item.idModalidad
                            }
                        })
                    };
                }
                else {
                    console.error(data.message);
                }
            },
            cache: true
        }
    });
    var fecthmodali= function () {
        $.ajax({
            type: 'GET',
            url: config.routes[0].getmodalidadventa,
            error: function (data) {
                console.error(data.responseJSON.message);
                setTimeout(function () {
                    fecthmodali();
                }, 2000)
            }
        }).then(function (resp) {
            var option = '';
            $.each(resp.data, function (i, item) {
                if (item.idModalidad == modalidad) {
                    option = new Option(item.nomModalidad, item.idModalidad, true, true);

                }
            })
            modSelect.append(option).trigger('change');
            modSelect.trigger({
                type: 'select2:select',
                params: {
                    data: resp.data
                }
            });
        });
    }

    fecthmodali();

  @endif



      @if(!is_null($solicitud->titular))

          @if($solicitud->titular->tipoTitular==1)
              $('input[name=tipoTitular][value="1"]').change();
          @elseif($solicitud->titular->tipoTitular==2)
              $("#inlineRadio2").attr('checked', true);
              $('input[name=tipoTitular][value="2"]').attr('checked', true);
              $('input[name=tipoTitular][value="2"]').change();
                config.flags[0].representantelegal=true;
                config.flags[0].apoderado=true;
          @elseif($solicitud->titular->tipoTitular==3)
              $('input[name=tipoTitular][value="3"]').attr('checked', true);
              $('input[name=tipoTitular][value="3"]').change();
              config.flags[0].representantelegal=true;
              config.flags[0].apoderado=true;
          @endif
          config.flags[0].titular=true;
      @endif
      @if($soldata->profesional!=null && !is_null($solicitud->profesional->poderProfesional))
          config.flags[0].profesionalvalidado=true;
      @endif
      @if($soldata->titular!=null || !is_null($solicitud->titular->idTitular))
          config.flags[0].titular=true;
      @endif
      @if (!is_null($soldata->fabricantePrincipalInfo))
        config.flags[0].labprincipal=true;
          @if ($solicitud->fabricantePrincipal->procedencia=='N')
            $('input[name=origenFab][value="E04,E55,E57"]').attr('checked', true);
            $('input[name=origenFab][value="E04,E55,E57"]').change();
          @else
            $('input[name=origenFab][value="E30"]').attr('checked', true);
            $('input[name=origenFab][value="E30"]').change();

          @endif
      @endif

      @if (!is_null($solicitud->bpmPrincipal) && count($solicitud->bpmPrincipal))
      globalBpmPrincipal = {!! $solicitud->bpmPrincipal !!}
      //console.log(globalBpmPrincipal)
      @endif

      @if (!is_null($solicitud->bpmAlternos) && count($solicitud->bpmAlternos))
        globalBpmAlterno = {!! $solicitud->bpmAlternos !!}

      @endif

      @if (!is_null($solicitud->bpmAcondicionadores) && count($solicitud->bpmAcondicionadores))
        globalBpmAcondicionador = {!! $solicitud->bpmAcondicionadores !!}
        //console.log(globalBpmAcondicionador)
      @endif
      @if (!is_null($solicitud->bpmRelacionados) && count($solicitud->bpmRelacionados))
        globalBpmRelacionados = {!! $solicitud->bpmRelacionados !!}
        //console.log(globalBpmAcondicionador)
      @endif
      @if (!is_null($solicitud->bpmFabPrinActivo) && count($solicitud->bpmFabPrinActivo))
        globalBpmFabPrinActivos = {!! $solicitud->bpmFabPrinActivo !!}
        //console.log(globalBpmAcondicionador)
      @endif
      @if ($solicitud->materialEmpaque!=null)

        dataStep5();
        dataStep8();
        var titular = $('input[name="tipoTitular"]:checked').val();
        var nomTituInput = $('#nomTitularProduc').val();
        var paisTituInput = $('#paisTituPri').val();

        if(titular==1){
              if(config.flags[0].titular==false){
                  alertify.alert("Mensaje de sistema","Debe de seleccionar un titular!");

              }else if(config.flags[0].profesionalvalidado==false){
                alertify.alert("Mensaje de sistema","¡Debe validar el número de poder del profesional responsable legal dando click en el botón de buscar!");
              }else{
                   $('#propietarioProd').val(nomTituInput);
                   $('#paisTitular').val("El Salvador");
              }
            }
      @endif
      @if($solicitud->solicitudesDetalle->origenProducto==4)
        ruta = config.routes[0].getvistareconomutuo;
        ajaxViews(ruta);
      @endif
  </script>
