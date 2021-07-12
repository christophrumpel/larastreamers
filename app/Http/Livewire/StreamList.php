<?php

namespace App\Http\Livewire;

use App\Models\Stream;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class StreamList extends Component
{
    use WithPagination;

    public bool $isArchive = false;

    public function render(): View
    {
        $streams = Stream::approved()
            ->when($this->isArchive, function($builder) {
                $builder->finished()->fromLatestToOldest();
            }, function($builder) {
                $builder->upcoming()->fromOldestToLatest();
            })
            ->paginate(10);

        return view('livewire.stream-list', [
            'streamsByDate' => $streams,
        ]);
    }
}
