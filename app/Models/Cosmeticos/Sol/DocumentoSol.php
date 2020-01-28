<?php

namespace App\Models\Cosmeticos\Sol;

use Illuminate\Database\Eloquent\Model;

class DocumentoSol extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_cosmeticos_si.SOL.documentosSol';
    protected $primaryKey='idDetalleDoc';
    public $timestamps = false;
    public $autoincrement = false;
    protected $fillable = ['idSolicitud','idItemDoc','idDetalleDoc'];
    protected $visible = ['idSolicitud','idItemDoc','idDetalleDoc'];

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

    public function detalleDocumento(){
        return $this->hasOne('App\Models\Cosmeticos\Sol\DetalleDocumento','idDoc','idDetalleDoc'    );
    }



}
