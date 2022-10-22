<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class SubmitTwitchLiveStream extends Component
{

    public $twitchChannel;

    public $twitchStreamName;

    protected $crClientId = 'gdr0c96kxmxg449n8ba1ofngzm4jgb';
    protected $crClientSecret = 'h0yrtn6356b4o5wahvtybcqqb9gmdv';
    protected $crToken = 'rmsjje7jlmr2i7iu8tmgymrhfcdux1';

    public function render()
    {
        return view('livewire.submit-twitch-live-stream');
    }

    public function submit(): void
    {
        $broadcasterId = $this->getBroadcasterId();
        $channelSchedule = $this->getChannelSchedule($broadcasterId);

        dd(collect($channelSchedule['data']['segments'])
            ->firstWhere(function(array $segment) {
                return Str::of($segment['title'])->contains($this->twitchStreamName);
            }));
    }

    private function getBroadcasterId()
    {
        return Http::withToken($this->crToken)
            ->withHeaders([
                'Client-Id' => $this->crClientId,
            ])
            ->get('https://api.twitch.tv/helix/users', [
                'login' => $this->twitchChannel,
            ])->json()['data'][0]['id'];
    }

    private function getChannelSchedule(string $broadcasterId)
    {
        return Http::withToken($this->crToken)
            ->withHeaders([
                'Client-Id' => $this->crClientId,
            ])
            ->get('https://api.twitch.tv/helix/schedule', [
                'broadcaster_id' => $broadcasterId,
            ])->json();
    }


}
