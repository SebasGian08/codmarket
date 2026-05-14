<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $primaryKey = 'id_blog';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'category_id',
        'user_id',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(BlogsCategory::class, 'category_id', 'id_blogs_categories');
    }

    public function tags()
    {
        return $this->belongsToMany(
            BlogTag::class,
            'blogs_blog_tag',
            'blog_id',
            'tag_id'
        );
    }
}