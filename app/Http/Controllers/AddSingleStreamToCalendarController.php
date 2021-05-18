<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Spatie\IcalendarGenerator\Components\Calendar;

class AddSingleStreamToCalendarController extends Controller
{
    public function __invoke(Stream $stream)
    {
        $calendar = Calendar::create()
            ->name("Larastreamers: watch {$stream->title}")
            ->description('There is no better way to learn than by watching other developers code live. Find out who is streaming next in the Laravel world.');

        $calendar->event($stream->toCalendarItem());

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar');
    }
}
