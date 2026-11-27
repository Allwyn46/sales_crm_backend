<?php

namespace App\Providers;

use App\Models\Lead;
use App\Models\User;
use App\Policies\LeadPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     */
    public function boot(): void
    {
        Gate::policy(Lead::class, LeadPolicy::class);
    }
}
