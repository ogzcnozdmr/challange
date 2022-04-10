<?php

namespace App\Jobs;

use App\Events\DeletedEvent;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ExpiredSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscriptions = Subscription::where([
            ['status', '=', true],
            ['expire_date', '<=', Carbon::now()]
        ])->with('device')->get();

        // süresi geçmiş kayıtların status false yapılarak subscriptionObserver tetikleniyor.
        foreach ($subscriptions as $subscription) {
            $subscription->status = false;
            $subscription->save();
        }
    }
}
