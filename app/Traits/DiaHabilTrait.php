<?php
namespace App\Traits;

use DB;
use Carbon\Carbon;

trait DiaHabilTrait {

    /**
     * Obtener dias habiles.
     * or.merino
     *
     * @param      <type>  $fecha  La fecha que se desea corroborar en formato Y-m-d 00:00:00
     */
    public function esDiaHabil($fecha){
    	$now = $fecha;
    	$anio = $now->format('Y'); //año actual
      $ahoraMesDia = $now->format('m-d'); //mes y dia actual

      $ahoraInicio = Carbon::parse($now->format('Y-m-d').' 08:00:00');
      $ahoraFin = Carbon::parse($now->format('Y-m-d').' 16:00:00');

      $flagDiaHabil = 1; //SI es un día con horario habil

      if(!$now->isWeekend()){
        $diasFeriados = DB::connection('sqlsrv')->table('dnm_catalogos.cat.dias_feriados')->where('ano',$anio)->pluck('dia');

        foreach ($diasFeriados as $key => $value) { //Conviertiendo el value en un array
          $valueArray = json_decode($value); //Pasando el value en array
          if(in_array($ahoraMesDia,$valueArray)){
            $flagDiaHabil = 0;
            break;
          }
        }

      }
      else
        $flagDiaHabil = 0;

      if($flagDiaHabil == 1){
        if($now->between($ahoraInicio,$ahoraFin))
          return true;
        else
          return false;
      }
      else
        return false;
    }

    public function siguienteDiaHabil($fecha){
    	$diaHabilSiguiente = null;
      $dia = Carbon::parse($fecha->format('Y-m-d').' 08:00:00');

      if($fecha<$dia){
            $boolean = $this->esDiaHabil($dia);
            if($boolean){
                    $diaHabilSiguiente = $dia;
            }else{
                    for($i=1;$i<=20;$i++){
                      $fecha->addDays(1);
                      $dia = Carbon::parse($fecha->format('Y-m-d').' 08:00:00');
                      $boolean = $this->esDiaHabil($dia);
                      if($boolean){
                        $diaHabilSiguiente = $dia;
                        break;
                      }
                  }
            }

      }else{
         for($i=1;$i<=20;$i++){
            $fecha->addDays(1);
            $dia = Carbon::parse($fecha->format('Y-m-d').' 08:00:00');
            $boolean = $this->esDiaHabil($dia);
            if($boolean){
              $diaHabilSiguiente = $dia;
              break;
            }
          }

      }


      return $diaHabilSiguiente;
    }

}