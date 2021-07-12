<div class="py-16 space-y-16">
    @foreach ((new \App\Actions\SortStreamsByDateAction())->handle($streamsByDate->getCollection()) as $date => $streams)
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
    @endforeach

    <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
        {{ $streamsByDate->links() }}
    </div>
</div>
