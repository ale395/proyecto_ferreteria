<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearFamiliasRequest extends FormRequest
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
        
        //validamos que todos los campos sean del tipo string
        //y que num_familia no se repita
        return [
           'num_familia' => 'required|string|max:10|unique:num_familia',
           'descripcion' => 'required|string|max:30'
        ];
    }

    public function messages()
    {
        return [
        'num_familia.required' => 'El :attribute es obligatorio.',
        'num_familia.unique' => ':attribute ya existe'
        'descripcion.required' => 'Añade un :attribute al producto',
        ];
    }

    public function attributes()
{
    return [
        'num_familia' => 'Codigo de la familia',
        'descripcion' => 'Descripcion',
    ];
}
}
