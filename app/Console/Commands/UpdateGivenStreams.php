<?php

namespace App\Console\Commands;

use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class UpdateGivenStreams extends Command
{
    protected $signature = 'larastreamers:update-streams {--soon-live-only}';

    protected $description = 'Update all today/upcoming streams';

    public function handle(): int
    {
        $streams = Stream::query()
            ->when($this->option('soon-live-only'),
                fn(Builder $query) => $query
                    ->where('status', StreamData::STATUS_LIVE)
                    ->orWhere('scheduled_start_time', '<=', now()->addMinutes(10)),
                fn(Builder $query) => $query->upcoming()
            )
            ->get()
            ->keyBy('youtube_id');

        if ($streams->isEmpty()) {
            $this->info('There are no streams to update.');

            return self::SUCCESS;
        }

        $this->info("Fetching {$streams->count()} stream(s) from API.");

        $updatesCount = Youtube::videos($streams->keys())
            ->map(fn(StreamData $streamData) => optional($streams
                ->get($streamData->videoId))
                ->update([
                    'title' => $streamData->title,
                    'description' => $streamData->description,
                    'channel_title' => $streamData->channelTitle,
                    'thumbnail_url' => $streamData->thumbnailUrl,
                    'scheduled_start_time' => $streamData->plannedStart,
                    'status' => $streamData->status,
                ]))
            ->filter()
            ->count();

        $this->info($updatesCount.' stream(s) were updated.');

        return self::SUCCESS;
    }
}
