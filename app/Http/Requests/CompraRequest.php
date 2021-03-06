<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ComprasCab;
class CompraRequest extends FormRequest
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
                    'nro_factura'=>'required|unique_with:compras_cab,proveedor_id,timbrado', 
                    'timbrado'=>'required', 
                    'proveedor_id'=>'required', 
                    'moneda_id'=>'required',
                    'fecha_emision'=>'required',
                    'fecha_vigencia_timbrado'=>'required',
                    'valor_cambio'=>'required',
                    'tab_articulo_id'=>'required',
                    'tab_cantidad'=>'required', 
                    'tab_costo_unitario'=>'required'

                ];
            }
            case 'PUT':
            {
                return [
                    'nro_factura'=>'required|unique_with:compras_cab,proveedor_id,timbrado,'.$this->id, 
                    'timbrado'=>'required',
                    'proveedor_id'=>'required', 
                    'moneda_id'=>'required',
                    'fecha_emision'=>'required',
                    'fecha_vigencia_timbrado'=>'required',
                    'valor_cambio'=>'required',
                    'tab_articulo_id'=>'required',
                    'tab_cantidad'=>'required', 
                    'tab_costo_unitario'=>'required'
                ];
            }
            case 'PATCH':
            {
                return [
                    'nro_factura'=>'required|unique_with:compras_cab,proveedor_id,timbrado,'.$this->id, 
                    'timbrado'=>'required',
                    'proveedor_id'=>'required', 
                    'moneda_id'=>'required',
                    'fecha_emision'=>'required',
                    'fecha_vigencia_timbrado'=>'required',
                    'valor_cambio'=>'required',
                    'tab_articulo_id'=>'required',
                    'tab_cantidad'=>'required', 
                    'tab_costo_unitario'=>'required'
                ];
            }
            default:break;
        }

    }

    public function messages()
    {
        return [
            'nro_factura.required' => 'Ingrese el número del comprobante.',
            'nro_factura.unique' => 'Comprobante ya existe.',
            'timbrado.required' => 'Ingrese el timbrado',
            'proveedor_id.required' => 'Ingrese el proveedor.',
            'tab_costo_unitario.required' => 'El artículo no tiene costo',
            'moneda.required' => 'Debe seleccionar una moneda.',
            'tab_articulo_id.required' => 'Ingrese al menos un artículo.',
            'tab_cantidad.required' => 'Ingrese la cantidad.',
            'fecha_emision.required' => 'Ingrese la fecha del comprobante.',
            'fecha_vigencia_timbrado.required' => 'Ingrese la vigencia del timbrado.'
        ];

    }   
}
