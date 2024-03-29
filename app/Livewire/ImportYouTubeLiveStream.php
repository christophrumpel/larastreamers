<?php

namespace App\Livewire;

use App\Actions\ImportVideoAction;
use App\Services\YouTube\YouTubeException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ImportYouTubeLiveStream extends Component
{
    public string $youTubeId = '';
    public string $language = 'en';

    public function render(): View
    {
        return view('livewire.import-you-tube-live-stream');
    }

    public function importStream(): void
    {
        try {
            (new ImportVideoAction())->handle($this->youTubeId, $this->language, approved: true);
        } catch (YouTubeException $exception) {
            $this->addError('stream', $exception->getMessage());

            return;
        }

        session()->flash('stream-message', 'Stream "'.$this->youTubeId.'" was added successfully.');

        $this->reset(['youTubeId']);
    }
}
