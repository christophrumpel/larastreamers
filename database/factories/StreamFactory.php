<?php

namespace Database\Factories;

use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StreamFactory extends Factory
{
    protected $model = Stream::class;

    public function definition(): array
    {
        return [
            'channel_title' => $this->faker->title,
            'title' => $this->faker->title,
            'description' => $this->faker->text(100),
            'youtube_id' => Str::random(10),
            'thumbnail_url' => '',
            'scheduled_start_time' => Carbon::tomorrow()->toIso8601String(),
            'status' => StreamData::STATUS_UPCOMING,
        ];
    }
}
