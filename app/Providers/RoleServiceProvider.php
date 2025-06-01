<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Role;
use App\Http\Middleware\CheckRole;

class RoleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Don't bind 'role' to the Role model - that was causing the error
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
