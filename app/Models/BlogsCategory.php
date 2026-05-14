<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogsCategory extends Model
{
    protected $table = 'blogs_categories';
    protected $primaryKey = 'id_blogs_categories';

    protected $fillable = [
        'name',
        'slug'
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id', 'id_blogs_categories');
    }
}