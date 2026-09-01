<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoVentaRequest extends FormRequest
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
            'estado' => 'nullable|in:0,1',
        ];
    }
}
