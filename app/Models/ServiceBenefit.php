<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceBenefit extends Model
{
    protected $table = 'service_benefits';

    protected $fillable = [
        'service_id',
        'titulo',
        'descripcion',
        'icono',
        'imagen',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id_service');
    }

    public function details()
    {
        return $this->hasMany(ServiceBenefitDetail::class, 'service_benefit_id')
            ->orderBy('id');
    }
}