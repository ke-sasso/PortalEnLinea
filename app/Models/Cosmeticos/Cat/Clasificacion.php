<?php

namespace App\Models\Cosmeticos\Cat;

use Illuminate\Database\Eloquent\Model;

class Clasificacion extends Model
{
  //
  protected $connection = 'sqlsrv';
  protected $table = 'dnm_cosmeticos_si.COS.clasificacion';
  protected $primaryKey='idClasificacion';
  const CREATED_AT = 'fechaCreacion';
  const UPDATED_AT = 'fechaModificacion';

  protected $fillable = ['idClasificacion','nombreClasificacion','idUsuarioCrea'];

  public function getDateFormat(){
      return 'Y-m-d H:i:s';
  }
}
