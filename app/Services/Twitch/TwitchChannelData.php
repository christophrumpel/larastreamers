<?php

namespace App\Services\Twitch;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class TwitchChannelData extends DataTransferObject
{
    public string $broadcasterId;
    public string $broadcasterLogin;
    public string $broadcasterName;
    public string $gameName;
    public string $gameId;
    public string $title;
    public string $delay;

    public function prepareForModel(): array
    {
        return [
            'platform' => 'twitch',
            'platform_id' => $this->broadcasterId,
            'name' => $this->broadcasterName,
            'url' => "https://www.twitch.tv/{$this->broadcasterLogin}",
            'description' => '',
            'auto_import' => true,
        ];
    }
}
