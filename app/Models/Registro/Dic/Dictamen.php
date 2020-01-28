<?php

namespace App\Models\Registro\Dic;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Ucc\UnificacionPortal;
class Dictamen extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'dnm_urv_si.DIC.dictamenes';
    protected $primaryKey='idDictamen';
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaModificacion';

    public function getDateFormat(){
        return 'Y-m-d H:i:s';
    }

     public function validacionDictamen(){
        return $this->hasMany('App\Models\Registro\Dic\ValidacionDictamen','idDictamen','idDictamen');
    }
    public function estado(){
        return $this->hasOne('App\Models\Registro\Sol\EstadosSolicitud','idEstado','estadoDictamen');
    }

     public static function getCamposObservador($idSolicitud){
         //CONSULTAMOS TODOS LOS CAMPOS OBSERVADOS MENOS LOS DOCUMENTOS
         $dic =  Dictamen::where('idSolicitud',$idSolicitud)->select('idDictamen')->where('estadoDictamen',4)->get()->toArray();
         $campos=ValidacionDictamen::whereIn('idDictamen',$dic)->where('nombreTabla','<>','PRE.solicitudDocumentos')->where('estadoCampo',2)->pluck('campo')->toArray();
         return $campos;
    }

    public static function getTablasObservadas($idSolicitud){
         //CONSULTAMOS TODOS LAS TABLAS OBSERVADOS MENOS LOS DOCUMENTOS
         $dic =  Dictamen::where('idSolicitud',$idSolicitud)->select('idDictamen')->where('estadoDictamen',4)->get()->toArray();
         $campos=ValidacionDictamen::whereIn('idDictamen',$dic)->where('nombreTabla','<>','PRE.solicitudDocumentos')->where('estadoCampo',2)->pluck('nombreTabla')->toArray();
         return $campos;
    }

     public static function getDocumentosObservados($idSolicitud){
           //CONSULTAMOS TODOS LOS DOCUMENTOS OBSERVADOS
          $arreglo=[];
         $dic =  Dictamen::where('idSolicitud',$idSolicitud)->select('idDictamen')->where('estadoDictamen',4)->get()->toArray();
         $campos=ValidacionDictamen::whereIn('idDictamen',$dic)->where('nombreTabla','PRE.solicitudDocumentos')->where('estadoCampo',2)->pluck('campo')->toArray();

         if(count($campos)>0){
              for($a=0;$a<count($campos);$a++){
                 array_push($arreglo, substr($campos[$a], 12));
              }

               $lab = UnificacionPortal::where('idSolicitud',$idSolicitud)->get()->first();
                if(!empty($lab)){
                    if(count($lab->revisionMetodologica)>0){
                        $revision = $lab->revisionMetodologica()->orderBy('fecha_creacion','desc')->pluck('id_revision_metodologica');
                         $validacionesUcc = ValidacionDictamen::whereIn('idDictamen',$revision)->where('esLab',1)->where('estadoCampo',2)->pluck('campo')->toArray();
                         for($b=0;$b<count($validacionesUcc);$b++){
                             array_push($arreglo, substr($validacionesUcc[$b], 12));
                         }
                         //SI LOS SIGUIENTES DOCUMENTOS COPIAS SON OBSEVADOS, ENTONCES HABILITAMOS LOS DOCUMENTOS ORIGINALES
                                     /*if(in_array(18,$arreglo)){
                                        array_push($arreglo,7);
                                     }
                                     if(in_array(19,$arreglo)){
                                        array_push($arreglo,8);
                                     }
                                     if(in_array(20,$arreglo)){
                                        array_push($arreglo,11);
                                     }*/
                    }
                }
                //HABILITAMOS POR DEFECTO LOS SIGUIENTES DOCUMENTOS
               array_push($arreglo,1);array_push($arreglo,49);array_push($arreglo,50);array_push($arreglo,51);
              return $arreglo;
         }else{

                    $lab = UnificacionPortal::where('idSolicitud',$idSolicitud)->get()->first();
                    if(!empty($lab)){
                        //HABILITAMOS POR DEFECTO LOS SIGUIENTES DOCUMENTOS
                        array_push($campos,1);array_push($arreglo,49);array_push($arreglo,50);array_push($arreglo,51);
                                if(count($lab->revisionMetodologica)>0){
                                     $revision = $lab->revisionMetodologica()->orderBy('fecha_creacion','desc')->pluck('id_revision_metodologica');
                                     $validacionesUcc = ValidacionDictamen::whereIn('idDictamen',$revision)->where('esLab',1)->where('estadoCampo',2)->pluck('campo')->toArray();
                                     for($b=0;$b<count($validacionesUcc);$b++){
                                         array_push($campos, substr($validacionesUcc[$b], 12));
                                     }
                                     //SI LOS SIGUIENTES DOCUMENTOS COPIAS SON OBSEVADOS, ENTONCES HABILITAMOS LOS DOCUMENTOS ORIGINALES
                                     /*if(in_array(18,$campos)){
                                        array_push($campos,7);
                                     }
                                     if(in_array(19,$campos)){
                                        array_push($campos,8);
                                     }
                                     if(in_array(20,$campos)){
                                        array_push($campos,11);
                                     }*/

                                }
                     }else{
                        //HABILITAMOS POR DEFECTO LOS SIGUIENTES DOCUMENTOS
                        array_push($campos,1); array_push($arreglo,49);array_push($arreglo,50);array_push($arreglo,51);
                     }
        }

         return $campos;
    }

    public static function getDocumentosObsSubsanacion($idSolicitud){
           //CONSULTAMOS TODOS LOS DOCUMENTOS OBSERVADOS
          $arreglo=[];
         $dic =  Dictamen::where('idSolicitud',$idSolicitud)->select('idDictamen')->where('estadoDictamen',4)->get()->toArray();
         $campos=ValidacionDictamen::whereIn('idDictamen',$dic)->where('nombreTabla','PRE.solicitudDocumentos')->whereNotIn('campo',['docrequisito27'])->where('estadoCampo',2)->pluck('campo')->toArray();

         if(count($campos)>0){
              for($a=0;$a<count($campos);$a++){
                 array_push($arreglo, substr($campos[$a], 12));
              }

               $lab = UnificacionPortal::where('idSolicitud',$idSolicitud)->get()->first();
               if(!empty($lab)){
                    if(count($lab->revisionMetodologica)>0){
                        $revision = $lab->revisionMetodologica()->orderBy('fecha_creacion','desc')->pluck('id_revision_metodologica');
                         $validacionesUcc = ValidacionDictamen::whereIn('idDictamen',$revision)->where('esLab',1)->where('estadoCampo',2)->whereNotIn('campo',['docrequisito18','docrequisito19','docrequisito20','docrequisito27'])->pluck('campo')->toArray();
                         for($b=0;$b<count($validacionesUcc);$b++){
                             array_push($arreglo, substr($validacionesUcc[$b], 12));
                         }
                    }
                }
              return $arreglo;
         }else{
                    $campos=[];
                    $lab = UnificacionPortal::where('idSolicitud',$idSolicitud)->get()->first();
                    if(!empty($lab)){
                        //HABILITAMOS POR DEFECTO LOS SIGUIENTES DOCUMENTOS
                       // array_push($campos,1);array_push($arreglo,49);array_push($arreglo,50);array_push($arreglo,51);
                                if(count($lab->revisionMetodologica)>0){
                                     $revision = $lab->revisionMetodologica()->orderBy('fecha_creacion','desc')->pluck('id_revision_metodologica');
                                     $validacionesUcc = ValidacionDictamen::whereIn('idDictamen',$revision)->where('esLab',1)->where('estadoCampo',2)->whereNotIn('campo',['docrequisito18','docrequisito19','docrequisito20','docrequisito27'])->pluck('campo')->toArray();
                                     for($b=0;$b<count($validacionesUcc);$b++){
                                         array_push($campos, substr($validacionesUcc[$b], 12));
                                     }

                                }
                     }
        }
        return $campos;
    }



}
