<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use Illuminate\Database\Seeder;

class TaskManagerSeeder extends Seeder
{
    /**
     * Run the database seeders.
     * php artisan migrate:fresh --seed --seeder=ProjectCode\\TaskManager\\Database\\Seeders\\TaskManagerSeeder
     * php artisan db:seed --class=ProjectCode\\TaskManager\\Database\\Seeders\\TaskManagerSeeder
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
