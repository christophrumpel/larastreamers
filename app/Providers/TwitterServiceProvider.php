<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\TwitterInterface;
use App\Services\Twitter\TwitterManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TwitterOAuth::class, function(): TwitterOAuth {
            return new TwitterOAuth(
                config('services.twitter.consumer_key'),
                config('services.twitter.consumer_secret'),
                config('services.twitter.access_token'),
                config('services.twitter.access_token_secret')
            );
        });

        $this->app->bind(TwitterInterface::class, function(Application $app): TwitterInterface {
            return (new TwitterManager($app))->driver();
        });
    }
}
