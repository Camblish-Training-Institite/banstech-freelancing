<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // In AppServiceProvider boot method or a migration/seeder


        // Role::findOrCreate('project_manager')
        // ->givePermissionTo([
        //     'manage projects',
        //     'view project details',
        //     'create milestones',
        //     'update milestones',
        //     'assign freelancers',
        //     'send messages',
        //     'request project management', // Optional: if PMs can request to manage others
        // ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
