<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasUuids, HasTranslations;

    protected $fillable = [
        'parent_id',
        'title',
        'description',
        'slug',
        'status',
        'sort',
    ];

    public $translatable = ['title', 'description'];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'status' => 'boolean',
        'sort' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    // public function determineOrderColumnName(): string
    // {
    //     return "sort";
    // }
}
