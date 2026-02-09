<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //TOP THREE
        $permissions = [
            'view-category',
            'create-category',
            'update-category',
            'delete-category',
            'restore-category',
            'force-delete-category',
            'toggle-category-status',

            'view-product',
            'create-product',
            'update-product',
            'delete-product',
            'restore-product',
            'force-delete-product',
            'toggle-product-status',

            'view-order',
            'create-order',
            'update-order',
            'delete-order',
            'restore-order',
            'force-delete-order',
            'toggle-order-status',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
