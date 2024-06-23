<?php

namespace ProjectCode\User\Database\Seeders;

use Illuminate\Database\Seeder;
use ProjectCode\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     * php artisan migrate:fresh --seed --seeder=ProjectCode\\User\\Database\\Seeders\\UserSeeder
     * php artisan db:seed --class=ProjectCode\\User\\Database\\Seeders\\UserSeeder
     */
    public function run(): void
    {
        User::create([
            'name' => "ProjectCode Admin",
            'email' => "admin@projectcode.ug",
            'email_verified_at' => now(),
            'password' => Hash::make('P@ssw0rd'),
            'remember_token' => Str::random(10),
        ]);

        // seed the users

        for ($i = 0; $i <= 6; $i++) {
            User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('P@ssw0rd'),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
