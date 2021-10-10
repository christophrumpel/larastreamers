<?php

namespace App\Http\Livewire;

use App\Models\Stream;
use Livewire\Component;
use Livewire\WithPagination;

class StreamListArchive extends Component
{
    use WithPagination;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public ?string $search = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.stream-list-archive', [
            'streams' => Stream::query()
                ->approved()
                ->finished()
                ->search($this->search)
                ->fromLatestToOldest()
                ->paginate(24)
        ]);
    }
}
