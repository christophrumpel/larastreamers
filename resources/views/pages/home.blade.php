<x-main-layout>
    @include('pages.partials.header-home')

    <main class="{{ $upcomingStream ? 'md:-mt-16' : '' }} flex-1 text-white bg-gray-darkest">
        <livewire:stream-list />
    </main>
</x-main-layout>
