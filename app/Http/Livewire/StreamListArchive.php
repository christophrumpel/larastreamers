<?php

namespace App\Http\Livewire;

use App\Models\Channel;
use App\Models\Stream;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class StreamListArchive extends Component
{
    use WithPagination;

    /** @var string[][] */
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
            ->with('channel:id,name')
            ->approved()
            ->finished()
            ->search($this->search)
            ->byStreamer($this->streamer)
            ->fromLatestToOldest()
            ->paginate(24);

        // @phpstan-ignore-next-line
        $channels = Channel::has('approvedFinishedStreams')->select(['id', 'name'])->get()->pluck('name', 'hashid');

        return view('livewire.stream-list-archive', [
            'streams' => $streams,
            'channels' => $channels,

        ]);
    }
}
