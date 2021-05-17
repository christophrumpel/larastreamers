<div class="bg-blue-100 rounded-md overflow-hidden mb-1">
    <a class="grid grid-cols-1 md:grid-cols-3" target="_blank"
       href="{{ $stream->link() }}">
        <div class="relative md:col-span-1 md:border-r-8 md:border-gray-800">
            <img class="object-cover" src="{{ $stream->thumbnail_url }}"
                 alt="Youtube Live Stream Preview image"/>
            @if($stream->isLive())
                <div class="absolute top-2.5 right-2.5">
                    <div class="flex place-items-center space-x-2 rounded bg-black bg-opacity-75 px-2 py-1">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                            <span class="inline-flex rounded-full h-3 w-3 bg-red-700"></span>
                        </span>
                        <span class="uppercase tracking-wider font-bold text-gray-100">live</span>
                    </div>
                </div>
            @endif
        </div>
        <div class="md:col-span-2 md:border-l-8 md:border-gray-800 px-8 py-8 md:py-4">
            @ray($stream->scheduled_start_time)
            <x-local-time class="block text-2xl mb-2 text-gray-600" :date="$stream->scheduled_start_time"/>
            {{-- <time class="block text-2xl mb-2 text-gray-600">{{ $stream->scheduled_start_time->format('gA') }}</time>--}}
            <h3 class="text-2xl font-bold"> {{ $stream->title }}</h3>
            <p>{{ $stream->channel_title }}</p>
        </div>
    </a>
</div>
