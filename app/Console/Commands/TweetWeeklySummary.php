<?php

namespace App\Console\Commands;

use App\Models\Stream;
use App\Services\Twitter;
use App\Services\Youtube\StreamData;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class TweetWeeklySummary extends Command
{
    protected $signature = 'larastreamers:tweet-weekly-summary';

    protected $description = 'Tweet out a weekly summary.';

    public function handle()
    {
        $streams = Stream::whereNotNull('approved_at')
            ->where('status', StreamData::STATUS_FINISHED)
            ->whereBetween('actual_start_time', [
                Carbon::today()->subWeek()->startOfWeek(),
                Carbon::today()->subWeek()->endOfWeek()->endOfDay(),
            ])
            ->get();

        app(Twitter::class)
            ->tweet("There were {$streams->count()} streams last week. Thanks to all the viewers 🙏🏻.");
    }
}
