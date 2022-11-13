<?php

namespace App\Enums;

enum TwitchEventSubscription: string
{
    case STREAM_ONLINE = 'stream.online';
    case STREAM_OFFLINE = 'stream.offline';
}
