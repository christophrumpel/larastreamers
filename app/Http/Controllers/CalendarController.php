<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Spatie\IcalendarGenerator\Components\Calendar;

class CalendarController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $calendar = Calendar::create()
            ->name('Larastreamers')
            ->description('There is no better way to learn than by watching other developers code live. Find out who is streaming next in the Laravel world.')
            ->refreshInterval(CarbonInterval::hour()->totalMinutes)
            ->productIdentifier('larastreamers.com');

        Stream::query()
            ->when(
                $request->get('languages'),
                fn($query, $languages) => $query->whereIn('language_code', Arr::wrap(explode(',', $languages)))
            )
            ->notOlderThanAYear()
            ->each(fn(Stream $stream) => $calendar->event(
                $stream->toCalendarItem()
            ));

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar');
    }
}
