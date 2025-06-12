<?php

namespace App\Providers;

use App\Models\Company;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share company data with all views
        View::composer('*', function ($view) {
            $view->with('company', Company::first());
        });
    }
} 