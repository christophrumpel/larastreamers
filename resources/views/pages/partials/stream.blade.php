<div class="bg-blue-100 rounded-md overflow-hidden">
    <a class="grid grid-cols-1 md:grid-cols-3" target="_blank"
       href="{{ $stream->link() }}">
        <img class="md:col-span-1 md:border-r-8 md:border-gray-800" src="{{ $stream->thumbnail_url }}"
             alt="Youtube Live Stream Preview image"/>
        <div class="md:col-span-2 md:border-l-8 md:border-gray-800 px-8 py-8 md:py-4">
            @ray($stream->scheduled_start_time)
            <x-local-time class="block text-2xl mb-2 text-gray-600" :date="$stream->scheduled_start_time"/>
            {{-- <time class="block text-2xl mb-2 text-gray-600">{{ $stream->scheduled_start_time->format('gA') }}</time>--}}
            <h3 class="text-2xl font-bold"> {{ $stream->title }}</h3>
            <p>{{ $stream->channel_title }}</p>
        </div>
    </a>
</div>
