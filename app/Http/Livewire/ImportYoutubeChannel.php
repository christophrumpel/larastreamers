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

    public function importChannel()
    {
        try {
            $channelData = Youtube::channel($this->youtubeChannelId);
        } catch (YoutubeException $exception) {
            return $this->addError('channel', $exception->getMessage());
        }


        Channel::updateOrCreate($channelData->prepareForModel());

        dispatch(new ImportYoutubeChannelStreamsJob($this->youtubeChannelId));

        session()->flash('channel-message', 'Channel "'. $this->youtubeChannelId . '" was added successfully.');

        $this->reset(['youtubeChannelId']);
    }
}
