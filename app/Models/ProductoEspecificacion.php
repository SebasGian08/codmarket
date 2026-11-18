<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoEspecificacion extends Model
{
    protected $table = 'productos_especificaciones';
    protected $primaryKey = 'id_especificacion';

    protected $fillable = [
        'id_producto',
        'nombre',
        'valor',
        'orden',
    ];

    public $timestamps = false;

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
