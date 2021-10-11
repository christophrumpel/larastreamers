<?php

namespace App\Http\Livewire;

use App\Actions\ImportVideoAction;
use App\Services\YouTube\YouTubeException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ImportYouTubeLiveStream extends Component
{
    public $youTubeId;
    public $language = 'en';

    public static function getName(): string
    {
        return 'import-youtube-live-stream';
    }

    public function render(): View
    {
        return view('livewire.import-youtube-live-stream');
    }

    public function importStream()
    {
        try {
            (new ImportVideoAction())->handle($this->youTubeId, $this->language, approved: true);
        } catch (YouTubeException $exception) {
            return $this->addError('stream', $exception->getMessage());
        }

        session()->flash('stream-message', 'Stream "'.$this->youTubeId.'" was added successfully.');

        $this->reset(['youTubeId']);
    }
}
