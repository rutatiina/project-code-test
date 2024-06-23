<?php

namespace ProjectCode\TaskManager\Database\Seeders;

use ProjectCode\TaskManager\Models\Project;
use ProjectCode\TaskManager\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use ProjectCode\TaskManager\Models\Category;
use ProjectCode\TaskManager\Models\Member;
use ProjectCode\TaskManager\Models\Priority;
use ProjectCode\TaskManager\Models\Status;
use ProjectCode\User\Models\User;

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

            $noOfTasks = fake()->numberBetween(2, 5);
            for ($i = 1; $i <= $noOfTasks; $i++) {
                //randommly get two categories
                $category = Category::inRandomOrder()->first();
                $priority = Priority::inRandomOrder()->first();
                $status = Status::inRandomOrder()->first();
                $taskName = fake()->text(50);

                //create the task
                $task = Task::create([
                    'project_id' => $project->id,
                    'name' => $taskName,
                    'slug' => Str::of($taskName)->slug('-'),
                    'description' => fake()->text(200),
                    'start_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                    'end_date' => fake()->dateTimeBetween('+10 days', '+100 days')->format('Y-m-d'),
                    'category_id' => $category->id,
                    'priority_id' => $priority->id,
                    'status_id' => $status->id
                ]);


                //create the task members
                $noOfMembers = fake()->numberBetween(2, 5);
                $members = [];
                for ($i = 0; $i < $noOfMembers; $i++) {
                    $user = User::whereNotIn('id', $members)->inRandomOrder()->first();
                    Member::create([
                        'project_id' => $project->id,
                        'user_id' => $user->id,
                        'task_id' => $task->id,
                    ]);
                    $members[] = $user->id;
                }
            }
        }
    }
}
