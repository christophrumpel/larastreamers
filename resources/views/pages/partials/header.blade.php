<header class="bg-gray-lightest">

    @include('pages.partials.nav')

    <section class="max-w-6xl mx-auto flex pt-12">

        <!-- Headline Preview -->
        <div class="w-3/5">
            <div class="w-full px-4 mx-auto sm:px-6 md:px-8">
                <div class="flex flex-col items-start justify-between gap-8 md:items-center md:flex-row">
                    <div class="max-w-xl pt-6 space-y-8">

                        <h2 class="text-gray-darkest text-4xl font-bold">Watch other developers <span
                                class="text-red">code live</span></h2>
                        <p class="text-xl text-gray-dark leading-6">
                            There is no better way to learn than by watching other developers
                            code live. Find out who is streaming next in the Laravel world.
                        </p>
                        @if($showCalendarDownloads)
                            <x-add-streams-to-calendar/>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        <!-- Headline Preview -->


        <!-- Stream Preview -->
        @if($upcomingStream)
            <div class="relative w-2/5">
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

            </div>
        @endif
        <!-- Stream Preview -->


    </section>
</header>
