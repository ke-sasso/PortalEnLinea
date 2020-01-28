<?php

namespace App\Http\Requests\Registro\NuevoRegistro;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class Step8Request extends FormRequest
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
            'idSolicitud7'               => 'required',
            'listLabAcondicionador'      => 'required',   
            
        ];
    }

    public function attributes()
    {
        return[
            'idSolicitud7'   =>                 'Id solicitud',
            'listLabAcondicionador'           =>  'Laboratorio acondicionador',
         
        ];
    }

    public function messages()
    {
        return[
             'idSolicitud7.required'    => 'Id solicitud',
             'listLabAcondicionador.required'           =>  'Laboratorio acondicionador es requerido',
        
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
