<?php

namespace App\Http\Livewire;

use App\Facades\Youtube;
use App\Jobs\ImportYoutubeChannelStreamsJob;
use App\Models\Channel;
use App\Services\Youtube\YoutubeException;
use Livewire\Component;

class ImportYoutubeChannel extends Component
{
    public $youtubeChannelId;
    public $languageCode = 'en';

    public function importChannel(): void
    {
        try {
            $channelData = Youtube::channel($this->youtubeChannelId);
        } catch (YoutubeException $exception) {
            $this->addError('channel', $exception->getMessage());

            return;
        }
        Channel::updateOrCreate(array_merge($channelData->prepareForModel(), ['language_code' => $this->languageCode]));

        dispatch(new ImportYoutubeChannelStreamsJob($this->youtubeChannelId, $this->languageCode));

        session()->flash('channel-message', 'Channel "'.$this->youtubeChannelId.'" was added successfully.');

        $this->reset(['youtubeChannelId', 'languageCode']);
    }
}
