<?php

namespace App\Models\Registro\Sol;

use Illuminate\Database\Eloquent\Model;

class SolicitudSeguimiento extends Model{
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.PRE.solicitudSeguimiento';
    protected $primaryKey = 'idRequest';
    public $timestamps = false;
    protected $fillable = ['idSolicitud','estadoSolicitud','observaciones','idUsuarioCreacion','fechaCreacion'];
}
