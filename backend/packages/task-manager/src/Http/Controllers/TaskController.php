<?php

namespace ProjectCode\TaskManager\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use ProjectCode\TaskManager\Models\Task;

class TaskController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index()
    {
        $tasks = Task::get();
        // return $tasks->toArray();
        return response()->json($tasks);
    }

    /**
     * Show the profile for a given user.
     */
    public function store(string $id): array
    {
        return [];
    }

    /**
     * show given record
     */
    public function show(string $id): array
    {
        return [];
    }

    /**
     * edit / update given record.
     */
    public function update(string $id): array
    {
        return [];
    }

    /**
     * destroy given record.
     */
    public function destroy(string $id): array
    {
        return [];
    }
}
