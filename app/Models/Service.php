<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Service extends Model
{
    use SoftDeletes;    
    protected $table = 'services';
    protected $primaryKey = 'id_service';
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'content',
        'portada',
        'descripcion_portada',
        'descripcion_breve_portada',
        'imagen_portada',
        'imagen_referencial',
        'estado'
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'id_service');
    }

    public function benefits()
    {
        return $this->hasMany(ServiceBenefit::class, 'service_id', 'id_service');
    }

    public function plans()
    {
        return $this->hasMany(ServicePlan::class, 'service_id', 'id_service');
    }

    public function portafolios()
    {
        return $this->hasMany(Portafolio::class, 'service_id', 'id_service');
    }
}