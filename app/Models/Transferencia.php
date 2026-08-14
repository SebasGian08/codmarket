<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    protected $table = 'transferencias';
    protected $primaryKey = 'id_transferencia';

    protected $fillable = [
        'numero',
        'correlativo',
        'id_tienda_origen',
        'id_tienda_destino',
        'id_usuario',
        'fecha',
        'observacion',
        'estado'
    ];

    protected $dates = ['fecha'];

    public function tiendaOrigen()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda_origen');
    }

    public function tiendaDestino()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda_destino');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function detalle()
    {
        return $this->hasMany(TransferenciaDetalle::class, 'id_transferencia');
    }
}
