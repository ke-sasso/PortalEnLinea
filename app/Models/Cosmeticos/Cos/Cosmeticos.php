<?php

namespace App\Models\Cosmeticos\Cos;

use Illuminate\Database\Eloquent\Model;

class Cosmeticos extends Model
{
    //

    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.COS.Cosmeticos';
    protected $primaryKey='idCosmetico';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';


    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }


}
