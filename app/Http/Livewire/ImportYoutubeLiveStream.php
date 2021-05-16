<?php

namespace App\Http\Livewire;

use Alaouy\Youtube\Facades\Youtube;
use App\Models\Stream;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ImportYoutubeLiveStream extends Component
{
    public $youtubeId;

    public function render(): View
    {
        return view('livewire.import-youtube-live-stream');
    }

    public function importStream(): void
    {
        $video = Youtube::getVideoInfo($this->youtubeId, ['snippet', 'liveStreamingDetails']);
        if(!isset($video->snippet->liveBroadcastContent) || $video->snippet->liveBroadcastContent !== 'upcoming') {
            $this->addError('stream', 'This video is not an upcoming stream');

            return;
        }

        Stream::updateOrCreate(['youtube_id' => $video->id], [
            'youtube_id' => $video->id,
            'channel_title' => $video->snippet->channelTitle,
            'title' => $video->snippet->title,
            'thumbnail_url' => $video->snippet->thumbnails->maxres->url,
            'scheduled_start_time' => Carbon::create($video->liveStreamingDetails->scheduledStartTime)->timezone('Europe/Vienna')
        ]);
    }
}
