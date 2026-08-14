<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'nombre',
        'id_tipo_documento',
        'numero_documento',
        'telefono',
        'correo',
        'direccion',
        'es_varios',
        'estado'
    ];

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_cliente');
    }
}
