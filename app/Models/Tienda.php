<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tienda extends Model
{
    protected $table = 'tiendas';
    protected $primaryKey = 'id_tienda';

    protected $fillable = [
        'codigo',
        'nombre',
        'direccion',
        'telefono',
        'es_principal',
        'estado'
    ];

    public function cajas()
    {
        return $this->hasMany(Caja::class, 'id_tienda');
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_tienda');
    }

    public function getCajaAbiertaAttribute()
    {
        return $this->cajas()->where('estado', 1)->first();
    }

    public function vendedores()
    {
        return $this->belongsToMany(Vendedor::class, 'vendedores_tiendas', 'id_tienda', 'id_vendedor')
            ->withPivot(['id_vendedor_tienda', 'estado', 'fecha_inicio', 'fecha_fin']);
    }
}
