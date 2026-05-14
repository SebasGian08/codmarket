<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogsCategory extends Model
{
    protected $table = 'blogs_categories';

    protected $fillable = ['name','slug'];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
}