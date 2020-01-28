<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPortal extends Model
{
    //

    protected $table = 'dnm_usuarios_portal.portal_usuarios';
    protected $primaryKey = 'idUsuario';
    public $timestamps = false;
}
