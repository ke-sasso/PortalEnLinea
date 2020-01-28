<?php

namespace App\Models\Registro\Sol\Paso9;

use Illuminate\Database\Eloquent\Model;

class Farmacologia extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PASO9.farmacologica';
    protected $primaryKey='idFichaTecnica';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function detalle(){
        return $this->hasOne('App\Models\Registro\Sol\Paso9\DetalleFarmacologia','idFichaTecnica','idFichaTecnica');
    }
}
