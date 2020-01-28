<?php
namespace App\Models\Ucc;

use Illuminate\Database\Eloquent\Model;

class UnificacionPortal extends Model
{
  protected $table='ucc.si_ucc_unificacion_portal';
  protected $primaryKey='id_unificacion';
  const CREATED_AT ='fecha_creacion';
  const UPDATED_AT ='fecha_modificacion';

  public function portalSolicitud(){
    return $this->hasOne('App\Models\RegistroPortal\Sol\SolicitudPortal','idSolicitud','idSolicitud');
  }

  public function revisionMetodologica(){
      return $this->hasMany('App\Models\Ucc\RevisionMetodologicaPortal','id_unificacion_revision','id_unificacion');
  }
  
}