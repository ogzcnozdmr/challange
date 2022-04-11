<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $devices = Device::factory(100)->create();
        foreach ($devices as $device){
            Subscription::create([
                'device_id' => $device->id,
                'receipt' => md5(Str::random()) . rand(0, 10),
                'status' => true,
                'expire_date' => Carbon::now()->addHours(-1)->format('Y-m-d H:i:s')
            ]);
        }
    }
}
