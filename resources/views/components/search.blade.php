<div class="flex flex-row md:justify-end max-w-6xl md:max-auto">
    <div class="flex flex-col sm:flex-row rounded-lg overflow-hidden shadow-xl">

        <label for="streamer" class="sr-only">Filter by streamer</label>
        <select wire:model="streamer"
                id="streamer"
            class="border-white focus:border-1 focus:border-red"
        >
            <option value="">All Streamers</option>
            @foreach($channels as $channel)
                <option value="{{ $channel['hashid'] }}" wire:key="streamer_{{ $channel['hashid'] }}">{{ $channel['name'] }}</option>
            @endforeach
        </select>

        <label for="search" class="sr-only">Search</label>
        <input type="search" {{ $attributes->merge(['class' => 'w-60 md:w-80 text-gray-dark px-8 py-4 placeholder-gray-light border-white focus:border-1 focus:border-red', 'id' => 'search', 'placeholder' => 'Search for a stream...', 'role' => 'search', 'aria-placeholder' => "A stream's title"]) }} />
        <div class="flex items-center justify-center bg-red text-white py-2 px-4">
            <x-icons.search class="w-4 h-4 fill-current stroke-current" />
        </div>
    </div>
</div>
