<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;

class CsspEstablecimiento extends Model
{
    protected $table = 'cssp.cssp_establecimientos';
    protected $primaryKey = 'ID_ESTABLECIMIENTO';
    public $incrementing = false;
    public $timestamps = false;
}
