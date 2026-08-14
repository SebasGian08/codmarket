<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferenciaDetalle extends Model
{
    protected $table = 'transferencias_detalle';
    protected $primaryKey = 'id_transferencia_detalle';

    protected $fillable = [
        'id_transferencia',
        'id_variante',
        'cantidad'
    ];

    public function transferencia()
    {
        return $this->belongsTo(Transferencia::class, 'id_transferencia');
    }

    public function variante()
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante');
    }
}
