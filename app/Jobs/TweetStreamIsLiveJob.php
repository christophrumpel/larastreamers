<?php

namespace App\Jobs;

use App\Models\Stream;
use App\Services\Twitter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TweetStreamIsLiveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Stream $stream,
    ) {}

    public function handle(Twitter $twitter)
    {
        if ($this->stream->hasBeenTweeted()) {
            return;
        }

        $status = "🔴 A new stream just started: {$this->stream->title}" . PHP_EOL . $this->stream->url();

        if (app()->environment('production')) {
            $twitter->tweet($status);
        }

        $this->stream->markAsTweeted();
    }
}
