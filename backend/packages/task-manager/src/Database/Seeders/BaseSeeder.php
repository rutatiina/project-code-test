<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     * php artisan migrate:fresh --seed --seeder=ProjectCode\\TaskManager\\Database\\Seeders\\BaseSeeder
     * php artisan db:seed --class=ProjectCode\\TaskManager\\Database\\Seeders\\BaseSeeder
     */
    public function run(): void
    {
        $this->call([
            StatusesSeeder::class,
            CategoriesSeeder::class,
            PrioritiesSeeder::class,
            TagsSeeder::class,
            ProjectsSeeder::class,
        ]);
    }
}
