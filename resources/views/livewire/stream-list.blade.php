<div class="w-full max-w-6xl mx-auto py-24 space-y-16" id="scrollTop">
    @foreach ($streamsByDate as $date => $streams)
        <section class="space-y-2">
            <header
                class="sticky top-0 z-20 py-4 bg-gray-700 bg-opacity-90">
                <div class="w-full max-w-6xl px-4  sm:px-6 md:px-8">
                    <h2 class="text-3xl font-bold tracking-tight text-red-400 md:text-4xl text-white">
                        {{ $date }}
                    </h2>
                </div>
            </header>

            <div class="flex w-full max-w-6xl max-auto">
                <ul class="bg-gray-darker w-full max-w-4xl rounded-2xl ml-32 py-8 space-y-12">
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

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (message, component) => {
                let scrollTop = document.getElementById("scrollTop")
                window.scrollTo({top: scrollTop.offsetTop, left: 0, behaviour: 'smooth'})
            })
        })
    </script>
@endpush
