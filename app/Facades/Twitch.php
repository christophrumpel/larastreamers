<?php

namespace App\Facades;

use App\Services\Twitch\TwitchClient;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\TwitchClient
 */
class Twitch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TwitchClient::class;
    }
}
