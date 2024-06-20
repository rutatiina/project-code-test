<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use Illuminate\Database\Seeder;
use ProjectCode\TaskManager\Database\Seeders\TasksSeeder;

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
            TasksSeeder::class,
        ]);
    }
}
