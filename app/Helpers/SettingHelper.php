<?php

namespace App\Helpers;

use Illuminate\Support\Str;




if (!function_exists('generateUniqueSlug')) {

    function generateUniqueSlug($model, $class, string $locale): string
    {
        $title = $model->getTranslation('title', $locale) ?? $model->getTranslation('name', $locale);

        $baseSlug = slugify($title);
        $slug = $baseSlug;
        $i = 1;

        while (
            $class::where("slug->{$locale}", $slug)
            ->when($model->exists, fn($query) => $query->where('id', '!=', $model->id))
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $i++;
        }

        return $slug;
    }
}


if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = preg_replace('/[^\p{Arabic}a-zA-Z0-9\s\-]+/u', '', $text);
        $text = preg_replace('/[\s\-]+/u', '-', trim($text));

        return Str::lower($text);
    }
}
