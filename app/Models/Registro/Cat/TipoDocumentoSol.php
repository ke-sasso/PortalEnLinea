<?php

namespace App\Models\Registro\Cat;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentoSol extends Model
{
  //
  protected $connection = 'sqlsrv';
  protected $table = 'dnm_urv_si.CAT.tipoDocumentoSol';
  protected $primaryKey='idTipoDoc';
  public $timestamps    = false;


}
