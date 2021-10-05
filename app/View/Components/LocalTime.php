<?php

namespace App\View\Components;

use Carbon\Carbon;
use Illuminate\View\Component;

class LocalTime extends Component
{
    public function __construct(public Carbon $date, public string $format = 'YYYY-MM-DD HH:mm (z)')
    {
    }

    public function render()
    {
        return view('components.local-time');
    }
}
