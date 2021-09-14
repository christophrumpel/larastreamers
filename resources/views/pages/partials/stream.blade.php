<li class="relative flex relative -ml-24">
    <a class="w-1/2 flex-none overflow-hidden rounded -mr-12 z-0"
       title="Open on YouTube"
       target="_blank"
       href="{{ $stream->url() }}">

        @if (!$isArchive && $stream->isLive())
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

        <figure class="flex items-center overflow-auto rounded-t-xl lg:rounded-xl aspect-w-16 aspect-h-9">
            <img class="flex-none overflow-hidden rounded -mr-12 z-0"
                 src="{{ $stream->thumbnail_url }}"
                 alt="Video thumbnail"/>
        </figure>
    </a>
    <article class="pl-24 px-6 flex flex-col justify-between items-start space-y-2 bg-gray-600 rounded-b-xl lg:rounded-xl">

        @if($stream->duration)
            <div class="flex items-center space-x-2">
                <span class="block text-gray-400 text-sm">Duration:</span>
                <span class="font-semibold tracking-wider uppercase text-sm text-white">{{ $stream->duration }}</span>
            </div>
        @endif

        <header class="flex-1">
            <h3 class="text-2xl font-bold tracking-tight">
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

        @if (!$isArchive)
            <ul class="flex flex-wrap gap-6">
                <li>
                    <x-stream-button link="{{ $stream->toWebcalLink() }}" name="Add to calendar">
                        <x-icons.calendar/>
                    </x-stream-button>
                </li>

                <li>
                    <x-stream-button link="{{ route('calendar.ics.stream', $stream) }}" name="Download .ics file">
                        <x-icons.download/>
                    </x-stream-button>
                </li>
            </ul>
        @endif
    </article>
</li>
