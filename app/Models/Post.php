<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{

    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'thumbnail',
        'tags',
        'is_published',
    ];
    protected $casts = [
        'tags' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
