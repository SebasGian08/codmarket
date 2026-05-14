<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';
    protected $primaryKey = 'id_configuracion';

    protected $fillable = ['clave', 'valor'];

    public $timestamps = false;
}