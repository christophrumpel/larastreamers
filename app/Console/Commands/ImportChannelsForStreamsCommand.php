<?php

namespace App\Console\Commands;

use App\Facades\YouTube;
use App\Models\Channel;
use App\Models\Stream;
use App\Services\YouTube\StreamData;
use Illuminate\Console\Command;

class ImportChannelsForStreamsCommand extends Command
{
    protected $signature = 'larastreamers:import-streams-channels {stream?}';

    protected $description = 'Imports channels for given streams.';

    public function handle(): int
    {
        $streamsToImportChannels = $this->argument('stream') ? collect([$this->argument('stream')]) : Stream::whereNull('channel_id')
            ->approved()
            ->limit(50)
            ->get();

        if ($streamsToImportChannels->isEmpty()) {
            $this->info('There are no streams without a channel.');

            return self::SUCCESS;
        }

        $this->info("Fetching {$streamsToImportChannels->count()} stream(s) from API to check for channel.");

        $youTubeResponse = YouTube::videos($streamsToImportChannels->pluck('youtube_id'));

        $this->info("Found {$youTubeResponse->count()} stream(s) from API.");

        if ($youTubeResponse->isEmpty()) {
            $this->info('No channels were imported or updated.');

            return self::SUCCESS;
        }

        $youTubeResponse->each(function(StreamData $streamData) {
            // Import new channel
            $channelData = YouTube::channel($streamData->channelId);
            $channel = Channel::updateOrCreate(['platform_id' => $channelData->platformId], array_merge($channelData->prepareForModel(), ['language_code' => 'en']));
            $stream = Stream::where('youtube_id', $streamData->videoId)->first();
            $stream?->update(['channel_id' => $channel->id]);
        });

        $this->info($streamsToImportChannels->count().' stream channels were updated or imported.');

        return self::SUCCESS;
    }
}
