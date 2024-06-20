<?php

namespace ProjectCode\TaskManager\Http\Controllers;

use App\Http\Controllers\Controller;
use ProjectCode\TaskManager\Models\Project;

class ProjectController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index()
    {
        $projects = Project::get();
        return response()->json($projects);
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
