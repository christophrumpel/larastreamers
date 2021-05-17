<?php

namespace App\Console\Commands;

use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Console\Command;

class UpdateGivenStreams extends Command
{
    protected $signature = 'larastreamers:update-streams';

    protected $description = 'Update all today/upcoming streams';

    public function handle(): int
    {
        $streams = Stream::all()->keyBy('youtube_id');

        if ($streams->isEmpty()) {
            $this->info('There are no streams in the database.');

            return self::SUCCESS;
        }

        $updatesCount = Youtube::videos($streams->keys())
            ->map(fn(StreamData $streamData) => optional($streams
                ->get($streamData->videoId))
                ->update([
                    'title' => $streamData->title,
                    'channel_title' => $streamData->channelTitle,
                    'thumbnail_url' => $streamData->thumbnailUrl,
                    'scheduled_start_time' => $streamData->plannedStart->timezone('UTC'),
                ]))
            ->filter()
            ->count();

        $this->info($updatesCount.' stream(s) were updated.');

        return self::SUCCESS;
    }
}
