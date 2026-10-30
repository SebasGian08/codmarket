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
        'ficha_pdf',
        'id_marca',
        'id_proveedor',
        'orden',
        'peso',
        'dimensiones',
        'destacado',
        'nuevo',
        'estado',
        'id_usuario_registro',
        'id_usuario_modifica'
    ];

    protected static function booted()
    {
        static::creating(function ($producto) {
            if ($producto->orden === null) {
                $producto->orden = ((int) static::max('orden')) + 1;
            }
        });
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_registro');
    }

    public function usuarioModifica()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_modifica');
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

    public function getPrimeraVarianteConImagenAttribute()
    {
        $variantes = $this->relationLoaded('variantes')
            ? $this->variantes
            : $this->variantes()->with('imagenes')->get();

        return $variantes->first(function ($variante) {
            return $variante->imagenes->isNotEmpty();
        }) ?? $variantes->first();
    }
}
