<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    protected $table = 'blogs_tags';
    protected $primaryKey = 'id_blogs_tags';

    protected $fillable = [
        'name',
        'slug'
    ];

    public $timestamps = true;

    public function blogs()
    {
        return $this->belongsToMany(
            Blog::class,
            'blogs_blog_tag',
            'tag_id',
            'blog_id'
        );
    }
}