<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StreamButton extends Component
{
    public function __construct(public string $link, public string $name){}


    public function render(): View
    {
        return view('components.stream-button');
    }
}
