<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uid' => rand(10000, 1000000),
            'appId' => rand(10000, 1000000),
            'language' => rand(0, 10) % 2 ? 'tr' : 'en',
            'os' => rand(0, 10) % 2 ? 'ios' : 'android'
        ];
    }
}
