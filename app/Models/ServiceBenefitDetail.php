<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceBenefitDetail extends Model
{
    protected $table = 'service_benefit_details';

    protected $fillable = [
        'service_benefit_id',
        'descripcion',
    ];

    public function benefit()
    {
        return $this->belongsTo(ServiceBenefit::class, 'service_benefit_id');
    }
}
