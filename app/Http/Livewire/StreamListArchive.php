<?php

namespace App\Http\Livewire;

use App\Models\Stream;
use Livewire\Component;
use Livewire\WithPagination;

class StreamListArchive extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.stream-list-archive', [
            'streams' => Stream::approved()->finished()->fromLatestToOldest()->paginate(24)
        ]);
    }
}
