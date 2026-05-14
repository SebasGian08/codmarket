<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'imagen',
        'icono',
        'id_categoria_padre',
        'orden',
        'estado'
    ];

    public function padre()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria_padre');
    }

    public function hijos()
    {
        return $this->hasMany(Categoria::class, 'id_categoria_padre');
    }

    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'producto_categorias',
            'id_categoria',
            'id_producto'
        );
    }
}