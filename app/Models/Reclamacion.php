<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamacion extends Model
{
    protected $table = 'reclamaciones';
    protected $primaryKey = 'id_reclamacion';

    protected $fillable = [
        'id_documento_tipo',
        'numero_documento',
        'correo_electronico',
        'numero_celular',
        'tipo_bien',
        'monto_reclamado',
        'detalle_bien',
        'id_reclamacion_tipo',
        'detalle_reclamacion',
        'pedido_consumidor',
        'acepta_datos',
        'estado',
    ];

    public function documentoTipo()
    {
        return $this->belongsTo(DocumentoTipo::class, 'id_documento_tipo', 'id_documento_tipo');
    }

    public function tipoReclamacion()
    {
        return $this->belongsTo(ReclamacionTipo::class, 'id_reclamacion_tipo', 'id_reclamacion_tipo');
    }
}
