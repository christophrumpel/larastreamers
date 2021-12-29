<a href="{{ $link }}"
   aria-label="{{ $ariaLabel }}"
   class="w-full sm:w-auto inline-flex bg-gray-darkest text-gray-light hover:text-white rounded-lg px-5 py-2">
    {{ $slot }}
    <span class="text-base ml-4 ">{{ $name }}</span>
</a>
