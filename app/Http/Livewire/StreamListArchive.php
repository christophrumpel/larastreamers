<?php

namespace App\Http\Livewire;

use App\Models\Stream;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class StreamListArchive extends Component
{
    use WithPagination;

    protected $queryString = [
        'search' => ['except' => ''],
        'streamer' => ['except' => ''],
    ];

    public ?string $search = null;

    public ?string $streamer = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {

        $streams = Stream::query()
            ->approved()
            ->finished()
            ->search($this->search)
            ->byStreamer($this->streamer)
            ->fromLatestToOldest()
            ->paginate(24);

        return view('livewire.stream-list-archive', [
            'streams' => $streams,
        ]);
    }
}
