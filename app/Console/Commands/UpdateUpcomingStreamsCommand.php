<?php

namespace App\Console\Commands;

use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateUpcomingStreamsCommand extends Command
{
    protected $signature = 'larastreamers:update-upcoming-streams';

    protected $description = 'Update all upcoming streams.';

    public function handle(): int
    {
        $streams = Stream::query()
            ->approved()
            ->upcoming()
            ->get()
            ->keyBy('youtube_id');

        if ($streams->isEmpty()) {
            $this->info('There are no streams to update.');

            return self::SUCCESS;
        }

        $this->info("Fetching {$streams->count()} stream(s) from API to update.");

        $youtubeResponse = Youtube::videos($streams->keys());

        $streams->map(function (Stream $stream) use ($youtubeResponse) {

            /** @var StreamData|null $streamData */
            $streamData = $youtubeResponse->where('videoId', $stream->youtube_id)->first();

            if (is_null($streamData)) {
                $stream->update([
                    'status' => StreamData::STATUS_DELETED,
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
            ]);
        });

        $this->info($streams->count() . ' stream(s) were updated.');

        return self::SUCCESS;
    }
}
