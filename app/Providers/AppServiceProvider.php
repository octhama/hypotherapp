<?php

namespace App\Providers;

use App\Policies\DashboardAccessPolicy;
use App\Policies\RapportPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Client;
use App\Models\Poney;
use App\Policies\ClientPolicy;
use App\Policies\PoneyPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Enregistrement des politiques d'autorisation.
     */
    public function boot(): void
    {
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(Poney::class, PoneyPolicy::class);

        // $this->registerPolicies();
        // Gate::define('view-reports', [DashboardAccessPolicy::class, 'viewReports']);
    }

    /**
     * Enregistrement des services de l'application.
     */
    public function register(): void
    {
        //
    }

    private function registerPolicies(): void
    {
        Gate::define('viewAny', [ClientPolicy::class, 'viewAny']);
        Gate::define('view', [ClientPolicy::class, 'view']);
        Gate::define('create', [ClientPolicy::class, 'create']);
        Gate::define('update', [ClientPolicy::class, 'update']);
        Gate::define('delete', [ClientPolicy::class, 'delete']);
        Gate::define('generate-invoice', [ClientPolicy::class, 'generateInvoice']);
    }
}
