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
            [
                "name" => "Prototype",
                "color" => "bg-purple-600",
            ],
            [
                "name" => "Research",
                "color" => "bg-green-600",
            ],
            [
                "name" => "Testing",
                "color" => "bg-yellow-400",
            ],
        ];

        foreach ($items as $key => $item) {
            Tag::create([
                'id' => ++$key,
                'name' => $item["name"],
                'slug' => Str::of($item)->slug('-'),
                'color' => $item["color"],
                'description' => fake()->text(200)
            ]);
        }
    }
}
