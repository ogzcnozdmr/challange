<?php

namespace App\Observers;

use App\Events\DeletedEvent;
use App\Events\RenewedEvent;
use App\Events\StartedEvent;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionObserver
{
    /**
     * Handle the Subscription "created" event.
     *
     * @param \App\Models\Subscription $subscription
     * @return void
     */
    public function created(Subscription $subscription)
    {
        event(new StartedEvent($subscription, $subscription->device()->first()));
    }

    /**
     * Handle the Subscription "updated" event.
     *
     * @param \App\Models\Subscription $subscription
     * @return void
     */
    public function updated(Subscription $subscription)
    {
        if ($subscription->status) {
            event(new RenewedEvent($subscription, $subscription->device()->first()));
        } else {
            event(new DeletedEvent($subscription, $subscription->device()->first()));
        }
    }

    /**
     * Handle the Subscription "deleted" event.
     *
     * @param \App\Models\Subscription $subscription
     * @return void
     */
    public function deleted(Subscription $subscription)
    {
        //
    }

    /**
     * Handle the Subscription "restored" event.
     *
     * @param \App\Models\Subscription $subscription
     * @return void
     */
    public function restored(Subscription $subscription)
    {
        //
    }

    /**
     * Handle the Subscription "force deleted" event.
     *
     * @param \App\Models\Subscription $subscription
     * @return void
     */
    public function forceDeleted(Subscription $subscription)
    {
        //
    }
}
