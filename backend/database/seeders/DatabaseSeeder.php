<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ProjectCode\TaskManager\Database\Seeders\TaskManagerSeeder;
use ProjectCode\User\Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        //Add the default seeders for the packages
        $this->call([
            UserSeeder::class,
            TaskManagerSeeder::class,
        ]);
    }
}
