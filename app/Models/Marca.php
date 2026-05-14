<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas';
    protected $primaryKey = 'id_marca';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'logo',
        'banner',
        'sitio_web',
        'orden',
        'estado'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_marca');
    }
}