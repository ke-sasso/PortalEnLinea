<?php
/**
 * Created by PhpStorm.
 * User: steven.mena
 * Date: 20/2/2018
 * Time: 3:09 PM
 */

namespace App\Http\Controllers\Tools;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use Validator;
use Log;
use Carbon\Carbon;
use Config;


class ToolsController extends Controller
{
    //
    private $url = null;
    private $token = null;

    public function __construct()
    {
        $this->url = Config::get('app.api');
        $this->token = Config::get('app.token');
    }


    public function searchTitular(Request $rq)
    {

        $rules = [
            'q' => 'required',
            'tipoTitular' => 'required',
            'idUnidad' => 'required'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            /*$query='null';*/

            if ($rq->has('q')) {
                $query = $rq->q;
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/search/titular', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'nitOrNom' => $query,
                    'tipoTitular' => $rq->tipoTitular,
                    'idUnidad' => $rq->idUnidad
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'data' => $r->data], 200);

            } else if ($r->status == 400) {
                return response()->json(['status' => 400, 'message' => $r->message], 200);
            } else if ($r->status == 404) {
                return response()->json(['status' => 404, 'message' => $r->message], 200);
            }
        } catch (\Exception $e) {
            //throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }

    public function getTitular(Request $rq)
    {

        $rules = [
            'nitOrPp' => 'required',
            'tipoTitular' => 'required',
            'unidad' => 'required'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/get/titular', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'nitOrPp' => $rq->nitOrPp,
                    'tipoTitular' => $rq->tipoTitular,
                    'idUnidad' => $rq->unidad
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $data['propietario'] = $r->data;
                if ($r->data->tipoPersona == 'N') $data['dui'] = $r->data->numeroDocumento;


                if ($rq->unidad == 'URV') {
                    return view('registro.nuevoregistro.pasos.paso3.bodyTitular', $data);
                } elseif ($rq->unidad == 'COS') {
                    return view('cosmeticos.nuevoregistro.pasos.paso3.bodytitular', $data);
                }

            } else if ($r->status == 400) {
                return new JsonResponse([
                    'status' => 400,
                    'message' => $r->message
                ], 200);

            } else if ($r->status == 404) {
                return new JsonResponse([
                    'status' => 404,
                    'message' => $r->message
                ], 200);
            }
        } catch (\Exception $e) {
            //throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage() . $e->getFile() . $e->getLine()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }

    public function getTitularRegistro(Request $rq)
    {

        $rules = [
            'nitOrPp' => 'required',
            'tipoTitular' => 'required',
            'unidad' => 'required'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/get/titular', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'nitOrPp' => $rq->nitOrPp,
                    'tipoTitular' => $rq->tipoTitular,
                    'idUnidad'  => 'URV'
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                $data['propietario'] = $r->data;
                //dd($data);
                if($r->data->tipoPersona=='N') if(!empty($r->data->dui)) $data['dui'] = $r->data->dui;

                if($rq->form==1){
                    return view('registro.nuevoregistro.pasos.paso3.bodyTitular',$data);
                }else{
                    return view('registro.nuevoregistro.pasosEdit.paso3.bodyTitular',$data);
                }


            } else if ($r->status == 400) {
                return new JsonResponse([
                    'status' => 400,
                    'message' => $r->message
                ], 200);

            } else if ($r->status == 404) {
                return new JsonResponse([
                    'status' => 404,
                    'message' => $r->message
                ], 200);
            }
        } catch (\Exception $e) {
            //throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage() . $e->getFile() . $e->getLine()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }

    public function getProfesionalByPoder(Request $rq)
    {

        $rules = [
            'poder' => 'required',
            'unidad' => 'required'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/getProfesional/byPoder', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'numPoder' => $rq->poder,
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {

                $data['profesional'] = $r->data;
                if ($rq->unidad == 'URV') {
                    return view('registro.nuevoregistro.pasos.paso3.bodyprofesional', $data);
                } elseif ($rq->unidad == 'COS') {
                    $viewHtml = (String)view('cosmeticos.nuevoregistro.pasos.paso3.bodyprofesional', $data);
                    return new JsonResponse([
                        'status' => 200,
                        'data' => $viewHtml
                    ], 200);
                }

            } else if ($r->status == 400) {
                return new JsonResponse([
                    'status' => 400,
                    'message' => $r->message
                ], 400);

            } else if ($r->status == 404) {
                return new JsonResponse([
                    'status' => 404,
                    'message' => $r->message
                ], 404);
            }
        } catch (\Exception $e) {
            //throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }

    public function getRepresentanteLegalByPoder(Request $rq)
    {

        $rules = [
            'poder' => 'required'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/getRepresentante/byPoder/legales', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'numPoder' => $rq->poder,
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {

                $data['representanteL'] = $r->data;
                return view('registro.nuevoregistro.pasos.paso3.bodyRepresentante', $data);
            } else if ($r->status == 400) {
                return new JsonResponse([
                    'status' => 400,
                    'message' => $r->message
                ], 200);

            } else if ($r->status == 404) {
                return new JsonResponse([
                    'status' => 404,
                    'message' => $r->message
                ], 200);
            }
        } catch (\Exception $e) {
            //throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }

    public function getTodosApoderadosByPoder(Request $rq)
    {

        $rules = [
            'poder' => 'required'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/getTodosApoderados/byPoder', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'numPoder' => $rq->poder,
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {

                $data['apoderados'] = $r->data;
                return view('registro.nuevoregistro.pasos.paso3.bodyApoderado', $data);
            } else if ($r->status == 400) {
                return new JsonResponse([
                    'status' => 400,
                    'message' => $r->message
                ], 400);

            } else if ($r->status == 404) {
                return new JsonResponse([
                    'status' => 404,
                    'message' => $r->message
                ], 404);
            }
        } catch (\Exception $e) {
            //throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }

    public function searchFabOrImp(Request $rq)
    {

        $rules = [
            'q' => 'sometimes',
            'origenFab' => 'required',
            'many' => 'required',
            'unidad' => 'required'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $query = 'null';

            if ($rq->has('q')) {
                $query = $rq->q;
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/search/fabOrImp', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'fabOrImp' => $query,
                    'tipoEst'  => $rq->origenFab,
                    'many'     => $rq->many,
                    'unidad'   => $rq->unidad
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'data' => $r->data], 200);

            } else if ($r->status == 400) {
                return response()->json(['status' => 400, 'message' => $r->message], 200);
            } else if ($r->status == 404) {
                return response()->json(['status' => 404, 'message' => $r->message], 200);
            }
        } catch (\Exception $e) {
            throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }

    public function searchDistribuidor(Request $rq)
    {

        $rules = [
            'q' => 'sometimes'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $query = 'null';

            if ($rq->has('q')) {
                $query = $rq->q;
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/search/distribuidor', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'nomDist' => $query
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'data' => $r->data], 200);

            } else if ($r->status == 400) {
                return response()->json(['status' => 400, 'message' => $r->message], 200);
            } else if ($r->status == 404) {
                return response()->json(['status' => 404, 'message' => $r->message], 200);
            }
        } catch (\Exception $e) {
            throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }

    public function searchDistribuidorCos(Request $rq)
    {

        $rules = [
            'q' => 'sometimes'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $query = 'null';

            if ($rq->has('q')) {
                $query = $rq->q;
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/search/distribuidor/cos', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'nomDist' => $query
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'data' => $r->data], 200);

            } else if ($r->status == 400) {
                return response()->json(['status' => 400, 'message' => $r->message], 200);
            } else if ($r->status == 404) {
                return response()->json(['status' => 404, 'message' => $r->message], 200);
            }
        } catch (\Exception $e) {
            throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }

    public function searchDistribuidorAll(Request $rq)
    {

        $rules = [
            'q' => 'sometimes'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $query = 'null';

            if ($rq->has('q')) {
                $query = $rq->q;
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/search/distribuidor', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'nomDist' => $query
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'data' => $r->data], 200);

            } else if ($r->status == 400) {
                return response()->json(['status' => 400, 'message' => $r->message], 200);
            } else if ($r->status == 404) {
                return response()->json(['status' => 404, 'message' => $r->message], 200);
            }
        } catch (\Exception $e) {
            throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }

    public function getApoderadoByPoder(Request $rq)
    {

        $rules = [
            'poder' => 'required',
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/getApoderado/byPoder', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'numPoder' => $rq->poder,
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {

                $data['representante'] = $r->data;
                return view('registro.nuevoregistro.pasos.paso3.bodyprofesional', $data);

            } else if ($r->status == 400) {
                return new JsonResponse([
                    'status' => 400,
                    'message' => $r->message
                ], 200);

            } else if ($r->status == 404) {
                return new JsonResponse([
                    'status' => 404,
                    'message' => $r->message
                ], 200);
            }
        } catch (\Exception $e) {
            //throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }


     public function searchEstaRelacionados(Request $rq)
    {

        $rules = [
            'q' => 'sometimes'
        ];

        $v = Validator::make($rq->all(), $rules);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 400);
        }

        try {

            $query = 'null';

            if ($rq->has('q')) {
                $query = $rq->q;
            }

            $client = new Client();
            $res = $client->request('POST', $this->url . 'pel/tools/getEstablecimientos/relacionados', [
                'headers' => [
                    'tk' => $this->token,

                ],
                'form_params' => [
                    'nomEsta' => $query
                ]
            ]);

            $r = json_decode($res->getBody());

            if ($r->status == 200) {
                return response()->json(['status' => 200, 'data' => $r->data], 200);

            } else if ($r->status == 400) {
                return response()->json(['status' => 400, 'message' => $r->message], 200);
            } else if ($r->status == 404) {
                return response()->json(['status' => 404, 'message' => $r->message], 200);
            }
        } catch (\Exception $e) {
            throw $e;
            Log::error('Error Exception', ['time' => Carbon::now(), 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Error, favor contacte a DNM informática'
            ], 500);
        }
    }



}
