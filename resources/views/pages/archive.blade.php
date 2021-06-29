<x-main-layout :show-calendar-downloads="false" title="Larastreamers Archive" description="Did you miss a stream? Don't worry. Just scroll through the archive and watch it now.">
    <h2 class="mt-12 text-4xl text-center">Archive</h2>
    <livewire:stream-list :streams-by-date="$pastStreamsByDate" :is-archive="true" />
</x-main-layout>

