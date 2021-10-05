<li class="relative flex flex-col md:flex-row relative pb-8 md:pb-0 -ml-0 md:-ml-24 xl:-ml-32">
    <a class="w-full md:w-1/2 flex-none md:-mr-12 z-0 md:px-6 md:px-0 mb-2 md:mb-0"
       title="Open on YouTube"
       target="_blank"
       href="{{ $stream->url() }}">

        @if ($stream->isLive())
            <div class="absolute top-2.5 right-2.5 z-10">
                <div
                    class="flex px-2 py-1 space-x-2 bg-gray-900 rounded bg-opacity-80 backdrop-filter backdrop-blur-xl backdrop-saturate-150 place-items-center">
                    <div class="w-2 h-2 bg-red-500 rounded-full">
                        <div class="w-2 h-2 bg-red-500 rounded-full opacity-75 animate-ping"></div>
                    </div>

                    <span class="text-xs font-bold tracking-wider text-gray-100 uppercase">live</span>
                </div>
            </div>
        @endif

        <figure class="group flex items-center  aspect-w-16 aspect-h-9 mb-4 md:mb-0  filter drop-shadow-lg">
            <img class="flex-none overflow-hidden md:-mr-12 z-0 rounded-t-xl md:rounded-xl object-cover transition ease-in-out transform group-hover:-translate-x-10"
                 src="{{ $stream->thumbnail_url }}"
                 alt="Video thumbnail"/>
        </figure>
    </a>
    <article
        class="md:pl-24 px-6 flex flex-col justify-between items-start space-y-4 md:space-y-2 bg-gray-600 rounded-b-xl lg:rounded-xl">

        <header class="flex-1">
            <h3 class="text-2xl font-bold tracking-tight ">
                <a title="Open on YouTube"
                   target="_blank"
                   href="{{ $stream->url() }}">
                    {{ $stream->title }}
                </a>
            </h3>

            <p class="text-base text-gray flex items-center">
                <x-icons.icon-user class="w-4 h-4 mr-2 inline text-gray fill-current stroke-current"/>
                {{ $stream->channel_title }}
            </p>
            <p class="text-base text-gray flex items-center">
                <x-icons.icon-time class="w-4 h-4 mr-2 inline text-gray fill-current stroke-current"/>
                <x-local-time class=""
                              :date="$date = $stream->actual_start_time ?? $stream->scheduled_start_time"
                              :format="$date->isToday() ? 'HH:mm (z)' : 'YYYY-MM-DD HH:mm (z)'"/>
            </p>
            @if ($stream->language->shouldRender())
                <p class="text-base text-gray flex items-center">
                    <x-icons.world class="w-4 h-4 mr-2 inline text-gray fill-current stroke-current"/>
                    <span class="">{{ $stream->language->name }}</span>
                </p>
            @endif
        </header>

        <ul class="w-full sm:w-auto flex flex-wrap gap-3 md:gap-6">
            <li class="w-full sm:w-auto group">
                <x-stream-button link="{{ $stream->toWebcalLink() }}" name="Add to calendar">
                    <x-icons.calendar class="text-red w-6 h-6 text-gray group-hover:text-red"/>
                </x-stream-button>
            </li>

            <li class="w-full sm:w-auto group">
                <x-stream-button link="{{ route('calendar.ics.stream', $stream) }}" name="Download .ics file">
                    <x-icons.download class="w-6 h-6 text-gray group-hover:text-red"/>
                </x-stream-button>
            </li>
        </ul>
    </article>
</li>
