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
use Validator;
use Log;
use Carbon\Carbon;
use App\fpdf\PDF_Code128;
use App\Models\Cssp\Mandamientos\Mandamiento;
use App\Models\Cosmeticos\VwPropietariosCosHig;
use Config;
use App\Http\Controllers\Anualidades\VariosMetodosController;
use App\Http\Controllers\Anualidades\PagosMoraController;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Cosmeticos\Cos\Cosmeticos;
use App\Models\Higienico\Hig\Higienicos;
class CosmeticoController extends Controller
{
     private $url=null;

    public function __construct() {
        $this->url = Config::get('app.api');
    }

    public function index()
    {
      //dd(DB::connection('sqlsrv')->SELECT('EXEC SP.propietariosCosHig ?',['P06011563']));
          $data = ['title' 			=> 'Anualidades'
          ,'subtitle'			=> 'Anualidades Cosméticos'
          ,'breadcrumb' 		=> [
            ['nom'	=>	'Anualidades', 'url' => '#'],
            ['nom'	=>	'Anualidades  Cosméticos', 'url' => '#']
          ]];
         /* if(!Session::has('msnErrorServicio')){
            try{
                   $client = new Client();
                   $res = $client->request('POST', $this->url.'anualidades/getPropietarios/cosmeticos',[
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                    ],
                        'form_params' =>[
                            'idProfesional'   =>Session::get('prof')
                    ]]);
             }catch(\GuzzleHttp\Exception\RequestException $e){
                    Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                    Session::flash('msnError','<b>¡ESTIMADO USUARIO OCURRIO UN PROBLEMA AL CARGAR LA INFORMACIÓN, POR FAVOR ESPERE UN MOMENTO E INTENTA NUEVAMENTE!</b>');
                    Session::flash('msnErrorServicio','ERROR ODIN');
                    $this->emailErrorOdin('¡Problemas al consultar información de cosméticos anualidades! - FECHA '.Carbon::now());
                    return redirect()->route('ver.anualidades.cosmeticos');
              }
             $r = json_decode($res->getBody());
             if($r->status==200)
                 $data['propietarios'] =$r->data[0];
             else
                 $data['propietarios']=[];
          }else{
                $data['propietarios']=[];
          }*/
          return view('recetas.anualidades.cosmeticos.index',$data);

    }
    public function getPropietariosList(Request $request){
      if(!Session::has('msnErrorServicio')){
        try{
               $client = new Client();
               $res = $client->request('POST', $this->url.'anualidades/getPropietarios/cosmeticos',[
                'headers' => [
                    'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                ],
                    'form_params' =>[
                        'idProfesional'   =>Session::get('prof')
                ]]);
         }catch(\GuzzleHttp\Exception\RequestException $e){
                Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                Session::flash('msnError','<b>¡ESTIMADO USUARIO OCURRIO UN PROBLEMA AL CARGAR LA INFORMACIÓN, POR FAVOR ESPERE UN MOMENTO E INTENTA NUEVAMENTE!</b>');
                Session::flash('msnErrorServicio','ERROR ODIN');
                $this->emailErrorOdin('¡Problemas al consultar información de cosméticos anualidades! - FECHA '.Carbon::now());
                return redirect()->route('ver.anualidades.cosmeticos');
          }
         $result = '<option value="" selected>SELECCIONAR PROPIETARIO...</option>';
         $r = json_decode($res->getBody());
          if($r->status==200){
                $pro=$r->data[0];
                foreach ($pro as $value) {
                    $result .= "<option value='$value->ID'>$value->NOMBRE ($value->ID)</option>";
                }
              //$data['propietarios'] =$r->data[0];
           }
              
        }else{
            $result = "";
        }
        return $result;
    }

    public function lista(Request $request){
        try{
                $client = new Client();
                $res = $client->request('POST', $this->url.'anualidades/lista/cosmeticos',[
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                    ],
                        'form_params' =>[
                            'tipo'          =>  $request->tipo,
                            'nacional'      =>  $request->origen,
                            'propietario'   =>  $request->propietario,
                            'idProfesional' => Session::get('prof')
                    ]]);
        }catch(\GuzzleHttp\Exception\RequestException $e){
                   $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                  Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                  $this->emailErrorOdin('¡Problemas al consultar información de cosméticos anualidades! - FECHA '.Carbon::now());
                  return json_encode($results);
        }
        $r = json_decode($res->getBody());
        if($r->status==200){

               $rows=$r->data[0];
               $annioActual = (int)date("Y");
               $annio = (date("Y")-1);
               $fechaActual = date('Y-m-d');
               $fechaLimite= date('Y-m-d',strtotime(date('Y').'-06-30'));
               $cosmeticos = new Collection;
               for($a=0;$a<count($rows);$a++){
                         $cmbox='<input type="checkbox" onClick="cont();" name="idPagos[]" id="idPagos" data-val="'.$rows[$a]->pago.'" value="'.Crypt::encrypt($rows[$a]->idProducto).'"/>';
                          $year = date('Y',strtotime($rows[$a]->vigenciaHasta));
                          $fechaVigencia = date('Y-m-d', strtotime($rows[$a]->vigenciaHasta));
                          $fechaLicencia = date('Y-m-d', strtotime($rows[$a]->renovacion));
                          $annioLicencia = (int)date('Y', strtotime($rows[$a]->renovacion));
                          if ($annio==$year) {
                           //vERIFICANDO LA FECHA ACTUAL QUE NO SEA MAYOR A 31 DE MARZO
                          if ($fechaActual<=$fechaLimite) {
                            //Fecha actual menor a la del límite
                          if ($annioActual<=$annioLicencia) {
                            //Si la fecha actual es menor a la de licencia puede proceder
                              $disable="";
                              $estado = VariosMetodosController::countGeneradosCosmeticos($rows[$a]->idProducto);
                             } else {
                              $disable="disabled";
                              $estado=3; //BLOQUEADO,SIN PODER GENERAR MANDAMIENTO LICENCIA VENCIDA
                            }

                          } else {
                            //Fecha actual mayor a la del límite
                            $disable="disabled";
                            $estado=0; //BLOQUEADO,SIN PODER GENERAR MANDAMIENTO DESPUÉS DEL 30 DE JUNIO
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

                   $text=$cmbox;
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
                 //$text=$cmbox;
                 $cosmeticos->push([
                            'combobox'         => $text,
                            'nombre_comercial' => $rows[$a]->idProducto.' - '.$rows[$a]->nombreComercial,
                            'vigente_hasta' => $rows[$a]->vigenciaHasta,
                            'ultima_renovacion' => $rows[$a]->renovacion,
                            'valor' => '<h4><span class="label label-success">$'.$rows[$a]->pago.'</span></h4>'

                   ]);

               }

          return Datatables::of($cosmeticos)->make(true);
        }else{
                    $results = array(
                    "draw"            => 0,
                    "recordsTotal"    => 0,
                    "recordsFiltered" => 0,
                    "data"            => []);
                    return json_encode($results);
        }



    }

     public function imprimirHoja($idEnlace){
      $idEnlace=Crypt::decrypt($idEnlace);
      if($idEnlace==''){
        Session::flash('msnError','¡Problemas al generar el mandamiento!');
        return back();
      }
        $client = new Client();
        $res = $client->request('POST', $this->url.'anualidades/pagopdf/importador',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'idEnlace'   =>$idEnlace
          ]

        ]);

      $r = json_decode($res->getBody());
      if($r->status==200){
      $info=$r->data[0];


      }else{
        Session::flash('msnError','¡Problemas al generar el pdf!');
        return back();
      }


    }

    public function store(Request $request)
    {
            $v = Validator::make($request->all(),[
                'idPagos'        =>'required',
                'tipoval'        =>'required|numeric|not_in:0|between:1,2',
                'origenval'      =>'required|numeric|not_in:0|between:1,2',
                'propietarioval' =>'required',
                'cuentade'       =>'sometimes|max:100'
              ]);
            $v->setAttributeNames([
                'idPagos'       =>'codigos de mandamiento',
                'tipoval'       =>'tipo de producto',
                'origenval'     =>'origen de producto',
                'propietarioval'=>'propietario',
                'cuentade'      =>'por cuenta de'
            ]);
            if ($v->fails()){
              $msg = "<ul>";
              foreach ($v->messages()->all() as $err) {
                $msg .= "<li>$err</li>";
              }
              $msg .= "</ul>";
              return redirect()->route('ver.anualidades.cosmeticos')->withErrors($msg);
            }
            try{
                $client = new Client();
                $res = $client->request('POST', $this->url.'anualidades/lista/cosmeticos',[
                    'headers' => [
                        'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
                    ],
                    'form_params' =>[
                            'tipo'  =>  $request->tipoval,
                            'nacional'  =>  $request->origenval,
                            'propietario'  =>  $request->propietarioval,
                            'idProfesional' => Session::get('prof')
                    ]]);
           }catch(\GuzzleHttp\Exception\RequestException $e){
                  Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                  $this->emailErrorOdin('¡Problemas al consultar información de cosméticos anualidades! - FECHA '.Carbon::now());
                  return redirect()->route('ver.anualidades.cosmeticos');
             }
            $r = json_decode($res->getBody());
            if($r->status==200){
                $arrayproductos=[];
                $productoselecionados=[];
                $productosvalidos=[];
                //INFORMACION DE LOS PRODUCTOS DEL PROFESIONAL
                $datapro=$r->data[0];
                //INFORMACION DEL PAGO DE LOS PRODUCTOS
                $datapago=$r->datapago[0];
                //dd($datapago[0]->CODIGO);
                foreach($datapro as $dpro){
                  array_push($arrayproductos,$dpro->idProducto);
                }
                foreach($request->idPagos as $key => $producto){
                    array_push($productoselecionados,Crypt::decrypt($producto));
                }
                //RECOREMOS LOS PRODUCTOS SELECCIONADOS Y VALIDAMOS SI PERTENECEN AL ID PROFESIONAL DE QUIEN LO ESTA GENERANDO
                foreach($productoselecionados as $producto){
                    //SI EL PRODUCTO NO ESTA EN LA LISTA DE PRODUCTOS RETORNAMOS UN MENSAJE
                    if(!in_array($producto,$arrayproductos)){
                      Session::flash('msnError','¡VERIFICAR LISTA DE PRODUCTOS DEL PROPIETARIO!');
                      return back();
                    }
                }
                //VALIDAMOS SI LOS PRODUCTOS SELECCIONADOS TIENEN SU FECHA DE VIGENCIA Y RENOVACIÓN VALIDAS
                $annioActual = (int)date("Y");
                $annio = (date("Y")-1);
                $fechaActual = date('Y-m-d');
                $fechaLimite= date('Y-m-d',strtotime(date('Y').'-06-30'));
                $estado=0;
                $msg = "<ul>";
                foreach($datapro as $pro){
                    if(in_array($pro->idProducto,$productoselecionados)){
                             //array_push($productosvalidos,array('idproducto'=>$pro->idProducto,'nombreComercial'=>$pro->nombreComercial));
                              $year = date('Y',strtotime($pro->vigenciaHasta));
                              $fechaVigencia = date('Y-m-d', strtotime($pro->vigenciaHasta));
                              $fechaLicencia = date('Y-m-d', strtotime($pro->renovacion));
                              $annioLicencia = (int)date('Y', strtotime($pro->renovacion));
                            if($annio==$year) {
                                 //VERIFICANDO LA FECHA ACTUAL QUE NO SEA MAYOR A 31 DE MARZO
                                if($fechaActual<=$fechaLimite) {
                                     //Fecha actual menor a la del límite
                                    if($annioActual<=$annioLicencia) {
                                      //Si la fecha actual es menor a la de licencia puede proceder
                                      array_push($productosvalidos,array('idproducto'=>$pro->idProducto,'nombreComercial'=>$pro->nombreComercial));
                                    }else{
                                        $estado=$estado+1; //BLOQUEADO,SIN PODER GENERAR MANDAMIENTO LICENCIA VENCIDA
                                         $msg .= "<li>El producto $pro->nombreComercial tiene licencia vencida.</li>";
                                    }
                                } else {
                                  //Fecha actual mayor a la del límite
                                  $estado=$estado+1; //BLOQUEADO,SIN PODER GENERAR MANDAMIENTO DESPUÉS DEL 30 DE JUNIO
                                  $msg .= "<li>Para el  producto $pro->nombreComercial no se puede generar mandamiento después del 30 de Junio.</li>";
                                }

                            }else if($annio>$year) {
                              $estado=$estado+1; //Observado, VIGENCIA CON RETRASO
                              $msg .= "<li>El producto $pro->nombreComercial tienve vigencia con retraso</li>";
                            }else if($annio<$year){
                              $estado=$estado+1; //Pagado, VIGENCIA PAGADA
                              $msg .= "<li>El producto $pro->nombreComercial ya esta vigente</li>";
                            }
                    }
                }
                if($estado>0){
                      $msg .= "</ul>";
                      return redirect()->route('ver.anualidades.cosmeticos')->withErrors($msg);
                }

            }else{
                return redirect()->route('ver.anualidades.cosmeticos')->withErrors("¡Estimado usuario la información que ingresa no es valida, vuelva a intentarlo!");
            }
            $objeto = new PagosMoraController();
            $aplicaMora = $objeto->comprobarFecha();
            $TOTAL = $datapago[0]->VALOR*count($productosvalidos);
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
              $VENCIMIENTO=Carbon::now()->endOfMonth()->format('Y/m/d');
            }else{
              $totalMora=0;
              $TOTAL=number_format((float)$TOTAL, 2, '.', '');
              //$VENCIMIENTO =  DB::select(DB::raw('SELECT CONCAT(YEAR(CURDATE()),\'\/03\/31\') AS VENCIMIENTO'));
              $VENCIMIENTO = Carbon::createFromFormat('Y/m/d',date('Y').'/03/01')->endOfMonth()->format('Y/m/d');
            }
            //traer ID_MANDAMIENTO
            if($TOTAL>=9999){
              return redirect()->route('ver.anualidades.cosmeticos')->withErrors("¡Estimado usuario, el sistema genera mandamientos de pagos por un monto inferior a $9,999.99!");
            }
            $ID_MANDAMIENTO =Mandamiento::orderBy('ID_MANDAMIENTO', 'desc')->select('ID_MANDAMIENTO')->first();
            $ID_MANDAMIENTO=$ID_MANDAMIENTO->ID_MANDAMIENTO+1;
            $idProfesional = Session::get('prof');

            try {
                DB::beginTransaction();
                $mandamiento = new Mandamiento;
                $mandamiento->ID_MANDAMIENTO = $ID_MANDAMIENTO;
                $mandamiento->FECHA = date('Y/m/d');
                $mandamiento->HORA = date('H:i:s');
                $mandamiento->ID_CLIENTE = $idProfesional;
                $mandamiento->A_NOMBRE = Session::get('name').' '.Session::get('lastname');
                $mandamiento->FECHA_VENCIMIENTO = $VENCIMIENTO;
                $mandamiento->ID_JUNTA = 'U21';
                $mandamiento->TOTAL = $TOTAL;
                $mandamiento->NOMBRE_CLIENTE = Session::get('name').' '.Session::get('lastname');
                $mandamiento->POR_CUENTA = mb_strtoupper($request->cuentade, 'UTF-8');
                $mandamiento->ID_USUARIO_CREACION = Session::get('user').'@'.$request->ip();
                $mandamiento->save();
                //detalle
                 for($i=0;$i<count($productosvalidos);$i++){
                         $mandamiento->detalle()->create([
                        'ID_CLIENTE'=>$productosvalidos[$i]["idproducto"]
                        ,'ID_TIPO_PAGO'=>$idTipoPago
                        ,'VALOR'=>$datapago[0]->VALOR
                        ,'TIPO_ANUALIDAD'=>3
                        ,'NOMBRE_CLIENTE'=>$productosvalidos[$i]["nombreComercial"]
                      ]);
                }
                if($aplicaMora){
                  $totalMora=number_format((float)$totalMora, 2, '.', '');
                  $mandamiento->detalle()->create([
                    'ID_CLIENTE'=>$datapago[0]->MORA_CODIGO
                    ,'ID_TIPO_PAGO'=>$datapago[0]->MORA_TIPO_PAGO
                    ,'VALOR'=>$totalMora
                    ,'TIPO_ANUALIDAD'=>0
                    ,'NOMBRE_CLIENTE'=>$datapago[0]->MORA_NOMBRE
                  ]);
                }
                $mandamiento=Mandamiento::find($ID_MANDAMIENTO);
                if(count($productosvalidos)>1){
                  $textoComentario='Pago anualidad de '.count($productosvalidos).' productos. Ver lista anexa.';
                }else{
                  $textoComentario='Pago anualidad de un producto. Ver lista anexa.';
                }

                error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                define('FPDF_FONTPATH',app_path().'/fpdf/font/');
                $pdf=new PDF_Code128('P','mm','Letter');
                $pdf->AddPage();
                $pdf->SetXY(5,9);
                $pdf->SetFont('Arial','',12);
                $pdf->SetTitle('MANDAMIENTO - '.$mandamiento->ID_MANDAMIENTO);
                $pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(0,5,'NIT 0614-020312-105-7',0,1,'C');
                $pdf->SetFont('Times','',8);
                $pdf->Cell(0,4,'MANDAMIENTO DE INGRESOS',0,1,'C');
                $pdf->SetFont('Times','',7);
                $unidad  = $datapago[0]->UNIDAD;
                $valorman=$datapago[0]->VALOR*count($productosvalidos);
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
                $pdf->MultiCell(20,3,'$ '.number_format((float)$valorman, 2, '.', '').'',0,'L',false);
                $pdf->Ln();
                $pdf->MultiCell(165,3,$textoComentario,0,'L',false);
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
                $pdf->SetXY(180,65);
                $pdf->Write(5,'Copia: Banco');
                $pdf->SetXY(180,60);
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
                $pdf->MultiCell(20,3,'$ '.number_format((float)$valorman, 2, '.', '').'',0,'L',false);
                $pdf->Ln();
                $pdf->MultiCell(165,3,$textoComentario,0,'L',false);
                $pdf->Ln();
                //*****
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
                $pdf->SetXY(180,145);
                $pdf->Write(5,'Usuario: '.$mandamiento->ID_CLIENTE.'');
                $pdf->SetXY(10,150);
                $pdf->Write(4,utf8_decode('Este mandamiento de ingreso será valido con la CERTIFICACIÓN DE LA MAQUINA Y EL SELLO del colector autorizado o con el comprobante del pago electrónico y podrá ser pagado en la red de las Agencias del Banco Agrícola, S.A.'),0,0,'J');

                $pdf->AddPage();
                $image1 = "img/escudo-new.jpg";
                $image2 = "img/dnm-new.jpg";
                $pdf->SetXY(5,9);
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(0,5,'ANUALIDAD '.date("Y").'',0,1,'C');
                $pdf->SetFont('Times','',8);
                $pdf->Cell(0,4,utf8_decode('MANDAMIENTO N° '.$mandamiento->ID_MANDAMIENTO),0,1,'C');
                $pdf->SetFont('Times','',7);
                //A set
                $pdf->SetXY(9,2);
                $pdf->Image(url($image1),11,9,14.5);
                $pdf->SetXY(190,9);
                $pdf->Image($image2);
                $pdf->SetXY(160,10);
                $pdf->SetFont('Arial','',10);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->SetXY($x-150, $y+10);
                $pdf->Cell(150,4,'',0,1,'J');
                $pdf->ln();
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->Cell(15,4,''.utf8_decode("N°").'',1,0,'C');
                $pdf->Cell(35,4,''.utf8_decode("N° REGISTRO").'',1,0,'C');
                $pdf->Cell(150,4,''.utf8_decode("PRODUCTO").'',1,0,'C');
                $pdf->SetFont('Times','',7);
                $pdf->ln();
                $counter = 1;
                for($a=0;$a<count($productosvalidos);$a++){
                    $altoProd=$pdf->GetMultiCellHeight(150,4,$productosvalidos[$a]["nombreComercial"],1,'L');
                    $altoFinal=$altoProd;
                    $consProd= ($altoFinal/($altoProd/4));
                    $pdf->MultiCell(15,$altoFinal,''.$counter.'',1,'C',false);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->SetXY($x+15, $y-$altoFinal);
                    $pdf->MultiCell(35,$altoFinal,''.utf8_decode($productosvalidos[$a]["idproducto"]).'',1,'C',false);
                    $pdf->SetXY($x+50, $y-$altoFinal);
                    $pdf->MultiCell(150,$consProd,''.utf8_decode("".$productosvalidos[$a]["nombreComercial"]."").'',1,'J',false);
                    $pdf->ln();
                    $pdf->SetXY(10,$y);
                    $counter +=1;
                }
                $pdf->Output('MANDAMIENTO-'.$mandamiento->ID_MANDAMIENTO.'.pdf','D');

          DB::commit();

        }catch (\Exception $e){
           DB::rollback();
           Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
           return redirect()->route('ver.anualidades.cosmeticos')->withErrors("¡PROBLEMAS AL GENERAR MANDAMIENTO DE PAGO, POR FAVOR ESPERE UN MOMENTO E INTENTA NUEVAMENTE!");
        }

    }


   public function hojaProductosCosmeticos(Request $request)
    {
      //dd($request->all());
        $v = Validator::make($request->all(),[
                'tipo1'       =>'required|numeric|not_in:0|between:1,2',
                'origen1'     =>'required|numeric|not_in:0|between:1,2',
                'pro1'        =>'required'
              ]);
        $v->setAttributeNames([
                'tipo1'         =>'tipo de producto',
                'origen1'       =>'origen de producto',
                'pro1'          =>'propietario',
            ]);
        if ($v->fails()){
              $msg = "<ul>";
              foreach ($v->messages()->all() as $err) {
                $msg .= "<li>$err</li>";
              }
              $msg .= "</ul>";
               return back()->withErrors($msg);
       }
       try{
       $client = new Client();
       $res = $client->request('POST', $this->url.'anualidades/lista/cosmeticos',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
            ],
                'form_params' =>[
                    'tipo'  =>  $request->tipo1,
                    'nacional'  =>  $request->origen1,
                    'propietario'  =>  $request->pro1,
                    'idProfesional' => Session::get('prof')
            ] ]);
      }catch(\GuzzleHttp\Exception\RequestException $e){
                  Log::error('Error Exception',['time'=>Carbon::now(),'code'=>$e->getFile(),'line'=>$e->getLine(),'msg'=>$e]);
                  $this->emailErrorOdin('¡Problemas al consultar información de cosmeticos anualidades! - FECHA '.Carbon::now());
                  return redirect()->route('ver.anualidades.cosmeticos');
      }
      $r = json_decode($res->getBody());

          $propietario=VwPropietariosCosHig::find($request->pro1);
          $pdf=new PDF_Code128('P','mm','legal');
          /*IMPRIMIENDO ANEXOSS*/
          $pdf->AddPage();
          $image1 = "img/escudo-new.jpg";
          $image2 = "img/dnm-new.jpg";
          $pdf->SetXY(5,9);
          $pdf->SetFont('Arial','',12);
          $pdf->Cell(0,5,'DIRECCION NACIONAL DE MEDICAMENTOS',0,1,'C');
          $pdf->SetFont('Arial','',10);
          $pdf->Cell(0,5,'ANUALIDAD '.date("Y").'',0,1,'C');
          $pdf->SetFont('Times','',8);
          $pdf->Cell(0,4,utf8_decode('LISTA DE PRODUCTOS COSMÉTICOS E HIGIÉNICOS HASTA LA FECHA '.date("Y-m-d")),0,1,'C');
          $pdf->SetFont('Times','',7);
          //A set
          $pdf->Image(url($image1),11,8,14.5);
          $pdf->SetXY(190,8);
          $pdf->Image($image2);
          $pdf->SetXY(160,10);
          $pdf->SetFont('Arial','',10);
          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->SetXY($x-150, $y+10);
          $pdf->ln();
          $x = $pdf->GetX();
          $y = $pdf->GetY();
          $pdf->Cell(0,4,utf8_decode($propietario->nombre),0,1,'C');
          $pdf->ln();
          $pdf->Cell(10,4,''.utf8_decode("N°").'',1,0,'C');
          $pdf->Cell(25,4,''.utf8_decode("N° REGISTRO").'',1,0,'C');
          $pdf->Cell(90,4,''.utf8_decode("PRODUCTO").'',1,0,'C');
          $pdf->Cell(35,4,''.utf8_decode("ULTIMA ANUALIDAD").'',1,0,'C');
          $pdf->Cell(35,4,''.utf8_decode("RENOVACIÓN").'',1,0,'C');
          $pdf->SetFont('Times','',7);
          $pdf->ln();
          $counter=1;
         if($r->status==200){
             $rows=$r->data[0];
             for($a=0;$a<count($rows);$a++){

              $altoProd=$pdf->GetMultiCellHeight(90,4,$rows[$a]->nombreComercial,1,'L');
              $altoFinal=$altoProd;
              $consProd= ($altoFinal/($altoProd/4));
              $pdf->MultiCell(10,$altoFinal,''.$counter.'',1,'C',false);
              $x = $pdf->GetX();
              $y = $pdf->GetY();


              $pdf->SetXY($x+10, $y-$altoFinal);
              $pdf->MultiCell(25,$altoFinal,''.utf8_decode($rows[$a]->idProducto).'',1,'C',false);

              $pdf->SetXY($x+35, $y-$altoFinal);
              $pdf->MultiCell(90,$consProd,''.utf8_decode("".$rows[$a]->nombreComercial."").'',1,'L',false);

              $pdf->SetXY($x+125, $y-$altoFinal);
              $pdf->MultiCell(35,$altoFinal,''.utf8_decode("".$rows[$a]->vigenciaHasta."").'',1,'C',false);

              $pdf->SetXY($x+160, $y-$altoFinal);
              $pdf->MultiCell(35,$altoFinal,''.utf8_decode("".$rows[$a]->renovacion."").'',1,'C',false);
              $pdf->ln();
              $pdf->SetXY(10,$y);
              $counter +=1;
                /*
                $annio = (date("Y")-1);
                $year = date('Y',strtotime($rows[$a]->vigenciaHasta));
                $fechaVigencia = date('Y-m-d', strtotime($rows[$a]->vigenciaHasta));
                $fechaLicencia = date('Y-m-d', strtotime($rows[$a]->renovacion));
                $annioLicencia = (int)date('Y', strtotime($rows[$a]->renovacion));
                $fechaActual = date('Y-m-d');
                $fechaLimite= date('Y-m-d',strtotime(''.date('Y').'-04-31'));
                  if($annio==$year) {



                  } else if($annio>$year) {


                  }else if($annio<$year) {
                  //Alto del prodsucto
                  $altoProd=$pdf->GetMultiCellHeight(150,4,$rows[$a]->nombreComercial,1,'L');

                  $altoFinal=$altoProd;

                  $consProd= ($altoFinal/($altoProd/4));
                  $pdf->MultiCell(15,$altoFinal,''.$counter.'',1,'C',false);
                  $x = $pdf->GetX();
                  $y = $pdf->GetY();
                  $pdf->SetXY($x+15, $y-$altoFinal);
                  $pdf->MultiCell(35,$altoFinal,''.utf8_decode($rows[$a]->idProducto).'',1,'C',false);
                  $pdf->SetXY($x+50, $y-$altoFinal);
                  $pdf->MultiCell(150,$consProd,''.utf8_decode("".$rows[$a]->nombreComercial."").'',1,'J',false);
                  $pdf->ln();
                  $pdf->SetXY(10,$y);
                  $counter +=1;

                  }*/
              }


          }else{

                /*$pdf->MultiCell(10,4,''.$counter.'',1,'C',false);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->SetXY($x+10, $y-4);
                $pdf->MultiCell(25,4,''.utf8_decode("Sin registros").'',1,'C',false);
                $pdf->SetXY($x+35, $y-4);
                $pdf->MultiCell(90,4,''.utf8_decode("Sin registros").'',1,'J',false);
                $pdf->SetXY($x+35, $y-4);
                $pdf->MultiCell(125,4,''.utf8_decode("").'',1,'J',false);
                $pdf->SetXY($x+35, $y-4);
                $pdf->MultiCell(160,4,''.utf8_decode("").'',1,'J',false);
                $pdf->ln();
                $pdf->SetXY(10,$y);*/
                return redirect()->route('ver.anualidades.cosmeticos')->withErrors("¡NO EXISTE INFORMACIÓN DE PRODUCTOS!");

          }
          $pdf->Output(date("Y-m-d").' LISTA-COSHIG.pdf','D');



    }
}
