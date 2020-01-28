<?php

namespace App\Models\Cosmeticos;

use Illuminate\Database\Eloquent\Model;
use DB;

class VwPropietariosCosHig extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.CAT.vwPropietariosCosHig';
    protected $primaryKey = 'nit';
    public $autoincrement = false;
    public $timestamps = false;

}
