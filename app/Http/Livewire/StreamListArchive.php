<?php

namespace App\Http\Livewire;

use App\Models\Stream;
use Livewire\Component;

class StreamListArchive extends Component
{
    public function render()
    {
        return view('livewire.stream-list-archive', ['streams' => Stream::approved()->finished()->fromLatestToOldest()->paginate(20)]);
    }
}
