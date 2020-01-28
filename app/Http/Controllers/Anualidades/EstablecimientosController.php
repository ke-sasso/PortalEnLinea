<?php

namespace App\Http\Controllers\Anualidades;
use App\Models\EstablecimientoPortal;
use App\Models\Establecimientos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mumpo\FpdfBarcode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Auth;
use Crypt;
use DB;
use Validator;
use Log;
use Carbon\Carbon;
use Datatables;
use Session;
use App\fpdf\PDF_Code128;
use App\Models\Cssp\Mandamientos\Mandamiento;
use Config;
use Illuminate\Database\Eloquent\Collection;



class EstablecimientosController extends Controller
{
     private $url=null;

    public function __construct() {
        $this->url = Config::get('app.api');
    }

    public function index(){

            $data = ['title' 			=> 'Anualidades'
            ,'subtitle'			=> 'Establecimientos e importadores'
            ,'breadcrumb' 		=> [
              ['nom'	=>	'Anualidades', 'url' => '#'],
              ['nom'	=>	'Establecimientos e importadores', 'url' => '#']
            ]];
            //CONSULTAMOS LOS TIPOS DE ESTABLECIMIENTOS
          if(!Session::has('msnErrorServicio')){
                try {
                      $client = new Client();
                      $res = $client->request('POST', $this->url.'anualidades/get/tipoEstablecimiento',[
                            'headers' => [
                                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                      ]]);
                }catch(\GuzzleHttp\Exception\RequestException $e){
                  Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                  Session::flash('msnError','<b>¡ESTIMADO USUARIO OCURRIO UN PROBLEMA AL CARGAR LA INFORMACIÓN, POR FAVOR ESPERE UN MOMENTO E INTENTA NUEVAMENTE!</b>');
                  Session::flash('msnErrorServicio','ERROR ODIN');
                  $this->emailErrorOdin('¡Problemas al consultar información de establecimientos anualidades! - FECHA '.Carbon::now());
                  return redirect()->route('ver.anualidades.establecimientos');
                }
                $r = json_decode($res->getBody());
                if($r->status==200){
                   $data['tipos']=$r->data[0];
                }else{
                  $data['tipos']=[];
                }
          }else{
               $data['tipos']=[];
          }
          return view('recetas.anualidades.establecimientos.index',$data);
    }
    public function lista(Request $request){
        $tipoEstab=$request->tipo;
        $numEstab=$request->num;
        $idEstab="";
        $client = new Client();
        try{
              $res = $client->request('POST', $this->url.'anualidades/get/establecimientos',[
                  'headers' => [
                      'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                  ],
                      'form_params' =>[
                          'tipoEstab'=>$tipoEstab,
                          'numEstab'=>$numEstab
                ]

              ]);
         }catch(\GuzzleHttp\Exception\RequestException $e){
                   $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                  Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                  $this->emailErrorOdin('¡Problemas al consultar información de establecimientos anualidades! - FECHA '.Carbon::now());
                  return json_encode($results);
        }
        $r = json_decode($res->getBody());
        if($r->status==200){

              $rows=$r->data[0];
              $establecimientos =new Collection;
              $annio = (date("Y")-1);
              $fechaLimite= date('Y-m-d',strtotime(date('Y').'-06-30'));
              $fechaActual = date('Y-m-d');
              for($a=0;$a<count($rows);$a++){
                    $year =  date('Y',strtotime($rows[$a]->vigenteHasta));
                    $fechaVigencia = date('Y-m-d', strtotime($rows[$a]->vigenteHasta));

                    if($annio==$year){
                      //VERIFICANDO LA FECHA ACTUAL QUE NO SEA MAYOR A 31 DE MARZO
                      if ($fechaActual<=$fechaLimite) {
                          //Si la fecha actual es menor a la de licencia puede proceder
                          $disable="";
                          $estado = VariosMetodosController::countGeneradosEstablecimientos($rows[$a]->idEstablecimiento);
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
                    $valueCbx= '<input type="hidden" name="idPagos" id="idPagos" value="'.Crypt::encrypt($rows[$a]->idEstablecimiento).'"/><a class="btn btn-xs btn-success btn-perspective" onclick="generarMandamiento();" ><i class="fa fa-check-square-o"></i>Generar mandamiento</a>';

                 if($disable=='disabled'){
                    //NO GENERAR MANDAMIENTO
                        $text="";
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
                        $text=$valueCbx;
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
                   //$text=$valueCbx;
                   $establecimientos->push([
                            'combobox'             => $text,
                            'nombreComercial'      => $rows[$a]->idEstablecimiento.' - '.$rows[$a]->nombreComercial,
                            'vigenteHasta'         => $rows[$a]->vigenteHasta,
                            'propietario'          => $rows[$a]->propietario,
                            'valor' => '<h4><span class="label label-success"> $'.$rows[$a]->valor.'</span></h4>'
                   ]);


              }
              return Datatables::of($establecimientos)->make(true);

        }else{
                    $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                    return json_encode($results);
        }



    }

     public function store(Request $request){

          $v = Validator::make($request->all(),[
                'idPagos'        =>'required',
                'tipoval'        =>'required',
                'cuentade'       =>'sometimes|max:100'
              ]);
            $v->setAttributeNames([
                'idPagos'       =>'codigos de mandamiento',
                'tipoval'       =>'tipo de producto',
                'cuentade'      =>'por cuenta de'
            ]);
            if ($v->fails()){
              $msg = "<ul>";
              foreach ($v->messages()->all() as $err) {
                $msg .= "<li>$err</li>";
              }
              $msg .= "</ul>";
               return redirect()->route('ver.anualidades.establecimientos')->withErrors($msg);
            }

          //CONSULTAMOS LA INFORMACIÓN DEL PAGO
           $idEstablecimiento=Crypt::decrypt($request->idPagos);
           try{
                   $client = new Client();
                   $res = $client->request('POST', $this->url.'anualidades/get/establecimientos',[
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                    ],
                          'form_params' =>[
                              'tipoEstab'=>$request->tipoval,
                              'numEstab'=>$idEstablecimiento
                    ]]);
           }catch(\GuzzleHttp\Exception\RequestException $e){
                  Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                  $this->emailErrorOdin('¡Problemas al consultar información de establecimientos anualidades! - FECHA '.Carbon::now());
                  return redirect()->route('ver.anualidades.establecimientos');
            }
            $r = json_decode($res->getBody());
            if($r->status==200){
                    $datapago=$r->datapago[0];
                    $annio = (date("Y")-1);
                    $fechaLimite= date('Y-m-d',strtotime(''.date('Y').'-06-30'));
                    $fechaActual = date('Y-m-d');
                    $rows=$r->data[0];
                    $estado=0;
                    $productosvalidos=[];
                    $msg = "<ul>";
                    foreach($rows as $ro){
                          $year =  date('Y',strtotime($ro->vigenteHasta));
                          $fechaVigencia = date('Y-m-d', strtotime($ro->vigenteHasta));
                          //array_push($productosvalidos,array('idestablecimiento'=>$ro->idEstablecimiento,'nombreComercial'=>$ro->nombreComercial));
                          if ($annio==$year) {
                             //VERIFICANDO LA FECHA ACTUAL QUE NO SEA MAYOR A 31 DE MARZO
                              if ($fechaActual<=$fechaLimite) {
                                  //Si la fecha actual es menor a la de licencia puede proceder
                                   array_push($productosvalidos,array('idestablecimiento'=>$ro->idEstablecimiento,'nombreComercial'=>$ro->nombreComercial));
                              }else{
                                 //Fecha actual mayor a la del límite
                                $estado=$estado+1; //BLOQUEADO,SIN PODER GENERAR MANDAMIENTO CON RETRASO
                                 $msg .= "<li>El establecimiento $ro->nombreComercial tiene licencia vencida.</li>";
                              }
                          }else if($annio>$year) {
                             $estado=$estado+1; //Observado, VIGENCIA CON RETRASO
                             $msg .= "<li>El establecimiento $ro->nombreComercial presenta vigencia con retraso.</li>";
                          }else if($annio<$year) {
                             $estado=$estado+1; //Observado, VIGENCIA CON RETRASO
                             $msg .= "<li>El establecimiento $ro->nombreComercial esta vigente.</li>";
                          }
                     }
                     if($estado>0){
                       $msg .= "</ul>";
                       return redirect()->route('ver.anualidades.establecimientos')->withErrors($msg);
                     }
            }else{
               return redirect()->route('ver.anualidades.establecimientos')->withErrors("¡Estimado usuario la información que ingresa no es valida, vuelva a intentarlo!");
            }

        $objeto = new PagosMoraController();
        $aplicaMora = $objeto->comprobarFecha();
        $TOTAL = $datapago[0]->VALOR;
        $idTipoPago=$datapago[0]->ID_TIPO_PAGO;
        $objeto = new PagosMoraController;
        if ($aplicaMora) {
          $porcentaje = $objeto->getValorPorcentual();
          $diferencia = $objeto->mesDiferencias();
          $totalMora= ($TOTAL * $porcentaje)*$diferencia;
          $TOTAL= $TOTAL+$totalMora;
          $TOTAL=number_format((float)$TOTAL, 2, '.', '');
          //$dataMora = $objeto->datosIdTipoPago($idTipoPago);
          //$VENCIMIENTO = DB::select(DB::raw("SELECT REPLACE(LAST_DAY(CURDATE()),'-','/') AS VENCIMIENTO"));
          //ULTIMO DÍA DEL MES
          $VENCIMIENTO=Carbon::now()->endOfMonth()->format('Y/m/d');
        }else{
           $totalMora=0;
           $TOTAL=number_format((float)$TOTAL, 2, '.', '');
           //$VENCIMIENTO =  DB::select(DB::raw('SELECT CONCAT(YEAR(CURDATE()),\'\/03\/31\') AS VENCIMIENTO'));
           //ULTIMO DIA DEL MES DE MARZO
           $VENCIMIENTO = Carbon::createFromFormat('Y/m/d',date('Y').'/03/01')->endOfMonth()->format('Y/m/d');
        }
        if($TOTAL>=9999){
          return redirect()->route('ver.anualidades.establecimientos')->withErrors("¡Estimado usuario, el sistema genera mandamientos de pagos por un monto inferior a $9,999.99!");
        }
        $ID_MANDAMIENTO =Mandamiento::orderBy('ID_MANDAMIENTO', 'desc')->select('ID_MANDAMIENTO')->first();
        $ID_MANDAMIENTO=$ID_MANDAMIENTO->ID_MANDAMIENTO+1;
      try {
          DB::beginTransaction();

          $mandamiento = new Mandamiento;
          $mandamiento->ID_MANDAMIENTO = $ID_MANDAMIENTO;
          $mandamiento->FECHA = date('Y/m/d');
          $mandamiento->HORA = date('H:i:s');
          $mandamiento->ID_CLIENTE = Session::get('user');
          $mandamiento->A_NOMBRE = Session::get('name').' '.Session::get('lastname');
          $mandamiento->FECHA_VENCIMIENTO = $VENCIMIENTO;
          $mandamiento->ID_JUNTA = 'U21';
          $mandamiento->TOTAL = $TOTAL;
          $mandamiento->NOMBRE_CLIENTE = Session::get('name').' '.Session::get('lastname');
          $mandamiento->POR_CUENTA = mb_strtoupper($request->cuentade, 'UTF-8');
          $mandamiento->ID_USUARIO_CREACION = Session::get('user').'@'.$request->ip();
          $mandamiento->save();
          //detalle
          $mandamiento->detalle()->create([
            'ID_CLIENTE'=>$idEstablecimiento
            ,'ID_TIPO_PAGO'=>$idTipoPago
            ,'VALOR'=>$TOTAL-$totalMora
            ,'NOMBRE_CLIENTE'=>$productosvalidos[0]["nombreComercial"]
            ,'COMENTARIOS_ANEXOS'=>''
            ,'TIPO_ANUALIDAD'=>2
          ]);

          if ($aplicaMora){
              $mandamiento->detalle()->create([
              'ID_CLIENTE'=>$datapago[0]->MORA_CODIGO
              ,'ID_TIPO_PAGO'=>$datapago[0]->MORA_TIPO_PAGO
              ,'VALOR'=>$totalMora
              ,'TIPO_ANUALIDAD'=>0
              ,'NOMBRE_CLIENTE'=>$datapago[0]->MORA_NOMBRE
              ,'COMENTARIOS_ANEXOS'=>''
            ]);
          }
          $mandamiento=Mandamiento::find($ID_MANDAMIENTO);
          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
          define('FPDF_FONTPATH',app_path().'/fpdf/font/');
          $pdf=new PDF_Code128('P','mm','Letter');
          $pdf->AddPage();
          $pdf->SetXY(5,9);
          $pdf->SetFont('Arial','',12);
          $pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(0,5,'NIT 0614-020312-105-7',0,1,'C');
          $pdf->SetFont('Times','',8);
          $pdf->Cell(0,4,'MANDAMIENTO DE INGRESOS',0,1,'C');
          $pdf->SetFont('Times','',7);
          $unidad  = $datapago[0]->UNIDAD;
          $precio=number_format((float)$datapago[0]->VALOR, 2, '.', '');
          $pdf->Cell(0,4,'UNIDAD DE '.$unidad.' - PAGOS VARIOS WEB',0,1,'C');
          $pdf->SetFont('Times','',7);
          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(150,4,'Cliente:  '.$mandamiento->ID_CLIENTE.' - '.utf8_decode($mandamiento->NOMBRE_CLIENTE).'',0,1,'J');
          $pdf->SetXY($x + 135, $y);
          $pdf->SetFont('Times','B',10);
          $pdf->Cell(10,4,'Por: $'.$TOTAL.'                        No.: '.$mandamiento->ID_MANDAMIENTO.'',0,1,'J');
          $pdf->SetFont('Times','',7);
          $pdf->Cell(0,4,'Por Cuenta de: '.utf8_decode($mandamiento->POR_CUENTA).'                                                                                                                                                                                                 ',0,1,'J');
          $pdf->Cell(0,4,'_____________________________________________________________________________________________________________________________________________________________ ',0,1,'J');
          $pdf->Cell(15,3,$datapago[0]->CODIGO,0,'J',0);
          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->MultiCell(165,3,$datapago[0]->NOMBRE_TIPO_PAGO,0,'L',false);
          $pdf->SetXY($x + 165, $y);
          $pdf->MultiCell(20,3,'$ '.$precio.'',0,'L',false);
          $pdf->Ln();
          $pdf->MultiCell(165,3,$idEstablecimiento.' - '.$productosvalidos[0]["nombreComercial"],0,'L',false);
                  //*****
          $pdf->Ln();
          if($aplicaMora){
            $pdf->Cell(15,3,$datapago[0]->MORA_CODIGO,0,'J',0);
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(165,3,$datapago[0]->MORA_NOMBRE,0,'L',false);
            $pdf->SetXY($x + 165, $y);
            $totalMora=number_format((float)$totalMora, 2, '.', '');
            $pdf->MultiCell(20,3,'$ '.$totalMora.'',0,'L',false);
          }
          $pdf->SetX($x);
          $pdf->SetFont('Times','',7);
          $pdf->SetXY(10,51);
          $pdf->Write(5,'_____________________________________________________________________________________________________________________________________________________________',0,1,'J');
          $pdf->SetFont('Times','B',7);
          $pdf->SetXY(190,56);
          $pdf->Write(5,'$ '.$TOTAL.'',0,1,'R',0);
//A set
          $pdf->Image(url('img/escudo-new.jpg'),11,9,14.5);
          $pdf->SetXY(190,9);
          $pdf->Image(url('img/dnm-new.jpg'));
          $pdf->SetXY(150,16);
          $pdf->SetFont('Arial','',10);
          $pdf->Write(5,'Decreto 417');
          $pdf->SetFont('Times','',7);
          $pdf->SetXY(90,55);
          $pdf->Write(5,'NPE:'.$mandamiento->NPE.'');
          $pdf->SetXY(10,60);
          $pdf->Write(5,'Emitido:'.$mandamiento->FECHA.'');
          $pdf->SetXY(10,65);
          $pdf->SetFont('Times','B',10);
          $pdf->Write(5,'Vencimiento:'.$mandamiento->FECHA_VENCIMIENTO.'');
          $pdf->SetFont('Times','',7);
          $pdf->Code128(70,60,$mandamiento->CODIGO_BARRA,80,6);
          $pdf->SetXY(75,65);
          $pdf->Write(5,$mandamiento->CODIGO_BARRA_TEXTO);
          $pdf->SetXY(180,60);
          $pdf->Write(5,'Copia: Banco');
          $pdf->SetXY(170,65);
          $pdf->Write(5,'Usuario: '.$mandamiento->ID_CLIENTE.'');
          $pdf->SetXY(10,70);
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
          $pdf->Cell(15,3,$datapago[0]->CODIGO,0,'J',0);
          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->MultiCell(165,3,$datapago[0]->NOMBRE_TIPO_PAGO,0,'L',false);
          $pdf->SetXY($x + 165, $y);
          $pdf->MultiCell(20,3,'$ '.$precio.'',0,'L',false);
          $pdf->Ln();
          $pdf->MultiCell(165,3,$idEstablecimiento.' - '.$productosvalidos[0]["nombreComercial"],0,'L',false);
          $pdf->Ln();
          //*****
          if ($aplicaMora) {
              $pdf->Cell(15,3,$datapago[0]->MORA_CODIGO,0,'J',0);
              $x = $pdf->GetX();
              $y = $pdf->GetY();
              $pdf->MultiCell(165,3,$datapago[0]->MORA_NOMBRE,0,'L',false);
              $pdf->SetXY($x + 165, $y);
              $totalMora=number_format((float)$totalMora, 2, '.', '');
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
          $pdf->Image(url('img/escudo-new.jpg'),11,87,14.5);
          $pdf->SetXY(190,85);
          $pdf->Image(url('img/dnm-new.jpg'));
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
          $pdf->SetXY(170,145);
          $pdf->Write(5,'Usuario: '.$mandamiento->ID_CLIENTE.'');
          $pdf->SetXY(10,150);
          $pdf->Write(4,utf8_decode('Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podrá ser pagado en la red de las Agencias del Banco Agrícola, S.A.'),0,0,'J');
          $pdf->Output('MANDAMIENTO-'.$mandamiento->ID_MANDAMIENTO.'.pdf','D');

          DB::commit();
        }catch (\Exception $e){
          DB::rollback();
          Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
          return redirect()->route('ver.anualidades.establecimientos')->withErrors("¡PROBLEMAS AL GENERAR MANDAMIENTO DE PAGO, POR FAVOR ESPERE UN MOMENTO E INTENTA NUEVAMENTE!");
        }

    }


      public function imprimirHoja($idEnlace){
      $idEnlace=Crypt::decrypt($idEnlace);
      if($idEnlace==''){
        Session::flash('msnError','¡Problemas al generar el mandamiento!');
        return back();
      }
        $client = new Client();
        $res = $client->request('POST', $this->url.'anualidades/get/hoja/establecimiento',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idEnlace'   =>$idEnlace
          ]

        ]);

      $r = json_decode($res->getBody());

      /*IMPRIMIENDO ANEXOSS*/
  $pdf=new PDF_Code128('P','mm','Letter');
  $pdf->AddPage();
  $image1 = "img/escudo.jpg";
  $image2 = "img/dnm.jpg";
  $pdf->SetXY(5,5);
  $pdf->SetFont('Arial','',12);

  $pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,5,'UNIDAD JURIDICA',0,1,'C');
  $pdf->Cell(0,5,'SECCION DE ESTABLECIMIENTOS',0,1,'C');
  $pdf->Cell(0,5,'RENOVACION ANUAL DE ESTABLECIMIENTOS',0,1,'C');
  $pdf->Cell(0,5,'DATOS GENERALES',0,1,'C');
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
  $pdf->SetXY($x-150, $y+10);
  $pdf->ln();
  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $pdf->SetFont('Times','',7);
  $pdf->ln();


    if($r->status==200){
         $info=$r->data[0];

        $pdf->Cell(195,5,"TIPO DE ESTABLECIMIENTO: ".utf8_decode($info->NOMBRE_TIPO_ESTABLECIMIENTO),1,1,'L');
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(150,5,"NOMBRE ESTABLECIMIENTO: ".utf8_decode($info->nombre_establecimiento_enlace),1,1,'L');
        $pdf->SetXY($x+150, $y);
        $pdf->Cell(45,5,"No. INSCRIPCION: ".utf8_decode($info->id_establecimiento_enlace),1,1,'L');
        $pdf->MultiCell(195,4,''.utf8_decode("DIRECCION: ".$info->direccion_establecimiento_enlace).'',1,'J',false);

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(95,5,"MUNICIPIO: ".utf8_decode($info->municipio_establecimiento),1,1,'L');
        $pdf->SetXY($x+95, $y);
        $pdf->Cell(100,5,"DEPARTAMENTO: ".utf8_decode($info->departamento_establecimiento),1,1,'L');

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(50,5,"TELEFONO 1: ".utf8_decode($info->telefono_1_establecimiento),1,1,'L');
        $pdf->SetXY($x+50, $y);
        $pdf->Cell(50,5,"TELEFONO 2: ".utf8_decode($info->telefono_2_establecimiento),1,1,'L');
        $pdf->SetXY($x+100, $y);
        $pdf->Cell(95,5,"CORREO: ".utf8_decode($info->correo_establecimiento),1,1,'L');

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(25,5,"HORARIO: ",0,1,'L');
        $pdf->SetXY($x+25, $y);
        $pdf->Cell(170,5,"LUNES A VIERNES: ".utf8_decode($info->desde_lunes_viernes." -- ".$info->hasta_lunes_viernes),0,1,'C');
        $pdf->SetXY($x+25, $y+4);
        $pdf->Cell(170,5,"SABADOS: ".utf8_decode($info->desde_sabados." -- ".$info->hasta_sabados),0,1,'C');
        $pdf->SetXY($x+25, $y+8);
        $pdf->Cell(170,5,"DOMINGOS: ".utf8_decode($info->desde_domingos." -- ".$info->desde_domingos),0,1,'C');

        $pdf->MultiCell(195,4,''.utf8_decode("A QUE SE DEDICA EL ESTABLECIMIENTO: ".$info->dedicacion_establecimiento).'',1,'J',false);

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        if($info->tipo_propietario_establecimiento==1){
          $propietario="JURIDICO";
        }else{
          $propietario="NATURAL";
        }
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(35,5,"PROPIETARIO: ".utf8_decode($propietario),1,1,'L');
        $pdf->SetXY($x+35, $y);

        $pdf->SetFont('Times','',7);
        $pdf->Cell(160,5,"NOMBRE: ".utf8_decode($info->nombre_propietario),1,1,'L');


        $x = $pdf->GetX();
        $y = $pdf->GetY();
        if($info->tipo_propietario_establecimiento==1){

          $pdf->Cell(95,5,"No. DUI: NO APLICA.",1,1,'L');
        }else{
          $pdf->Cell(95,5,"No. DUI: ".utf8_decode($info->dui_propietario),1,1,'L');
        }

        $pdf->SetXY($x+95, $y);
        $pdf->Cell(100,5,"No NIT: ".utf8_decode($info->nit_propietario),1,1,'L');

          $pdf->MultiCell(195,4,''.utf8_decode("DIRECCION COMPLETA: ".$info->direccion_propietario).'',1,'J',false);

          $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(50,5,"TELEFONO FIJO: ".utf8_decode($info->telefono_propietario),1,1,'L');
        $pdf->SetXY($x+50, $y);
        $pdf->Cell(50,5,"CELULAR: ".utf8_decode($info->celular_propietario),1,1,'L');
        $pdf->SetXY($x+100, $y);
        $pdf->Cell(95,5,"CORREO: ".utf8_decode($info->correo_propietario),1,1,'L');


        if($info->tipo_propietario_establecimiento==1){
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

            $pdf->MultiCell(195,4,''.utf8_decode("DIRECCION COMPLETA: ".$info->direccion_representante).'',1,'J',false);

            $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(50,5,"TELEFONO FIJO: ".utf8_decode($info->telefono_representante),1,1,'L');
          $pdf->SetXY($x+50, $y);
          $pdf->Cell(50,5,"CELULAR: ".utf8_decode($info->celular_representante),1,1,'L');
          $pdf->SetXY($x+100, $y);
          $pdf->Cell(95,5,"CORREO: ".utf8_decode($info->correo_representante),1,1,'L');
          /*FIN representante legal*/
        }
        /*seccion apoderado*/
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(35,5,"APODERADO (SI APLICA)",1,1,'L');
        $pdf->SetXY($x+35, $y);
        $pdf->SetFont('Times','',7);
        $pdf->Cell(160,5,"NOMBRE: ".utf8_decode($info->nombre_apoderado),1,1,'L');

        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(95,5,"No. DUI: ".utf8_decode($info->dui_apoderado),1,1,'L');
        $pdf->SetXY($x+95, $y);
        $pdf->Cell(100,5,"No NIT: ".utf8_decode($info->nit_apoderado),1,1,'L');

          $pdf->MultiCell(195,4,''.utf8_decode("DIRECCION COMPLETA: ".$info->direccion_apoderado).'',1,'J',false);

          $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(50,5,"TELEFONO FIJO: ".utf8_decode($info->telefono_apoderado),1,1,'L');
        $pdf->SetXY($x+50, $y);
        $pdf->Cell(50,5,"CELULAR: ".utf8_decode($info->celular_apoderado),1,1,'L');
        $pdf->SetXY($x+100, $y);
        $pdf->Cell(95,5,"CORREO: ".utf8_decode($info->correo_apoderado),1,1,'L');
        /*FIN seccion apoderado*/

        /*Complemento de farmacias*/
        if ($info->NOMBRE_TIPO_ESTABLECIMIENTO=="FARMACIAS") {
          $pdf->SetFont('Arial','B',7);
          $pdf->MultiCell(195,4,''.utf8_decode("COMPLEMENTO FARMACIA"),1,'J',false);
          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->SetFont('Times','',7);
          $pdf->Cell(100,5,utf8_decode("¿POSEE UN ÁREA PARA PREPARADOS MAGISTRALES Y OFICINALES?"),1,1,'L');
          $pdf->SetXY($x+100, $y);
          $pdf->Cell(25,5,"SI",0,1,'R');
          $pdf->SetXY($x+125, $y);
          if($info->area_preparados==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+145, $y);
          $pdf->Cell(25,5,"NO: ",0,1,'R');
          $pdf->SetXY($x+170, $y);
          if($info->area_preparados==0){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }

          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(100,5,utf8_decode("¿REALIZA FRACCIONAMIENTO  DE PRODUCTOS?"),1,1,'L');
          $pdf->SetXY($x+100, $y);
          $pdf->Cell(25,5,"SI",0,1,'R');
          $pdf->SetXY($x+125, $y);
          if($info->realiza_fraccionamiento==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+145, $y);
          $pdf->Cell(25,5,"NO: ",0,1,'R');
          $pdf->SetXY($x+170, $y);
          if($info->realiza_fraccionamiento==0){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }

          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(100,5,utf8_decode("¿VENDE PRODUCTOS CONTROLADOS?"),1,1,'L');
          $pdf->SetXY($x+100, $y);
          $pdf->Cell(25,5,"SI",0,1,'R');
          $pdf->SetXY($x+125, $y);
          if($info->vende_controlados==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+145, $y);
          $pdf->Cell(25,5,"NO: ",0,1,'R');
          $pdf->SetXY($x+170, $y);
          if($info->vende_controlados==0){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }

          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(100,5,utf8_decode("¿VENDE PRODUCTOS BIOLOGICOS?"),1,1,'L');
          $pdf->SetXY($x+100, $y);
          $pdf->Cell(25,5,"SI",0,1,'R');
          $pdf->SetXY($x+125, $y);
          if($info->vende_biologicos==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+145, $y);
          $pdf->Cell(25,5,"NO: ",0,1,'R');
          $pdf->SetXY($x+170, $y);
          if($info->vende_biologicos==0){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }

          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(100,5,utf8_decode("¿VENDE PRODUCTOS BIOTECNOLOGICOS?"),1,1,'L');
          $pdf->SetXY($x+100, $y);
          $pdf->Cell(25,5,"SI",0,1,'R');
          $pdf->SetXY($x+125, $y);
          if($info->vende_biotecnologicos==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+145, $y);
          $pdf->Cell(25,5,"NO: ",0,1,'R');
          $pdf->SetXY($x+170, $y);
          if($info->vende_biotecnologicos==0){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }

          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(100,5,utf8_decode("¿VENDE PRODUCTOS CITOSTÁTICOS?"),1,1,'L');
          $pdf->SetXY($x+100, $y);
          $pdf->Cell(25,5,"SI",0,1,'R');
          $pdf->SetXY($x+125, $y);
          if($info->vende_citostaticos==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+145, $y);
          $pdf->Cell(25,5,"NO: ",0,1,'R');
          $pdf->SetXY($x+170, $y);
          if($info->vende_citostaticos==0){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
        }else if(($info->NOMBRE_TIPO_ESTABLECIMIENTO=="DROGUERIAS")){
          $pdf->SetFont('Arial','B',7);
          $pdf->MultiCell(195,4,''.utf8_decode("COMPLEMENTO DROGUERÍAS"),1,'J',false);
          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->SetFont('Times','B',7);
          $pdf->Cell(75,5,utf8_decode("TIPO DE MEDICAMENTOS QUE MANEJA"),1,1,'L');
          $pdf->SetXY($x+75, $y);
          $pdf->Cell(25,5,"PRODUCTOS CONTROLADOS",0,1,'L');
          $pdf->SetXY($x+115, $y);
          if($info->productos_controlados==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+145, $y);
          $pdf->Cell(25,5,"PRODUCTOS BIOTECNOLOGICOS: ",0,1,'R');
          $pdf->SetXY($x+170, $y);
          if($info->productos_biotecnologicos==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+75, $y+7);
          $pdf->Cell(25,5,"PRODUCTOS DE VENTA LIBRE: ",0,1,'R');
          $pdf->SetXY($x+115, $y+7);
          if($info->productos_venta_libre==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+145, $y+7);
          $pdf->Cell(25,5,"PRODUCTOS BIOLOGICOS: ",0,1,'R');
          $pdf->SetXY($x+170, $y+7);
          if($info->productos_biologicos==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
        }else if($info->NOMBRE_TIPO_ESTABLECIMIENTO=="LAB. FARMACÉUTICOS"){

        }else if($info->NOMBRE_TIPO_ESTABLECIMIENTO=="BOTIQUINES"){
          $pdf->SetFont('Arial','B',7);
          $pdf->MultiCell(195,4,''.utf8_decode("COMPLEMENTO BOTIQUINES"),1,'J',false);
          $pdf->SetFont('Times','',7);
          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(75,5,utf8_decode("TIPO DE BOTIQUIN"),1,1,'L');
          $pdf->SetXY($x+75, $y);
          $pdf->Cell(50,5,"DE HOSPITAL PUBLICO O PRIVADO",0,1,'R');
          $pdf->SetXY($x+125, $y);
          if($info->botiquin_tipo==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+145, $y);
          $pdf->Cell(40,5,"DE CLINICA ASISTENCIAL",0,1,'R');
          $pdf->SetXY($x+185, $y);
          if($info->botiquin_tipo==0){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }

          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(100,5,utf8_decode("¿ATIENDEN EMERGENCIAS LAS 24 HORAS?"),1,1,'L');
          $pdf->SetXY($x+100, $y);
          $pdf->Cell(25,5,"SI",0,1,'R');
          $pdf->SetXY($x+125, $y);
          if($info->atienden_emergencias==1){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
          $pdf->SetXY($x+145, $y);
          $pdf->Cell(25,5,"NO: ",0,1,'R');
          $pdf->SetXY($x+185, $y);
          if($info->atienden_emergencias==0){
            $pdf->Cell(5,5,"X",1,1,'L');
          }else{
            $pdf->Cell(5,5,"",1,1,'L');
          }
        }
        if($info->NOMBRE_TIPO_ESTABLECIMIENTO!="LABORATORIOS ARTESANALES DE PRODUCTOS NATURALES" && $info->NOMBRE_TIPO_ESTABLECIMIENTO!="DISTRIBUIDORA DE MEDICAMENTOS DE LIBRE VENTA")
        {
          /*seccion Regente*/

          $pdf->SetFont('Arial','B',7);
          $pdf->Cell(195,5,"REGENTE",1,1,'L');

          $pdf->SetFont('Times','',7);

          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(95,5,"NOMBRE: ".utf8_decode($info->nombre_regente),1,1,'L');
          $pdf->SetXY($x+95, $y);
          $pdf->Cell(100,5,"No. J.V.P.Q.F: ".utf8_decode($info->jvpqf_regente),1,1,'L');

            $pdf->MultiCell(195,4,''.utf8_decode("DIRECCION COMPLETA: ".$info->direccion_regente).'',1,'J',false);

            $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(50,5,"TELEFONO FIJO: ".utf8_decode($info->telefono_regente),1,1,'L');
          $pdf->SetXY($x+50, $y);
          $pdf->Cell(50,5,"CELULAR: ".utf8_decode($info->celular_regente),1,1,'L');
          $pdf->SetXY($x+100, $y);
          $pdf->Cell(95,5,"CORREO: ".utf8_decode($info->correo_regente),1,1,'L');
          /*HORARIO**/
          $horarioFinal="HORARIO";
          $queryHorarioS1=DB::select(DB::raw("SELECT * FROM cssp.si_urv_portal_horario_regente where horario_semana=1 and id_portal_hoja=".$idEnlace.""));
          $queryHorarioS2=DB::select(DB::raw("SELECT * FROM cssp.si_urv_portal_horario_regente where horario_semana=2 and id_portal_hoja=".$idEnlace.""));
          $queryHorarioS3=DB::select(DB::raw("SELECT * FROM cssp.si_urv_portal_horario_regente where horario_semana=3 and id_portal_hoja=".$idEnlace.""));
          $queryHorarioS4 =DB::select(DB::raw("SELECT * FROM cssp.si_urv_portal_horario_regente where horario_semana=4 and id_portal_hoja=".$idEnlace.""));

          if (count($queryHorarioS1)>0) {
            $horarioFinal.=" SEMANA 1:";
            foreach($queryHorarioS1 as $r1){
              $horarioFinal.=" ".$r1->horario_dia." ".$r1->horario_desde."--".$r1->horario_hasta.";";
            }
          }
          if (count($queryHorarioS2)>0) {
            $horarioFinal.=" SEMANA 2:";
            foreach($queryHorarioS2 as $r2){
              $horarioFinal.=" ".$r2->horario_dia." ".$r2->horario_desde."--".$r2->horario_hasta.";";
            }
          }
           if (count($queryHorarioS3)>0) {
            $horarioFinal.=" SEMANA 3:";
            foreach($queryHorarioS3 as $r3){
              $horarioFinal.=" ".$r3->horario_dia." ".$r3->horario_desde."--".$r3->horario_hasta.";";
            }
          }
           if (count($queryHorarioS4)>0) {
            $horarioFinal.=" SEMANA 4:";
            foreach($queryHorarioS4 as $r4){
               $horarioFinal.=" ".$r4->horario_dia." ".$r4->horario_desde."--".$r4->horario_hasta.";";
            }
          }
          $pdf->MultiCell(195,4,''.utf8_decode($horarioFinal).'',1,'J',false);
          /*FIN seccion Regente*/
        }



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



      public function getEstablecimientosGeneral(Request $request){

      $client = new Client();
      $txtNo = Crypt::decrypt($request->param);

        $res = $client->request('POST', $this->url.'/est/get/estGeneral',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'no'     => (string)$txtNo
            ]

        ]);

        $r = json_decode($res->getBody());
        //dd(json_decode($res->getBody()));
          if($r->status==200){
                   $d=$r->data[0];
                    // dd(json_decode($d->telefonosContacto));
          return response()->json($d);
        }
        elseif($r->status==404){

          Session::flash('msnError',$r->message);
          return back()->withInput();
          $collection=null;
        }


  }
    public function getPropietario(Request $request){
    //dd($request->all());
    $client = new Client();
    $txtNo = Crypt::decrypt($request->param);

        $res = $client->request('POST',$this->url.'/est/get/propietario',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'no'     => (string)$txtNo
            ]

        ]);

          $r = json_decode($res->getBody());
         //dd(json_decode($res->getBody()));
          if($r->status==200){
                   $d=$r->data[0];

                  //  dd($r->data[0]);
          return response()->json($d);
        }
        elseif($r->status==404){

          Session::flash('msnError',$r->message);
          return back()->withInput();
          $collection=null;
        }


  }
  public function getRegerentes(Request $request){
    //dd($request->all());
    $client = new Client();
    $txtNo = Crypt::decrypt($request->param);

        $res = $client->request('POST',$this->url.'/est/get/regentes',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'no'     => (string)$txtNo
            ]

        ]);

          $r = json_decode($res->getBody());

          if($r->status==200){
               $d=$r->data[0];

               return response()->json($d);
        }
        elseif($r->status==404){

          Session::flash('msnError',$r->message);
          return back()->withInput();
          $collection=null;
        }


  }

    public function getRegerentesHorarios(Request $request){
    //dd($request->all());


    $client = new Client();
    $txtNo = Crypt::decrypt($request->param);
    $txtidProfesional = $request->idProfesional;

        $res = $client->request('POST',$this->url.'/est/get/horario',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'no'     => (string)$txtNo,
                    'idProfesional'   => (string)$txtidProfesional
            ]

        ]);

          $r = json_decode($res->getBody());

          if($r->status==200){
          $d=$r->data[0];
              $horario =json_decode($d[0]->horarioRegente);
         //  dd($horario);
           $dia=1;
           for($i=0;$i<date("t");$i++){
            if($i<9){
                  $fecha[$i]= date("Y")."-".date("m")."-0".$dia++;
                 }else{
                    $fecha[$i]= date("Y")."-".date("m")."-".$dia++;
                 }
            }

          //dd($fecha);
               $lp=1;
               foreach ($horario as $v) {
                  $Lunes[$lp]= $v->L1;
                     $lp++;
            $Lunes[$lp]= $v->L2;
            $lp++;
        }
         $mp=1;
               foreach ($horario as $v) {
                  $Martes[$mp]= $v->M1;
                     $mp++;
            $Martes[$mp]= $v->M2;
            $mp++;
        }
          $mip=1;
               foreach ($horario as $v) {
                  $Miercoles[$mip]= $v->MI1;
                     $mip++;
            $Miercoles[$mip]= $v->MI2;
            $mip++;
        }
          $jp=1;
               foreach ($horario as $v) {
                  $Jueves[$jp]= $v->J1;
                     $jp++;
            $Jueves[$jp]= $v->J2;
            $jp++;
        }
            $vp=1;
               foreach ($horario as $v) {
                  $Viernes[$vp]= $v->V1;
                     $vp++;
            $Viernes[$vp]= $v->V2;
            $vp++;
        }
         $sp=1;
               foreach ($horario as $v) {
                  $Sabado[$sp]= $v->S1;
                     $sp++;
            $Sabado[$sp]= $v->S2;
            $sp++;
        }
         $dp=1;
               foreach ($horario as $v) {
                  $Domingo[$dp]= $v->D1;
                     $dp++;
            $Domingo[$dp]= $v->D2;
            $dp++;
        }
    //dd(strlen($Lunes[1]));
                 $s=0;$cd=1;$cs=1;$cl=1;$cm=1; $cmi=1;$cj=1; $cv=1;

        for($z=00; $z<date("t");$z++){
                      $fechats = strtotime($fecha[$z]);

                      switch (date('w', $fechats)){
              case 0:

                if($cd==1){
                     if (strlen($Domingo[1])>0) {
                      $mostrar = "De:  ". $Domingo[1];
                      $data[$s]['title']= $mostrar;
                  $data[$s]['color']='#00CC66';
                    $data[$s]['start']= $fecha[$z];
                    $s++;
                      $mostrar = "Hasta: ". $Domingo[2];
                      $data[$s]['title']= $mostrar;
                  $data[$s]['color']='#0066CC';
                    $data[$s]['start']= $fecha[$z];
                    $s++;
                    $cd++;
                      }else{
                           $cd++;
                      }
             }elseif($cd==2){
                    if (strlen($Domingo[3])>0) {
                    $mostrar = "De:  ". $Domingo[3];
                      $data[$s]['title']= $mostrar;
                  $data[$s]['color']='#00CC66';
                    $data[$s]['start']= $fecha[$z];
                    $s++;
                      $mostrar = "Hasta: ". $Domingo[4];
                      $data[$s]['title']= $mostrar;
                  $data[$s]['color']='#0066CC';
                    $data[$s]['start']= $fecha[$z];
                    $s++;
                    $cd++;
                     }else{ $cd++;
                      }

             }elseif($cd==3){
                    if (strlen($Domingo[5])>0) {
                    $mostrar = "De:  ". $Domingo[5];
                      $data[$s]['title']= $mostrar;
                  $data[$s]['color']='#00CC66';
                    $data[$s]['start']= $fecha[$z];
                    $s++;
                      $mostrar = "Hasta: ". $Domingo[6];
                      $data[$s]['title']= $mostrar;
                  $data[$s]['color']='#0066CC';
                    $data[$s]['start']= $fecha[$z];
                    $s++;
                    $cd++;
                     }else{ $cd++;
                      }
             }elseif($cd==4){
                    if (strlen($Domingo[7])>0) {
                    $mostrar = "De:  ". $Domingo[7];
                      $data[$s]['title']= $mostrar;
                  $data[$s]['color']='#00CC66';
                    $data[$s]['start']= $fecha[$z];
                    $s++;
                      $mostrar = "Hasta: ". $Domingo[8];
                      $data[$s]['title']= $mostrar;
                  $data[$s]['color']='#0066CC';
                    $data[$s]['start']= $fecha[$z];
                    $s++;
                    $cd++;
                     }else{ $cd++;
                      }
             }

            break;
            case 1:
              if($cl==1){
                      if (strlen($Lunes[1])>0) {
                    $mostrar = "De:  ". $Lunes[1];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Lunes[2];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cl++;
                  }else{$cl++;}
             }elseif($cl==2){
                  if (strlen($Lunes[3])>0) {
                  $mostrar = "De:  ". $Lunes[3];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Lunes[4];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cl++;
                  }else{$cl++;}

             }elseif($cl==3){
                  if (strlen($Lunes[5])>0) {
                  $mostrar = "De:  ". $Lunes[5];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Lunes[6];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cl++;
                   }else{$cl++;}
             }elseif($cl==4){
                  if (strlen($Lunes[7])>0) {
                  $mostrar = "De:  ". $Lunes[7];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Lunes[8];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cl++;
                    }else{$cl++;}
             }

             break;
            case 2:
            if($cm==1){
                  if (strlen($Martes[1])>0) {
                    $mostrar = "De:  ". $Martes[1];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Martes[2];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cm++;
                  }else{ $cm++;}
             }elseif($cm==2){
                  if (strlen($Martes[3])>0) {
                  $mostrar = "De:  ". $Martes[3];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Martes[4];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cm++;
                    }else{ $cm++;}

             }elseif($cm==3){
                if (strlen($Martes[5])>0) {
                  $mostrar = "De:  ". $Martes[5];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Martes[6];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cm++;
                  }else{ $cm++;}
             }elseif($cm==4){
                  if (strlen($Martes[7])>0) {
                  $mostrar = "De:  ". $Martes[7];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Martes[8];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cm++;
                    }else{ $cm++;}
             }
             break;
            case 3:
           if($cmi==1){
                  if (strlen($Miercoles[1])>0) {
                    $mostrar = "De:  ". $Miercoles[1];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Miercoles[2];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cmi++;
                    }else{$cmi++;}
             }elseif($cmi==2){
                  if (strlen($Miercoles[3])>0) {
                  $mostrar = "De:  ". $Miercoles[3];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Miercoles[4];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cmi++;
                   }else{$cmi++;}

             }elseif($cmi==3){
                  if (strlen($Miercoles[5])>0) {
                  $mostrar = "De:  ". $Miercoles[5];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Miercoles[6];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cmi++;
                  }else{$cmi++;}
             }elseif($cmi==4){
                  if (strlen($Miercoles[7])>0) {
                  $mostrar = "De:  ". $Miercoles[7];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Miercoles[8];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cmi++;
                   }else{$cmi++;}
             }

             break;
            case 4:
        if($cj==1){
                 if(strlen($Jueves[1])>0) {
                    $mostrar = "De:  ". $Jueves[1];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Jueves[2];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cj++;
                  }else{$cj++;}
        }elseif($cj==2){
                  if (strlen($Jueves[3])>0) {
                  $mostrar = "De:  ". $Jueves[3];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Jueves[4];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cj++;
                  }else{$cj++;}

        }elseif($cj==3){
                    if (strlen($Jueves[5])>0) {
                  $mostrar = "De:  ". $Jueves[5];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Jueves[6];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cj++;
                   }else{$cj++;}
        }elseif($cj==4){
                  if (strlen($Jueves[7])>0) {
                  $mostrar = "De:  ". $Jueves[7];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Jueves[8];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cj++;
                  }else{$cj++;}
                 }

            break;
            case 5:
            if($cv==1){
                    if (strlen($Viernes[1])>0) {
                    $mostrar = "De:  ". $Viernes[1];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Viernes[2];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cv++;
                    }else{ $cv++;}
             }elseif($cv==2){
                  if (strlen($Viernes[3])>0) {
                  $mostrar = "De:  ". $Viernes[3];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Viernes[4];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cv++;
                   }else{ $cv++;}

             }elseif($cv==3){
                  if (strlen($Viernes[5])>0) {
                  $mostrar = "De:  ". $Viernes[5];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Viernes[6];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cv++;
                   }else{ $cv++;}
             }elseif($cv==4){
                  if (strlen($Viernes[7])>0) {
                  $mostrar = "De:  ". $Viernes[7];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Viernes[8];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cv++;
                  }else{ $cv++;}
             }


            break;
            case 6:

              if($cs==1){
                           if (strlen($Sabado[1])>0) {
                    $mostrar = "De:  ". $Sabado[1];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#00CC66';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                    $mostrar = "Hasta: ". $Sabado[2];
                    $data[$s]['title']= $mostrar;
                $data[$s]['color']='#0066CC';
                  $data[$s]['start']= $fecha[$z];
                  $s++;
                  $cs++;
                  }else{
                    $cs++;
                  }

             }elseif($cs==2){
               if (strlen($Sabado[3])>0) {
              $mostrar = "De:  ". $Sabado[3];
                $data[$s]['title']= $mostrar;
            $data[$s]['color']='#00CC66';
              $data[$s]['start']= $fecha[$z];
              $s++;
                $mostrar = "Hasta: ". $Sabado[4];
                $data[$s]['title']= $mostrar;
            $data[$s]['color']='#0066CC';
              $data[$s]['start']= $fecha[$z];
              $s++;
              $cs++;
               }else{
                    $cs++;
                  }


             }elseif($cs==3){
              if (strlen($Sabado[5])>0) {
              $mostrar = "De:  ". $Sabado[5];
                $data[$s]['title']= $mostrar;
            $data[$s]['color']='#00CC66';
              $data[$s]['start']= $fecha[$z];
              $s++;
                $mostrar = "Hasta: ". $Sabado[6];
                $data[$s]['title']= $mostrar;
            $data[$s]['color']='#0066CC';
              $data[$s]['start']= $fecha[$z];
              $s++;
              $cs++;
               }else{
                    $cs++;
                  }

             }elseif($cs==4){
              if (strlen($Sabado[7])>0) {
              $mostrar = "De:  ". $Sabado[7];
                $data[$s]['title']= $mostrar;
            $data[$s]['color']='#00CC66';
              $data[$s]['start']= $fecha[$z];
              $s++;
                $mostrar = "Hasta: ". $Sabado[8];
                $data[$s]['title']= $mostrar;
            $data[$s]['color']='#0066CC';
              $data[$s]['start']= $fecha[$z];
              $s++;
              $cs++;
               }else{
                    $cs++;
                  }

             }



             break;
        }

        }


          return json_encode($data);
        }
        elseif($r->status==404){

          Session::flash('msnError',$r->message);
          return back()->withInput();
          $collection=null;
        }

  }

  public function verInfoGeneral($idEstablecimiento)
    {
      $data = ['title'      => 'Información'
      ,'subtitle'     => 'Establecimiento'
      ,'breadcrumb'     => [
        ['nom'  =>  'Información', 'url' => '#'],
        ['nom'  =>  'Establecimiento', 'url' => '#']
      ]];

      $comp=VariosMetodosController::comprobarHojaEstablecimiento(Crypt::decrypt($idEstablecimiento));
      if($comp>0){
        Session::flash('msnError','¡La información del establecimiento ya se envío!');
        return back();
      }
      $data['id']=$idEstablecimiento;
       return view('recetas.anualidades.establecimientos.mostrar',$data);

 }
     public function storeInformacion(Request $request){
      //dd($request->all());
      $v = Validator::make($request->all(),[
            'id' => 'required',
            'acuerdo' => 'required',
            'telefonosContacto1' => 'sometimes|regex:/^[0-9]{4}-[0-9]{4}$/',
            'telefonosContacto2' => 'sometimes|regex:/^[0-9]{4}-[0-9]{4}$/',
            'emailContacto' => 'sometimes|email',
            'comentario' => 'required_if:acuerdo,0'
            ]);

        $v->setAttributeNames([
          'id'       => 'id establecimiento',
          'acuerdo'       => 'está de acuerdo',
          'telefonosContacto1' => 'Telefono 1',
          'telefonosContacto2' => 'Telefono 2',
          'emailContacto' => 'Email',

        ]);
        $v->setCustomMessages([
          'id.required'       => 'Identificador del Establecimiento es requerido',
          'acuerdo.required'       => 'Acuerdo es requerido',
          'telefonosContacto1.regex' => 'El telefono 1 debe tener el formato es 8888-8888',
          'telefonosContacto2.regex' => 'El telefono 2 debe tener el formato es 8888-8888',
          'emailContacto.email' => 'Email no valido, digite un email valido',
          'comentario.required_if' => 'El campo observaciones es requirido si NO esta de acuerdo con la informacion',
          ]);

      if ($v->fails())
        {
          $msg = "<ul class='text-warning'>";
          foreach ($v->messages()->all() as $err) {
            $msg .= "<li>$err</li>";
          }
          $msg .= "</ul>";
          //return $msg;
          return response()->json(['status' => 400, 'message' => $msg]);
        }
        try {

            $id = Crypt::decrypt($request->id);
              $horario['LV'][0] = $request->L1;
              $horario['LV'][1] = $request->L2;

              $horario['S'][0] = $request->S1;
              $horario['S'][1] = $request->S2;


              $horario['D'][0] = $request->D1;
              $horario['D'][1] = $request->D2;

            $horarioServicio['horario'] = $horario;

           $esta = new EstablecimientoPortal();
           $esta->idEstablecimiento=$id;
           $esta->acuerdo=$request->acuerdo;
           $esta->pointX=$request->txtLatEst;
           $esta->pointY=$request->txtLngEst;
           $esta->comentario=$request->comentario;
           $esta->horarioServicio=json_encode($horarioServicio);
           $esta->usuarioCreacion=Session::get('user').'@'.$request->ip();
           $esta->fechaCreacion=date('Y-m-d H:i:s');
           $esta->save();

           $est = Establecimientos::findOrFail($id);
           $est->telefonosContacto='["'.$request->telefonosContacto1.'","'.$request->telefonosContacto2.'"]';
           if($request->has('emailContacto')){
            $est->emailContacto=$request->emailContacto;
           }
           $est->save();

      } catch(Exception $e){
          DB::rollback();
          Log::error($e->getMessage());
          return response()->json(['status' => 404, 'message' => 'No se ha podido guardar la información, contacte al administrador del sistema!']);
      }
      return response()->json(['status' => 200,'id'=>$request->id]);

  }

  public function detalleEstablecimiento($idEstablecimiento){
      // $url = Config::get('app.api');
       $client = new Client();
       $idEstablecimiento=Crypt::decrypt($idEstablecimiento);

       //--------DATOS GENERALES DEL ESTABLECIMIENTO-----------------
        $getGeneral = $client->request('POST',  $this->url.'/est/get/estGeneral',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'no'     => (string)$idEstablecimiento
            ]

        ]);
        $g1 = json_decode($getGeneral->getBody());
        if($g1->status==200){
                   $datosGenerales=$g1->data[0];

      }elseif($g1->status==404){
        Session::put('errorPdf','No hay información');
      return redirect()->route('exp.establecimientos.id');

      }
       //--------DATOS PROPIETARIOS DEL ESTABLECIMIENTO-----------------
        $getPropietarios = $client->request('POST',  $this->url.'/est/get/propietario',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'no'     => (string)$idEstablecimiento
            ]

        ]);
        $g2 = json_decode($getPropietarios->getBody());
        if($g2->status==200){
                   $datosPropietario=$g2->data[0];

    }elseif($g2->status==404){
    //  dd("ERROR EN LA CONSULTA DATOS PROPIETARIO");
      $datosPropietario=null;
      }

      //---------------DATOS REGENTE------------------
        $getRegente = $client->request('POST',  $this->url.'/est/get/regentes',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'no'     => (string)$idEstablecimiento
            ]

        ]);
        $g3 = json_decode($getRegente->getBody());
        if($g3->status==200){
                   $datosRegente=$g3->data[0];

    }elseif($g3->status==404){
    //  dd("ERROR EN LA CONSULTA DATOS REGENTE");
      $datosRegente=null;
      }

                 $data = ['general' => $datosGenerales,
                  'pro'     => $datosPropietario,
                  'reg'   => $datosRegente];
                 // return $data;

                //$pdf = PDF::loadView('pdf.datosEstablecimiento', $data);
                //return $pdf->stream();

                 $view =  \View::make('pdf.datosEstablecimiento',$data)->render();
                 $pdf = \App::make('dompdf.wrapper');
                 $pdf->loadHTML($view);
                 return $pdf->stream("ESTABLECIMIENTO - ".$datosGenerales->idEstablecimiento.".pdf");


}


}
