<?php

namespace App\Models\Registro\Dic;

use Illuminate\Database\Eloquent\Model;
use DB;
class ValidacionDictamen extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.DIC.validacionDictamen';
    protected $primaryKey='idValidacion';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

   public function dictamen(){
        return $this->belongsTo('App\Models\Registro\Dic\Dictamen','idDictamen','idDictamen');
    }
}
