<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Datatables;
use App\Http\Requests;
use App\PubCatEstadoSol;
use App\PubCatTramites;
use App\PubCatMedios;
use App\Models\Establecimientos;	
use DB;
use App\Http\Requests\SolicitudRequest;
use App\VwSolicitudes;
use App\VwVerSolicitudes;
use Session;
use GuzzleHttp\Client;
use Crypt;
use File;
use Config;

use App\Models\Cssp\Productos;
use App\Models\Cssp\Propietarios;
use App\Models\PromoPub\Solicitud;
//core de la ficha promocion y publicidad
class PromoPubController extends Controller
{
    //
     private $url=null;
    
    public function __construct() { 
        $this->url = Config::get('app.api');
    }

    //funcion que muestra la vista de ingreso de una nueva solicitud nacional
    public function index(){

    	$data = ['title' 			=> 'Solicitud de Promocion'
				,'subtitle'			=> 'Nueva Solicitud'
				,'breadcrumb' 		=> [
			 		['nom'	=>	'Promocion y Publicidad', 'url' => '#'],
			 		['nom'	=>	'Nueva Solicitud', 'url' => '#']
				]]; 
		//$idProfesional=
		//dd(Session::get('perfil'));
		$perfiles=Session::get('perfil');
		$nit=Session::get('user');
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/e/get',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'nit' => $nit,
	    		'tipo' =>'FARMACEUTICO'
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['establecimientos']=$r->data[0];	
		}
		
		//variable productos vacia
		$data['estado']=0;
		$data['productos']=null;
		$data['medios']= PubCatMedios::getMedios();
		
		//dd(count($r->data[0]));
		return view('promoypub.nuevaSolicitud',$data);
    }

    //funcion que muestra la vista para una nueva solicitud extranjera
    public function solicituext(){

    	$data = ['title' 			=> 'Solicitud de Promocion'
				,'subtitle'			=> 'Nueva Solicitud'
				,'breadcrumb' 		=> [
			 		['nom'	=>	'Promocion y Publicidad', 'url' => '#'],
			 		['nom'	=>	'Nueva Solicitud', 'url' => '#']
				]]; 
		//$idProfesional=
		//dd(Session::get('perfil'));
		$perfiles=Session::get('perfil');
		//dd($perfiles);
		/*$nit=Session::get('user');
		$client = new Client();
		$res = $client->request('POST', $this->url.'pel/e/get',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'nit' => $nit,
	    		'tipo' =>'TITULAR'
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			$data['establecimientos']=$r->data[0];	
		}
		elseif($r->status==404){
		    return  redirect()->route('');
        }*/
		//variable productos vacia
		$data['estado']=0;
		$data['productos']=null;
		$data['medios']= PubCatMedios::getMedios();

		//dd($data);

		return view('promoypub.nuevaSolicitudExt',$data);

    }

    //funcion que valida el mandamiento a utilizar en la solicitud
    public function ValidarMandamiento(Request $request){

    	//return $request->all();
    	//$idmandamiento = Input::get('mandamiento');
    	
    	$idmandamiento = $request->get('mandamiento');
    	$client = new Client();
		$res = $client->request('POST', $this->url.'pyp/verificar-mandamiento',[
			'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    	],
	    	'form_params' =>[
	    		'idMandamiento' => (string)$idmandamiento,
	    
	    	]	
    	]);

		$r = json_decode($res->getBody());

		if($r->status==200){
			if(count($r->data[0]))				
				return response()->json(['status' => 200,'message' => "Se ha validado el mandamiento", 'data' => $r->data[0]]);
		}
		else{
			
		}
		
    }

    //funcion que muestra la vista para ver las solicitudes del usuario
    public function verSolicitudes()
	{
		//
		$data = ['title' 			=> 'Tramites Publicitarios'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
			 		['nom'	=>	'Tramites Publicitarios', 'url' => route('ver.solicitudes')],
			 		['nom'	=>	'', 'url' => '#']
				
				]];
		/*$tramitespub= VwSolicitudes::getDataRowsPublicidad();
		$datatable=Datatables::of($tramitespub->whereIn('idEstado',array(4,5,6,7,8))->orderBy('numeroSolicitud','desc'))
							->make(true);
		dd($datatable);*/
		//$result = array()
		$estados = PubCatEstadoSol::getEstados();
		$data['estados']=$estados;
		$tramites=PubCatTramites::getTramites();
		$data['tramites']=$tramites;
		//dd($data);
		return view('promoypub.versolocitud',$data);
	}

	//funcion para el dataTable ver la solicitudes
    public function getDataRowsPublicidadBySearch(Request $request){
		
    	$nit=Session::get('user');
		//$tramitespub= VwVerSolicitudes::VerSolicitudesByNit($nit);
		$tramitespub = Solicitud::getSolicitudes($nit);
		if($tramitespub!=null){
		
		if(empty($request->get('nsolicitud')) and empty($request->get('nomComercial')) and empty($request->get('nregistro'))
				and empty($request->get('estado')) and empty($request->get('tipo')))

			return Datatables::of($tramitespub)
							->addColumn('subsanacion', function ($dt) {
								
	                            if($dt->idEstado==11){			
	                				$subsanada= VwSolicitudes::getSolicitudPadre($dt->numeroSolicitud);
	                				if($subsanada==0){	
	                					return	'<a href="'.route('subsanar',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-warning btn-perspective"><i class="fa fa-pencil-square-o"></i>SUBSANAR</a>';
	                				}
	                				else{ return''; }
	                			}
	                			else{
	                				return '<a href="'.route('imprimir.solicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" target="_blank" title="Imprimir Solicitud"><i class="fa fa-file icon-rounded icon-xs icon-info"></i></a>';
	                			}
	           				 })
							->addColumn('impDic', function ($dt){
								return '';
	                            /*if($dt->idEstado==11){			
	                				
	          						return	'<a href="'.route('imprimir.dictamen',['idSolicitud'=> Crypt::encrypt($dt->idSolicitud),'idEstado' => $dt->idEstado]).'" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
	                			}
	                			elseif($dt->idEstado==10){
	                				return	'<a href="'.route('imprimir.dictamen',['idSolicitud'=> Crypt::encrypt($dt->idSolicitud),'idEstado' => $dt->idEstado]).'" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';	
	                			}
	                			elseif($dt->idEstado==9){
	                				return	'<a href="'.route('imprimir.dictamen',['idSolicitud'=> Crypt::encrypt($dt->idSolicitud),'idEstado' => $dt->idEstado]).'" target="_blank" title="Imprimir Solicitud"><i class="fa fa-print icon-rounded icon-xs icon-primary"></i></a>';
	           				 	}
	           				 	else{
	           				 		return '';
	           				 	}*/
	           				 })
							->make(true);

		else{
				return Datatables::of($tramitespub)
						->addColumn('subsanacion', function ($dt) {
								
	                            if($dt->idEstado==11){			
	                				$subsanada= VwSolicitudes::getSolicitudPadre($dt->numeroSolicitud);
	                				if($subsanada==0){	
	                					return	'<a href="'.route('subsanar',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" class="btn btn-xs btn-warning btn-perspective"><i class="fa fa-pencil-square-o"></i>SUBSANAR</a>';
	                				}
	                				else{ return ''; }
	                			}else{
	                				return '<a href="'.route('imprimir.solicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" target="_blank" title="Imprimir Solicitud"><i class="fa fa-file icon-rounded icon-xs icon-info"></i></a>';
	                			}
	           				 })
						->addColumn('impDic', function ($dt){
								return '';
						})
	        			->filter(function($query) use ($request){

	        				if($request->has('nsolicitud')){
	        					$query->where('numeroSolicitud','=',$request->get('nsolicitud'));
	        				}
	        				if($request->has('nomComercial')){
	        					$query->where('nombreComercial','like',"%".$request->get('nomComercial')."%");
	        				}
	        				if($request->has('nregistro')){
	        					
	        					$query->where('idEstablecimiento','like',"%".$request->get('nregistro')."%");
	        				}
	        				if($request->has('estado')){
	        					$query->where('idEstado','=',$request->get('estado'));
	        				}
	        				if($request->has('tipo')){

	        					$query->where('nombreTramite','like',"%".$request->get('tipo')."%");
	        				}

	        			})
						->make(true);
			}
		}
		else{
			$results = array(
		            "draw" 			  => 0,		            
		        	"recordsTotal"    => 0,
		        	"recordsFiltered" => 0,
		          	"data"            => []);
			return json_encode($results);
		}
	}
    
    // funcion para obtener los productos a hacer promocion o publicadad por establecimiento
    public function getProductosByEst(Request $request){
    	//and $request->has('idEstablecimiento')
    	$codigo = substr($request->idEstablecimiento,0,3);
    	
    	if($request->has('tipoTramite')){
    			if($request->tipoTramite==1){
    			$idModalidadVenta=[1,5];
	    		}
	    		else{
	    			$idModalidadVenta=[1,3];
	    		}
	    		/*
	    		$codigo = substr($request->idEstablecimiento,0,3);
			 
				if($codigo==='E04'){
					$idEstablecimiento=$request->idEstablecimiento;
				}
				else{
					$idEstablecimiento='0';
				}

    			$client = new Client();
				$res = $client->request('POST', $this->url.'pel/p/f/get',[
				'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    		],
	    		'form_params' =>[
	    		'tipo' =>$request->get('tipoTramite'),
	    		'idEstablecimiento' =>$idEstablecimiento
	    		]	
	    		
    			]);
				
				$r = json_decode($res->getBody());
				return $r;

				if($r->status==200){

					$productos_pupi = new Collection;
					//
					//$r->data[0]->where('idModalidadVenta',1);
			        for ($i = 0; $i < count($r->data[0]); $i++) {
			            $productos_pupi->push([
			                'idProducto'         => $r->data[0][$i]->idProducto,
			                'nombreComercial' =>	$r->data[0][$i]->nombreComercial,
			                //'idModalidadVenta' => 	$r->data[0][$i]->idModalidadVenta,
			                'nombreModalidadVenta' => $r->data[0][$i]->nombreModalidadVenta,
			                'vigenteHasta' => $r->data[0][$i]->vigenteHasta,
			                'ultimaRenovacion' => $r->data[0][$i]->ultimaRenovacion,
			             
			            ]);
			        }*/
			 if($request->idEstablecimiento!=null and $codigo==='E04'){

	            $productos = DB::table('dnm_usuarios_portal.vwproductos as prod')
	                        ->join('cssp.siic_productos_fabricantes as prodfab','prod.idProducto','=','prodfab.ID_PRODUCTO')
	                        ->join('dnm_establecimientos_si.est_establecimientos as est','prodfab.ID_FABRICANTE','=','est.idEstablecimiento')
	                        ->where('est.idEstablecimiento',$request->idEstablecimiento)
                            ->where('ACTIVO','A')
	                        ->whereIn('prod.idModalidadVenta',[1,3,5])
	                        ->where(function($query) use ($request){
	                             if($request->tipo==1) //PROMOCION
	                                $query->whereIn('idModalidadVenta',[1,5]);
	                             else if($request->tipo==2) //PUBLICIDAD
	                                $query->whereIn('idModalidadVenta',[1,3]);
	                        })->groupBy('prod.idProducto')->select('prod.*')->get();
        	}
        	else{            
	            $productos= DB::table('dnm_usuarios_portal.vwproductos')->where('ACTIVO','A')->where(function($query) use ($request){
					            if($request->tipoTramite==1) //PROMOCION
					                $query->whereIn('idModalidadVenta',[1,5]);
					            else if($request->tipoTramite==2) //PUBLICIDAD
					                $query->whereIn('idModalidadVenta',[1,3]);
					            })->groupBy('idProducto');
        	}
			       

			return Datatables::of($productos)->make(true);
			       // return Datatables::of($productos_pupi)->make(true);
				//}
				/*elseif($r->status==400){
					Session::flash('msnError',$r->message );
					return back()->withInput();
					$productos_pupi=null;
				}*/
    			
    		}
    		
}
	

	//funcion para obtener los productos que se desea hacer promo o pub en una solcitud por propietario
 public function getProductosByPP(Request $request){
 		
 		//dd($request->all());
 		
    	if($request->has('tipoTramite')){
    			$nit=Session::get('user');
    			$client = new Client();
    			$res = $client->request('POST', $this->url.'pel/p/t/get',[
				'headers' => [
	        	'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',
	   	
	    		],
	    		'form_params' =>[
	    		'nit' =>  $nit,
	    		'tipo' => $request->get('tipoTramite'),
	    		]	
	    		
    			]);

				$r = json_decode($res->getBody());
				//dd($r);

				if($r->status==200){
					//return $r->status;
					$productos_pupi = new Collection;
					//
					//$r->data[0]->where('idModalidadVenta',1);
			        for ($i = 0; $i < count($r->data[0]); $i++) {
			            $productos_pupi->push([
			                'idProducto'         => $r->data[0][$i]->idProducto,
			                'nombreComercial' =>	$r->data[0][$i]->nombreComercial,
			                //'idModalidadVenta' => 	$r->data[0][$i]->idModalidadVenta
			                'nombreModalidadVenta' => $r->data[0][$i]->nombreModalidadVenta,
			                'vigenteHasta' => $r->data[0][$i]->vigenteHasta,
			                'ultimaRenovacion' => $r->data[0][$i]->ultimaRenovacion,
                            'titular' => $r->data[0][$i]->nombreTitular,
                            'idTitular' => $r->data[0][$i]->idPropietario
			             
			            ]);
			        }
			        return Datatables::of($productos_pupi)->make(true);
				}
				elseif($r->status==400){
					Session::flash('msnError',$r->message );
					return back()->withInput();
					$productos_pupi=null;
				}
    			
    		}
    		
			
    	
}
    

	// funcion que guarda la nueva solicitud nacional.
    public function store(Request $request)

	{	


		// lo primero que verificamos es que si en la solicitud han ingresado el arte 
		//sino la han ingresado regresa a la vista mostrando el mensaje
		if($request->file('files')==null){
			Session::flash('msnError','Es Obligatorio que suba el arte publicitario');
			return back()->withInput();
		}

		
		//si el origen es 4 es subsanacion se mantiene el mismo medio 
		// y se guarda la solicitud padre 
		if($request->origen==4){
			$medio=$request->idMedio;
			$solpadre=$request->solpadre;
		}
		elseif($request->get('tipoTramite')==1){
			$medio=$request->get('idMedio1');
			$solpadre=null;
		}
		else{
			$medio=$request->get('idMedio');
			$solpadre=null;
		}


		$est[]=array();
		$est[0]=$request->get('numregistro');
		$nombre_est[]=array();
		$nombre_est[0]=$request->get('nomComercial');
		$nit=Session::get('user');
		$client = new Client();
		$res = $client->request('POST', $this->url.'pyp/s/data/create',[
		'headers' => [
		'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

		],
		'form_params' =>[
		'origen'      =>$request->origen,
		'nit'         =>$nit,
		'id_est'      =>$est,
		'nombre_est'  =>$nombre_est,
		'mandamiento' =>$request->num_mandamiento,
		'medio' 	  =>$medio,
		'version'	  =>$request->version,
		'productos'   =>$request->pTableData,
		'padre'		  =>$solpadre,
		'ip'		  =>$request->ip()		
		]	
		
		]);

		$r = json_decode($res->getBody());
		//dd($r);
		
		if($r->status==200){
			
				//$path='C:\Publicidad';
				$path='X:\Publicidad';
				$path2='X:/Publicidad/';

				$files= $request->file('files');
				//primero verifica si hay archivos a subir
				if(!empty($files)){
					$artes[]=array();
					for($i=0;$i<count($files);$i++) {
						//si hay archivos crear la ruta con el id del usuario
						$newpath=$path.'/'.$r->data->idSolicitud;
						File::makeDirectory($newpath, 0777, true, true);
						//$name= $files[$i]->getClientOriginalName();
						$name='Solicitud'.$r->data->idSolicitud.$i.'.'.$files[$i]->getClientOriginalExtension();
						$type= $files[$i]->getClientMimeType();
						$files[$i]->move($newpath,$name);
						
						$artes[$i][0]=$path2.$r->data->idSolicitud.'/'.$name;
						$artes[$i][1]=$type;
					}
					$client = new Client();
					$response = $client->request('POST', $this->url.'pyp/s/art/create',[
					'headers' => [
					'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

					],
					'form_params' =>[
					'solicitud'   =>$r->data->idSolicitud,
					'artes'       =>$artes,
					'ip'		  =>$request->ip()
					
					]	
					
					]);

					$re = json_decode($response->getBody());

					if($re->status==400){
						Session::flash('msnError',$re->message);
						return back()->withInput();
					}
				}
				else{
					Session::flash('msnError','Es Obligatorio que suba el arte publicitario');
					return back()->withInput();
				}
		
		Session::flash('msnExito','Se ha Ingresado solicitud sastisfactoriamente');
		$idSolicitud=$r->data->idSolicitud;
		return redirect()->back()->with('idSolicitud',$idSolicitud);

		}
		elseif($r->status==400){
			
			Session::flash('msnError',$r->message );
			return back()->withInput();
			//return response()->json(['status' => 200,'message' => $r->message]);
		}
				
	   	
}
	
	//funcion para imprimir el dictamen ya sea observado, favorable o desfavorable
	public function imprimir($idSolicitud,$idEstado){
		//http://si.medicamentos.gob.sv/inspecciones/
		//sin public
		$idSol = Crypt::decrypt($idSolicitud);
		$client = new Client();
		if($idEstado==9){
		$res = $client->request('GET', 'http://publicidad.medicamentos.gob.sv/index.php/printerCertificaciones/publicidadAprobada/'.$idSol,[
		 'headers' => [
        	]
    	]);
        //return $res->getBody();
        return response($res->getBody(), $res->getStatusCode())->header('Content-Type', $res->getHeader('content-type'));
		}
		elseif($idEstado==10){
		$res = $client->request('GET', 'http://publicidad.medicamentos.gob.sv/index.php/printerCertificaciones/publicidadDenegada/'.$idSol,[
		 'headers' => [
        	]
    	]);
        //return $res->getBody();
        return response($res->getBody(), $res->getStatusCode())->header('Content-Type', $res->getHeader('content-type'));
		}
		elseif($idEstado==11){
		$res = $client->request('GET', 'http://publicidad.medicamentos.gob.sv/index.php/printerCertificaciones/publicidadPrevenida/'.$idSol,[
		 'headers' => [
        	]
    	]);
        //return $res->getBody();
        return response($res->getBody(), $res->getStatusCode())->header('Content-Type', $res->getHeader('content-type'));
		}
		else{
			return redirect()->route('ver.solicitudes');
		}
	}


	// funcion para guardar una solicitud extranjera
	public function storeExt(Request $request)

	{
	    //dd($request->all());
	    //verifica si en la nueva solicitud existe uno o mas archivo subido como arte
		if($request->file('files')==null){
			Session::flash('msnError','Es Obligatorio que suba el arte publicitario');
			return back()->withInput();
		}
		
		/*$idEstablecimiento=[];
		for($i=0;$i<count($request->idEstablecimiento);$i++){
			$idEstablecimiento[$i]=$request->idEstablecimiento[$i];
		}*/
		
		
		$nombres=Establecimientos::getNameEstablecimientos($request->idEstablecimiento);
		//dd($nombres);
		if($nombres!=null){
			$nombreEst=[];
			for($i=0;$i<count($nombres[0]);$i++){
				$nombreEst[$i]=$nombres[0][$i]->nombreComercial;
			}
            //dd($nombreEst);
		}
		else{
			Session::flash('msnError','Debe seleccionar uno o más propietarios para procesar su solicitud!');
			return back()->withInput();
		}

		// si es origen 4 es subsanacion 	
		if($request->origen==4){
			$medio=$request->idMedio;
			$solpadre=$request->solpadre;
		}
		elseif($request->get('tipoTramite1')==1){
			$medio=$request->get('idMedio1');
			$solpadre=null;
		}
		else{
			$medio=$request->get('idMedio');
			$solpadre=null;
		}

		
		
		$nombre_est[]=array();
		$nombre_est[0]=$request->get('nomComercial');
		$nit=Session::get('user');
		$client = new Client();
		$res = $client->request('POST', $this->url.'pyp/s/data/create',[
		'headers' => [
		'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

		],
		'form_params' =>[
		'origen'      => $request->origen,
		'nit' 		  => $nit,
		'id_est'      =>$request->get('idEstablecimiento'),
		'nombre_est'  =>$nombreEst,
		'mandamiento' =>$request->num_mandamiento,
		'medio' 	  =>$medio,
		'version'	  =>$request->version,
		'productos'   =>$request->pTableData,
		'padre'		  =>$solpadre,
		'ip'		  =>$request->ip()		
		
		]	
		
		]);

		$r = json_decode($res->getBody());
		//dd($r);
		//var_dump($r);
		if($r->status==200){
			
			//$path='C:\Publicidad';
			$path='X:\Publicidad';
			$path2='X:/Publicidad/';
	
			$files= $request->file('files');

			//primero verifica si hay archivos a subir
			if(!empty($files)){
				$artes[]=array();
				for($i=0;$i<count($files);$i++) {
					//si hay archivos crear la ruta con el id del usuario
					$newpath=$path.'/'.$r->data->idSolicitud;
					File::makeDirectory($newpath, 0777, true, true);
					//$name= $files[$i]->getClientOriginalName();
					$name='Solicitud'.$r->data->idSolicitud.$i.'.'.$files[$i]->getClientOriginalExtension();
					$type= $files[$i]->getClientMimeType();
					$files[$i]->move($newpath,$name);
					
					$artes[$i][0]=$path2.$r->data->idSolicitud.'/'.$name;
					$artes[$i][1]=$type;
				}
				$client = new Client();
				$response = $client->request('POST', $this->url.'pyp/s/art/create',[
				'headers' => [
				'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

				],
				'form_params' =>[
				'solicitud'   =>$r->data->idSolicitud,
				'artes'       =>$artes,
				'ip'		  =>$request->ip()
				
				]	
				
				]);
				$re = json_decode($response->getBody());

				if($re->status==400){
						Session::flash('msnError',$re->message );
						return back()->withInput();
				}

					
			}
			else{
				Session::flash('msnError','Es Obligatorio que suba el arte publicitario');
				return back()->withInput();

			}
		
		
		Session::flash('msnExito','Se ha Ingresado solicitud sastisfactoriamente');
		$idSolicitud=$r->data->idSolicitud;
		return redirect()->back()->with('idSolicitud',$idSolicitud);
		
		}
		elseif($r->status==400){
			
			Session::flash('msnError',$r->message );
			return back()->withInput();
		}
	   	
	}

	//funcion para mostrar los datos de la solicitud a subsanar
	public function subsanacion($idSolicitud){


		$idsoli= Crypt::decrypt($idSolicitud);
		//dd($idsoli);
		$tramitespub= VwSolicitudes::solicitud($idsoli);
		
		if($tramitespub->idTipoTramite==1){
		
			$tramitespub->idEstablecimiento=json_decode($tramitespub->idEstablecimiento)[0];
			$tramitespub->nombreEstablecimiento =json_decode($tramitespub->nombreComercial)[0];
			//dd($tramitespub);
		}
		elseif($tramitespub->idTipoTramite==2){
			$tramitespub->idEstablecimiento=json_decode($tramitespub->idEstablecimiento);
			$tramitespub->nombreEstablecimiento =json_decode($tramitespub->nombreComercial);	
		}
		
		$data = ['title' 			=> 'Solicitud de Promocion'
				,'subtitle'			=> 'Subsanación Solicitud'
				,'breadcrumb' 		=> [
			 		['nom'	=>	'Promocion y Publicidad', 'url' => '#'],
			 		['nom'	=>	'Subsanación Solicitud', 'url' => '#']
				]]; 
		//$idProfesional=
		//dd(Session::get('perfil'));
		$data['estado']=$tramitespub->idEstado;
		$perfiles=Session::get('perfil');
		$nit=Session::get('user');
		if($tramitespub->idTipoTramite==1)
		{
			$tipo='FARMACEUTICO';
			$data['establecimientos']=null;
		}
		elseif($tramitespub->idTipoTramite==2){

		}


		$productos=$tramitespub->productos;

		foreach ($tramitespub->productos as $prod){
            $pp = Productos::findOrFail($prod->numRegistro)->propietario()->first();
            $prod->titular=$pp->NOMBRE_PROPIETARIO;
            $prod->idPropietario=$pp->ID_PROPIETARIO;
        }

        //dd($productos);

		$modalidad=[];
		for($i=0;$i<count($tramitespub->productos);$i++){
			$modalidad[$i]=$tramitespub->productos[$i]->modalidadVenta;
		}
		
		for($j=0;$j<count($modalidad);$j++){
			if($modalidad[$j]==3){
				//solo es con recta
				$origen=2;
			}
			else if($modalidad[$j]==1){
				//solo receta y sin receta
				$origen=1;

			}
		}
		
		//Obtener medio según tipo de pago mandamiento:
		$verificacion  = DB::select("Select md.ID_TIPO_PAGO from cssp.cssp_mandamientos m 
							inner join cssp.cssp_mandamientos_detalle md on md.id_mandamiento = m.id_mandamiento
                      		inner join cssp.cssp_mandamientos_recibos mr on mr.id_mandamiento = m.id_mandamiento
                      		where md.ID_TIPO_PAGO in (3611,3612,3613,3614,3615,3616,3617)
                      		and m.id_mandamiento=".$tramitespub->numMandamiento."");
        if(isset($verificacion[0])){
        	$idPago = $verificacion[0]->ID_TIPO_PAGO;   
        	switch($idPago){
                case 3611:  $tramitespub->detalle[0]->idMedio = 2;
                			$tramitespub->detalle[0]->nombreMedio = 'TELEVISIÓN';
                            break;
                case 3612:  $tramitespub->detalle[0]->idMedio = 3;
                			$tramitespub->detalle[0]->nombreMedio = 'RADIO';
                            break;
                case 3613:  $tramitespub->detalle[0]->idMedio = 1;
                			$tramitespub->detalle[0]->nombreMedio = 'PRENSA ESCRITA';
                            break;
                case 3614:  $tramitespub->detalle[0]->idMedio = 6;
                			$tramitespub->detalle[0]->nombreMedio = 'CARTELES Y SIMILARES';
                            break;
                case 3615:  $tramitespub->detalle[0]->idMedio = 4;
                			$tramitespub->detalle[0]->nombreMedio = 'VALLA PUBLICITARIA';
                            break;
                case 3616:  $tramitespub->detalle[0]->idMedio = 5;
                			$tramitespub->detalle[0]->nombreMedio = 'INTERNET';
                            break;
                default:  break;
            }     	
        }

		$data['origen']=$origen;								
		$data['detPub']=$tramitespub->detalle[0];
		
		$data['productos'] = $productos;
		$data['tipoTramite']=$tramitespub->idTipoTramite;
		$data['tramitespub']=$tramitespub;
		$data['medios']= PubCatMedios::getMedios();
		//dd($data);
		return view('promoypub.paneles.subsanacion',$data);
	}

	/*
	public static function verificarPestañaExtranjeros(){
        $nit=Session::get('user');
        $client = new Client();
        $url = Config::get('app.api');
        $res = $client->request('POST', $url.'pel/e/get',[
            'headers' => [
                'tk' => '$2y$10$T3fudlwIIDTna/dmUJ7fAuMGoKYNxI9eoA8eMXy0nf6WEDFxXMi0a',

            ],
            'form_params' =>[
                'nit' => $nit,
                'tipo' =>'TITULAR'
            ]
        ]);

        $r = json_decode($res->getBody());

        if($r->status==200){
            if(count($r->data[0])>0)
                return true;
        }
        elseif($r->status==404){
            return false;
        }
    }*/
}