<?php

namespace App\Models\Cssp;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\PersonaNatural;
use App\Municipios;
use App\Departamentos;
use App\Paises;
use App\Tratamiento;

class Productos extends Model
{
    //
    protected $table = 'cssp.cssp_productos';
    protected $primaryKey = 'ID_PRODUCTO';
    public $incrementing = false;
    const CREATED_AT = 'FECHA_CREACION';
    const UPDATED_AT = 'FECHA_MODIFICACION';


    public function propietario()
    {
        return $this->belongsTo('App\Models\Cssp\Propietarios', 'ID_PROPIETARIO', 'ID_PROPIETARIO');
    }

    public function productosFabricantes(){
        return $this->hasMany('App\Models\Cssp\Siic\ProductoFabricante','ID_PRODUCTO','ID_PRODUCTO');
    }

    //filas de todos los productos activos
    public static function getDataRows()
    {

        return DB::table('dnm_usuarios_portal.vwproductos as vwprod')
            ->join('cssp.cssp_tipos_productos as tipo', 'vwprod.tipoProducto', '=', 'tipo.ID_TIPO_PRODUCTO')
            ->select('vwprod.idProducto', 'vwprod.nombreComercial', 'vwprod.nombreModalidadVenta', 'vwprod.vigenteHasta', 'vwprod.ultimaRenovacion', 'tipo.NOMBRE_TIPO_PRODUCTO as tipoProd');
        //->where('vwprod.ACTIVO','A');
    }

    public static function getNomExportacionByProd($idProducto)
    {
        if ($idProducto) {

            return DB::table('cssp.siic_productos_nombres_exportacion as pnexp')
                ->join('cssp.cssp_paises as pa', 'pnexp.id_pais', '=', 'pa.id_pais')
                ->where('pnexp.id_producto', (string)$idProducto)
                ->select('pnexp.id_pais', 'pnexp.nombre_exportacion', 'pa.nombre_pais')
                ->get();

        }
    }

    public static function getFabricantesByProd($idProducto)
    {
        //arregalar lo de pais

        return DB::table('cssp.siic_productos_fabricantes as pf')
            ->join('cssp.cssp_establecimientos as est', 'pf.id_fabricante', '=', 'est.id_establecimiento')
            ->leftJoin('dnm_catalogos.cat_municipios as muni', 'est.idMunicipio', '=', 'muni.idMunicipio')
            ->leftJoin('dnm_catalogos.cat_departamentos as dep', 'muni.idDepartamento', '=', 'dep.idDepartamento')
            ->leftJoin('dnm_catalogos.cat_paises as pa', 'dep.idPais', '=', 'pa.codigoId')
            ->where('pf.id_producto', $idProducto)
            ->select('pf.id_fabricante', 'est.nombreComercial', 'pf.tipo', 'pf.vigente_hasta', 'pf.ultimo_pago', DB::raw('ifnull(pa.nombre,"N\A") as nombrepa'))
            ->orderBy('pf.id_fabricante', 'desc')
            ->get();


    }

    public static function getFabricanteByProdCssp($idProducto)
    {

        return DB::table('cssp.siic_productos_fabricantes as pf')
                  ->join('cssp.cssp_establecimientos as est', 'pf.id_fabricante', '=', 'est.id_establecimiento')
                  ->join('cssp.cssp_paises as c', 'est.id_pais', '=', 'c.id_pais')
                  ->where('pf.id_producto', $idProducto)
                  ->select('pf.ID_PRODUCTO', 'pf.id_fabricante', 'est.nombre_comercial as nombre', 'pf.tipo', DB::raw('DATE_FORMAT(pf.vigente_hasta,"%d-%m-%Y") as vigencia_hasta'), DB::raw('DATE_FORMAT(pf.ULTIMO_PAGO,"%d-%m-%Y") as ULTIMO_PAGO'), 'c.nombre_pais')
                  ->orderBy('tipo', 'desc')
                  ->get();

    }

    public static function getPropietario($idPropietario)
    {
        return DB::table('cssp.cssp_propietarios as pp')
            ->join(DB::raw('(select id_propietario from cssp.cssp_productos where activo="A" group by id_propietario) as prod'), 'pp.id_propietario', '=', 'prod.id_propietario')
            ->leftJoin('cssp.cssp_paises as p', 'pp.id_pais', '=', 'p.id_pais')
            ->where('pp.ACTIVO', 'A')
            ->where('pp.ID_PROPIETARIO', $idPropietario)
            ->select('pp.id_propietario', 'pp.NOMBRE_PROPIETARIO', 'p.NOMBRE_PAIS', 'pp.DIRECCION', 'pp.EMAIL', 'pp.TELEFONO_1')
            ->first();
    }

    public static function getPropietarioByProd($idProducto)
    {

        return DB::table('cssp.cssp_propietarios as pp')
            ->join('cssp.cssp_productos as prod', 'pp.id_propietario', '=', 'prod.id_propietario')
            ->leftJoin('cssp.cssp_paises as p', 'pp.id_pais', '=', 'p.id_pais')
            ->where('prod.ID_PRODUCTO', $idProducto)
            ->select('pp.id_propietario', 'pp.NOMBRE_PROPIETARIO', 'p.NOMBRE_PAIS')
            ->first();
    }

    public static function getLabAcondiByProd($idProducto)
    {
        if ($idProducto) {
            //arreglar lo de paises
            return DB::table('cssp.siic_PRODUCTOS_LABORATORIOS as pl')
                ->join('dnm_establecimientos_si.est_establecimientos as est', 'pl.id_LABORATORIO', '=', 'est.idEstablecimiento')
                ->leftJoin('dnm_catalogos.cat_municipios as muni', 'est.idMunicipio', '=', 'muni.idMunicipio')
                ->leftJoin('dnm_catalogos.cat_departamentos as dep', 'muni.idDepartamento', '=', 'dep.idDepartamento')
                ->leftJoin('dnm_catalogos.cat_paises as pa', 'dep.idPais', '=', 'pa.codigoId')
                ->where('pl.id_producto', $idProducto)
                ->select('pl.ID_LABORATORIO', 'est.nombreComercial', DB::raw('ifnull(pa.nombre,"N\A") as nombre_pais'))
                ->get();
        }

    }

    public static function getLabsAcondiByProdCssp($idProducto)
    {

        return DB::table('cssp.siic_productos_laboratorios as pl')
            ->join('cssp.cssp_establecimientos as est', 'pl.ID_LABORATORIO', '=', 'est.id_establecimiento')
            ->join('cssp.cssp_paises as c', 'est.id_pais', '=', 'c.id_pais')
            ->where('pl.ID_PRODUCTO', $idProducto)
            ->select('pl.ID_LABORATORIO', 'est.NOMBRE_COMERCIAL as nombreComercial', DB::raw('ifnull(c.nombre_pais,"N\A") as nombre_pais'))
            ->get();


    }


    public static function getPresentacionByProd($idProducto)
    {

        if ($idProducto) {
            return DB::table('cssp.vw_productos_presentaciones')
                ->where('ID_PRODUCTO', $idProducto)
                ->get();

        }
    }

    public static function getPresentacionConcat($idProducto)
    {

        if ($idProducto) {
            return DB::table('cssp.vw_productos_presentaciones')
                ->where('ID_PRODUCTO', $idProducto)
                ->select(DB::raw('GROUP_CONCAT(PRESENTACION_COMPLETA," ",ACCESORIOS) as presentaciones'))
                ->get();

        }
    }

    public static function getProfesionalByProducto($idProducto)
    {

        $profesional = DB::table('cssp.siic_productos_profesionales as pprof')
            ->join('cssp.cssp_profesionales as prof', 'pprof.id_profesional', '=', 'prof.id_profesional')
            ->where('pprof.id_producto', $idProducto)
            ->select('pprof.id_profesional', 'pprof.id_poder', 'prof.nombres', 'prof.apellidos', 'prof.NIT')
            ->first();

        if ($profesional->NIT != null) {
            $persona = PersonaNatural::find($profesional->NIT);
            if ($persona != null) {
                $personamuni = Municipios::where('idMunicipio', $persona->idMunicipio)->first();
                $persona['municipio'] = $personamuni->nombreMunicipio;
                $persondep = Departamentos::where('idDepartamento', $personamuni->idDepartamento)->first();
                $persona['departamento'] = $persondep->nombreDepartamento;
                $pais = Paises::where('codigoId', $persondep->idPais)->first();
                $persona['pais'] = $pais->nombre;
                $tratamiento = Tratamiento::where('idTipoTratamiento', $persona->idTipoTratamiento)->first();
                if ($tratamiento != null) {
                    $persona['tratamiento'] = $tratamiento->nombreTratamiento;
                } else {
                    $persona['tratamiento'] = "";
                }
                $persona->nombres = $profesional->nombres;
                $persona->apellidos = $profesional->apellidos;
                $profesional->persona = $persona;
                $profesional->persona->tels = json_decode($persona->telefonosContacto);
            } else {
                $persona = new PersonaNatural();
                $persona->nitNatural = "1";
                $persona->numeroDocumento = "N/A";
                $persona->nombres = $profesional->nombres;
                $persona->apellidos = $profesional->apellidos;
                $persona->conocidoPor = "N/A";
                $persona->emailsContacto = "N/A";
                $persona->direccion = "N/A";
                $persona->municipio = "N/A";
                $persona->departamento = "N/A";
                $persona->pais = "N/A";
                $persona->tratamiento = "N/A";
                $persona->tels = null;
                $profesional->persona = $persona;
            }
        } else {

            if ($profesional->nombres != null and $profesional->apellidos != null) {
                $persona = new PersonaNatural();
                $persona->nitNatural = "1";
                $persona->numeroDocumento = "N/A";
                $persona->nombres = $profesional->nombres;
                $persona->apellidos = $profesional->apellidos;
                $persona->conocidoPor = "N/A";
                $persona->emailsContacto = "N/A";
                $persona->direccion = "N/A";
                $persona->municipio = "N/A";
                $persona->departamento = "N/A";
                $persona->pais = "N/A";
                $persona->tratamiento = "N/A";
                $persona->tels = null;
                $profesional->persona = $persona;
            } else {
                $profesional->persona = null;
            }
        }
        return $profesional;
    }

    public static function getFormaFarmByProd($idProducto)
    {

        if ($idProducto) {
            return DB::table('cssp.siic_productos_formas_farmaceuticas as pff')
                ->join('cssp.siic_formas_farmaceuticas as ff', 'pff.id_forma_farmaceutica', '=', 'ff.id_forma_farmaceutica')
                ->where('ff.activo', 'A')
                ->where('pff.id_producto', $idProducto)
                ->select('pff.id_forma_farmaceutica', 'ff.ID_FORMA_FARMACEUTICA_FAMILIA', 'ff.nombre_forma_farmaceutica', 'ff.activo')
                ->get();

        }
    }

}
