<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtributoValor extends Model
{
    protected $table = 'atributos_valores';
    protected $primaryKey = 'id_valor';

    protected $fillable = [
        'id_atributo',
        'valor'
    ];

    public function atributo()
    {
        return $this->belongsTo(Atributo::class, 'id_atributo');
    }
}