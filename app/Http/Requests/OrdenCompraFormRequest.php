<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\OrdenCompraCab;

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
        //$orden_compra = OrdenCompraCab::find($this->orden_compra);

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'nro_orden'=>'required', 
                    'proveedor_id'=>'required', 
                    'moneda_id'=>'required',
                    'fecha_emision'=>'required',
                    'tab_articulo_id'=>'required',
                    'tab_cantidad'=>'required', 
                    'tab_costounitario'=>'required'
                ];
            }
            case 'PUT':
            {
                return [
                    'nro_orden'=>'required|unique:orden_compras_cab,nro_orden,'.$this->id, 
                    'proveedor_id'=>'required', 
                    'moneda_id'=>'required',
                    'fecha_emision'=>'required',
                    'tab_articulo_id'=>'required',
                    'tab_cantidad'=>'required', 
                    'tab_costounitario'=>'required'
                ];
            }
            case 'PATCH':
            {
                return [
                    'nro_orden'=>'required|unique:orden_compras_cab,nro_orden,'.$this->id, 
                    'proveedor_id'=>'required', 
                    'moneda_id'=>'required',
                    'fecha_emision'=>'required',
                    'tab_articulo_id'=>'required',
                    'tab_cantidad'=>'required', 
                    'tab_costounitario'=>'required'
                ];
            }
            default:break;
        }

    }

    public function messages()
    {
        return [
            'nro_orden.required' => 'El :attribute es obligatorio.',
            'nro_orden.unique' => 'el número de Orden ya existe.',
            'proveedor_id.required' => 'Ingrese el proveedor.',
            'costo_unitario.required' => 'El artículo no tiene costo',
            'moneda.required' => 'Debe seleccionar una moneda.',
            'articulo_id.required' => 'Ingrese al menos un artículo.',
            'fecha_emision.required' => 'Ingrese la fecha.'
        ];

    }   
}
