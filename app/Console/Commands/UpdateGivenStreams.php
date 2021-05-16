<?php

namespace App\Console\Commands;

use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Console\Command;

class UpdateGivenStreams extends Command
{
    protected $signature = 'larastreamers:update-streams';

    protected $description = 'Command description';

    public function handle()
    {
        $streams = Stream::all()->keyBy('youtube_id');

        if($streams->isEmpty()) {
            return $this->info('There are no streams in the database.');
        }

        $updatesCount = Youtube::videos($streams->keys())
            ->map(fn(StreamData $streamData) => optional($streams
                ->get($streamData->videoId))
                ->update([
                    'title' => $streamData->title,
                    'channel_title' => $streamData->channelTitle,
                    'thumbnail_url' => $streamData->thumbnailUrl,
                    'scheduled_start_time' => $streamData->plannedStart->timezone('Europe/Vienna'),
                ]))
            ->filter()
            ->count();

        $this->info($updatesCount . ' stream(s) were updated.');
    }
}
