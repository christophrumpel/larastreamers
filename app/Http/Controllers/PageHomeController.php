<?php

namespace App\Http\Controllers;

use App\Actions\CollectTimezones;
use App\Actions\PrepareStreams;
use App\Models\Stream;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PageHomeController extends Controller
{
    public function __invoke(Request $request): Factory|View|Application
    {
        $currentTimezone = $this->getCurrentTimezone($request);

        $streamsByDate = app(PrepareStreams::class)
            ->handle(Stream::upcoming()->get(), $currentTimezone);

        $timezones = app(CollectTimezones::class)->handle();

        return view('pages.home', [
            'streamsByDate' => $streamsByDate,
            'currentTimezone' => $currentTimezone,
            'timezones' => $timezones
        ]);
    }

    private function getCurrentTimezone(Request $request): mixed
    {
        return $request->get('timezone') ?? Http::get("https://freegeoip.app/json/{$request->ip()}")->json('time_zone');
    }
}
