<?php

namespace App\Console\Commands;

use App\Facades\YouTube;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Console\Command;

class CheckIfLiveStreamsHaveEndedCommand extends Command
{
    protected $signature = 'larastreamers:check-if-livestreams-ended';

    protected $description = 'Check if current live streams have already ended and set them to finished.';

    public function handle(): int
    {
        $streams = Stream::query()
            ->live()
            ->get()
            ->keyBy('youtube_id');

        if ($streams->isEmpty()) {
            $this->info('There are no streams to update.');

            return self::SUCCESS;
        }

        $this->info("Fetching {$streams->count()} stream(s) from API to update their status.");

        $updatesCount = YouTube::videos($streams->keys())
            ->map(fn (StreamData $streamData) => optional($streams
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
