<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\IcalendarGenerator\Components\Calendar;

class CalendarController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $calendar = Calendar::create()
            ->name('Larastreamers')
            ->description('There is no better way to learn than by watching other developers code live. Find out who is streaming next in the Laravel world.');

        Stream::query()->each(fn(Stream $stream) => $calendar->event(
            $stream->toCalendarItem()
        ));

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar');
    }
}
