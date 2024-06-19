<?php

namespace TaskManager\Database\Seeders;

use Illuminate\Database\Seeder;
use TaskManager\Database\Seeders\PostSeeder;
use TaskManager\Database\Seeders\PostTemplateSeeder;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->call([
            PostSeeder::class,
            PostTemplateSeeder::class,
        ]);
    }
}
