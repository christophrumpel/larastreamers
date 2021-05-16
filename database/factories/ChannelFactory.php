<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    public function definition(): array
    {
        return [
            'platform' => 'youtube',
            'platform_id' => $this->faker->word,
            'slug' => $this->faker->slug,
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'thumbnail_url' => $this->faker->url,
            'country' => $this->faker->countryCode,
            'on_platform_since' => Carbon::now(),
        ];
    }
}
