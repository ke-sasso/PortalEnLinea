<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudRequest extends FormRequest
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
            'numregistro'           => 'required',
            'nomComercial'          => 'required',
            'pTableData'               => 'required',
            'version'                 => 'required',
            'num_mandamiento'            => 'required',
            'files'                 => 'required'
            
        ];
    }

    public function attributes()
    {
        return[
            'numregistro'       => 'N° de Registro del Establecimiento',
            'nomComercial'      => 'Nombre del Establecimiento',
            'pTableData'        => 'Productos',
            'version'            => 'Version de publicidad',
            'files'             => 'Seleccione un archivo a subir'
        ];
    }
}
