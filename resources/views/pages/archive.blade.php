<x-main-layout :show-calendar-downloads="false">
    <h2 class="text-4xl text-center">Archive</h2>
    <livewire:stream-list :streams-by-date="$pastStreamsByDate" :is-archive="true" />
</x-main-layout>

