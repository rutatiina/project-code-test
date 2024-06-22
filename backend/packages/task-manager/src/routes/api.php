<?php

use Illuminate\Support\Facades\Route;
use ProjectCode\TaskManager\Http\Controllers\ProjectController;
use ProjectCode\TaskManager\Http\Controllers\TaskController;
use ProjectCode\TaskManager\Http\Controllers\CategoryController;
use ProjectCode\TaskManager\Http\Controllers\TagController;
use ProjectCode\TaskManager\Http\Controllers\PriorityController;
use ProjectCode\TaskManager\Http\Controllers\StatusController;

Route::resource('/api/projects', ProjectController::class);
Route::resource('/api/tasks', TaskController::class);
Route::resource('/api/categories', CategoryController::class);
Route::resource('/api/tags', TagController::class);
Route::resource('/api/priorities', PriorityController::class);
Route::resource('/api/statuses', StatusController::class);

Route::group(['middleware' => ['web', 'auth']], function () {
});
