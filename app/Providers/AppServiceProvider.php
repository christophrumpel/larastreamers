<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $url = parse_url(route('calendar.ics'));
            $webcalLink = "webcal://{$url['host']}{$url['path']}";
            $view->with('webcalLink', $webcalLink);
        });
    }
}
