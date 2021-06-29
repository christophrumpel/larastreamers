<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MainLayout extends Component
{
    public function __construct(public bool $showCalendarDownloads = true, public string $title = 'Larastreamers', public string $description = 'Larastreamers shows you who is live-coding next in the Laravel world. Never miss a live stream again!')
    {
    }

    public function render(): View
    {
        return view('components.main-layout');
    }
}
