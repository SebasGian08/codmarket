<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinoPago extends Model
{
    protected $table = 'destinos_pago';
    protected $primaryKey = 'id_destino_pago';

    protected $fillable = [
        'codigo',
        'nombre',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function metodosPagos()
    {
        return $this->hasMany(MetodoPago::class, 'id_destino_pago');
    }
}
