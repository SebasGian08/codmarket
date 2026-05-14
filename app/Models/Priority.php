<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $table = 'priorities';
    protected $primaryKey = 'id_priority';

    protected $fillable = [
        'nombre',
        'color',
        'nivel'
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'id_priority');
    }
}