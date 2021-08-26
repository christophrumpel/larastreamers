<li class="flex items-center relative space-y-8">
    <a class="w-1/3 flex-none overflow-hidden rounded -mr-12 z-0"
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
    <article class="pl-24 p-10 flex flex-col items-start space-y-2 bg-gray-600 rounded-b-xl lg:rounded-xl">
        <div class="flex items-center">
            <x-local-time class="font-bold tracking-tight text-red-400"
                          :date="$stream->actual_start_time ?? $stream->scheduled_start_time"/>
            @if ($stream->language->shouldRender())
                <span class="block mx-2 text-gray-500">&bull;</span>
                <span class="font-semibold tracking-wider uppercase text-white">{{ $stream->language->name }}</span>
            @endif
        </div>

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

            <p class="text-lg font-medium text-gray-300">
                {{ $stream->channel_title }}
            </p>
        </header>

        @if (!$isArchive)
            <ul class="flex flex-wrap gap-6">
                <li>
                    <a href="{{ $stream->toWebcalLink() }}"
                       class="inline-flex items-center space-x-2 transition hover:text-gray-300">
                        <x-icons.calendar/>
                        <span class="text-sm font-medium">Add to calendar</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('calendar.ics.stream', $stream) }}"
                       class="inline-flex items-center space-x-2 transition hover:text-gray-300">
                        <x-icons.download/>

                        <span class="text-sm font-medium">Download .ics file</span>
                    </a>
                </li>
            </ul>
        @endif
    </article>
</li>
