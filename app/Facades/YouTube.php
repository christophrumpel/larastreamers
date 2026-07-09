<?php

namespace App\Facades;

use App\Services\YouTubeClient;
use Illuminate\Support\Facades\Facade;

/**
 * @see YouTubeClient
 */
class YouTube extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return YouTubeClient::class;
    }
}
