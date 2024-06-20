<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use ProjectCode\TaskManager\Models\Tag;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $items = [
            "Prototype",
            "Research",
            "Testing",
        ];

        foreach ($items as $key => $item) {
            Tag::create([
                'id' => ++$key,
                'name' => $item,
                'slug' => Str::of($item)->slug('-'),
                'description' => fake()->text(200)
            ]);
        }
    }
}
