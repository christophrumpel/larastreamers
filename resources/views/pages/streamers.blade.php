<x-main-layout title="Larastreamers Streamers" description="The Hall Of Fame Of Larastreamers 🔥">

    <x-page-header title="Streamers ({{ $channels->count() }})" />

    <main class="flex-1 text-white bg-gray-darkest">
        <div class="w-full max-w-6xl mx-auto px-6 xl:px-0 py-24 space-y-16">
            <div class="grid grid-col-1 md:grid-cols-2 gap-x-6 gap-y-14 -mt-40">
                @foreach($channels as $channel)
                    <x-streamer-channel :channel="$channel" />
                @endforeach
            </div>
        </div>
    </main>

</x-main-layout>
