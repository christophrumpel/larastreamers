<?php

namespace App\Http\Livewire;

use App\Models\Channel;
use App\Models\Stream;
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
        $channel = Channel::create([
            'platform' => 'twitch',
            'platform_id' => $channel['id'],
            'name' => $channel['display_name'],
            'youtube_custom_url' => "https://www.twitch.tv/{$channel['login']}",
            'description' => $channel['description'],
            'thumbnail_url' => $channel['profile_image_url'],
            'on_platform_since' => now(),
        ]);

        // Get stream segments
        $response = $this->getStreamSegments($channel);
        $streams = data_get($response, 'data.segments');

        // Store stream segments
        collect($streams)->each(function(array $stream) use ($channel) {
            Stream::create([
                'stream_id' => $stream['id'],
                'title' => $stream['title'],
                'thumbnail_url' => $channel->thumbnail_url,
                'scheduled_start_time' => $stream['start_time'],
            ]);
        });
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

    protected function getStreamSegments(Channel $channel): mixed
    {
        return Http::withToken(config('services.twitch.token'))
            ->withHeaders([
                'Client-Id' => config('services.twitch.client_id'),
            ])
            ->get('https://api.twitch.tv/helix/schedule', [
                'broadcaster_id' => $channel->platform_id,
            ])->json();
    }
}
