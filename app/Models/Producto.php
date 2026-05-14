<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'descripcion_corta',
        'id_marca',
        'id_proveedor',
        'peso',
        'dimensiones',
        'destacado',
        'nuevo',
        'estado'
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function categorias()
    {
        return $this->belongsToMany(
            Categoria::class,
            'producto_categorias',
            'id_producto',
            'id_categoria'
        );
    }

    public function variantes()
    {
        return $this->hasMany(ProductoVariante::class, 'id_producto');
    }

    public function imagenes()
    {
        return $this->hasMany(ProductoImagen::class, 'id_producto');
    }

    public function getImagenPrincipalUrlAttribute()
    {
        $img = $this->imagenes->where('principal', 1)->first() ?? $this->imagenes->first();

        return $img ? asset($img->url) : asset('assets/images/tienda_virtual/default.png');
    }
}