<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use ProjectCode\TaskManager\Models\Category;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $items = [
            "Incidential",
            "Coordinated",
            "Planned",
        ];

        foreach ($items as $key => $item) {
            Category::create([
                'id' => ++$key,
                'name' => $item,
                'slug' => Str::of($item)->slug('-'),
                'description' => fake()->text(200)
            ]);
        }
    }
}
