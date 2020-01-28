
<div class="panel panel-success">
  <div class="panel-heading" >
    <h4 class="panel-title" >
        LOTES:
    </h4>
  </div>
    <div class="panel-body">
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <div class="input-group">
                <div class="input-group-addon"><b>Selecione el número de lotes a adicionar en el trámite:</b></div>
                    <select name="numlotes" id="numlotes" class="form-control">
                      <option value="0">Seleccione...</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>

                    </select>
              </div>
        </div>
        <br>
          
        <div id="codigos">
        </div>

        <br>
        <br>

        <div id="tablelotes">
          <table width="100%" class="table table-bordered table-hover table-responsive">
            <thead class="thead-inverse">
              <tr>
                <th width="12%">N° LOTE</th>
                <th width="12%">UNIDADES</th>
                <th width="15%">FECHA VENCIMIENTO</th>
                <th width="60%">PRESENTACION</th>
                <th width="1%">-</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
    </div>
</div>


@section('jsTramites')

  //funcion para el tramite 66
  $('#numlotes').on('change', function() {
        if(this.value==0){
          //alertify.alert("Mensaje de sistema","Debe seleccionar un numero de cuantos codigos adiccionar");
        }
        else{
          if(!$('#txtregistro').val()){
            alertify.alert("Mensaje de sistema","Debe seleccionar un producto para realizar el tramite");
            $("#numlotes").val("0").change();
          }
          else{
          numcod=$("#numlotes option:selected" ).val();
           
          
            //console.log(numcod);
            $('#tablelotes').hide();
            $("#tablelotes tbody tr").remove(); 
            for(j=0; j< numcod; j++){
              $('#tablelotes').show();
                 $('#tablelotes tbody').append('<tr><td contenteditable="true"><input type="text" class="form-control text-uppercase" id="lote" name="lote[]" value="" required></td><td><input type="number" class="form-control" id="unidades" name="unidades[]" value="" required></td><td><input type="text" class="form-control datepicker" readonly="readonly" name="fecha1[]" placeholder="00-00-0000" autocomplete="false" value="" required></td><td><select name="presentaciones[]" id="presentaciones" class="form-control presentacion"><option value="0" >Seleccione una presentación</option>'+presentxt+'</select></td><td><button class="btn btn-danger btnEliminar"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
            }
            
            $('#tablelotes').show();
          }
        }

        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        $('.datepicker').datepicker({format: 'dd-mm-yyyy',onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }}).on('changeDate',function(){
            $(this).datepicker('hide');
        });

  });


  $("#tablelotes").on('click', '.btnEliminar', function () {
      $(this).closest('tr').remove();
  });
@endsection