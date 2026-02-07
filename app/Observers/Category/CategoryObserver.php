<?php

namespace App\Observers\Category;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Log;


use function App\Helpers\generateUniqueSlug;

class CategoryObserver
{

    public function saving(Category $category): void
    {

        $category->setTranslations('slug', [
            'ar' => generateUniqueSlug($category, Category::class, 'ar'),
            'en' => generateUniqueSlug($category, Category::class, 'en'),
        ]);
    }

    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
