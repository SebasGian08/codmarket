<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePlan extends Model
{
    protected $table = 'service_plans';

    protected $fillable = [
        'service_id',
        'nombre',
        'precio',
        'descripcion',
        'destacado'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id_service');
    }

    public function features()
    {
        return $this->hasMany(ServicePlanFeature::class, 'plan_id', 'id');
    }
}