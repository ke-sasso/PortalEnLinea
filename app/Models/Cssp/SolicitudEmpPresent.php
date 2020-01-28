<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;

class SolicitudEmpPresent extends Model
{
    //
    protected $table = 'cssp.si_urv_solicitudes_empaques_presentaciones';
    protected $primaryKey = 'ID_PRESENTACION_SOLICITUD';
    public $timestamps = false;
}
