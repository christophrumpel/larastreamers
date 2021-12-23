@if($upcomingStream)
    <div class="relative hidden w-2/5 md:block">
        <a href="{{ $upcomingStream->url() }}">

            <div
                class="absolute z-10 p-2 text-xs font-bold tracking-widest text-white uppercase -top-4 left-8 bg-red rounded-xl">
                @if($upcomingStream->isLive())  Live @else Upcoming @endif
            </div>
            <div class="overflow-hidden shadow-lg rounded-xl">


                <div class="flex flex-col bg-white">
                    <figure class="overflow-auto aspect-w-16 aspect-h-9">
                        <img class="object-cover w-full h-full"
                             src="{{ $upcomingStream->thumbnail_url }}"
                             alt="Video thumbnail"/>
                    </figure>

                    <div class="p-8">
                        <h3 class="mb-4 text-xl font-bold">{{ $upcomingStream->title }}</h3>
                        <p class="flex items-center mb-2 text-base text-gray-dark">
                            <x-icons.icon-user class="inline w-4 h-4 mr-2 fill-current stroke-current text-gray-dark"/>
                            {{ $upcomingStream->channel->name }}  @if ($upcomingStream->language?->shouldRender())
                                ({{ $upcomingStream->language->name }}) @endif
                        <p class="flex items-center text-base text-gray-dark">
                            <x-icons.icon-time class="inline w-4 h-4 mr-2 fill-current stroke-current text-gray-dark"/>
                            <time datetime="{{ $upcomingStream->actual_start_time }}" class="flex text-base text-gray-dark">
                               {{ $upcomingStream->startForHumans }}
                            </time>
                        </p>
                    </div>
                </div>
            </div>
        </a>
    </div>

@endif
