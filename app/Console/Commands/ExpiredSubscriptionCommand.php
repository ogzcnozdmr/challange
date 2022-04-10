<?php

namespace App\Console\Commands;

use App\Jobs\ExpiredSubscriptionJob;
use Illuminate\Console\Command;

class ExpiredSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:expire-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscription Expired Control';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dispatch(new ExpiredSubscriptionJob());
    }
}
