<x-layout :timezones="$timezones" :currentTimezone="$currentTimezone">
    <div class="space-y-16">
        @foreach($streamsByDate as $date => $streams)
            <article>
                <h2 class="text-red-400 text-5xl font-bold mb-6">{{ $date }}</h2>
                @foreach($streams as $stream)
                    <div class="bg-blue-100 rounded-md overflow-hidden">
                        <a class="block flex flex-col md:flex-row" target="_blank"
                           href="https://www.youtube.com/watch?v={{ $stream->youtube_id }}">
                            <img class="w-full md:w-96 md:border-r-8 md:border-gray-800" src="{{ $stream->thumbnail_url }}" width="400"
                                 alt="Youtube Live Stream Preview image"/>
                            <div class="md:border-l-8 md:border-gray-800 px-8 py-8 md:py-4">
                                <time class="block text-2xl mb-2 text-gray-600">{{ $stream->scheduled_start_time->format('gA') }}</time>
                                <h3 class="text-2xl font-bold"> {{ $stream->title }}</h3>
                                <p>{{ $stream->channel_title }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </article>
        @endforeach
    </div>

</x-layout>
