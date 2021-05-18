<?php

namespace App\Http\Livewire;

use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\YoutubeException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ImportYoutubeLiveStream extends Component
{
    public $youtubeId;

    public function render(): View
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

        Stream::updateOrCreate(['youtube_id' => $video->videoId], [
            'channel_title' => $video->channelTitle,
            'title' => $video->title,
            'description' => $video->description,
            'thumbnail_url' => $video->thumbnailUrl,
            'scheduled_start_time' => $video->plannedStart,
            'status' => $video->status,
        ]);

        session()->flash('stream-message', 'Stream "'.$this->youtubeId.'" was added successfully.');

        $this->reset(['youtubeId']);
    }
}
