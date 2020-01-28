<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VacunasRequest extends FormRequest
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
            'idProducto'              => 'required',
            'presentaciones'          => 'required',
            'loteMin'                 => 'required|numeric',
            'loteMax'                 => 'required|numeric',
            'loteVidaUtil'            => 'required',
            'loteVolumen'             => 'required',
            'envasesLote'             => 'required',
            'dosisLote'               => 'required',
            'loteFecFabricacion'      => 'required|date',
            'loteFecExpiracion'       => 'required|date',
            'numLote'                 => 'required',
            'idEstablecimiento'       => 'required'
            
        ];
    }

    public function attributes()
    {
        return[
            'idProducto'          => 'Producto',
            'presentaciones'      => 'Presentación',
            'loteMin'             => 'Condiciones de Almacenamiento (Min)',
            'loteMax'             => 'Condiciones de Almacenamiento (Max)',
            'loteVidaUtil'        => 'Vida Útil Etiquetada',
            'loteVolumen'         => 'Tamaño/Volumen del Lote',
            'envasesLote'         => 'Total de Envases a Liberar Por Lote',
            'dosisLote'           => 'Total de Dosis a Liberar Por Lote',
            'loteFecFabricacion'  => 'Fecha de Fabricación',
            'loteFecExpiracion'   => 'Fecha de Expiración',
            'numLote'             => 'Número de Lote (Clave)',
            'idEstablecimiento'   => 'Establecimiento'
        ];
    }
}
