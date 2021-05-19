<li class="grid lg:grid-cols-[1fr,2fr] lg:gap-4">
    <a class="relative transition focus:ring-4 focus:ring-red-400 focus:outline-none rounded-t-xl lg:rounded-xl"
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

        <figure class="overflow-auto rounded-t-xl lg:rounded-xl aspect-w-16 aspect-h-9">
            <img class="object-cover w-full h-full"
                src="{{ $stream->thumbnail_url }}"
                alt="Video thumbnail" />
        </figure>
    </a>

    <article class="flex flex-col items-start p-6 space-y-2 bg-gray-600 rounded-b-xl lg:rounded-xl">
        <x-local-time class="inline-block font-bold tracking-tight text-red-400"
            :date="$stream->scheduled_start_time" />

        <header class="flex-1">
            <h3 class="text-2xl font-bold tracking-tight">
               <a  title="Open on YouTube"
                   target="_blank"
                   href="{{ $stream->url() }}">
                    {{ $stream->title }}
                </a>
            </h3>

            <p class="text-lg font-medium text-gray-300">
                {{ $stream->channel_title }}
            </p>
        </header>

        <ul class="flex flex-wrap gap-6">
            <li>
                <a href="{{ route('calendar.ics.stream', $stream) }}"
                    class="inline-flex items-center space-x-2 transition hover:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-gray-300"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>

                    <span class="text-sm font-medium">Add to calendar</span>
                </a>
            </li>
        </ul>
    </article>
</li>
