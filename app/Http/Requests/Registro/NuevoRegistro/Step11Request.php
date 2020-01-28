<?php

namespace App\Http\Requests\Registro\NuevoRegistro;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class Step11Request extends FormRequest
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
            'idSolicitud10'   => 'required',  
        ];
    }

    public function attributes()
    {
        return[
            'idSolicitud10'   => 'Id solicitud', 
        ];
    }

    public function messages()
    {
        return[
             'idSolicitud10.required'    => 'Id solicitud es requerido',
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
