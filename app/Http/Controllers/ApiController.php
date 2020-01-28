<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Crypt;
use Session;
use Config;
use App\Models\Cssp\SolicitudesVueTramites;
use App\Models\Cssp\SolicitudesVue;
use App\Models\Cssp\Productos;
use App\Models\Sim\SimSolicitudes;
use App\Models\Sim\SimDictamenPost;
use App\Models\Sim\TramitesPost;
use App\Models\Sim\SolCodigoModelo;
use App\Models\Sim\SimProductos;
use App\Models\Sim\SolicitudesFabs;
use App\Models\Sim\DesistimientoSol;
use App\Models\Sim\CertificacionPost;
use App\PersonaNatural;
use GuzzleHttp\Client;

class ApiController extends Controller
{
    //
    public function resolucionSim($idSolicitud,$idTramite){

      //$idSolicitud=Crypt::decrypt($idSolicitud);
      //$idTramite=Crypt::decrypt($idTramite);
      $solicitud = SimSolicitudes::find($idSolicitud);
      //$tramite=$solicitud->soltramites()->first();
      $tramite = TramitesPost::find($idTramite);
      $producto= DB::table('sim.vw_productos_insumos_general')->where('ID_PRODUCTO',$solicitud->IM)->first();
      $solicitante= PersonaNatural::find($solicitud->NIT_SOLICITANTE);

        if(date("Y-m-d",strtotime($solicitud->FECHA_CREACION)) >= "2018-03-14"){
            $fabricante= SimSolicitudes::fabricantesResolucion($solicitud->IM);
        }
        else{
            $fabricante= DB::table('sim.sim_fabricantes as fb')
                ->join('sim.sim_productos_fabricantes as pf','fb.ID_FABRICANTE','=','pf.id_fabricante')
                ->join('cssp.cssp_paises as pa','fb.PAIS','=','pa.ID_PAIS')
                ->where('pf.id_producto',$solicitud->IM)
                ->select('fb.NOMBRE_FABRICANTE','fb.DIRECCION','pf.id_producto','pa.NOMBRE_PAIS')
                ->first();
        }



      $data['solicitante']=$solicitante;
      $data['producto']=$producto;
      $data['solicitud']=$solicitud;
      $data['tramite']=$tramite;
      $data['fabricante']=$fabricante;
      $data['modelos']=DB::table('sim.vw_productos_insumos_codigos_modelos')->where('ID_PRODUCTO',$solicitud->IM)->get();
      //dd($data);

      if($idTramite==3){
        $h=date('H',strtotime($solicitud->FECHA_CREACION));
        $mi=date('i',strtotime($solicitud->FECHA_CREACION));
        $y=date('Y',strtotime($solicitud->FECHA_CREACION));
        $d=date('d',strtotime($solicitud->FECHA_CREACION));
        $m=date('m',strtotime($solicitud->FECHA_CREACION));

        $hora=$this->numAletras($h);
        $min=$this->numAletras($mi);
        $dias=$this->numAletras($d);
        $year=$this->numAletras($y);

        $meses = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");

        $data['dia']="a las ".$hora.' horas '.$min." minutos del día ".$dias." de ".$meses[$m-1]." del ".$year;
        //$codmod=DB::table('sim.sim_solicitud_codigos_modelos')->where('solicitud_id','=',211)->get();
        //$data['codmod']=$codmod;


        $view =  \View::make('pdf.constancia',$data)->render();
        //dd($data);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
      }
      else if($idTramite==5){
        $solcodmod=SolCodigoModelo::where('solicitud_id',$idSolicitud)->get();
        $data['solcodmod']=$solcodmod;
        $resolucion= CertificacionPost::where('solicitud_id',$idSolicitud)->first();
        $data['resolucion']=$resolucion;
        //dd($data);
        $view =  \View::make('pdf.Sim.adiccioncodigo',$data)->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
      }
      elseif($solicitud->ESTADO_SOLICITUD==2){
         if($idTramite==15 || $idTramite==18 || $idTramite==17){
           return $this->desistimientoSolicitud($idSolicitud,$idTramite);
        }
        else{
          $resolucion= CertificacionPost::where('solicitud_id',$idSolicitud)->first();
          if($idTramite==1){
            $fabricantes=SolicitudesFabs::getFabricantesResol($idSolicitud);
            $data['fabricantes']=$fabricantes;
          }
          $data['resolucion']=$resolucion;
          //dd($data);
          $view =  \View::make('pdf.resolucionsim',$data)->render();

          $pdf = \App::make('dompdf.wrapper');
          $pdf->loadHTML($view);
        }

      }
      elseif($solicitud->IM==null && $solicitud->ESTADO_SOLICITUD==3){
        return $this->desistimientoSolicitud($idSolicitud,$idTramite);
      }
      elseif($solicitud->ESTADO_SOLICITUD==3){
        $dictamen= SimDictamenPost::where('solicitud_id',$idSolicitud)->first();
        $data['dictamen']=$dictamen;
        //dd($data);
        $view =  \View::make('pdf.Sim.resoluobservadasim',$data)->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
      }
     return $pdf->stream('Solicitud'.$idSolicitud.'.pdf');
    }

    public function  desistimientoSolicitud($idSolicitud,$idTramite){
      $solicitud = SimSolicitudes::find($idSolicitud);
      $data['solicitud']=$solicitud;
      $tramite = TramitesPost::find($idTramite);
      $data['tramite']=$tramite;
      if($idTramite==17 || $idTramite==18 || $idTramite==15){
        $resolucion= CertificacionPost::where('solicitud_id',$idSolicitud)->first();
        $data['resolucion']=$resolucion;
      }
      else{
        $desistimiento=DesistimientoSol::where('pim',$solicitud->PIM)->first();
        $data['desistimiento']=$desistimiento;
      }
      $data['idTramite']=$idTramite;
      //dd($data);
      $view =  \View::make('pdf.desistimiento',$data)->render();
      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);

     return $pdf->stream($tramite->nombre.$idSolicitud.'.pdf');
    }

    public function resolucionCosObservada($idSol){

        $idSolicitud=Crypt::decrypt($idSol);
        //$url="http://localhost/sicosmeticos/public/cosapi/resolucion/".$idSolicitud;
        $url="http://localhost:8081/sicosmeticos/public/cosapi/resolucion/".$idSolicitud;
        $client = new Client();
        $res = $client->request('GET',$url,[
            'headers' => [
                'tk' => '$2y$10$jkneujX8hEpQ0puFxXuAMOSvhEN9nfoThrGiuKl0CdECWvrq2wl.u'
            ]
        ]);

        return response($res->getBody(), $res->getStatusCode())->header('Content-Type', $res->getHeader('content-type'));
    }

    public static function numAletras($num, $fem = false, $dec = true) //$num es el numero que recibe cualquiera los otros parametros no se para que son
  {
     $matuni[2]  = "dos";
     $matuni[3]  = "tres";
     $matuni[4]  = "cuatro";
     $matuni[5]  = "cinco";
     $matuni[6]  = "seis";
     $matuni[7]  = "siete";
     $matuni[8]  = "ocho";
     $matuni[9]  = "nueve";
     $matuni[10] = "diez";
     $matuni[11] = "once";
     $matuni[12] = "doce";
     $matuni[13] = "trece";
     $matuni[14] = "catorce";
     $matuni[15] = "quince";
     $matuni[16] = "diecis&eacute;is";
     $matuni[17] = "diecisiete";
     $matuni[18] = "dieciocho";
     $matuni[19] = "diecinueve";
     $matuni[20] = "veinte";
     $matunisub[2] = "dos";
     $matunisub[3] = "tres";
     $matunisub[4] = "cuatro";
     $matunisub[5] = "quin";
     $matunisub[6] = "seis";
     $matunisub[7] = "sete";
     $matunisub[8] = "ocho";
     $matunisub[9] = "nove";

     $matdec[2] = "veint";
     $matdec[3] = "treinta";
     $matdec[4] = "cuarenta";
     $matdec[5] = "cincuenta";
     $matdec[6] = "sesenta";
     $matdec[7] = "setenta";
     $matdec[8] = "ochenta";
     $matdec[9] = "noventa";
     $matsub[3]  = 'mill';
     $matsub[5]  = 'bill';
     $matsub[7]  = 'mill';
     $matsub[9]  = 'trill';
     $matsub[11] = 'mill';
     $matsub[13] = 'bill';
     $matsub[15] = 'mill';
     $matmil[4]  = 'millones';
     $matmil[6]  = 'billones';
     $matmil[7]  = 'de billones';
     $matmil[8]  = 'millones de billones';
     $matmil[10] = 'trillones';
     $matmil[11] = 'de trillones';
     $matmil[12] = 'millones de trillones';
     $matmil[13] = 'de trillones';
     $matmil[14] = 'billones de trillones';
     $matmil[15] = 'de billones de trillones';
     $matmil[16] = 'millones de billones de trillones';

     //Zi hack
     $float=explode('.',$num);
     $num=$float[0];

     $num = trim((string)@$num);
     if ($num[0] == '-') {
      $neg = 'menos ';
      $num = substr($num, 1);
     }else
      $neg = '';
     while ($num[0] == '0') $num = substr($num, 1);
     if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
     $zeros = true;
     $punt = false;
     $ent = '';
     $fra = '';
     for ($c = 0; $c < strlen($num); $c++) {
      $n = $num[$c];
      if (! (strpos(".,'''", $n) === false)) {
       if ($punt) break;
       else{
        $punt = true;
        continue;
       }

      }elseif (! (strpos('0123456789', $n) === false)) {
       if ($punt) {
        if ($n != '0') $zeros = false;
        $fra .= $n;
       }else

        $ent .= $n;
      }else

       break;

     }
     $ent = '     ' . $ent;
     if ($dec and $fra and ! $zeros) {
      $fin = ' coma';
      for ($n = 0; $n < strlen($fra); $n++) {
       if (($s = $fra[$n]) == '0')
        $fin .= ' cero';
       elseif ($s == '1')
        $fin .= $fem ? ' una' : ' un';
       else
        $fin .= ' ' . $matuni[$s];
      }
     }else
      $fin = '';
     if ((int)$ent === 0) return 'Cero ' . $fin;
     $tex = '';
     $sub = 0;
     $mils = 0;
     $neutro = false;
     while ( ($num = substr($ent, -3)) != '   ') {
      $ent = substr($ent, 0, -3);
      if (++$sub < 3 and $fem) {
       $matuni[1] = 'una';
       $subcent = 'as';
      }else{
       $matuni[1] = $neutro ? 'un' : 'uno';
       $subcent = 'os';
      }
      $t = '';
      $n2 = substr($num, 1);
      if ($n2 == '00') {
      }elseif ($n2 < 21)
       $t = ' ' . $matuni[(int)$n2];
      elseif ($n2 < 30) {
       $n3 = $num[2];
       if ($n3 != 0) $t = 'i' . $matuni[$n3];
       $n2 = $num[1];
       $t = ' ' . $matdec[$n2] . $t;
      }else{
       $n3 = $num[2];
       if ($n3 != 0) $t = ' y ' . $matuni[$n3];
       $n2 = $num[1];
       $t = ' ' . $matdec[$n2] . $t;
      }
      $n = $num[0];
      if ($n == 1) {
       $t = ' ciento' . $t;
      }elseif ($n == 5){
       $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
      }elseif ($n != 0){
       $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
      }
      if ($sub == 1) {
      }elseif (! isset($matsub[$sub])) {
       if ($num == 1) {
        $t = ' mil';
       }elseif ($num > 1){
        $t .= ' mil';
       }
      }elseif ($num == 1) {
       $t .= ' ' . $matsub[$sub] . '?n';
      }elseif ($num > 1){
       $t .= ' ' . $matsub[$sub] . 'ones';
      }
      if ($num == '000') $mils ++;
      elseif ($mils != 0) {
       if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
       $mils = 0;
      }
      $neutro = true;
      $tex = $t . $tex;
     }
     $tex = $neg . substr($tex, 1) . $fin;
     //Zi hack --> return ucfirst($tex);
     //$end_num=ucfirst($tex).' '.$float[1].'/100';
    // return ucfirst($tex); ucfirst es la primera letra en mayuscula
    return $tex;
  }
}
