<?php

namespace App\Console\Commands;

use App\Jobs\ImportYoutubeChannelStreamsJob;
use App\Models\Channel;
use Illuminate\Console\Command;

class ImportChannelStreamsCommand extends Command
{
    protected $signature = 'larastreamers:import-channel-streams';

    protected $description = 'Command description';

    public function handle(): int
    {
        Channel::all()
            ->each(fn(Channel $channel) => dispatch(new ImportYoutubeChannelStreamsJob($channel->platform_id, $channel->language_code)));

        return self::SUCCESS;
    }
}
