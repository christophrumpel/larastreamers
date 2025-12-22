<?php

namespace App\Jobs;

use App\Facades\YouTube;
use App\Models\Channel;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportYoutubeChannelStreamsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $youTubeChannelId, public string $languageCode = 'en') {}

    public function handle(): void
    {
        $streams = YouTube::upcomingStreams($this->youTubeChannelId);

        $streams->map(function(StreamData $streamData) {
            // Check if stream exists and was rejected
            $existingStream = Stream::where('youtube_id', $streamData->videoId)->first();
            
            // Don't auto-approve if stream was previously rejected
            if ($existingStream && $existingStream->rejected_at) {
                return;
            }

            Stream::updateOrCreate(['youtube_id' => $streamData->videoId], [
                'channel_id' => optional(Channel::where('platform_id', $streamData->channelId)->first())->id,
                'youtube_id' => $streamData->videoId,
                'title' => $streamData->title,
                'description' => $streamData->description,
                'thumbnail_url' => $streamData->thumbnailUrl,
                'scheduled_start_time' => $streamData->plannedStart,
                'language_code' => $this->languageCode,
                'status' => $streamData->status,
                'approved_at' => now(),
            ]);
        });
    }
}
