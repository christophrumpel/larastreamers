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

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public bool $isArchive = false;

    public ?string $search = null;

    public function updatedSearch($value): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $streams = Stream::approved()
            ->when($this->isArchive, function(Builder $builder) {
                $builder->finished()->fromLatestToOldest();
            }, function(Builder $builder) {
                $builder->upcoming()->fromOldestToLatest();
            })
            ->when($this->search, function(Builder $builder, $search) {
                $builder->where(function(Builder $builder) use ($search) {
                    $builder->where('title', 'like', "%{$search}%")
                        ->orWhere('channel_title', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('livewire.stream-list', [
            'streamsByDate' => $streams->setCollection(
                (new SortStreamsByDateAction())->handle($streams->getCollection())
            ),
        ]);
    }
}
