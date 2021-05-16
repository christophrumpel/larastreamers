<?php

namespace App\Facades;

use App\Services\YoutubeClient;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\YoutubeClient
 */
class Youtube extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return YoutubeClient::class;
    }
}
