<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HomeLayout extends Component
{

    public function __construct(public array $timezones, public string $currentTimezone) {}


    public function render()
    {
        return view('components.layout');
    }
}
