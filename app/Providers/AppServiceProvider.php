<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use App\Services\YouTube\YouTubeException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

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

        $this->bootRoute();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function bootRoute(): void
    {
        RateLimiter::for('api', function(Request $request) {
            return Limit::perMinute(60)->by((string) $request->user()?->id ?: (string) $request->ip());
        });

        
    }
}
