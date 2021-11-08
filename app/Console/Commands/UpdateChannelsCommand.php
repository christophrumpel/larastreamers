<?php

namespace App\Console\Commands;

use App\Facades\YouTube;
use App\Models\Channel;
use App\Services\YouTube\ChannelData;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UpdateChannelsCommand extends Command
{
    protected $signature = 'larastreamers:update-channels';

    protected $description = 'Update all channels.';

    public function handle(): int
    {
        $channels = Channel::query()
            ->get()
            ->keyBy('platform_id');

        if ($channels->isEmpty()) {
            $this->info('There are no channels to update.');

            return self::SUCCESS;
        }

        $this->info("Fetching {$channels->count()} channels(s) from API to update.");

        $channels
            ->chunk(50)
            ->each(function (Collection $channels) {
                $youTubeResponse = YouTube::channels($channels->keys());
                $channels->each(function (Channel $channel) use ($youTubeResponse) {

                    /** @var ChannelData|null $channelData */
                    $channelData = $youTubeResponse->where('platformId', $channel->platform_id)->first();

                    if ($channelData) {
                        Channel::where('platform_id', $channel->platform_id)
                            ->first()
                            ?->update($channelData->prepareForModel());
                    }
                });
            });

        return self::SUCCESS;
    }
}
