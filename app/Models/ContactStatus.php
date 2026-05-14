<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactStatus extends Model
{
    protected $table = 'contact_statuses';
    protected $primaryKey = 'id_status';

    protected $fillable = [
        'nombre',
        'color',
        'orden'
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'id_status');
    }
}