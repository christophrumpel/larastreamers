<div>
    <x-page-header title="Archive">
        <x-search wire:model.debounce.300ms="search" />
    </x-page-header>

    <main class="flex-1 text-white bg-gray-darkest">
        <div class="w-full max-w-6xl mx-auto px-6 xl:px-0 py-24 space-y-16" >

           <div>
               @if(count($streams))
                   <section class="space-y-2 -mt-40">
                       <div class="flex w-full max-w-6xl max-auto">
                           <ul class="w-full rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                               @foreach ($streams as $stream)
                                   @include('pages.partials.stream-archive')
                               @endforeach
                           </ul>
                       </div>
                   </section>
               @else
                   <div>
                       <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
                           <h2 class="text-center text-3xl font-bold tracking-tight text-white md:text-4xl">
                               No streams found.
                           </h2>
                       </div>
                   </div>
               @endif
           </div>

            <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
                {{ $streams->links() }}
            </div>
        </div>
    </main>
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
