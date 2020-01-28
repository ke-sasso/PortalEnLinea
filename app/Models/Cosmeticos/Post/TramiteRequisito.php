<?php

namespace App\Models\Cosmeticos\Post;

use Illuminate\Database\Eloquent\Model;
use DB;
class TramiteRequisito extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.POST.tramiteRequisitos';
    protected $primaryKey='idTramiteRequisito';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';
    //protected $fillable = ['nombreTramite'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public static function getDocumentosByTramite(){
        return DB::connection('sqlsrv')->table('dnm_cosmeticos_si.POST.tramiteRequisitos as t1')
                ->join('dnm_cosmeticos_si.POST.tramites as t2','t1.idTramite','=','t2.idTramite')
                ->join('dnm_cosmeticos_si.POST.requisitos as t3','t1.idRequisito','=','t3.idRequisito')
                ->select('t3.idRequisito','t3.nombreRequisito', 't1.idTramite')
                ->where('t1.estado','A')
                ->get();
    }

}
