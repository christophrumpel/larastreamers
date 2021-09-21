<?php

namespace App\Http\Livewire;

use App\Actions\SortStreamsByDateAction;
use App\Models\Stream;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class StreamList extends Component
{
    use WithPagination;

    public function render(): View
    {
        $streams = Stream::approved()
            ->upcomingOrLive()->fromOldestToLatest()
            ->paginate(10);

        return view('livewire.stream-list', [
            'streamsByDate' => $streams->setCollection(
                (new SortStreamsByDateAction())->handle($streams->getCollection())
            ),
        ]);
    }
}
