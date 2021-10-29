<?php

namespace App\Console\Commands;

use App\Facades\YouTube;
use App\Models\Channel;
use App\Services\YouTube\ChannelData;
use Illuminate\Console\Command;

class UpdateAllChannelDetails extends Command
{
    protected $signature = 'larastreamers:update-channel-details';

    protected $description = 'Update all channel details';

    public function handle()
    {
        $channels = Channel::query()
            ->get()
            ->keyBy('platform_id');


        if ($channels->isEmpty()) {
            $this->info('There are no channels to update.');

            return self::SUCCESS;
        }

        $this->info("Fetching {$channels->count()} channels(s) from API to update.");

        $channels->chunk(50)->each(function ($channels) {
            $youTubeResponse = YouTube::channels($channels->keys());
            $channels->each(function(Channel $channel) use ($youTubeResponse) {

                /** @var ChannelData|null $channelData */
                $channelData = $youTubeResponse->where('platformId', $channel->platform_id)->first();

                Channel::updateOrCreate(['platform_id' => $channel->platform_id], $channelData->prepareForModel());
            });
        });
    }
}
