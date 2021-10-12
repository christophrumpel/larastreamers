<x-main-layout title="Larastreamers Streamers" description="The Hall Of Fame Of Larastreamers 🔥">
    <h2>Streamers</h2>
    <div class="grid grid-cols-2">
        @foreach($channels as $channel)
            <div>
                <img src="{{ $channel->thumbnail_url }}" alt="YouTube channel image from {{ $channel->name }}" />
                <p>{{ $channel->name }}</p>
                <p>{{ $channel->description }}</p>
                <a href="{{ route('archive', ['search' => $channel->name]) }}">Show streams</a>
            </div>
        @endforeach
    </div>


</x-main-layout>
