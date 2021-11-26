<?php

namespace App\Http\Livewire;

use App\Facades\YouTube;
use App\Jobs\ImportYoutubeChannelStreamsJob;
use App\Models\Channel;
use App\Services\YouTube\YouTubeException;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ImportYouTubeChannel extends Component
{
    public string $youTubeChannelId = '';
    public string $languageCode = 'en';

    public static function getName(): string
    {
        return 'import-youtube-channel';
    }

    public function render(): View
    {
        return view('livewire.import-youtube-channel');
    }

    public function importChannel(): void
    {
        try {
            $channelData = YouTube::channel($this->youTubeChannelId);
        } catch (YouTubeException $exception) {
            $this->addError('channel', $exception->getMessage());

            return;
        }
        Channel::updateOrCreate(array_merge($channelData->prepareForModel(), ['language_code' => $this->languageCode]));

        dispatch(new ImportYoutubeChannelStreamsJob($this->youTubeChannelId, $this->languageCode));

        session()->flash('channel-message', 'Channel "'.$this->youTubeChannelId.'" was added successfully.');

        $this->reset(['youTubeChannelId', 'languageCode']);
    }
}
