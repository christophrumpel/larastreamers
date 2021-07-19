<?php

namespace App\Console\Commands;

use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\StreamData;
use Illuminate\Console\Command;

class CheckIfUpcomingStreamsAreLiveCommand extends Command
{
    protected $signature = 'larastreamers:update-live-streams';

    protected $description = 'Check if upcoming streams are already live and set them live.';

    public function handle(): int
    {
        $streams = Stream::query()
            ->upcoming()
            ->where('scheduled_start_time', '<=', now()->addMinutes(15))
            ->get()
            ->keyBy('youtube_id');

        if ($streams->isEmpty()) {
            $this->info('There are no streams to update.');

            return self::SUCCESS;
        }

        $this->info("Fetching {$streams->count()} stream(s) from API to update.");

        $updatesCount = Youtube::videos($streams->keys())
            ->map(fn(StreamData $streamData) => optional($streams
                ->get($streamData->videoId))
                ->update([
                    'status' => $streamData->status,
                ]))
            ->filter()
            ->count();

        $this->info($updatesCount.' stream(s) were updated.');

        return self::SUCCESS;
    }
}
