<?php

namespace App\Http\Livewire;

use App\Models\Channel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ImportTwitchChannel extends Component
{

    public string $channelName = '';

    public function render(): View
    {
        return view('livewire.import-twitch-channel');
    }

    public function importChannel(): void
    {
        // Get channel
        $response = $this->getChannel();
        $channel = data_get($response, 'data.0');

        // Store the channel
        Channel::create([
            'platform' => 'twitch',
            'platform_id' => $channel['login'],
            'name' => $channel['display_name'],
            'youtube_custom_url' => "https://www.twitch.tv/aarondfrancis/{$channel['login']}",
            'description' => $channel['description'],
            'thumbnail_url' => $channel['profile_image_url'],
            'on_platform_since' => now(),
        ]);
    }

    protected function getChannel(): mixed
    {
        // TODO: Handle exception and return type
        return Http::withToken(config('services.twitch.token'))
            ->withHeaders([
                'Client-Id' => config('services.twitch.client_id'),
            ])
            ->get('https://api.twitch.tv/helix/users', [
                'login' => $this->channelName,
            ])->json();
    }
}
