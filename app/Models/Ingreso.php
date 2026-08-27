<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $table = 'ingresos';
    protected $primaryKey = 'id_ingreso';

    protected $fillable = [
        'numero',
        'correlativo',
        'tipo',
        'id_proveedor',
        'id_tienda',
        'id_usuario',
        'fecha',
        'observacion',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function detalle()
    {
        return $this->hasMany(IngresoDetalle::class, 'id_ingreso');
    }
}
