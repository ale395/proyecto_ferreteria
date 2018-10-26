<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdenCompraFormRequest extends FormRequest
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
            'nro_orden'=>'required', 
            'proveedor_id'=>'required', 
            'moneda_id'=>'required',
            'fecha_emision'=>'required',
            'articulo_id'=>'required',
            'cantidad'=>'required', 
            'costo_unitario'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'nro_orden.required' => 'El :attribute es obligatorio.',
            'proveedor_id.required' => 'Ingrese el proveedor.',
            'costo_unitario.required' => 'El artículo no tiene costo',
            'moneda.required' => 'Debe seleccionar una moneda.',
            'articulo_id.required' => 'Ingrese al menos un artículo.',
            'fecha_emision.required' => 'Ingrese la fecha.'
        ];

    }   
}
