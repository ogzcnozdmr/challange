<?php

namespace App\Listeners;

use App\Events\DeletedEvent;
use App\Events\RenewedEvent;
use App\Events\StartedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class EventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param StartedEvent|RenewedEvent|DeletedEvent $event
     * @return void
     */
    public function handle($event)
    {
        if ($event instanceof StartedEvent) {
            Log::info('StartedEvent Tetiklendi DeviceID: ' . $event->subscription->device_id . ' APPID: ' . $event->device->appId);
        } else if ($event instanceof RenewedEvent) {
            Log::info('Renewed Tetiklendi DeviceID: ' . $event->subscription->device_id . ' APPID: ' . $event->device->appId);
        } else if ($event instanceof DeletedEvent) {
            Log::info('Deleted Tetiklendi DeviceID: ' . $event->subscription->device_id . ' APPID: ' . $event->device->appId);
        }
    }
}
