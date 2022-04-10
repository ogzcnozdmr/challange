<?php

namespace App\Events;

use App\Jobs\FailedEventJob;
use App\Models\Device;
use App\Models\Subscription;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RenewedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\Subscription $subscription
     */
    public Subscription $subscription;

    /**
     * @var \App\Models\Device $device
     */
    public Device $device;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription, Device $device)
    {
        $this->subscription = $subscription;
        $this->device = $device;

        $this->handle();
    }

    /**
     * Event İşlemi
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $eventRequest = Request::create('/api/application/event', 'POST', [
            'uid' => $this->device->uid,
            'appId' => $this->device->appId,
            'event' => 'renewed'
        ]);

        $eventStatus = json_decode(app()->handle($eventRequest)->getStatusCode());
        //status farklıysa failed events veri tabanına kaydeder
        if ($eventStatus !== 200 && $eventStatus !== 201) {
            dispatch(new FailedEventJob(new static($this->subscription, $this->device)));
        } else {
            Log::info('APPID: ' . $this->device->appId . ' RenewedEvent Başarılı');
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
