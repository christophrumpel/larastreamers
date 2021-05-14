<?php

namespace Database\Factories;

use App\Models\Stream;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StreamFactory extends Factory
{
    protected $model = Stream::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'channel_title' => $this->faker->title,
            'title' => $this->faker->title,
            'youtube_id' => Str::random(10),
            'thumbnail_url' => '',
            'scheduled_start_time' => Carbon::tomorrow()->toIso8601String(),
        ];
    }
}
