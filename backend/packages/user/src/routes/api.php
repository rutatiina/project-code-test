<?php

use Illuminate\Support\Facades\Route;
use ProjectCode\User\Http\Controllers\UserController;
use ProjectCode\User\Http\Controllers\AuthController;
use Illuminate\Http\Request;

Route::post('/api/login', [AuthController::class, 'authenticate'])
    ->middleware('web');

Route::group(['middleware' => ['web', 'auth:sanctum']], function () {
    Route::resource('/api/users', UserController::class);
});
