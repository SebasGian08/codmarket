<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoTipo extends Model
{
    protected $table = 'documentos_tipo';

    protected $primaryKey = 'id_documento_tipo';

    protected $fillable = [
        'nombre',
        'codigo',
        'longitud',
        'estado',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'id_documento_tipo', 'id_documento_tipo');
    }
}
