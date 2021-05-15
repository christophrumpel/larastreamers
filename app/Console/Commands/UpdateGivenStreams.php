<?php

namespace App\Console\Commands;

use Alaouy\Youtube\Facades\Youtube;
use App\Models\Stream;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateGivenStreams extends Command
{
    protected $signature = 'larastreamers:update-streams';

    protected $description = 'Command description';

    public function handle()
    {
        $streams = Stream::upcoming()->get();

        if($streams->isEmpty()) {
            return $this->info('There are no streams in the database.');
        }

        $updatesCount = $streams
            ->map(function (Stream $stream) {
            $video = Youtube::getVideoInfo($stream->youtube_id, ['snippet', 'liveStreamingDetails']);

            return $stream->update([
                'channel_title' => $video->snippet->channelTitle,
                'title' => $video->snippet->title,
                'thumbnail_url' => $video->snippet->thumbnails->maxres->url,
                'scheduled_start_time' => Carbon::create($video->liveStreamingDetails->scheduledStartTime)->timezone('Europe/Vienna')
            ]);
        })
            ->filter(fn(bool $updated) => $updated)
            ->count();

        $this->info($updatesCount . ' stream(s) were updated.');
    }
}
