<?php

namespace App\Providers;

use App\Services\YouTube\YouTubeException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Http::macro('youtube', function(string $url, array $params = [], string $key = null): Collection {
            /** @var Factory $this */
            return $this
                ->asJson()
                ->baseUrl('https://youtube.googleapis.com/youtube/v3/')
                ->get($url, array_merge($params, [
                    'key' => config('services.youtube.key'),
                ]))
                ->onError(fn (Response $response) => throw YouTubeException::general($response->status(), $response->body()))
                ->collect($key);
        });
    }
}
