<?php

namespace App\Http\Requests\Registro\NuevoRegistro;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class Step5Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'idSolicitud4'               => 'required',
            'certificadolv'             =>     'sometimes',
            'nomProdPais'                   => 'sometimes',
            'titularProductoC' =>         'required',
            'fechaEmision'             =>     'sometimes|date',
            'fechaVencimiento'          => 'sometimes|date',
           
            
        ];
    }

    public function attributes()
    {
        return[
            'idSolicitud4'                => 'Id Solicitud',
            'certificadolv'               => 'Nombre de la autoridad emisora del certificado',
            'nomProdPais'                   => 'Nombre del producto registrado en país de procedencia',
            'titularProductoC' =>         'Titular del producto del Certificado',
            'fechaEmision'             =>     'Fecha Emision',
            'fechaVencimiento'          =>  'Fecha Vencimiento',

        ];
    }

    public function messages()
    {
        return[
            'idSolicitud4.required'                => 'Id Solicitud es requerido',
            'certificadolv.sometimes'               => 'Nombre de la autoridad emisora del certificado es requerido',
            'nomProdPais.sometimes'                   => 'Nombre del producto registrado en país de procedencia es requerido',
            'idtitularProductoC.required' =>         'Titular del producto del Certificado es requerido',
            'fechaEmision.sometimes'             =>     'Fecha Emision es requerido',
            'fechaVencimiento.sometimes'          =>  'Fecha Vencimiento es requerido',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->getMessages();
        $obj = $validator->failed();
        $result = [];
        $msg = "<ul class='text-warning'>";
        foreach($obj as $input => $rules){
            $i = 0;
            foreach($rules as $rule => $ruleInfo){
                //$rule = $input.'['.strtolower($rule).']';
                $result[$i] = "<li>".$errors[$input][$i]."</li>";
                $msg .= "<li>".$errors[$input][$i]."</li>";
                $i++;
            }
        }
        $msg .= "</ul>";
        //return $result;

        $errors = $validator->errors()->getMessages();

        /*throw new HttpResponseException(response()->json(['errors' => $msg
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));*/


        $response = new JsonResponse(
            [
                'status' => 422,
                'errors' => $msg
            ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
