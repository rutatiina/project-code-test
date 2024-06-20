<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use ProjectCode\TaskManager\Models\Project;
use ProjectCode\TaskManager\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use ProjectCode\TaskManager\Models\Category;
use ProjectCode\TaskManager\Models\Priority;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {

        $items = [
            "Meridian " . fake()->name(),
            "Risen " . fake()->name(),
            "Skillbox " . fake()->name(),
            "Strata insurance " . fake()->name(),
        ];

        foreach ($items as $key => $item) {
            $project = Project::create([
                'id' => ++$key,
                'name' => $item,
                'slug' => Str::of($item)->slug('-'),
                'description' => fake()->text(200)
            ]);

            //seed the project with tasks
            $tasks = [];

            for ($i = 1; $i <= 7; $i++) {
                //randommly get two categories
                $category = Category::inRandomOrder()->first();
                $priority = Priority::inRandomOrder()->first();
                $taskTitle = fake()->text(50);
                $tasks[] = [
                    'project_id' => $project->id,
                    'title' => $taskTitle,
                    'slug' => Str::of($taskTitle)->slug('-'),
                    'description' => fake()->text(200),
                    'category_id' => $category->id,
                    'priority_id' => $priority->id
                ];
            }

            foreach ($tasks as $task) {
                Task::create($task);
            }
        }
    }
}
