<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoImagen extends Model
{
    protected $table = 'productos_imagenes';

    protected $primaryKey = 'id_imagen';

    public $timestamps = true;

    protected $fillable = [
        'id_producto',
        'id_variante',
        'url',
        'principal',
        'orden'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function variante()
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante', 'id_variante');
    }

}