<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'movimientos';
    protected $primaryKey = 'id_movimiento';

    protected $fillable = [
        'id_variante',
        'id_tienda',
        'tipo',
        'cantidad',
        'id_referencia',
        'id_usuario',
        'fecha',
        'observacion'
    ];

    protected $dates = ['fecha'];

    public function variante()
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante');
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
