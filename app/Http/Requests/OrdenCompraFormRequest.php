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
            'sucursal_id'=>'required', 
            'moneda_id'=>'required',
            'fecha_emision'=>'required',
            'articulo_id'=>'required',
            'cantidad'=>'required', 
            'costo_unitario'=>'required'
        ];
    }
}
