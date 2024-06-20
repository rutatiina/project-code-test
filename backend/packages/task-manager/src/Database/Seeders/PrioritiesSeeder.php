<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use ProjectCode\TaskManager\Models\Priority;

class PrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $items = [
            "high",
            "medium",
            "low",
        ];

        foreach ($items as $key => $item) {
            Priority::create([
                'id' => ++$key,
                'name' => $item,
                'slug' => Str::of($item)->slug('-'),
                'description' => fake()->text(200)
            ]);
        }
    }
}
