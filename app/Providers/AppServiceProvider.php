<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('authUser', Auth::user());
                $view->with('userRoles', Auth::user()->getRoleNames());
                $view->with('userPermissions', Auth::user()->getAllPermissions()->pluck('name'));
            }
        });

        View::share('appName', config('app.name', 'Bagamoyo Municipal Management'));
        View::share('appVersion', '1.0.0');
    }
}
