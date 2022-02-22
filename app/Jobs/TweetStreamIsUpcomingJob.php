<?php

namespace App\Jobs;

use App\Facades\Twitter;
use App\Models\Stream;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class TweetStreamIsUpcomingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Stream $stream,
    ) {
    }

    public function handle(): void
    {
        if ($this->stream->tweetStreamIsUpcomingWasSend()) {
            return;
        }

        if ($this->stream->tweetStreamIsLiveWasSend()) {
            return;
        }

        $twitterHandleIfGiven = Str::of(' ')
            ->when((bool)$twitterHandle = $this->stream->channel?->twitter_handle, fn() => " by $twitterHandle ");

        Twitter::tweet("🔴 A new stream{$twitterHandleIfGiven}is about to start: {$this->stream->title}. Join now!".PHP_EOL.$this->stream->url());

        $this->stream->update([
            'upcoming_tweeted_at' => now(),
        ]);
    }
}
