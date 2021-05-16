<x-layout>
    <div class="space-y-16">
        <div class="flex justify-center md:justify-end">
            <a class="text-white hover:underline" href="{{ route('calendar.ics') }}">Add all streams to your calendar</a>
        </div>
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
