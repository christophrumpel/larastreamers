<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MainLayout extends Component
{
    public function __construct(public bool $showCalendarDownloads = true)
    {
    }

    public function render(): View
    {
        return view('components.main-layout');
    }
}
