@if($upcomingStream)
    <div class="hidden md:block relative w-2/5">
        <a href="{{ $upcomingStream->url() }}">

            <div
                class="z-10 absolute -top-4 left-8 bg-red rounded-xl font-bold text-xs text-white uppercase tracking-widest p-2">
                @if($upcomingStream->isLive())  Live @else Upcoming @endif
            </div>
            <div class="rounded-xl overflow-hidden shadow-lg">


                <div class="bg-white flex flex-col">
                    <figure class="overflow-auto aspect-w-16 aspect-h-9">
                        <img class="object-cover w-full h-full"
                             src="{{ $upcomingStream->thumbnail_url }}"
                             alt="Video thumbnail"/>
                    </figure>

                    <div class="p-8">
                        <h3 class="mb-4 font-bold text-xl">{{ $upcomingStream->title }}</h3>
                        <p class="mb-2 text-base text-gray-dark flex items-center">
                            <x-icons.icon-user class="w-4 h-4 mr-2 inline text-gray-dark fill-current stroke-current"/>
                            {{ $upcomingStream->channel_title }}  @if ($upcomingStream->language?->shouldRender())
                                ({{ $upcomingStream->language->name }}) @endif
                        <p class="text-base text-gray-dark flex items-center">
                            <x-icons.icon-time class="w-4 h-4 mr-2 inline text-gray-dark fill-current stroke-current"/>
                            <x-local-time class="text-base"
                                          :date="$upcomingStream->actual_start_time ?? $upcomingStream->scheduled_start_time"/>
                        </p>
                    </div>
                </div>
            </div>
        </a>
    </div>

@endif
