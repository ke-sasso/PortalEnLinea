<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Cssp\VueTramitesTipos;
use App\Models\Cssp\SolicitudesVueTramites;
use App\Models\Cssp\Productos;
class SolicitudesVue extends Model
{
    //
    protected $table = 'cssp.siic_solicitudes_vue';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;


    public function dictamenes()
    {
        return $this->hasMany('App\Models\Cssp\DictamenPost','ID_SOLICITUD', 'ID_SOLICITUD');
    }

    public function detallePresentaciones()
    {
        return $this->hasMany('App\Models\Cssp\SolVuePresetanciones','ID_SOLICITUD', 'ID_SOLICITUD');
    }

    public function SolicitudVueTramite()
    {
        return $this->belongsTo('App\Models\Cssp\SolicitudesVueTramites','ID_SOLICITUD', 'ID_SOLICITUD');
    }


    public static function getSolicitudesCertificadas($nit){

      return DB::table('cssp.siic_solicitudes_vue as sol')->leftJoin('cssp.siic_solicitudes_vue_tramites as vuetra','sol.ID_SOLICITUD','=','vuetra.ID_SOLICITUD')
            ->leftJoin('cssp.siic_solicitudes_vue_tramites_tipos as tra','vuetra.ID_TRAMITE','=','tra.ID_TRAMITE')
            ->leftJoin('cssp.cssp_productos as prod','sol.NO_REGISTRO','=','prod.ID_PRODUCTO')
            ->whereIn('sol.ID_ESTADO',[9,2])
            ->where('USUARIO_CREACION','like',"%portalenlinea%")
            ->select('sol.ID_SOLICITUD','sol.NO_REGISTRO','prod.NOMBRE_COMERCIAL','tra.ID_TRAMITE','tra.NOMBRE_TRAMITE','sol.ID_ESTADO')
            ->orderBy('sol.ID_SOLICITUD','DESC');


    }

    public static function getMaxIdSolicitud(){
    	return DB::select("SELECT max(CONVERT(ID_SOLICITUD,UNSIGNED)) + 1 AS MAXIMO FROM CSSP.SIIC_SOLICITUDES_VUE")[0]->MAXIMO;
    }

    public static function getUltimoExp(){
    	$ultimoexp=str_replace('/',"",substr(date('Y/m/d'),2,5));
      	$numero = DB::select("SELECT MAX(CAST(MID(NO_EXPEDIENTE,7,4) AS UNSIGNED)) + 1 AS MAXIMO
                                  FROM CSSP.SIIC_SOLICITUDES_VUE  where MID(NO_EXPEDIENTE,1,6) =".'"PR'.$ultimoexp.'"'."");
      	if($numero[0]->MAXIMO==null)
          $num='0001';
      else
          $num=(string)$numero[0]->MAXIMO;

       $longitud=4;
       $longitud-=strlen($num);
       $lado="I";
       $relleno="0";

       for ($i=0; $i<$longitud; $i++) {
          if ($lado==="I")
            $num = $relleno . $num;
          else
            $num += (string) $relleno;
      }

       $numexpediente='PR'.$ultimoexp.$num;
       return $numexpediente;
    }
    public static function getIdFormaFarmByProd($nregistro){
    	if($nregistro){
    		$idFormaFarm= DB::table('cssp.siic_productos_formas_farmaceuticas as pff')
		                    ->join('cssp.siic_formas_farmaceuticas as ff','pff.id_forma_farmaceutica','=','ff.id_forma_farmaceutica')
		                    ->where('ff.activo','A')
		                    ->where('pff.id_producto',$nregistro)
		                    ->select('pff.id_forma_farmaceutica')
		                    ->first();
        //dd($idFormaFarm);
		    //return $idFormaFarm;
         if($idFormaFarm!=null){
          return $idFormaFarm->id_forma_farmaceutica;
        }
        else{
          return null;
        }

        }
    }

    public static function getFichasProductoByProd($nregistro){
    	if($nregistro){
    		$fichas = DB::table('cssp.cssp_productos as p')
                          ->join('cssp.cssp_tipos_productos as tp', 'p.id_tipo_producto','=','tp.id_tipo_producto')
                          ->join('cssp.cssp_productos_estados as e','p.ACTIVO','=','e.ID_ESTADO')
                          ->join('cssp.siic_clasificaciones_productos as cp','p.id_clasificacion','=','cp.id_clasificacion')
                          ->join('cssp.siic_vias_administracion as va','p.id_via_administracion', '=','va.id_via_administracion')
                          ->select('p.ID_PRODUCTO','p.NOMBRE','p.NOMBRE_COMERCIAL','p.FECHA_INSCRIPCION','p.RECETA',
                                   'p.VIGENTE_HASTA','p.ULTIMA_RENOVACION', 'p.ANO_EXPEDIENTE','p.VIDA_UTIL','p.ID_CLASIFICACION','p.NACIONAL',
                                   'p.INDICACIONES_TERAPEUTICAS','tp.id_tipo_producto','e.NOMBRE_ESTADO',
                                   'p.CONDICIONES_ALMACENAMIENTO','cp.nombre_clasificacion','p.id_via_administracion','va.NOMBRE_VIA_ADMINISTRACION','p.FORMULA','p.EXCIPIENTES','p.UNIDAD_DE_DOSIS','p.ID_PROPIETARIO')
                          ->where('p.id_producto',$nregistro)
                          ->first();
            return $fichas;
    	}
    }

    public static function getPropietarioByProd($idPropietario){

    	return DB::table('cssp.cssp_propietarios as prop')->join('cssp.cssp_paises as pa','prop.ID_PAIS','=','pa.ID_PAIS')->where('prop.ID_PROPIETARIO',$idPropietario)
                    ->select('prop.ID_PROPIETARIO','prop.NOMBRE_PROPIETARIO','prop.EMAIL','pa.NOMBRE_PAIS')->first();
    }

    public static function getProfesionalByProd($nregistro){
    	if($nregistro){

	    return	DB::table('cssp.siic_productos_profesionales as pprof')
			        ->join('cssp.cssp_profesionales as prof','pprof.id_profesional','=','prof.id_profesional')
			        ->where('pprof.id_producto',$nregistro)
			        ->select('pprof.id_profesional','prof.nombres','prof.apellidos','prof.NIT')
			        ->first();
	    }
    }

    public static function getPoderesByProd($nregistro){
        if($nregistro){

            return DB::table('cssp.siic_productos_profesionales as pf')
                    ->join('cssp.siic_profesionales_poderes as pp',function($join){
                        $join->on('pf.id_poder','=','pp.id_poder')->on('pf.id_profesional','=','pp.id_profesional');
                    })
                    ->join('cssp.cssp_profesionales as prof','pp.id_profesional','=','prof.id_profesional')
                    ->where('pf.id_producto',$nregistro)
                    ->select('pf.id_profesional','pf.id_poder',DB::raw('concat(prof.nombres,'.'" "'.',prof.apellidos) as nombre'))
                    ->first();

        }
    }

    public static function getApoderadosByProd($nregistro){
        if($nregistro){

            return DB::table('cssp.siic_productos_apoderados as pa')
                    ->join('cssp.siic_apoderados_poderes as ap',function($join){
                        $join->on('pa.id_poder','=','ap.id_poder')->on('pa.id_apoderado','=','ap.id_apoderado');
                    })
                    ->join('cssp.siic_apoderados as apo','ap.id_apoderado','=','apo.id_apoderado')
                    ->where('pa.id_producto',$nregistro)
                    ->select('pa.ID_APODERADO','pa.id_poder',DB::raw('concat(apo.nombres,'.'" "'.',apo.apellidos) as nombre'))
                    ->first();
        }

    }

    public static function insertarSolicitudVue($idSolicitud,$usuarioCreacion,$nregistro){

      DB::select('call cssp.InsertSolicitudVue(?,?,?)',array($idSolicitud,$usuarioCreacion,$nregistro));
    }

    public static function dictaminarSolicitudVue($idSolicitud,$idArea,$idTramite,$usuarioCreacion){


      DB::select('call cssp.sp_dictaminar_solicitud(?,?,?,?)',array($idSolicitud,$idArea,$idTramite,$usuarioCreacion));
    }

    public static function certificarSolicitudVue($idSolicitud,$idTramite,$tomarNota,$fichasproductos,$usuarioCreacion){
      $tramite=VueTramitesTipos::find($idTramite);
      $cuerpo='';
        //cambio de nombre de exportacion

       if($idTramite==48)
       {
        DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitud));
       }
       if($idTramite==36){
          DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitud));
          DB::select('call cssp.POSTREGISTROS_CAMBIO_NOMBRE_EXPORTACION_DIR(?,?)',array($idSolicitud,$idTramite));
          DB::select('call cssp.sp_post_cambio_nom_exportacion(?,?)',array($idSolicitud,$idTramite));
       }

       if($idTramite==54){
          DB::select('call cssp.POSTREGISTROS_ACTUALIZACION_DISENIO_EMPAQUE_DIR(?,?)',array($idSolicitud,$idTramite));
         $cuerpo='Admitido dicho escrito, agréguese la documentación presentada y con base al dictamen del analista de la Unidad de Registro, esta Dirección RESUELVE:
               <b>TOMAR NOTA</b> de ' .strtoupper($tomarNota).','.' '.$tramite->NOMBRE_TRAMITE.',
               del producto denominado <b> '.$fichasproductos->NOMBRE_COMERCIAL.'.</b>';
        $vueTramites= SolicitudesVueTramites::find($idSolicitud);
        $vueTramites->TEXTO_IMPRIMIR_PORTAL=$cuerpo;
        $vueTramites->save();

      }

      if($idTramite==29){
        DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitud));
        DB::select('call cssp.POSTREGISTROS_NUEVA_MONOGRAFIA_DIR(?,?)',array($idSolicitud,$idTramite));
      }

      if($idTramite==27){
        DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitud));
        DB::select('call cssp.POSTREGISTROS_NUEVO_INSERTO_DIR(?,?)',array($idSolicitud,$idTramite));

      }

      if($idTramite==64){
        DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitud));
          $cuerpo='Admitido dicho escrito, agréguese la documentación presentada y con base al dictamen del analista de la Unidad de Registro, esta Dirección RESUELVE: ,'
                  .'<b>TOMAR NOTA</b> de, '.strtoupper($tomarNota). '
                  del producto denominado <b>'.$fichasproductos->NOMBRE_COMERCIAL.'</b>.';

          $cuerpo1='Admitido dicho escrito, agréguese la documentación presentada y con base al dictamen del analista de la Unidad de Registro, esta Dirección RESUELVE: ,'
                  .'<style isBold=\"true\">TOMAR NOTA</style> de, '.strtoupper($tomarNota). '
                  del producto denominado <style isBold=\"true\">'.$fichasproductos->NOMBRE_COMERCIAL.'</style>.';
          $vueTramites= SolicitudesVueTramites::find($idSolicitud);
          $vueTramites->TEXTO_IMPRIMIR=$cuerpo1;
          $vueTramites->TEXTO_IMPRIMIR_PORTAL=$cuerpo;
          $vueTramites->save();
      }

      if($idTramite==66){
        DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitud));
        $solPresentacion=DB::table('cssp.siic_solicitudes_vue_detalle_presentaciones')->where('ID_SOLICITUD',$idSolicitud)->first();
        $solicitud=SolicitudesVue::find($idSolicitud);
        $producto=Productos::find($solicitud->NO_REGISTRO);
        $presentacion=Productos::getPresentacionByProd($solicitud->NO_REGISTRO)->where('ID_PRESENTACION',$solPresentacion->ID_PRESENTACION)->first();
        //dd($presentacion);
        $cuerpo='Admitido el presente escrito y de conformidad al dictamen médico de carácter favorable, '.
                'esta Dirección resuelve AUTORIZAR el Agotamiento de Empaque, numero de lote: <b>'.$solPresentacion->ID_LOTE.'</b>, cuyas existencias es de '.'<b>'.$solPresentacion->UNIDADES.'</b>'.', para el producto denominado <b>'.
                $producto->NOMBRE_COMERCIAL.'</b>, solicitado en el referido escrito, para la presentación: <b>'.$presentacion->PRESENTACION_COMPLETA.' '.$presentacion->ACCESORIOS.'</b>.';

        $cuerpo1='Admitido el presente escrito y de conformidad al dictamen médico de carácter favorable, '.
                'esta Dirección resuelve AUTORIZAR el Agotamiento de Empaque, numero de lote: <style isBold=\"true\">'.$solPresentacion->ID_LOTE.'</style>, cuyas existencias es de '.'<style isBold=\"true\">'.$solPresentacion->UNIDADES.'</style>'.', para el producto denominado <style isBold=\"true\">'.
                $producto->NOMBRE_COMERCIAL.'</style>, solicitado en el referido escrito, para la presentación: <style isBold=\"true\">'.$presentacion->PRESENTACION_COMPLETA.' '.$presentacion->ACCESORIOS.'</style>.';
        $vueTramites= SolicitudesVueTramites::find($idSolicitud);
        $vueTramites->TEXTO_IMPRIMIR=$cuerpo1;
        $vueTramites->TEXTO_IMPRIMIR_PORTAL=$cuerpo;
        $vueTramites->save();
      }

      if($idTramite==37 || $idTramite==38 || $idTramite==39){
        DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitud));
        DB::select('call cssp.POSTREGISTROS_CAMBIO_ARTE_DIR(?,?)',array($idSolicitud,$idTramite));

      }

      if($idTramite==61){
        $textoAdicional='';
        DB::select('call cssp.POSTREGISTROS_DESCONTINUACION_FABRICANTE_DIR(?,?,?)',array($idSolicitud,$idTramite,$textoAdicional));
      }

      if($idTramite==67){
        $textoAdicional='';
        DB::select('call cssp.POSTREGISTROS_DESCONTINUACION_LABORATORIO_DIR(?,?,?)',array($idSolicitud,$idTramite,$textoAdicional));
      }

      if($idTramite==51){
        DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitud));
        DB::select('call cssp.SP_POSTREGISTRO_DES_PRESENT_PORTAL(?,?)',array($idSolicitud,$idTramite));
        /*$cuerpo='Admitido dicho escrito, agréguese la documentación presentada y con base al dictamen del analista de la Unidad de Registro, '.
                'esta Dirección RESUELVE: TOMAR NOTA, <b>'.$tramite->NOMBRE_TRAMITE.'</b> , del producto denominado <style isBold=\"true\">'.$fichasproductos->NOMBRE_COMERCIAL.'</style>.';
        $cuerpo1='Admitido dicho escrito, agréguese la documentación presentada y con base al dictamen del analista de la Unidad de Registro, '.
                'esta Dirección RESUELVE: TOMAR NOTA, <style isBold=\"true\">'.$tramite->NOMBRE_TRAMITE.'</style> , del producto denominado <style isBold=\"true\">'.$fichasproductos->NOMBRE_COMERCIAL.'</style>.';
        $vueTramites= SolicitudesVueTramites::find($idSolicitud);
        $vueTramites->TEXTO_IMPRIMIR=$cuerpo1;
        $vueTramites->TEXTO_IMPRIMIR_PORTAL=$cuerpo;
        $vueTramites->save();*/
      }
      if($idTramite==21){
        //dd($idTramite);
        DB::select('call cssp.SP_POSTREGISTROS_AMPLIACION_PRESENTACION_PORTAL(?,?)',array($idSolicitud,$idTramite));
      }
      if($idTramite==57){

        DB::select('call cssp.POSTREGISTROS_GUARDAR_HISTORIAL(?)',array($idSolicitud));
        $forma=Productos::getFormaFarmByProd($fichasproductos->ID_PRODUCTO)[0]->nombre_forma_farmaceutica;
        $cuerpo='Admitido dicho escrito, agréguese la documentación presentada y de conformidad a lo establecido en el Artículo 32 de la'.
                'Ley de Medicamentos y Articulo 8 literal b) y 109 del Reglamento General de la  Ley de Medicamentos, '.'
                esta Dirección resuelve <b>AUTORIZAR</b>: el <b>'.$tramite->NOMBRE_TRAMITE.'</b>, del producto denominado<b>'.$fichasproductos->NOMBRE_COMERCIAL.'</b>. '.
                'Quedando como: <b>'.$forma.'</b>.';
        $vueTramites= SolicitudesVueTramites::find($idSolicitud);
        //$vueTramites->TEXTO_IMPRIMIR=$cuerpo;
        $vueTramites->TEXTO_IMPRIMIR_PORTAL=$cuerpo;
        $vueTramites->save();
      }

      $tramite=SolicitudesVueTramites::find($idSolicitud);
      $solicitudVue= SolicitudesVue::find($idSolicitud);
      //$solicitudVue->ID_ESTADO=10;
      //$solicitudVue->fecha_IMPRESION_licencia=
      $solicitudVue->texto_resolucion=$tramite->TEXTO_IMPRIMIR_PORTAL;
      $solicitudVue->ID_USUARIO_LICENCIA=$usuarioCreacion;

      $saved=$solicitudVue->save();

      if(!$saved){
        return view('errors.500');
      }
      else{
        return 1;
      }


    }


    public static function getSolicitudesEnProceso($idProducto){

      return DB::table('cssp.siic_solicitudes_vue')
            ->whereIn('ID_ESTADO',['1,2,3,4,5,6,8'])
            ->where('ID_TIPO_SOLICITUD',2)
            ->where('NO_REGISTRO',$idProducto)
           // ->where(DB::raw('year(FECHA_CREACION)'),'=','2017')
            ->orderBy('FECHA_CREACION','desc')
            ->select('ID_SOLICITUD','NO_REGISTRO','ID_ESTADO')
            ->get();


    }


}
