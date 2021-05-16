<?php

namespace App\Http\Livewire;

use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\YoutubeException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ImportYoutubeLiveStream extends Component
{
    public $youtubeId;

    public function render(): Factory|View|Application
    {
        return view('livewire.import-youtube-live-stream');
    }

    public function importStream()
    {
        try {
            $video = Youtube::video($this->youtubeId);
        } catch (YoutubeException $exception) {
            return $this->addError('stream', $exception->getMessage());
        }

        //return $this->addError('stream', 'This video is not an upcoming stream');

        Stream::updateOrCreate(['youtube_id' => $video->videoId], [
            'channel_title' => $video->channelTitle,
            'title' => $video->title,
            'thumbnail_url' => $video->thumbnailUrl,
            'scheduled_start_time' => $video->plannedStart->timezone('Europe/Vienna'),
        ]);
    }
}
