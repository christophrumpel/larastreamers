<?php

namespace App\Console\Commands;

use App\Jobs\TweetStreamIsLiveJob;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Console\Command;

class TweetAboutLiveStreamsCommand extends Command
{
    protected $signature = 'larastreamers:tweet-live';

    protected $description = 'Tweet announcement for streams (live)';

    public function handle(): int
    {
        $streams = Stream::query()
            ->where('status', StreamData::STATUS_LIVE)
            ->whereNull('tweeted_at')
            ->get()
            ->each(function(Stream $stream) {
                dispatch(new TweetStreamIsLiveJob($stream));
            });

        if ($streams->isEmpty()) {
            $this->info('There are no streams to tweet.');

            return self::SUCCESS;
        }

        $this->info("{$streams->count()} tweets sent");

        return self::SUCCESS;
    }
}
