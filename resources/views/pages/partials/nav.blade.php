<!-- This example requires Tailwind CSS v2.0+ -->
<nav>
    <div class="max-w-6xl mx-auto px-6 xl:px-0">
        <div class="relative flex justify-between h-20 border-b border-gray-lighter">
            <a href="{{ route('home') }}" class="ml-12 sm:ml-0 flex items-center text-gray-darkest text-base font-bold">
                📺 Larastreamers
            </a>
            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                <!-- Mobile menu button -->
                <button type="button" @click="showMobileNav = !showMobileNav"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red"
                        aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg x-show="!showMobileNav" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="showMobileNav" x-cloak class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center justify-center sm:items-stretch sm:justify-start">
                <div class="hidden sm:ml-6 sm:flex space-x-6 sm:space-x-10">
                    <x-nav-link :link="route('home')" name="Home" route-name="home"/>
                    <button class="border-transparent hover:border-gray-300 text-gray-dark inline-flex items-center px-1 pt-1 hover:text-gray-darkest text-base font-medium" @click="showSubmissionModal =! showSubmissionModal">Submit</button>
                    <x-nav-link :link="route('archive')" name="Archive" route-name="archive"/>
                </div>
            </div>

        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div
        x-cloak
        x-show="showMobileNav"
        x-transition:enter="transition ease-out duration-100 transform"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75 transform"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="sm:hidden" id="mobile-menu">
        <div class="pt-2 pb-4 space-y-1">
            <x-nav-mobile-link :link="route('home')" name="Home" route-name="home"/>
            <button
                @click="showSubmissionModal =! showSubmissionModal"
                class="border-transparent text-gray-dark hover:text-gray-darkest block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Submit
            </button>
            <x-nav-mobile-link :link="route('archive')" name="Archive" route-name="archive"/>
        </div>
    </div>
</nav>
