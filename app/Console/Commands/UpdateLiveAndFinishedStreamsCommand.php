<?php

namespace App\Console\Commands;

use App\Facades\YouTube;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Actions\UpdateStreamAction;

class UpdateLiveAndFinishedStreamsCommand extends Command
{
    protected $signature = 'larastreamers:update-live-and-finished-streams';

    protected $description = 'Update latest 50 live & finished streams.';

    public function handle(): int
    {
        $streams = Stream::query()
            ->approved()
            ->liveOrFinished()
            ->fromLatestToOldest()
            ->limit(50)
            ->get()
            ->keyBy('youtube_id');

        if ($streams->isEmpty()) {
            $this->info('There are no streams to update.');

            return self::SUCCESS;
        }

        $this->info("Fetching {$streams->count()} stream(s) from API to update.");

        $youTubeResponse = YouTube::videos($streams->keys());

        $streams->each(function(Stream $stream) use ($youTubeResponse) {
            $this->info("Updating {$stream->youtube_id} ...");

            /** @var StreamData|null $streamData */
            $streamData = $youTubeResponse->where('videoId', $stream->youtube_id)->first();

            if (is_null($streamData)) {
                $stream->update([
                    'status'    => StreamData::STATUS_DELETED,
                    'hidden_at' => Carbon::now(),
                ]);

                return;
            }

            (new UpdateStreamAction)->handle($stream, $streamData);
        });

        return self::SUCCESS;
    }
}
