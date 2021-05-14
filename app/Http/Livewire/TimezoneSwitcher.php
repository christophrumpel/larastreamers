<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TimezoneSwitcher extends Component
{

    public $timezones;

    public $currentTimezone;

    public function render()
    {
        return view('livewire.timezone-switcher');
    }

    public function updatedCurrentTimezone(string $timezone): void
    {
        $this->redirect(route('home', ['timezone' => $timezone]));
    }
}
