<?php

namespace App\Models\Registro\Sol;

use Illuminate\Database\Eloquent\Model;

class RequisitosDocumento extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PRE.requisitoDocumentos';
    protected $primaryKey='idItem';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }



}
