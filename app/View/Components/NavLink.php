<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavLink extends Component
{
    public bool $isCurrentRoute = false;

    public function __construct(public string $link, public string $name, public string $routeName){}

    public function render(): View
    {
        return view('components.nav-link');
    }
}
