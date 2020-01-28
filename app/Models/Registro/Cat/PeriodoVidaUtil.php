<?php

namespace App\Models\Registro\Cat;

use Illuminate\Database\Eloquent\Model;

class PeriodoVidaUtil extends Model
{
  //
  protected $connection = 'sqlsrv';
  protected $table = 'dnm_urv_si.CAT.periodoVidaUtil';
  protected $primaryKey='idPeriodo';
  public $timestamps    = false;


}
