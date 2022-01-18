<?php

namespace App\Console\Commands;

use App\Facades\Twitter;
use App\Models\Stream;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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

        Twitter::tweet(sprintf("Last week, there %s %s %s. 👏 %s 🙏🏻\n Find more Streams here: ".route('archive'),
            $streamsCount == 1 ? 'was' : 'were',
            $streamsCount,
            Str::plural('stream', $streamsCount),
        $streamsCount == 1 ? 'Thanks to everyone who participated.' : 'Thanks to all the streamers and viewers.'
        ));

        return self::SUCCESS;
    }
}
