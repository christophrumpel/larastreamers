<?php

namespace App\Http\Livewire;

use App\Enums\TwitchEventType;
use App\Models\Channel;
use App\Models\Stream;
use App\Models\TwitchChannelSubscription;
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
        $response = $this->getTwitchUser();
        $channel = data_get($response, 'data.0');

        // Store the channel
        $channel = Channel::create([
            'platform' => 'twitch',
            'platform_id' => $channel['id'],
            'name' => $channel['display_name'],
            'url' => "https://www.twitch.tv/{$channel['login']}",
            'description' => $channel['description'],
            'thumbnail_url' => $channel['profile_image_url'],
            'on_platform_since' => now(),
            'auto_import' => true,
        ]);

        // Add a subscription to online offline events
        $subscriptionForOnlineEventResponse = Http::withToken(config('services.twitch.token'))
            ->withHeaders([
                'Client-Id' => config('services.twitch.client_id'),
            ])
            ->post('https://api.twitch.tv/helix/eventsub/subscriptions', [
                'type' => 'stream.online',
                'version' => 1,
                'condition' => ['broadcaster_user_id' => $channel->platform_id],
                'transport' => ["method" => "webhook", "callback" => "https://dapxpgxslj.sharedwithexpose.com/webhooks", "secret" => "1234567890"]
            ])->json();

        TwitchChannelSubscription::create([
            'channel_id' => $channel->id,
            'subscription_event' => TwitchEventType::STREAM_ONLINE,
        ]);

        $subscriptionForOfflineEventResponse = Http::withToken(config('services.twitch.token'))
            ->withHeaders([
                'Client-Id' => config('services.twitch.client_id'),
            ])
            ->post('https://api.twitch.tv/helix/eventsub/subscriptions', [
                'type' => 'stream.offline',
                'version' => 1,
                'condition' => ['broadcaster_user_id' => $channel->platform_id],
                'transport' => ["method" => "webhook", "callback" => "https://dapxpgxslj.sharedwithexpose.com/webhooks", "secret" => "1234567890"]
            ])->json();

        TwitchChannelSubscription::create([
            'channel_id' => $channel->id,
            'subscription_event' => TwitchEventType::STREAM_OFFLINE,
        ]);

        ray($subscriptionForOnlineEventResponse, $subscriptionForOfflineEventResponse);

    }

    protected function getTwitchUser(): mixed
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

    protected function createStreamsFromSegments(mixed $streams, Channel $channel): void
    {
        collect($streams)->each(function (array $stream) use ($channel) {
            Stream::create([
                'stream_id' => $stream['id'],
                'title' => $stream['title'],
                'thumbnail_url' => $channel->thumbnail_url,
                'scheduled_start_time' => $stream['start_time'],
            ]);
        });
    }
}
