<div class="w-fill md:w-3/5{{ !$upcomingStream ? ' pb-8' : '' }}">
    <div class="w-full px-4 mx-auto px-6 xl:px-0">
        <div class="flex flex-col items-start justify-between gap-8 md:items-center md:flex-row">
            <div class="max-w-xl pt-6 space-y-8">

                <h1 class="text-gray-darkest text-4xl font-bold">Watch other developers <span
                        class="text-red">code live</span></h1>
                <p class="text-xl text-gray-dark leading-6">
                    There is no better way to learn than by watching other developers
                    code live. Find out who is streaming next in the Laravel world.
                </p>
                @if($upcomingStream)
                    <x-add-streams-to-calendar/>
                @endif
            </div>
        </div>
    </div>
</div>
