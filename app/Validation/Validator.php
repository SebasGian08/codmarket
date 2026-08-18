<?php

namespace App\Validation;

use Illuminate\Validation\Validator as BaseValidator;

class Validator extends BaseValidator
{
    /**
     * Validate an attribute is a valid image.
     *
     * En Laravel 5.8 la regla "image" falla para archivos .jpg porque
     * guessExtension() devuelve "jpg" pero la lista interna solo contiene "jpeg".
     * Se sobreescribe para aceptar ambas extensiones.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateImage($attribute, $value)
    {
        return $this->validateMimes($attribute, $value, ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'svg', 'webp']);
    }
}
