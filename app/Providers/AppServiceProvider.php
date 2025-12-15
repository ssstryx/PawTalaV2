<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Routing\Router;
use App\Http\Middleware\RoleMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    protected $listen = [
    \Illuminate\Auth\Events\Registered::class => [
        \App\Listeners\AssignDefaultRole::class, // (If you have this)
    ],

    // ADD THIS NEW ENTRY:
    \Illuminate\Auth\Events\Verified::class => [
        \App\Listeners\SetUserActiveOnVerified::class,
    ],
];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the RoleMiddleware as a route middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('role', RoleMiddleware::class);

        // This is a common place to define Blade directives as well
        // For example, if you have @role('admin') ... @endrole
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });
    }
}
