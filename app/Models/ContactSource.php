<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSource extends Model
{
    protected $table = 'contact_sources';
    protected $primaryKey = 'id_source';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'id_source');
    }
}