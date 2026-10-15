<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterLogo extends Model
{
    protected $table = 'footer_logos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'titulo',
        'imagen',
        'url',
        'orden',
        'estado',
    ];
}
