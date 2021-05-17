<div class="bg-blue-100 rounded-md overflow-hidden mb-1">
    <a class="block flex flex-col md:flex-row" target="_blank"
       href="{{ $stream->link() }}">
        <div>
            <img class="w-full md:w-96 md:border-r-8 md:border-gray-800" src="{{ $stream->thumbnail_url }}" width="400"
                 alt="Youtube Live Stream Preview image"/>
        </div>
        <div class="md:border-l-8 md:border-gray-800 px-8 py-8 md:py-4">
            @ray($stream->scheduled_start_time)
            <x-local-time class="block text-2xl mb-2 text-gray-600" :date="$stream->scheduled_start_time" />
            {{-- <time class="block text-2xl mb-2 text-gray-600">{{ $stream->scheduled_start_time->format('gA') }}</time>--}}
            <h3 class="text-2xl font-bold"> {{ $stream->title }}</h3>
            <p>{{ $stream->channel_title }}</p>
        </div>
    </a>
</div>
