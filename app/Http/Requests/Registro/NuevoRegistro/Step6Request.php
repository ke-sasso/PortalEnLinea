<?php

namespace App\Http\Requests\Registro\NuevoRegistro;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class Step6Request extends FormRequest
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
            'idSolicitud5'               =>  'required',
            'certificadobpm'             =>   'sometimes',
            'idcertificadobpm'           =>  'sometimes',
            'fechaEmision'               =>  'sometimes',
            'fechaVencimiento'           =>  'sometimes',
            'practLabAlternos'           => 'sometimes|array|min:1',
            'practLabAcondi'             =>  'sometimes|array|min:1',
        
            
        ];
    }

    public function attributes()
    {
        return[
            'idSolicitud5'               =>  'Id solicitud',
            'certificadobpm'             =>  'Nombre de la autoridad emisora para fabricante principal',
            'idcertificadobpm'           =>  'FABRICANTE PRINCIPAL',
            'fechaEmision'               =>  'Fecha emisión fabricante principal',
            'fechaVencimiento'           =>  'Fecha de vencimiento fabricante principal',
            'practLabAlternos'           =>  'FABRICANTE ALTERNO',
            'practLabAcondi'             =>  'LABORATORIO ACONDICIONADOR',
        
        ];
    }

    public function messages()
    {
        return[
             'idSolicitud5.required'               => 'Id solicitud',
            'idcertificadobpm.sometimes'           =>  'Fabricante principal',
            'practLabAlternos.sometimes'           =>  'Uno o más fabricantes alternos',
            'practLabAcondi.sometimes'             =>  'Uno o más laboratorio acondicionador',
        
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
