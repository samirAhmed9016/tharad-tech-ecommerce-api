<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];

        for ($i = 1; $i <= 50; $i++) {

            // Random date within last 2 years
            $createdAt = Carbon::now()->subDays(rand(0, 730));

            $users[] = [
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'email_verified_at' => $createdAt->copy()->addMinutes(rand(1, 1440)),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays(rand(0, 30)),
            ];
        }

        DB::table('users')->insert($users);
    }
}
