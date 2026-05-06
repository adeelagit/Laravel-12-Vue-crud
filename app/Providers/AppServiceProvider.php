<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin;

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
        // Define gates for admin permissions
        Gate::define('view_users', function (Admin $admin) {
            return $admin->hasPermissionTo('view_users', 'admin');
        });

        Gate::define('create_users', function (Admin $admin) {
            return $admin->hasPermissionTo('create_users', 'admin');
        });

        Gate::define('edit_users', function (Admin $admin) {
            return $admin->hasPermissionTo('edit_users', 'admin');
        });

        Gate::define('delete_users', function (Admin $admin) {
            return $admin->hasPermissionTo('delete_users', 'admin');
        });
    }
}
