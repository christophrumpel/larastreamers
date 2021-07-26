<?php

namespace App\Jobs;

use App\Models\Stream;
use App\Services\Twitter;
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
        if (! is_null($this->stream->announcement_tweeted_at)) {
            return;
        }

        $twitterHandleIfGiven = Str::of(' ')
            ->when($twitterHandle = $this->stream->channel?->twitter_handle, fn() => " by $twitterHandle ");

        app(Twitter::class)
            ->tweet("🔴 A new stream{$twitterHandleIfGiven}is about to start: {$this->stream->title}. Join now!".PHP_EOL.$this->stream->url());

        $this->stream->update([
            'announcement_tweeted_at' => now(),
        ]);
    }
}
