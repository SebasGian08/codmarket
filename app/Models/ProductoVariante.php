<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoVariante extends Model
{
    protected $table = 'productos_variantes';

    protected $primaryKey = 'id_variante';

    protected $fillable = [
        'id_producto',
        'sku',
        'codigo_barras',
        'precio',
        'precio_oferta',
        'costo',
        'stock',
        'estado'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function atributos()
    {
        return $this->belongsToMany(
            AtributoValor::class,
            'variantes_atributos',
            'id_variante',
            'id_valor' 
        );
    }

    public function imagenes()
    {
        return $this->hasMany(ProductoImagen::class, 'id_variante', 'id_variante');
    }
}