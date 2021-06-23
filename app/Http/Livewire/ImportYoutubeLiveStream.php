<?php

namespace App\Http\Livewire;

use App\Actions\ImportVideo;
use App\Facades\Youtube;
use App\Models\Stream;
use App\Services\Youtube\YoutubeException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ImportYoutubeLiveStream extends Component
{
    public $youtubeId;
    public $language = 'en';

    public function render(): View
    {
        return view('livewire.import-youtube-live-stream');
    }

    public function importStream()
    {
        try {
            (new ImportVideo())->handle($this->youtubeId, $this->language, approved: true);
        } catch (YoutubeException $exception) {
            return $this->addError('stream', $exception->getMessage());
        }

        session()->flash('stream-message', 'Stream "'.$this->youtubeId.'" was added successfully.');

        $this->reset(['youtubeId']);
    }
}
