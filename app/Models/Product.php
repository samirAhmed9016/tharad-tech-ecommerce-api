<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Concerns\HasUuid;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasFactory, HasUuids, InteractsWithMedia, HasTranslations;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'price',
        'quantity',
        'status',
    ];

    public $translatable = ['title', 'description'];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'status' => 'boolean',
    ];
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }



    /*
    |--------------------------------------------------------------------------
    | Media Collections
    |--------------------------------------------------------------------------
    */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products')
            ->singleFile();
    }
}
