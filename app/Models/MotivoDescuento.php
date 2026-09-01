<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoDescuento extends Model
{
    protected $table = 'motivos_descuento';
    protected $primaryKey = 'id_motivo_descuento';

    protected $fillable = [
        'nombre',
        'descripcion',
        'aplica_a',
        'estado',
    ];

    public function scopeItem($query)
    {
        return $query->where('aplica_a', 'ITEM');
    }

    public function scopeCabecera($query)
    {
        return $query->where('aplica_a', 'CABECERA');
    }
}
