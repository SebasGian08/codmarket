<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDescuento extends Model
{
    protected $table = 'tipos_descuento';
    protected $primaryKey = 'id_tipo_descuento';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'estado',
    ];

    public function reglas()
    {
        return $this->hasMany(ReglaDescuento::class, 'id_tipo_descuento');
    }
}
