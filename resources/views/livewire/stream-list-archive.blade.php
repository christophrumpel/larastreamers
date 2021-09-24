<div class="w-full max-w-6xl mx-auto py-24 space-y-16" id="scrollTop">

    <section class="space-y-2 -mt-48">
        <div class="flex w-full max-w-6xl max-auto">
            <ul class="w-full rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($streams as $stream)
                    @include('pages.partials.stream-archive')
                @endforeach
            </ul>
        </div>
    </section>

    <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
        {{ $streams->links() }}
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
