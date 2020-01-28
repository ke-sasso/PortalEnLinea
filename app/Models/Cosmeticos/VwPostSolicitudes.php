<?php

namespace App\Models\Cosmeticos;

use Illuminate\Database\Eloquent\Model;
use DB;

class VwPostSolicitudes extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.POST.vwSolicitudes';
    protected $primaryKey = 'idSolicitud';
    public $autoincrement = false;
    public $timestamps = false;


    public static function getSolicitudes($nit){
        return DB::connection('sqlsrv')->table('dnm_cosmeticos_si.POST.vwSolicitudes')
                ->distinct('idSolicitud')
                ->where('nitSolicitante',$nit);
    }
}
