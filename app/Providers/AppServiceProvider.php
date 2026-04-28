<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        URL::forceScheme('http');

        Gate::define('access-admin', fn($user) => $user->type === 'admin');
        Gate::define('access-registrar', fn($user) => $user->type === 'registrar');
        Gate::define('access-accounting', fn($user) => $user->type === 'accounting');
        Gate::define('access-admission', fn($user) => $user->type === 'admission');
        Gate::define('access-department', function($user) {
            return $user->department_id !== null;
        });
    }
}
