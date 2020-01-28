<?php

namespace App\Models\Sim;

use Illuminate\Database\Eloquent\Model;

class RequisitoDocumento extends Model
{
    //
    protected $table = 'sim.sim_tramite_post_requisito_documento';
    protected $primaryKey = 'idRequisitoDocumento';
    public $timestamps = false;
}
