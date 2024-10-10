<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputError extends Component
{
    public function __construct(public string $message) {}

    public function render(): View
    {
        return view('components.input-error');
    }
}
