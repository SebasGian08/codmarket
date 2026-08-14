<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';
    protected $primaryKey = 'id_vendedor';

    protected $fillable = [
        'id_usuario',
        'nombre',
        'documento',
        'telefono',
        'correo',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function cajas()
    {
        return $this->hasMany(Caja::class, 'id_vendedor');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_vendedor');
    }
}
