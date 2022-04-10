<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Observers\SubscriptionObserver;
use Illuminate\Support\ServiceProvider;

class ObserverProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Subscription Observer
        Subscription::observe(SubscriptionObserver::class);
    }
}
