<?php

namespace App\View\Components;

use Carbon\Carbon;
use Illuminate\View\Component;

class LocalTime extends Component
{
    public function __construct(public Carbon $date)
    {
        ray('in class', $this->date);
    }

    public function render()
    {
        return view('components.local-time');
    }
}
