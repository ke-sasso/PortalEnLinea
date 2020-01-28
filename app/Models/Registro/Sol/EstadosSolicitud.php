<?php

namespace App\Models\Registro\Sol;

use Illuminate\Database\Eloquent\Model;

class EstadosSolicitud extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.SOL.estadosSolicitudes';
    protected $primaryKey='idEstado';
    public $timestamps    = false;


}
