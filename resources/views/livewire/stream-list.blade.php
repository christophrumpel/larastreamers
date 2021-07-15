<div class="py-16 space-y-16" id="scrollTop">
    @if($isArchive)
        <div class="w-full max-w-xl px-4 mx-auto sm:px-6 md:px-8">
            <div>
                <label for="search" class="sr-only">Search</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" aria-hidden="true">
                        <!-- Heroicon name: solid/search -->
                        <svg class="mr-3 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.debounce.300ms="search" type="search" name="search" id="search" class="focus:ring-red-400 focus:border-red-400 block w-full pl-9 shadow-sm sm:text-sm border-gray-300 rounded-md text-gray-600" placeholder="Search">
                </div>
            </div>
        </div>
    @endif

    @forelse($streamsByDate as $date => $streams)
        <section class="space-y-2">
            <header
                class="sticky top-0 z-20 py-4 bg-gray-700 bg-opacity-90 backdrop-filter backdrop-blur-lg backdrop-saturate-150">
                <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
                    <h2 class="text-3xl font-bold tracking-tight text-red-400 md:text-4xl">
                        {{ $date }}
                    </h2>
                </div>
            </header>

            <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
                <ul class="grid gap-4 md:grid-cols-2 lg:grid-cols-1">
                    @foreach ($streams as $stream)
                        @include('pages.partials.stream')
                    @endforeach
                </ul>
            </div>
        </section>
    @empty
        <div>
            <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
                <h2 class="text-center text-3xl font-bold tracking-tight text-white md:text-4xl">
                    No streams found.
                </h2>
            </div>
        </div>
    @endforelse

    <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
        {{ $streamsByDate->links() }}
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (message, component) => {
                let scrollTop = document.getElementById("scrollTop")
                window.scrollTo({ top: scrollTop.offsetTop, left: 0, behaviour: 'smooth' })
            })
        })
    </script>
@endpush
