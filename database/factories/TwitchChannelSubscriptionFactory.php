<?php

namespace Database\Factories;

use App\Models\TwitchChannelSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TwitchChannelSubscriptionFactory extends Factory
{
    protected $model = TwitchChannelSubscription::class;

    public function definition(): array
    {
        return [
            'verified' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
