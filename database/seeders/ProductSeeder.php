<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;
use App\Models\Product;
use Illuminate\Support\Str;


class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $fakerEn = Faker::create('en_US');
        $fakerAr = Faker::create('ar_SA');

        $categories = Category::pluck('id')->toArray();

        if (empty($categories)) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');
            return;
        }

        $products = [
            ['title' => ['en' => 'Black Glasses',       'ar' => 'نظارة سوداء'],     'image' => 'black-glasses.jpg'],
            ['title' => ['en' => 'Leather Jacket',      'ar' => 'جاكيت جلد'],       'image' => 'leather-jacket.jpg'],
            ['title' => ['en' => 'Classic Glasses',     'ar' => 'نظارة كلاسيك'],     'image' => 'glasses.jpg'],
            ['title' => ['en' => 'Stylish Hat',         'ar' => 'قبعة أنيقة'],       'image' => 'hat.jpg'],
            ['title' => ['en' => 'Wireless Headphones', 'ar' => 'سماعات لاسلكية'],   'image' => 'headphone.jpg'],
            ['title' => ['en' => 'Luxury Lipstick',     'ar' => 'أحمر شفاه فاخر'],   'image' => 'lipstick.jpg'],
            ['title' => ['en' => 'Perfume Bottle',      'ar' => 'زجاجة عطر'],        'image' => 'perfume.jpg'],
            ['title' => ['en' => 'Smart Watch',         'ar' => 'ساعة ذكية'],        'image' => 'smart-watch.jpg'],
            ['title' => ['en' => 'Sports Shoes',        'ar' => 'حذاء رياضي'],       'image' => 'sports-shoes.jpg'],
        ];

        for ($i = 0; $i < 100; $i++) {
            $item = $products[array_rand($products)];

            $product = Product::create([
                'category_id' => $categories[array_rand($categories)],
                'title'       => $item['title'],
                'description' => [
                    'en' => $fakerEn->sentence(12),
                    'ar' => $fakerAr->sentence(12),
                ],
                'price'       => $fakerEn->randomFloat(2, 50, 5000),
                'quantity'    => $fakerEn->numberBetween(10, 200),
                'status'      => true,
            ]);

            // Optional: Spatie Media
            $imagePath = storage_path('app/seed-images/products/' . $item['image']);

            if (File::exists($imagePath)) {
                $product->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('products');
            }
        }

        $this->command->info('100 products created successfully!');
    }
}
