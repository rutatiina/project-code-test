<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use ProjectCode\TaskManager\Models\Task;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $tasks = [];

        for ($i = 1; $i <= 3; $i++) {
            $taskTitle = fake()->text(50);
            $tasks[] = [
                'id' => $i,
                'project_id' => $i,
                'title' => $taskTitle,
                'slug' => Str::of($taskTitle)->slug('-'),
                'description' => fake()->text(200)
            ];
        }

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
