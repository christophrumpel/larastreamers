<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Models\Stream;
use Illuminate\Console\Command;

class TweetAboutWeeklySummaryCommand extends Command
{
    protected $signature = 'larastreamers:tweet-weekly-summary';

    protected $description = 'Tweet out a weekly summary.';

    public function handle(): int
    {
        $streamsCount = Stream::approved()
            ->finished()
            ->fromLastWeek()
            ->count();

        if (! $streamsCount) {
            $this->info('There were no streams last week.');

            return self::SUCCESS;
        }

        Twitter::tweet("There were $streamsCount streams last week. 👏 Thanks to all the streamers and viewers. 🙏🏻\n Find them here: ".route('archive'));

        return self::SUCCESS;
    }
}
