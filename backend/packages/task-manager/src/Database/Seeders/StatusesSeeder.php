<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use ProjectCode\TaskManager\Models\Status;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $items = [
            "to-do",
            "refined",
            "verified",
            "doing",
            "done",
        ];

        foreach ($items as $key => $item) {
            Status::create([
                'id' => ++$key,
                'name' => $item,
                'slug' => Str::of($item)->slug('-'),
                'description' => fake()->text(200)
            ]);
        }
    }
}
