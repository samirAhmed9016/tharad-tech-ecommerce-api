<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{

    public function run(): void
    {
        $categories = [
            [
                'title' => ['en' => 'Accessories', 'ar' => 'إكسسوارات'],
                'description' => ['en' => 'All kinds of accessories', 'ar' => 'جميع أنواع الإكسسوارات'],
                'slug'  => ['en' => 'accessories', 'ar' => 'اكسسوارات'],
                'sort'  => 1,
            ],
            [
                'title' => ['en' => 'Electronics', 'ar' => 'إلكترونيات'],
                'description' => ['en' => 'Electronic devices and gadgets', 'ar' => 'الأجهزة الإلكترونية والأدوات'],
                'slug'  => ['en' => 'electronics', 'ar' => 'الكترونيات'],
                'sort'  => 2,
            ],
            [
                'title' => ['en' => 'Fashion', 'ar' => 'أزياء'],
                'description' => ['en' => 'Fashion and clothing items', 'ar' => 'أزياء وملابس'],
                'slug'  => ['en' => 'fashion', 'ar' => 'ازياء'],
                'sort'  => 3,
            ],
            [
                'title' => ['en' => 'Beauty', 'ar' => 'تجميل'],
                'description' => ['en' => 'Beauty and cosmetic products', 'ar' => 'منتجات التجميل ومستحضرات التجميل'],
                'slug'  => ['en' => 'beauty', 'ar' => 'تجميل'],
                'sort'  => 4,
            ],
            [
                'title' => ['en' => 'Sports', 'ar' => 'رياضة'],
                'description' => ['en' => 'Sports equipment and apparel', 'ar' => 'معدات وملابس رياضية'],
                'slug'  => ['en' => 'sports', 'ar' => 'رياضة'],
                'sort'  => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'title'       => $category['title'],
                'description' => $category['description'],
                'slug'        => $category['slug'],
                'sort'        => $category['sort'],
                'status'      => true,
            ]);
        }
    }
}
