<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReglaDescuentoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'id_tipo_descuento' => 'required|exists:tipos_descuento,id_tipo_descuento',
            'valor' => 'required|numeric|min:0|max:9999999.99',
            'cantidad_min' => 'nullable|integer|min:0',
            'cantidad_max' => 'nullable|integer|gte:cantidad_min',
            'id_tipo_venta' => 'nullable|exists:tipos_venta,id_tipo_venta',
            'estado' => 'nullable|in:0,1',
        ];
    }
}
