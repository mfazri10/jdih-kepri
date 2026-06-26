<?php

namespace App\Providers;

use App\Support\AdminMenuBuilder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        Paginator::useBootstrapFive();

        View::composer(['layouts.admin', 'components.admin.sidebar'], function ($view): void {
            $view->with('sidebarMenus', Auth::check() ? AdminMenuBuilder::forUser(Auth::user()) : collect());
        });
    }
}
