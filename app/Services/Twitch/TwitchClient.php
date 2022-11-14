<?php

namespace App\Services\Twitch;

use Illuminate\Support\Facades\Http;

class TwitchClient
{

    public function channel(string $broadcasterId): TwitchChannelData
    {
        $channelResponse = Http::withToken(config('services.twitch.token'))
            ->withHeaders([
                'Client-Id' => config('services.twitch.client_id'),
            ])
            ->get('https://api.twitch.tv/helix/channels', [
                'broadcaster_id' => $broadcasterId,
            ])->json();

        return new TwitchChannelData(
            broadcasterId: $broadcasterId,
            broadcasterLogin: $channelResponse['data'][0]['broadcaster_login'],
            broadcasterName: $channelResponse['data'][0]['broadcaster_name'],
            gameName: $channelResponse['data'][0]['game_name'],
            gameId: $channelResponse['data'][0]['game_id'],
            title: $channelResponse['data'][0]['title'],
            delay: $channelResponse['data'][0]['delay'],
        );
    }
}
