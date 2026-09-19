<?php

namespace App\Console\Commands;

use App\Actions\UpdateStreamAction;
use App\Facades\YouTube;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Carbon\Carbon;
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

        $youTubeResponse = YouTube::videos($streams->keys());

        $streams->each(function(Stream $stream) use ($youTubeResponse) {
            /** @var StreamData|null $streamData */
            $streamData = $youTubeResponse->where('videoId', $stream->youtube_id)->first();

            if (is_null($streamData)) {
                $stream->update([
                    'status' => StreamData::STATUS_DELETED,
                    'hidden_at' => Carbon::now(),
                ]);

                return;
            }

            (new UpdateStreamAction)->handle($stream, $streamData);
        });

        $this->info($streams->count().' stream(s) were updated.');

        return self::SUCCESS;
    }
}
