<div class="relative z-0 inline-flex shadow-sm rounded-md" x-data="{ show: false }">
    <a
            href="{{ $webcalLink }}"
            class="relative flex items-center px-5 py-4 text-sm font-medium text-white transition bg-red rounded-md rounded-r-none shadow hover:bg-red-500 focus:bg-red-700 focus:outline-none focus:ring focus:ring-red-400"
    >
        Add streams to calendar
    </a>

    <div class="-ml-px relative block">
        <button type="button"
                class="relative inline-flex items-center px-2 py-4 rounded-r-md bg-red hover:bg-red-500 text-sm font-medium focus:z-10 focus:outline-none focus:ring focus:ring-red-400"
                @click="show = !show"
        >
            <span class="sr-only">Open dropdown</span>

            <x-icons.chevron-down />
        </button>

        <div
                class="origin-top-right absolute right-0 mt-2 -mr-1 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none focus:ring focus:ring-red-400"
                x-cloak
                x-show="show"
                @click.away="show = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
        >
            <div class="py-1 bg-gray-lighter">
                <a href="{{ route('calendar.ics') }}"
                   class="text-gray-dark block px-4 py-2 text-sm"
                >
                    Download .ics file
                </a>
            </div>
        </div>
    </div>
</div>
