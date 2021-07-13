<?php

namespace App\Console\Commands;

use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateArchivedStreamsCommand extends Command
{
    protected $signature = 'larastreamers:update-archived-streams';

    protected $description = 'Update latest 50 archived (& live) streams';

    public function handle()
    {
        $streams = Stream::query()
            ->approved()
            ->whereIn('status', [
                StreamData::STATUS_LIVE,
                StreamData::STATUS_FINISHED,
            ])
            ->fromLatestToOldest()
            ->limit(50)
            ->get()
            ->keyBy('youtube_id');

        if ($streams->isEmpty()) {
            $this->info('There are no streams to update.');

            return self::SUCCESS;
        }

        $this->info("Fetching {$streams->count()} stream(s) from API.");

        $youtubeResponse = Youtube::videos($streams->keys());

        $streams->each(function(Stream $stream) use ($youtubeResponse) {
            $this->info("Updating {$stream->youtube_id} ...");

            /** @var StreamData|null $streamData */
            $streamData = $youtubeResponse->where('videoId', $stream->youtube_id)->first();

            if (is_null($streamData)) {
                $stream->update([
                    'status'    => StreamData::STATUS_DELETED,
                    'hidden_at' => Carbon::now(),
                ]);

                return;
            }

            $stream->update([
                'title' => $streamData->title,
                'description' => $streamData->description,
                'channel_title' => $streamData->channelTitle,
                'thumbnail_url' => $streamData->thumbnailUrl,
                'scheduled_start_time' => $streamData->plannedStart,
                'status' => $streamData->status,
                'actual_start_time' => $streamData->actualStartTime,
                'actual_end_time' => $streamData->actualEndTime,
            ]);
        });

        return self::SUCCESS;
    }
}
