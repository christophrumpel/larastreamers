<?php

namespace App\Jobs;

use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportYoutubeChannelStreamsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function  __construct(public string $youtubeChannelId)
    {
    }

    public function handle(): void
    {
        $streams = Youtube::upcomingStreams($this->youtubeChannelId);

        $streams->map(function (StreamData $streamData){
           Stream::updateOrCreate(['youtube_id' => $streamData->videoId],[
               'youtube_id' => $streamData->videoId,
               'title' => $streamData->title,
               'channel_title' => $streamData->channelTitle,
               'thumbnail_url' => $streamData->thumbnailUrl,
               'scheduled_start_time' => $streamData->plannedStart->timezone('Europe/Vienna'),
           ]);
        });
    }
}
