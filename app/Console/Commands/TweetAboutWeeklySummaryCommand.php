<?php

namespace App\Console\Commands;

use App\Models\Stream;
use App\Services\Twitter;
use App\Services\Youtube\StreamData;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class TweetAboutWeeklySummaryCommand extends Command
{
    protected $signature = 'larastreamers:tweet-weekly-summary';

    protected $description = 'Tweet a summary about last weeks stream.';

    public function handle(): int
    {
        $streams = Stream::whereNotNull('approved_at')
            ->where('status', StreamData::STATUS_FINISHED)
            ->whereBetween('scheduled_start_time', [
                Carbon::now()->startOfWeek()->subDays(7),
                Carbon::now()->startOfWeek()->subDay()->endOfDay()
            ])
            ->get();

        if ($streams->isEmpty()) {
            $this->info('There were no streams last week.');

            return self::SUCCESS;
        }

        app(Twitter::class)
            ->tweet("Last week we had {$streams->count()} streams. Thanks to all the streamers and viewers 🙏🏻");

        return self::SUCCESS;
    }
}
