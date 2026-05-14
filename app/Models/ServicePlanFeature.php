<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePlanFeature extends Model
{
    protected $table = 'service_plan_features';

    protected $fillable = [
        'plan_id',
        'descripcion'
    ];

    public function plan()
    {
        return $this->belongsTo(ServicePlan::class, 'plan_id');
    }
}