<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AddStreamsToCalendar extends Component
{
    public string $webcalLink;

    public function __construct()
    {
        /** @var string[] $url */
        $url = parse_url(route('calendar.ics'));
        $webcalLink = "webcal://{$url['host']}{$url['path']}";

        $this->webcalLink = $webcalLink;
    }

    public function render()
    {
        return view('components.add-streams-to-calendar');
    }
}
