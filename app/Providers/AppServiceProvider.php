<?php

namespace App\Providers;

use App\Models\Battery;
use App\Models\Connector;
use App\Models\SolarPanel;
use App\Search\Products;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\ModelObserver;

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
        // Register product aggregator
        Products::bootSearchable();

        // Do not index models
        ModelObserver::disableSyncingFor(SolarPanel::class);
        ModelObserver::disableSyncingFor(Battery::class);
        ModelObserver::disableSyncingFor(Connector::class);
    }
}
