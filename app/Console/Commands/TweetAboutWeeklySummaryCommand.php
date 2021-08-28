<?php

namespace App\Console\Commands;

use App\Models\Stream;
use App\Services\Twitter;
use Illuminate\Console\Command;

class TweetAboutWeeklySummaryCommand extends Command
{
    protected $signature = 'larastreamers:tweet-weekly-summary';

    protected $description = 'Tweet a summary about last weeks stream.';

    public function handle(): int
    {
        $streams = Stream::query()
            ->approved()
            ->finished()
            ->fromLastWeek()
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
