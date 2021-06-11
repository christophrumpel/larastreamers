<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class StreamList extends Component
{

    public $streamsByDate;

    public $isArchive = false;

    public function render(): View
    {
        return view('livewire.stream-list');
    }
}
