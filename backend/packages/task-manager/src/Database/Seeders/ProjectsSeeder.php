<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use ProjectCode\TaskManager\Models\Project;
use ProjectCode\TaskManager\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use ProjectCode\TaskManager\Models\Category;
use ProjectCode\TaskManager\Models\Priority;
use ProjectCode\TaskManager\Models\Status;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {

        $items = [
            [
                "name" => "Meridian " . fake()->name(),
                "color" => "bg-rose-600"
            ],
            [
                "name" => "Risen " . fake()->name(),
                "color" => "bg-blue-600"
            ],
            [
                "name" => "Skillbox " . fake()->name(),
                "color" => "bg-yellow-400"
            ],
            [
                "name" => "Strata insurance " . fake()->name(),
                "color" => "bg-green-600"
            ],
        ];

        foreach ($items as $key => $item) {
            $project = Project::create([
                'id' => ++$key,
                'name' => $item["name"],
                'slug' => Str::of($item["name"])->slug('-'),
                'color' => $item["color"],
                'description' => fake()->text(200)
            ]);

            //seed the project with tasks
            $tasks = [];

            for ($i = 1; $i <= 7; $i++) {
                //randommly get two categories
                $category = Category::inRandomOrder()->first();
                $priority = Priority::inRandomOrder()->first();
                $status = Status::inRandomOrder()->first();
                $taskTitle = fake()->text(50);
                $tasks[] = [
                    'project_id' => $project->id,
                    'title' => $taskTitle,
                    'slug' => Str::of($taskTitle)->slug('-'),
                    'description' => fake()->text(200),
                    'start_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                    'end_date' => fake()->dateTimeBetween('+10 days', '+100 days')->format('Y-m-d'),
                    'category_id' => $category->id,
                    'priority_id' => $priority->id,
                    'status_id' => $status->id
                ];
            }

            foreach ($tasks as $task) {
                Task::create($task);
            }
        }
    }
}
