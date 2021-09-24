<li class="relative flex flex-col relative bg-gray-darker rounded-xl overflow-hidden">
    <a class="w-full flex-none overflow-hidden rounded z-0 md:px-0 mb-2 md:mb-0"
       title="Open on YouTube"
       target="_blank"
       href="{{ $stream->url() }}">

        <figure class="flex items-center overflow-auto rounded-t-xl lg:rounded-xl aspect-w-16 aspect-h-9">
            <img class="flex-none overflow-hidden rounded md:-mr-12 z-0"
                 src="{{ $stream->thumbnail_url }}"
                 alt="Video thumbnail"/>
        </figure>
    </a>
    <article class="px-6 py-4 flex flex-col justify-between items-start space-y-4 md:space-y-2 bg-gray-600 rounded-b-xl lg:rounded-xl">

        @if($stream->duration)
            <div class="flex items-center space-x-2">
                <span class="block text-gray-400 text-sm">Duration:</span>
                <span class="font-semibold tracking-wider uppercase text-sm text-white">{{ $stream->duration }}</span>
            </div>
        @endif

        <header class="flex-1">
            <h3 class="text-xl font-bold tracking-tight mb-2">
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
    </article>
</li>
