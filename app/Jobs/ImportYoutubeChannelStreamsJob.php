<?php

namespace App\Jobs;

use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportYoutubeChannelStreamsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $youtubeChannelId)
    {
    }

    public function handle(): void
    {
        $streams = Youtube::upcomingStreams($this->youtubeChannelId);

        $streams->map(function(StreamData $streamData) {
            Stream::updateOrCreate(['youtube_id' => $streamData->videoId], [
                'youtube_id' => $streamData->videoId,
                'title' => $streamData->title,
                'description' => $streamData->description,
                'channel_title' => $streamData->channelTitle,
                'thumbnail_url' => $streamData->thumbnailUrl,
                'scheduled_start_time' => $streamData->plannedStart,
                'status' => $streamData->status,
            ]);
        });
    }
}
