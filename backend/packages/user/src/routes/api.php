<?php

use Illuminate\Support\Facades\Route;
use ProjectCode\User\Http\Controllers\UserController;

Route::resource('/api/users', UserController::class);

Route::group(['middleware' => ['web', 'auth']], function () {
});
