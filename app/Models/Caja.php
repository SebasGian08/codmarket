<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'cajas';
    protected $primaryKey = 'id_caja';

    protected $fillable = [
        'id_tienda',
        'id_usuario',
        'id_vendedor',
        'nombre',
        'monto_apertura',
        'monto_cierre',
        'monto_diferencia',
        'fecha_apertura',
        'fecha_cierre',
        'estado'
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'id_vendedor');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_caja');
    }

    public function getTotalVentasAttribute()
    {
        return $this->ventas()->where('estado', 1)->sum('total');
    }

    public function movimientosDinero()
    {
        return $this->hasMany(MovimientoDinero::class, 'id_caja');
    }

    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'id_caja');
    }
}
