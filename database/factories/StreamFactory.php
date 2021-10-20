<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Language;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class StreamFactory extends Factory
{
    protected $model = Stream::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->text(100),
            'youtube_id' => Str::random(10),
            'thumbnail_url' => collect([
                'https://i.ytimg.com/vi/s9s7O7_jQh8/maxresdefault_live.jpg',
                'https://i.ytimg.com/vi/kZ93WrObSQc/maxresdefault_live.jpg',
                'https://i.ytimg.com/vi/yctFdkrTLqo/maxresdefault_live.jpg',
                'https://i.ytimg.com/vi/BSbPsCRBpRU/maxresdefault_live.jpg',
                'https://i.ytimg.com/vi/zlllPenYN60/maxresdefault_live.jpg',
            ])->random(),
            'scheduled_start_time' => Carbon::now()->addDays(random_int(2, 10)),
            'status' => StreamData::STATUS_UPCOMING,
            'language_code' => Arr::shuffle(Language::all()->map->code->toArray())[0],
            'approved_at' => now(),
        ];
    }

    public function finished(): StreamFactory
    {
        return $this->state(function() {
            return [
                'scheduled_start_time' => Carbon::yesterday()->subDays(random_int(0, 10))->toIso8601String(),
                'status' => StreamData::STATUS_FINISHED,
            ];
        });
    }

    public function upcoming(): StreamFactory
    {
        return $this->state(function() {
            return [
                'scheduled_start_time' => Carbon::today()->addDays(random_int(1, 10)),
                'status' => StreamData::STATUS_UPCOMING,
            ];
        });
    }

    public function live(): StreamFactory
    {
        return $this->state(function() {
            return [
                'scheduled_start_time' => Carbon::now(),
                'status' => StreamData::STATUS_LIVE,
            ];
        });
    }

    public function deleted(): StreamFactory
    {
        return $this->state(function() {
            return [
                'status' => StreamData::STATUS_DELETED,
                'hidden_at' => Carbon::now(),
            ];
        });
    }

    public function approved(): StreamFactory
    {
        return $this->state(fn() => ['approved_at' => Carbon::now()->subDay()]);
    }

    public function notApproved(): StreamFactory
    {
        return $this->state(fn() => ['approved_at' => null]);
    }

    public function liveTweetWasSend(): StreamFactory
    {
        return $this->state(function() {
            return [
                'tweeted_at' => now(),
            ];
        });
    }

    public function upcomingTweetWasSend(): StreamFactory
    {
        return $this->state(function() {
            return [
                'scheduled_start_time' => now()->addMinutes(5),
                'upcoming_tweeted_at' => now(),
            ];
        });
    }

    public function startsWithinUpcomingTweetRange(): StreamFactory
    {
        return $this->state(function() {
            return [
                'scheduled_start_time' => now()->addMinutes(5),
            ];
        });
    }

    public function startsOutsideUpcomingTweetRange(): StreamFactory
    {
        return $this->state(function() {
            return [
                'scheduled_start_time' => now()->addMinutes(6),
            ];
        });
    }

    public function withChannel(array $channelData = []): StreamFactory
    {
        return $this->for(Channel::factory()->create($channelData));
    }
}
