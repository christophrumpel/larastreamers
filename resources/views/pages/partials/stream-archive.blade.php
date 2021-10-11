<li class="relative flex flex-col relative bg-gray-darker rounded-xl overflow-hidden transition ease-in-out transform hover:-translate-y-5">
    <a class="w-full flex-none overflow-hidden z-0 md:px-0 mb-2 md:mb-0"
       title="Open on YouTube"
       target="_blank"
       href="{{ $stream->url() }}">

        <div class="relative">
            <figure class="overflow-auto aspect-w-16 aspect-h-9">
                <img class="overflow-hidden md:-mr-12 z-0 object-cover"
                     src="{{ $stream->thumbnail_url }}"
                     alt="Video thumbnail"/>
            </figure>
            @if($stream->duration)
                <div class="absolute flex left-6 bottom-4 px-3 py-2 rounded-md space-x-2 bg-gray-darker bg-opacity-80">
                    <span
                        class="font-semibold tracking-wider text-sm text-white">{{ $stream->duration }}</span>
                </div>
            @endif
        </div>
    </a>
    <article
        class="px-6 py-4 flex flex-col justify-between items-start space-y-4 md:space-y-2 bg-gray-600 rounded-b-xl lg:rounded-xl">

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
                              :format="'YYYY-MM-DD'"/>
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
