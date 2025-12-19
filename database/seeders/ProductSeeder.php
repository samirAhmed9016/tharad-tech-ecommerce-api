<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakerEnglish = Faker::create('en_US');
        $fakerArabic = Faker::create('ar_SA');

        $products = [
            ['title' => ['en' => 'Black Glasses',      'ar' => 'نظارة سوداء'],     'image' => 'black-glasses.jpg'],
            ['title' => ['en' => 'Leather Jacket',     'ar' => 'جاكيت جلد'],       'image' => 'blake-jacket.jpg'],
            ['title' => ['en' => 'Classic Glasses',    'ar' => 'نظارة كلاسيك'],     'image' => 'glasses.jpg'],
            ['title' => ['en' => 'Stylish Hat',        'ar' => 'قبعة أنيقة'],       'image' => 'hat.jpg'],
            ['title' => ['en' => 'Wireless Headphones', 'ar' => 'سماعات لاسلكية'],   'image' => 'headphone.jpg'],
            ['title' => ['en' => 'Over-Ear Headphones', 'ar' => 'سماعات رأس'],       'image' => 'headphone2.jpg'],
            ['title' => ['en' => 'Luxury Lipstick',    'ar' => 'أحمر شفاه فاخر'],   'image' => 'lipstick.jpg'],
            ['title' => ['en' => 'Perfume Bottle',     'ar' => 'زجاجة عطر'],        'image' => 'perfume.jpg'],
            ['title' => ['en' => 'Smart Watch',        'ar' => 'ساعة ذكية'],        'image' => 'smart-watch.jpg'],
            ['title' => ['en' => 'Sports Shoes',       'ar' => 'حذاء رياضي'],       'image' => 'sports-shoes.jpg'],
        ];


        for ($i = 0; $i < 10000; $i++) {
            $item = $products[array_rand($products)];

            $product = Product::create([
                'title' => $item['title'],
                'description' => [
                    'en' => $fakerEnglish->sentence(12),
                    'ar' => $fakerArabic->sentence(12),
                ],
                'price' => $fakerEnglish->randomFloat(2, 50, 5000),
                'quantity' => $fakerEnglish->numberBetween(100, 200),
            ]);

            // assign pic to the product after the product is created
            $imagePath = storage_path('app/seed-images/products/' . $item['image']);

            if (File::exists($imagePath)) {
                //because i use only 10 images so i cannot delete them after assigning to product
                //we use them again and again
                //preserve original to avoid file name conflicts
                //this will keep the original file and create a copy with a newname
                $product->addMedia($imagePath)->preservingOriginal()
                    ->toMediaCollection('products');
            }
        }
    }
}
