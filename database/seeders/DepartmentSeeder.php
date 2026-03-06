<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'title' => 'Electronics',
                'description' => 'All kinds of electronic devices and gadgets.',
                'slug' => 'electronics',
                'status' => true,
            ],
            [
                'title' => 'Clothing',
                'description' => 'Fashionable clothing for men, women, and kids.',
                'slug' => 'clothing',
                'status' => true,
            ],
            [
                'title' => 'Home & Kitchen',
                'description' => 'Essentials for your home and kitchen.',
                'slug' => 'home-kitchen',
                'status' => true,
            ],
            [
                'title' => 'Books',
                'description' => 'A wide range of books across various genres.',
                'slug' => 'books',
                'status' => true,
            ],
            [
                'title' => 'Sports & Outdoors',
                'description' => 'Gear and equipment for sports and outdoor activities.',
                'slug' => 'sports-outdoors',
                'status' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
