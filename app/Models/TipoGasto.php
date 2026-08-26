<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoGasto extends Model
{
    protected $table = 'tipos_gastos';
    protected $primaryKey = 'id_tipo_gasto';

    protected $fillable = [
        'nombre',
        'estado'
    ];

    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'id_tipo_gasto');
    }
}
