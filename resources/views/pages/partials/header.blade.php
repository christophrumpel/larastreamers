<header class="bg-gray-lightest">

    @include('pages.partials.nav')

    <section class="flex py-12 md:py-28">

        <div class="w-1/2">
            <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
                <div class="flex flex-col items-start justify-between gap-8 md:items-center md:flex-row">
                    <div class="max-w-xl space-y-8">

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

        <!-- Stream Preview -->
        <div class="w-1/2">

            @if($upcomingStream)
                <div class="bg-white flex flex-col">
                    <figure class="overflow-auto rounded-t-xl lg:rounded-xl aspect-w-16 aspect-h-9">
                        <img class="object-cover w-full h-full"
                             src="{{ $upcomingStream->thumbnail_url }}"
                             alt="Video thumbnail"/>
                    </figure>


                </div>
            @endif

        </div>
        <!-- Stream Preview -->


    </section>

</header>
