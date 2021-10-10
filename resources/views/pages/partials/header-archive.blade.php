<header class="bg-gray-lightest">

    @include('pages.partials.nav')

    <section class="max-w-6xl mx-auto flex flex-col md:flex-row justify-center items-center md:items-start md:justify-between px-2 px-6 xl:px-0 pt-24 pb-32">

        <h2 class="text-gray-darkest text-4xl font-bold mb-12 md:mb-0">Archive</h2>

        <x-search wire:model.debounce.300ms="search" />

    </section>
</header>
