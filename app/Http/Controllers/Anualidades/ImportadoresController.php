<?php

namespace App\Http\Controllers\Anualidades;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mumpo\FpdfBarcode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Auth;
use Crypt;
use DB;
use Datatables;
use Session;
use App\fpdf\PDF_Code128;
use App\Models\Cssp\Mandamientos\Mandamiento;
use Config;
use App\Http\Controllers\Anualidades\VariosMetodosController;
use App\Http\Controllers\Anualidades\PagosMoraController;
class ImportadoresController extends Controller
{
     private $url=null;
    
    public function __construct() { 
        $this->url = Config::get('app.api');
    }

    public function index()
    {

      $data = ['title' 			=> 'Anualidades'
      ,'subtitle'			=> 'Anualidades Importadores'
      ,'breadcrumb' 		=> [
        ['nom'	=>	'Anualidades', 'url' => '#'],
        ['nom'	=>	'Anualidades Importadores', 'url' => '#']
      ]];
       
      $data['nombre']=Session::get('name').' '.Session::get('lastname');
       return view('recetas.anualidades.importadores.index',$data);
    
    }
    public function lista(Request $request){

        $numEsta=$request->num;
        $client = new Client();
        $res = $client->request('POST', $this->url.'anualidades/lista/importadores',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'numEstab'   =>$numEsta
            ]  
        
        ]);

        $r = json_decode($res->getBody());
        if($r->status==200){

                $collection = collect($r->data[0]);
                return Datatables::of($collection)
                 ->addColumn('combobox', function ($dt)use($request){

                    $annio = (date("Y")-1);
                    $year = date('Y',strtotime($dt->imp_vigencia));
                    $fechaVigencia = date('Y-m-d', strtotime($dt->imp_vigencia));
                    $fechaActual = date('Y-m-d');
                    $fechaLimite= date('Y-m-d',strtotime(''.date('Y').'-06-30'));
                   if ($annio==$year) {
                          //vERIFICANDO LA FECHA ACTUAL QUE NO SEA MAYOR A 31 DE MARZO
                        if ($fechaActual<=$fechaLimite) {
                          //Si la fecha actual es menor a la de licencia puede proceder
                          //countGeneradosEstablecimientos($idEstab)
                          $disable="";
                          $estado = VariosMetodosController::countGeneradosEstablecimientos($dt->imp_id);
                          }else{
                         //Fecha actual mayor a la del límite
                           $disable="disabled";
                           $estado=0; //BLOQUEADO,SIN PODER GENERAR MANDAMIENTO CON RETRASO
                          }

                    }else if($annio>$year) {
                           $disable="disabled";
                           $estado=1; //Observado, VIGENCIA CON RETRASO
                    }else if($annio<$year) {
                            $disable="disabled";
                            $estado=2; //Pagado, VIGENCIA PAGADA
                    }

                if($disable=='disabled'){
                    //NO GENERAR MANDAMIENTO
                     $text='';
                     if ($estado==0) {
                      $text.='<i class="fa fa-exclamation icon-circle icon-bordered icon-xs icon-danger"></i>';
                    }else if($estado==1){
                      $text.='<i class="fa fa-exclamation icon-circle icon-bordered icon-xs icon-danger"></i>';
                    }else if($estado==2){
                      $text.='<i class="fa fa-usd icon-circle icon-bordered icon-xs icon-primary"></i>';
                    }else if($estado==3){
                      $text.='<i class="fa fa-exclamation icon-circle icon-bordered icon-xs icon-danger"></i>';
                    }else if($estado==4){
                      $text.='<i class="fa fa-file-pdf-o icon-circle icon-bordered icon-xs icon-primary"></i>';
                    }else if($estado==5){
                      $text.="";
                    }
                
                }else{
                    //GENERAR MANDAMIENTO
                    $text='<input type="hidden" name="imp_id" id="imp_id" value="'.Crypt::encrypt($dt->imp_id).'"/><input type="hidden" name="imp_nombre" id="imp_nombre" value="'.Crypt::encrypt($dt->imp_nombre).'"/><input type="hidden" name="idPago" id="idPago" value="'.Crypt::encrypt('85803099095').'"/>
                    <input type="hidden" name="cuenta" id="cuenta" value="'.Crypt::encrypt($request->nombre).'"/>
                    <a class="btn btn-xs btn-success btn-perspective" onclick="generarMandamiento();" ><i class="fa fa-check-square-o"></i>Generar mandamiento</a>';
                    if ($estado==0){
                      $text.='<i class="fa fa-exclamation icon-circle icon-bordered icon-xs icon-danger"></i>';
                    }else if($estado==1){
                      $text.='<i class="fa fa-exclamation icon-circle icon-bordered icon-xs icon-danger"></i>';
                    }else if($estado==2){
                      $text.='<i class="fa fa-check icon-circle icon-bordered icon-xs icon-primary"></i>';
                    }else if($estado==3){
                      $text.='<i class="fa fa-exclamation icon-circle icon-bordered icon-xs icon-danger"></i>';
                    }else if($estado==4){
                      $text.='<i class="fa fa-file-pdf-o icon-circle icon-bordered icon-xs icon-primary"></i>';
                    }else if($estado==5){
                     $text.="";
                    }
                }
                return $text;

                })

                ->addColumn('valor', function ($dt) {
                  //CONSULTAMOS LA INFORMACIÓN DEL PAGO------------------
                    $client = new Client();
                   $res = $client->request('POST', $this->url.'mandamiento/pagosvarios/consulta',[
                        'headers' => [
                            'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                        ],
                            'form_params' =>[
                                'idPago'   =>'3713'
                      ] 
        
                   ]);
                   $r = json_decode($res->getBody());
                   $info=$r->data[0];
                   $p1 =$info->VALOR;
                   //---------------------------------------
                return '<h4><span class="label label-success"> $'.$p1.'</span></h4>';
                   
                  })
                ->addColumn('detalle', function ($dt) {
                     $row=VariosMetodosController::comprobarHojaImportador($dt->imp_id);
                      if($row==''){
                        return '<a class="btn btn-xs btn-success btn-perspective" onclick="enviarHoja(\''.$dt->imp_id.'\');" ><i class="fa fa-check-square-o"></i>Enviar Hoja</a>';
                      }else{
                       return '<a  class="btn btn-xs btn-success btn-perspective" target="_blank"  href="'.route('imprimir.boleta.importador',['idEnlace'=>Crypt::encrypt($row)]).'"  ><i class="fa fa-edit"></i>Imprimir hoja</a>';
                      }
                   
                 })
                      
                ->make(true);
        }else{
                    $results = array(
                    "draw"            => 0,                 
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                    return json_encode($results);
        }
    
        

    }
    
 

    public function store(Request $request)
    {  
   
      if($request->idPago=='' || $request->imp_id=='' || $request->imp_nombre==''){
        Session::flash('msnError','¡Problemas al generar el mandamiento!');
        return back();
      }
        $idPago=Crypt::decrypt($request->idPago);
        $imp_id=Crypt::decrypt($request->imp_id);
        $imp_nombre=Crypt::decrypt($request->imp_nombre);
        $cuenta=Crypt::decrypt($request->cuenta);

        //CONSULTAMOS LA INFORMACIÓN DEL PAGO
        $client = new Client();
        $res = $client->request('POST', $this->url.'mandamiento/consulta/pago',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'cod1'   =>$idPago,
                    'cod2'   =>$idPago
          ] 
        
        ]);

      $r = json_decode($res->getBody());
      $info=$r->data[0];
      $p1 =$info->p1;

      //dd($p1);
      $objeto = new PagosMoraController();
      $aplicaMora = $objeto->comprobarFecha();

      $TOTAL = $p1->VALOR;
      $idTipoPago=$p1->ID_TIPO_PAGO;
       $objeto = new PagosMoraController;
      if ($aplicaMora) {
        $porcentaje = $objeto->getValorPorcentual();
        $diferencia = $objeto->mesDiferencias();
        $totalMora= ($TOTAL * $porcentaje)*$diferencia;
        $TOTAL= $TOTAL+$totalMora;
        $TOTAL=number_format((float)$TOTAL, 2, '.', '');
        $dataMora = $objeto->datosIdTipoPago($idTipoPago);
      }else{
        $totalMora=0;
        $TOTAL=number_format((float)$TOTAL, 2, '.', '');
      }
      //dd($dataMora->CODIGO);
      //[0]->codPago
      if($aplicaMora) {
        $a="/";$b="/31";
        DB::select(DB::raw("SELECT REPLACE(CURDATE() + INTERVAL 60 DAY,'-','/') AS VENCIMIENTO"));
        $VENCIMIENTO = DB::select(DB::raw('SELECT CONCAT(YEAR(CURDATE()),"'.$a.'",MONTH(CURDATE()),"'.$b.'") AS VENCIMIENTO'));
     
        }else{
        $VENCIMIENTO =  DB::select(DB::raw('SELECT CONCAT(YEAR(CURDATE()),\'\/03\/31\') AS VENCIMIENTO'));
       
      }

      //traer ID_MANDAMIENTO
      $ID_MANDAMIENTO =DB::select(DB::raw('SELECT MAX(ID_MANDAMIENTO)+1  AS LAST_ID FROM cssp.cssp_mandamientos'));

       //traer NPE
      if ($aplicaMora) {
            
        $NPE = DB::select(DB::raw('SELECT cssp.cod_NPE_ANUALIDAD_MULTA("'.$TOTAL.'") AS COD_NPE'));
      }else{
        $NPE = DB::select(DB::raw('SELECT cssp.cod_NPE_ANUALIDAD("'.$TOTAL.'") AS COD_NPE'));
      }
     // dd(DB::select(DB::raw('SELECT cssp.cod_NPE_ANUALIDAD_MULTA("'.$TOTAL.'.00'.'") AS COD_NPE')));
        //TRAER COD_BARRA
      if ($aplicaMora) {
        $COD_BARRA = DB::select(DB::raw('SELECT cssp.COD_BARRA_ANUALIDAD_MULTA("'.$TOTAL.'") AS COD_BARRA'));
      }else{
        $COD_BARRA = DB::select(DB::raw('SELECT cssp.COD_BARRA_ANUALIDAD("'.$TOTAL.'") AS COD_BARRA'));
    
      }
     //dd(DB::select(DB::raw('SELECT cssp.cod_BARRA_ANUALIDAD_MULTA("'.$TOTAL.'") AS COD_BARRA')));
      //TRAER COD_BARRA_TEXTO

      if ($aplicaMora) {
        $COD_BARRA_TEXTO = DB::select(DB::raw('SELECT cssp.cod_BARRA_TEXTO_ANUALIDAD_MULTA("'.$TOTAL.'") AS COD_BARRA_TEXTO'));
      }else{
        $COD_BARRA_TEXTO = DB::select(DB::raw('SELECT cssp.cod_BARRA_TEXTO_ANUALIDAD("'.$TOTAL.'") AS COD_BARRA_TEXTO')); 
      }
      //dd(DB::select(DB::raw('SELECT cssp.cod_BARRA_TEXTO_ANUALIDAD_MULTA("'.$TOTAL.'.00'.'") AS COD_BARRA_TEXTO')));
      $idProfesional = Session::get('prof');
      try {
          $mandamiento = new Mandamiento;
          $mandamiento->ID_MANDAMIENTO = $ID_MANDAMIENTO[0]->LAST_ID;
          $mandamiento->CODIGO_BARRA = $COD_BARRA[0]->COD_BARRA;
          $mandamiento->CODIGO_BARRA_TEXTO = $COD_BARRA_TEXTO[0]->COD_BARRA_TEXTO;
          $mandamiento->NPE = $NPE[0]->COD_NPE;
          $mandamiento->FECHA = date('Y/m/d');
          $mandamiento->HORA = date('H:i:s');
          $mandamiento->ID_CLIENTE = $idProfesional;
          $mandamiento->A_NOMBRE = Session::get('name').' '.Session::get('lastname');
          $mandamiento->FECHA_VENCIMIENTO = $VENCIMIENTO[0]->VENCIMIENTO;
          $mandamiento->ID_JUNTA = 'U21';
          $mandamiento->TOTAL = $TOTAL;
          $mandamiento->NOMBRE_CLIENTE = Session::get('name').' '.Session::get('lastname');
          $mandamiento->POR_CUENTA = mb_strtoupper($cuenta, 'UTF-8');
          $mandamiento->ID_USUARIO_CREACION = Session::get('user').'@'.$request->ip();
          $mandamiento->save();
          //detalle
          $mandamiento->detalle()->create([
            'ID_CLIENTE'=>$imp_id
            ,'ID_TIPO_PAGO'=>3730
            ,'VALOR'=>$TOTAL-$totalMora
            ,'TIPO_ANUALIDAD'=>5
            ,'COMENTARIOS'=>$request->comentario
            ,'NOMBRE_CLIENTE'=>$imp_nombre
            ,'COMENTARIOS_ANEXOS'=>''
          ]);
          if ($aplicaMora){
            $totalMora=number_format((float)$totalMora, 2, '.', '');
            $mandamiento->detalle()->create([
            'ID_CLIENTE'=>$dataMora->CODIGO
            ,'ID_TIPO_PAGO'=>$dataMora->ID_TIPO_PAGO
            ,'VALOR'=>$totalMora
            ,'TIPO_ANUALIDAD'=>0
            ,'COMENTARIOS'=>$request->comentario
            ,'NOMBRE_CLIENTE'=>$dataMora->NOMBRE_TIPO_PAGO
            ,'COMENTARIOS_ANEXOS'=>''
          ]);
          }

          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
          define('FPDF_FONTPATH',app_path().'/fpdf/font/');
          $pdf=new PDF_Code128('P','mm','legal');
          $pdf->AddPage();
    $image1 = "img/escudo.jpg";
    $image2 = "img/dnm.jpg";
    $pdf->SetXY(5,2);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,5,'NIT 0614-020312-105-7',0,1,'C');
    $pdf->SetFont('Times','',8);
    $pdf->Cell(0,4,'MANDAMIENTO DE INGRESOS',0,1,'C');
    $pdf->SetFont('Times','',7);


                $precio = $p1->VALOR;
                $tipo  = $p1->ID_TIPO_PAGO;
                $codigo = $p1->CODIGO;
                $descripcion   = $p1->NOMBRE_TIPO_PAGO;
                $unidad  = $p1->ID_JUNTA;
                $pdf->Cell(0,4,'UNIDAD DE '.$unidad.' - PAGOS VARIOS WEB',0,1,'C');


    $pdf->SetFont('Times','',7);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->Cell(150,4,'Cliente:  '.$mandamiento->ID_CLIENTE.' - '.utf8_decode($mandamiento->NOMBRE_CLIENTE).'',0,1,'J');
    $pdf->SetXY($x + 135, $y);
    $pdf->SetFont('Times','B',10);
    $pdf->Cell(10,4,'Por: $'.$mandamiento->TOTAL.'                        No.: '.$mandamiento->ID_MANDAMIENTO.'',0,1,'J');
    $pdf->SetFont('Times','',7);
    $pdf->Cell(0,4,'Por Cuenta de: '.utf8_decode($mandamiento->POR_CUENTA).'                                                                                                                                                                                                 ',0,1,'J');
    $pdf->Cell(0,4,'_____________________________________________________________________________________________________________________________________________________________ ',0,1,'J');
                $precio = $p1->VALOR;
                $tipo  = $p1->ID_TIPO_PAGO;
                $codigo = $p1->CODIGO;
                $descripcion   = $p1->NOMBRE_TIPO_PAGO;
                $unidad  = $p1->ID_JUNTA;
                $pdf->Cell(15,5,$codigo,0,'J',0);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(165,3,$descripcion,0,'L',false);
                $pdf->SetXY($x + 165, $y);
                $pdf->MultiCell(20,3,'$ '.$precio.'',0,'L',false);
                $pdf->Ln();
                $pdf->MultiCell(165,3,$comentarios,0,'L',false);
                  //*****
                $pdf->Ln();
    if ($aplicaMora) {
      $pdf->Cell(15,5,$dataMora->CODIGO,0,'J',0);
      $x = $pdf->GetX();
      $y = $pdf->GetY();
      $pdf->MultiCell(165,3,$dataMora->NOMBRE_TIPO_PAGO,0,'L',false);
      $pdf->SetXY($x + 165, $y);
      $pdf->MultiCell(20,3,'$ '.$totalMora.'',0,'L',false);


    }
$pdf->SetX($x);
$pdf->SetFont('Times','',7);
$pdf->SetXY(10,45);
$pdf->Write(5,'_____________________________________________________________________________________________________________________________________________________________',0,1,'J');
$pdf->SetFont('Times','B',7);
$pdf->SetXY(190,50);
$pdf->Write(5,'$ '.$mandamiento->TOTAL.'',0,1,'R',0);

//A set
$pdf->SetXY(9,2);
$pdf->Image($image1);
$pdf->SetXY(190,2);
$pdf->Image($image2);
$pdf->SetXY(160,10);
$pdf->SetFont('Arial','',10);
$pdf->Write(5,'Decreto 417');
$pdf->SetFont('Times','',7);
$pdf->SetXY(90,50);
$pdf->Write(5,'NPE:'.$mandamiento->NPE.'');
$pdf->SetXY(10,55);
$pdf->Write(5,'Emitido:'.$mandamiento->FECHA.'');
$pdf->SetXY(10,60);
$pdf->SetFont('Times','B',10);
$pdf->Write(5,'Vencimiento:'.$mandamiento->FECHA_VENCIMIENTO.'');
$pdf->SetFont('Times','',7);
$pdf->Code128(70,55,$mandamiento->CODIGO_BARRA,80,6);
$pdf->SetXY(75,60);
$pdf->Write(5,$mandamiento->CODIGO_BARRA_TEXTO);
$pdf->SetXY(180,55);
$pdf->Write(5,'Copia: Banco');
$pdf->SetXY(180,60);
$pdf->Write(5,'Usuario: '.$mandamiento->ID_CLIENTE.'');
$pdf->SetXY(10,65);
$pdf->Write(4,utf8_decode('Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podrá ser pagado en la red de las Agencias del Banco Agrícola, S.A.'),0,0,'J');

//segunda copia
$pdf->SetXY(10,87);
$pdf->SetFont('Arial','',12);
    $pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,5,'NIT 0614-020312-105-7',0,1,'C');
    $pdf->SetFont('Times','',8);
    $pdf->Cell(0,4,'MANDAMIENTO DE INGRESOS',0,1,'C');
    $pdf->SetFont('Times','',7);
                $precio = $p1->VALOR;
                $tipo  = $p1->ID_TIPO_PAGO;
                $codigo = $p1->CODIGO;
                $descripcion   = $p1->NOMBRE_TIPO_PAGO;
                $unidad  = $p1->ID_JUNTA;
                $pdf->Cell(0,4,'UNIDAD DE '.$unidad.' - PAGOS VARIOS WEB',0,1,'C');

    $pdf->SetFont('Times','',7);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->Cell(150,4,'Cliente:  '.$mandamiento->ID_CLIENTE.' - '.utf8_decode($mandamiento->NOMBRE_CLIENTE).'',0,1,'J');
    $pdf->SetXY($x + 135, $y);
    $pdf->SetFont('Times','',10);
    $pdf->Cell(10,4,'Por: $'.$TOTAL.'                        No.: '.$mandamiento->ID_MANDAMIENTO.'',0,1,'J');
    $pdf->SetFont('Times','',7);
    $pdf->Cell(0,4,'Por Cuenta de: '.utf8_decode($mandamiento->POR_CUENTA).'                                                                                                                                                                                                 ',0,1,'J');
    $pdf->Cell(0,4,'_____________________________________________________________________________________________________________________________________________________________ ',0,1,'J');
                $precio = $p1->VALOR;
                $tipo  = $p1->ID_TIPO_PAGO;
                $codigo = $p1->CODIGO;
                $descripcion   = $p1->NOMBRE_TIPO_PAGO;
                $unidad  = $p1->ID_JUNTA;

                $pdf->Cell(15,5,$codigo,0,'J',0);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(165,3,$descripcion,0,'L',false);
                $pdf->SetXY($x + 165, $y);
                $pdf->MultiCell(20,3,'$ '.$precio.'',0,'L',false);

                $pdf->Ln();

                $pdf->MultiCell(165,3,$comentarios,0,'L',false);
                $pdf->Ln();
                //*****
                if ($aplicaMora) {
                  $pdf->Cell(15,5,$dataMora->CODIGO,0,'J',0);
                  $x = $pdf->GetX();
                  $y = $pdf->GetY();
                  $pdf->MultiCell(165,3,$dataMora->NOMBRE_TIPO_PAGO,0,'L',false);
                  $pdf->SetXY($x + 165, $y);
                  $pdf->MultiCell(20,3,'$ '.$totalMora.'',0,'L',false);


                }

$pdf->SetX($x);


$pdf->SetFont('Times','',7);
$pdf->SetXY(10,130);
$pdf->Write(5,'_____________________________________________________________________________________________________________________________________________________________',0,1,'J');
$pdf->SetFont('Times','B',7);
$pdf->SetXY(190,135);
$pdf->Write(5,'$ '.$TOTAL.'',0,1,'R',0);

//A set
$pdf->SetXY(9,85);
$pdf->Image($image1);
$pdf->SetXY(190,85);
$pdf->Image($image2);
$pdf->SetXY(150,95);
$pdf->SetFont('Arial','',10);
$pdf->Write(5,'Decreto 417');
$pdf->SetFont('Times','',7);
$pdf->SetXY(90,135);
$pdf->Write(5,'NPE:'.$mandamiento->NPE.'');
$pdf->SetXY(10,140);
$pdf->Write(5,'Emitido:'.$mandamiento->FECHA.'');
$pdf->SetXY(10,145);
$pdf->SetFont('Times','B',10);
$pdf->Write(5,'Vencimiento:'.$mandamiento->FECHA_VENCIMIENTO.'');
$pdf->SetFont('Times','',7);
$pdf->Code128(70,140,$mandamiento->CODIGO_BARRA,80,6);
$pdf->SetXY(75,145);
$pdf->Write(5,$mandamiento->CODIGO_BARRA_TEXTO);
$pdf->SetXY(180,140);
$pdf->Write(5,'Copia: Cliente');
$pdf->SetXY(180,145);
$pdf->Write(5,'Usuario: '.$mandamiento->ID_CLIENTE.'');
$pdf->SetXY(10,150);
$pdf->Write(4,utf8_decode('Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podrá ser pagado en la red de las Agencias del Banco Agrícola, S.A.'),0,0,'J');
    				$pdf->Output('mandamiento_'.$mandamiento->ID_MANDAMIENTO.'.pdf','I');
      } catch (Exception $e) {

      }

    }

      public function imprimirHoja($idEnlace){ 
      $idEnlace=Crypt::decrypt($idEnlace);
      if($idEnlace==''){
        Session::flash('msnError','¡Problemas al generar el mandamiento!');
        return back();
      }


        $client = new Client();
        $res = $client->request('POST', $this->url.'anualidades/get/hoja/importadores',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idEnlace'   =>$idEnlace
          ] 
        
        ]);

      $r = json_decode($res->getBody());
      $pdf=new PDF_Code128('P','mm','Letter');

  /*IMPRIMIENDO ANEXOSS*/
  $pdf->AddPage();
  $image1 = "img/escudo.jpg";
  $image2 = "img/dnm.jpg";
  $pdf->SetXY(5,5);
  $pdf->SetFont('Arial','',12);

  $pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
  $pdf->SetFont('Arial','',7);
  $pdf->Cell(0,5,'FORMULARIO PARA PAGO DE ANUALIDADES COMO IMPORTADOR',0,1,'C');
  $pdf->SetFont('Times','',8);
  $pdf->SetFont('Times','',7);
  //Imágenes
  $pdf->SetXY(9,2);
  $pdf->Image($image1);
  $pdf->SetXY(190,2);
  $pdf->Image($image2);
  $pdf->SetXY(160,10);
  $pdf->SetFont('Arial','',10);
  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $pdf->SetXY($x-150, $y+5);
  $pdf->ln();
  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $pdf->SetFont('Times','',7);
  $pdf->ln(); 

      if($r->status==200){
      $info=$r->data[0];

      //dd($info);
      $pdf->Cell(195,5,"TIPO DE ESTABLECIMIENTO: ".utf8_decode($info->NOMBRE_TIPO_ESTABLECIMIENTO),1,1,'L');
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(150,5,"NOMBRE: ".utf8_decode($info->nombre_importador_enlace),1,1,'L');
        $pdf->SetXY($x+150, $y);
        $pdf->Cell(45,5,"No. INSCRIPCION: ".utf8_decode($info->id_importador_enlace),1,1,'L');
        $pdf->MultiCell(195,4,''.utf8_decode("DIRECCION: ".$info->direccion_importador_enlace).'',1,'J',false);

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(95,5,"MUNICIPIO: ".utf8_decode($info->municipio_importador),1,1,'L');
        $pdf->SetXY($x+95, $y);
        $pdf->Cell(100,5,"DEPARTAMENTO: ".utf8_decode($info->departamento_importador),1,1,'L');

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(95,5,"TELEFONO : ".utf8_decode($info->telefono_importador),1,1,'L');
        $pdf->SetXY($x+95, $y);
        $pdf->Cell(100,5,"CORREO: ".utf8_decode($info->correo_importador),1,1,'L');
        $pdf->Cell(195,5,"TARJETA IVA: ".utf8_decode($info->tarjeta_iva_importador),1,1,'L');


        $x = $pdf->GetX();
        $y = $pdf->GetY();
        if($info->tipo_propietario_importador==1){
          $propietario="JURIDICO";
        }else{
          $propietario="NATURAL";
        }
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(35,5,"PROPIETARIO: ".utf8_decode($propietario),1,1,'L');
        $pdf->SetXY($x+35, $y);

        $pdf->SetFont('Times','',7);
      
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        if($info->tipo_propietario_importador==1){
          
          $pdf->Cell(95,5,"No. DUI: NO APLICA.",1,1,'L');
        }else{
          $pdf->Cell(95,5,"No. DUI: ".utf8_decode($info->dui_propietario),1,1,'L');
        }

        $pdf->SetXY($x+95, $y);
        $pdf->Cell(65,5,"No NIT: ".utf8_decode($info->nit_importador_enlace),1,1,'L');
          
        if($info->tipo_propietario_importador==1){
          $pdf->Cell(195,5,"ABREVIATURA DE SOCIEDAD: ".utf8_decode($info->abreviatura_sociedad),1,1,'L');

        }else{
          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(95,5,"CARNET RESIDENTE : ".utf8_decode($info->carnet_residente_propietario),1,1,'L');
          $pdf->SetXY($x+95, $y);
          $pdf->Cell(100,5,"PROFESION: ".utf8_decode($info->profesion_propietario),1,1,'L');

        }

          
      

        if($info->tipo_propietario_importador==1){
          /*seccion representante legal*/
          $x = $pdf->GetX();
          $y = $pdf->GetY();
          
          $pdf->SetFont('Arial','B',7);
          $pdf->Cell(35,5,"REPRESENTANTE LEGAL",1,1,'L');
          $pdf->SetXY($x+35, $y);
          $pdf->SetFont('Times','',7);
          $pdf->Cell(160,5,"NOMBRE: ".utf8_decode($info->nombre_representante),1,1,'L');

          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(95,5,"No. DUI: ".utf8_decode($info->dui_representante),1,1,'L');
          $pdf->SetXY($x+95, $y);
          $pdf->Cell(100,5,"No NIT: ".utf8_decode($info->nit_representante),1,1,'L');
            $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(95,5,"TELEFONO FIJO: ".utf8_decode($info->telefono_representante),1,1,'L');
          $pdf->SetXY($x+95, $y);
          $pdf->Cell(100,5,"CORREO: ".utf8_decode($info->correo_representante),1,1,'L');
          /*FIN representante legal*/
        }
        $pdf->SetFont('Arial','B',7);
        $pdf->MultiCell(195,4,''.utf8_decode("GIRO A LA QUE SE DEDICA"),1,'J',false);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetFont('Times','B',7);
        $pdf->Cell(25,5,"IMPORTACION",0,1,'L');
        $pdf->SetXY($x+25, $y);
        if($info->dedica_importacion==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        $pdf->SetXY($x+50, $y);
        $pdf->Cell(25,5,"DISTRIBUCION: ",0,1,'R');
        $pdf->SetXY($x+75, $y);
        if($info->dedica_distribucion==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }

        $pdf->SetXY($x+100, $y);
        $pdf->Cell(25,5,"ORG. RELIGIOSAS: ",0,1,'R');
        $pdf->SetXY($x+125, $y);
        if($info->dedica_org_religiosas==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }

        $pdf->SetXY($x, $y+7);
        $pdf->Cell(25,5,"FABRICACION: ",0,1,'R');
        $pdf->SetXY($x+25, $y+7);
        if($info->dedica_fabricacion==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        $pdf->SetXY($x+50, $y+7);
        $pdf->Cell(25,5,"TRANSPORTE: ",0,1,'R');
        $pdf->SetXY($x+75, $y+7);
        if($info->dedica_transporte==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }

        $pdf->SetXY($x+100, $y+7);
        $pdf->Cell(25,5,"CONSUMIDOR: ",0,1,'R');
        $pdf->SetXY($x+125, $y+7);
        if($info->dedica_consumidor==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }


        $pdf->SetXY($x, $y+14);
        $pdf->Cell(25,5,"COMERCIALIZACION: ",0,1,'R');
        $pdf->SetXY($x+25, $y+14);
        if($info->dedica_comercializacion==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        $pdf->SetXY($x+50, $y+14);
        $pdf->Cell(25,5,"ALMACENAMIENTO: ",0,1,'R');
        $pdf->SetXY($x+75, $y+14);
        if($info->dedica_almacenamiento==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }

        $pdf->SetXY($x+100, $y+14);
        $pdf->Cell(25,5,"MAQUILA: ",0,1,'R');
        $pdf->SetXY($x+125, $y+14);
        if($info->dedica_maquila==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        $pdf->ln();
        /*SECCION CATEGORIA*/
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(45,5,utf8_decode("CATEGORIA DE IMPORTADOR"),0,1,'L');
        $pdf->SetXY($x+45, $y);
        $pdf->SetFont('Times','B',7);
        $pdf->Cell(5,5,"A",0,1,'L');
        $pdf->SetXY($x+50, $y);
        if($info->categoria_importador=="A"){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        $pdf->SetXY($x+75, $y);
        $pdf->Cell(5,5,"B",0,1,'R');
        $pdf->SetXY($x+80, $y);
        if($info->categoria_importador=="B"){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        $pdf->SetXY($x+105, $y);
        $pdf->Cell(5,5,"C",0,1,'R');
        $pdf->SetXY($x+110, $y);
        if($info->categoria_importador=="C"){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        $pdf->SetXY($x+130, $y);
        $pdf->Cell(5,5,"D",0,1,'R');
        $pdf->SetXY($x+135, $y);
        if($info->categoria_importador=="D"){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        /*FINAL SECCION CATEGORIA*/
        $pdf->ln();
        /*PRODUCTOS IMPORTA*/
        $pdf->SetFont('Arial','B',7);
        $pdf->MultiCell(195,4,''.utf8_decode("TIPO DE PRODUCTO QUE ACTUALMENTE IMPORTA"),1,'J',false);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetFont('Times','B',7);
        $pdf->Cell(25,5,"COSMETICOS",0,1,'L');
        $pdf->SetXY($x+25, $y);
        if($info->importa_cosmeticos==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        $pdf->SetXY($x+50, $y);
        $pdf->Cell(25,5,"MATERIAS PRIMAS: ",0,1,'R');
        $pdf->SetXY($x+75, $y);
        if($info->importa_materias_primas==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }

        $pdf->SetXY($x+100, $y);
        $pdf->Cell(25,5,"INSUMOS MEDICOS: ",0,1,'R');
        $pdf->SetXY($x+125, $y);
        if($info->importa_insumos_medicos==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }

        $pdf->SetXY($x, $y+7);
        $pdf->Cell(25,5,"HIGIENICOS: ",0,1,'R');
        $pdf->SetXY($x+25, $y+7);
        if($info->importa_higienicos==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        $pdf->SetXY($x+50, $y+7);
        $pdf->Cell(25,5,"PROD. QUIMICOS: ",0,1,'R');
        $pdf->SetXY($x+75, $y+7);
        if($info->importa_quimicos==1){
          $pdf->Cell(5,5,"X",1,1,'L');
        }else{
          $pdf->Cell(5,5,"",1,1,'L');
        }
        /*FINAL PRODUCTOS IMPORTA*/
        $pdf->ln();
        $pdf->Cell(10,4,''.utf8_decode("N°").'',1,0,'C');
        $pdf->Cell(70,4,''.utf8_decode("NOMBRE").'',1,0,'C');
        $pdf->Cell(60,4,''.utf8_decode("DIRECCION").'',1,0,'C');
        $pdf->Cell(45,4,''.utf8_decode("TELEFONO").'',1,0,'C');
        $pdf->Cell(10,4,''.utf8_decode("CATEG.").'',1,0,'C');
        $counter=1;
        $pdf->ln();
        $queryBodegas = DB::select(DB::raw("SELECT * FROM cssp.si_uj_portal_bodegas_importador where id_imp_enlace=".$idEnlace.""));

        if (count($queryBodegas)>0) {
          foreach($queryBodegas as $rest){
            //Alto del prodsucto
            $altoProd=$pdf->GetMultiCellHeight(70,4,$rest->nombre_bodega,1,'L');
            $altoFab=$pdf->GetMultiCellHeight(60,4,$rest->direccion_bodega,1,'L');
            $altoTel=$pdf->GetMultiCellHeight(45,4,$rest->telefono_bodega,1,'L');
            $altoCat=$pdf->GetMultiCellHeight(10,4,$rest->categoria_bodega,1,'L');
            
            if ($altoProd>$altoFab) {
              $altoFinal=$altoProd;
              
            }else{
              $altoFinal=$altoFab;
            }
            $consProd= ($altoFinal/($altoProd/4));
            $consFab= ($altoFinal/($altoFab/4));
            $consTel= ($altoFinal/($altoTel/4));
            $consCat= ($altoFinal/($altoCat/4));


            $pdf->MultiCell(10,$altoFinal,''.$counter.'',1,'C',false);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($x+10, $y-$altoFinal);
            $pdf->MultiCell(70  ,$consProd,''.utf8_decode($rest->nombre_bodega).'',1,'C',false);
            $pdf->SetXY($x+80, $y-$altoFinal);
            $pdf->MultiCell(60,$consFab,''.utf8_decode("".$rest->direccion_bodega."").'',1,'J',false);
            $pdf->SetXY($x+140, $y-$altoFinal);
            $pdf->MultiCell(45,$consTel,''.utf8_decode("".$rest->telefono_bodega."").'',1,'J',false);
            $pdf->SetXY($x+185, $y-$altoFinal);

            $pdf->MultiCell(10,$consCat,''.utf8_decode("".$rest->categoria_bodega."").'',1,'J',false);
            $pdf->ln();
            $pdf->SetXY(10,$y);
            $counter +=1;
          
          }
        }

        $pdf->ln();
        /*seccion profesional*/
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(45,5,"PROFESIONAL RESPONSABLE",1,1,'L');
        $pdf->SetXY($x+45, $y);
        $pdf->SetFont('Times','',7);
        $pdf->Cell(150,5,"NOMBRE: ".utf8_decode($info->nombre_profesional),1,1,'L');
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(95,5,"J.V.P.Q: ".utf8_decode($info->jvpqf_profesional),1,1,'L');
        $pdf->SetXY($x+95, $y);
        $pdf->Cell(100,5,"No NIT: ".utf8_decode($info->nit_profesional),1,1,'L');
          $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(95,5,"TELEFONO FIJO: ".utf8_decode($info->telefono_profesional),1,1,'L');
        $pdf->SetXY($x+95, $y);
        $pdf->Cell(100,5,"CORREO: ".utf8_decode($info->correo_profesional),1,1,'L');
        /*FIN seccion profesional*/
        /*seccion notificaciones*/
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(195,5,"NOTIFICACIONES",1,1,'L');
        
        $pdf->SetFont('Times','',7);
        $pdf->MultiCell(195,4,''.utf8_decode("LUGAR PARA NOTIFICACIONES: ".$info->notificar_lugar).'',1,'J',false);
        $pdf->MultiCell(195,4,''.utf8_decode("PERSONA AUTORIZADA: ".$info->persona_lugar).'',1,'J',false); 
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(95,5,"TELEFONO: ".utf8_decode($info->telefono_lugar),1,1,'L');
        $pdf->SetXY($x+95, $y);
        $pdf->Cell(100,5,"CORREO: ".utf8_decode($info->correo_lugar),1,1,'L');

      }else{
      $x = $pdf->GetX();
      $y = $pdf->GetY();
      $pdf->SetXY($x+15, $y-4);
      $pdf->MultiCell(35,4,''.utf8_decode("Sin registros").'',1,'C',false);
      $pdf->SetXY($x+50, $y-4);
      $pdf->MultiCell(150,4,''.utf8_decode("Sin registros").'',1,'J',false);
      $pdf->ln();
      $pdf->SetXY(10,$y); 
      }
      $pdf->Output('listadoEspecialidades.pdf','I');

    }
}
