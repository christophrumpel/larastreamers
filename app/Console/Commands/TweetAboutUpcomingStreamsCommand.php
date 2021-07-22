<?php

namespace App\Console\Commands;

use App\Jobs\TweetUpcomingStreamJob;
use App\Models\Stream;
use Illuminate\Console\Command;

class TweetAboutUpcomingStreamsCommand extends Command
{
    protected $signature = 'larastreamers:tweet-upcoming';

    protected $description = 'Tweet announcement for upcoming streams.';

    public function handle(): int
    {
        $streams = Stream::query()
            ->approved()
            ->upcoming()
            ->where('scheduled_start_time', '<=', now()->addMinutes(5))
            ->whereNull('announcement_tweeted_at')
            ->get()
            ->each(function(Stream $stream) {
                dispatch(new TweetUpcomingStreamJob($stream));
            });

        if ($streams->isEmpty()) {
            $this->info('There are no streams to tweet.');

            return self::SUCCESS;
        }

        $this->info("{$streams->count()} tweets sent");

        return self::SUCCESS;
    }
}
