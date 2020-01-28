<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogUsuario extends Model
{
    //
   protected $table = 'dnm_usuarios_portal.log_usuarios';
    protected $primaryKey = 'idLog';
   	public $timestamps = false;
}
