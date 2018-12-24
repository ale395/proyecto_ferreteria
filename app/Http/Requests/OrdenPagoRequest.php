<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdenPagoRequest extends FormRequest
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
                    'tab_banco_id'=>'required',
                    'tab_importe_che'=>'required', 
                    'tab_compra_id'=>'required',
                    'tab_importe_afectado'=>'required'
                ];
            }
            case 'PUT':
            {
                return [
                    'nro_orden'=>'required|unique:orden_pago,nro_orden,'.$this->id, 
                    'proveedor_id'=>'required', 
                    'moneda_id'=>'required',
                    'fecha_emision'=>'required',
                    'tab_banco_id'=>'required',
                    'tab_importe_che'=>'required', 
                    'tab_compra_id'=>'required',
                    'tab_importe_afectado'=>'required'
                ];
            }
            case 'PATCH':
            {
                return [
                    'nro_orden'=>'required|unique:orden_pago,nro_orden,'.$this->id, 
                    'proveedor_id'=>'required', 
                    'moneda_id'=>'required',
                    'fecha_emision'=>'required',
                    'tab_banco_id'=>'required',
                    'tab_importe_che'=>'required', 
                    'tab_compra_id'=>'required',
                    'tab_importe_afectado'=>'required'
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
            'moneda.required' => 'Debe seleccionar una moneda.',
            'fecha_emision.required' => 'Ingrese la fecha.',
            'tab_importe_che.required' => 'Ingrese el importe del cheque',
            'tab_banco_id.required' => 'Ingrese al menos un cheque.',
            'tab_compra_id.required' => 'Ingrese la factura a pagar',
            'tab_importe_afectado.required' => 'Ingrese el importe a afectar.'
        ];

    } 
}
