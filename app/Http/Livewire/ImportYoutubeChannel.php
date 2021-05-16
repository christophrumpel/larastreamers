<?php

namespace App\Http\Livewire;

use App\Facades\Youtube;
use App\Models\Channel;
use Livewire\Component;

class ImportYoutubeChannel extends Component
{
    public $youtubeChannelId;

    public function importChannel(): void
    {
        $response = Youtube::channel($this->youtubeChannelId);
        Channel::updateOrCreate($response->prepareForModel());
    }
}
