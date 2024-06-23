<?php

namespace ProjectCode\User\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('ProjectCode\User\Http\Controllers\UserController');
    }
}
