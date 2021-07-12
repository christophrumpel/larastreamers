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

    public function previousPage()
    {
        $this->setPage(max($this->page - 1, 1));
        $this->emit('scrollToTop');
    }

    public function nextPage()
    {
        $this->setPage($this->page + 1);
        $this->emit('scrollToTop');
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
        $this->emit('scrollToTop');
    }

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
