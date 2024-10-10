<?php

namespace App\Facades;

use App\Services\Twitter\OAuthTwitter;
use App\Services\Twitter\TwitterFake;
use App\Services\Twitter\TwitterInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @see OAuthTwitter
 * @see TwitterFake
 */
class Twitter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TwitterInterface::class;
    }

    public static function fake(): void
    {
        self::swap(new TwitterFake);
    }
}
