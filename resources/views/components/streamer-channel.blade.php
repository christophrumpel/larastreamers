<div class="flex flex-row items-start bg-gray-darker rounded-lg p-6 pb-8">
    <div class="w-1/5">
        <img class="-mt-12 w-full rounded-md overflow-hidden shadow" src="{{ $channel->thumbnail_url }}"
             alt="YouTube channel image from {{ $channel->name }}"/>
    </div>
    <div class="flex flex-col w-4/5 pl-8">
        <div class="mb-6">
            <p class="font-bold text-white mb-2">{{ $channel->name }}</p>
            <div class="flex items-center text-gray mb-4">
                <x-icons.marker class="w-4 h-4 mr-2 inline fill-current stroke-current"/>
                <p>{{ $channel->country }}</p>
            </div>
            <p class="text-gray">{{ \Illuminate\Support\Str::of($channel->description)->limit(100) }}</p>
        </div>
        <div class="flex justify-between">
            <a class="px-3 py-2 text-sm font-medium text-white transition bg-red rounded-md shadow hover:bg-red-dark focus:bg-red focus:outline-none"
               href="{{ route('archive', ['streamer' => $channel->hashid]) }}">Show {{ $channel->approved_finished_streams_count }} {{ \Illuminate\Support\Str::plural('stream', $channel->approved_finished_streams_count) }}</a>
            <div>
                <a href="{{ $channel->url() }}">
                    <x-icons.youtube
                        class="w-6 h-6 mr-2 inline text-gray fill-current stroke-current hover:text-white"/>
                </a>
                @if($channel->twitter_handle)
                    <a href="https://twitter.com/{{ $channel->twitter_handle }}">
                        <x-icons.twitter
                            class="w-6 h-6 mr-2 inline text-gray fill-current stroke-current hover:text-white"/>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
