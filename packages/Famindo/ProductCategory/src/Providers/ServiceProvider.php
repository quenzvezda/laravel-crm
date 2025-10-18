<?php

namespace Famindo\ProductCategory\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        // Admin routes
        Route::middleware(['web', 'admin_locale', 'user'])
            ->prefix(config('app.admin_path'))
            ->group(__DIR__.'/../Routes/admin.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'product-category');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'product-category');
    }

    public function register(): void
    {
        // Merge ACL and Menu into Admin
        $this->mergeConfigFrom(__DIR__.'/../Config/acl.php', 'acl');
        $this->mergeConfigFrom(__DIR__.'/../Config/menu.php', 'menu.admin');
    }
}
