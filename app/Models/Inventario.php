<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventarios';
    protected $primaryKey = 'id_inventario';

    protected $fillable = [
        'id_variante',
        'id_tienda',
        'cantidad'
    ];

    public function variante()
    {
        return $this->belongsTo(ProductoVariante::class, 'id_variante');
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'id_tienda');
    }
}
