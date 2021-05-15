<x-layout :timezones="$timezones" :currentTimezone="$currentTimezone">
    <div class="space-y-16">
        @foreach($streamsByDate as $date => $streams)
            <article>
                <h2 class="text-red-400 text-5xl font-bold mb-6">{{ $date }}</h2>
                @foreach($streams as $stream)
                    @include('pages.partials.stream')
                @endforeach
            </article>
        @endforeach
    </div>

</x-layout>
