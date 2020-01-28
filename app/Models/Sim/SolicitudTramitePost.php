<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class SolicitudTramitePost extends Model
{
    //sim.sim_solicitud_tramite_post
    protected $table = 'sim.sim_solicitud_tramite_post';
    protected $primaryKey = 'id_tramite_solicitud';
    public $timestamps = false;
}
