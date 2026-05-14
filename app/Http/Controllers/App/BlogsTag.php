<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogsTag extends Model
{
    protected $table = 'blogs_tags';

    protected $fillable = ['name','slug'];

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