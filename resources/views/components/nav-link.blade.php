    <a href="{{ $link }}" class="{{ request()->routeIs($routeName) ? 'border-red border-b-4 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} -m-1 inline-flex items-center px-1 pt-1 text-lg font-medium">
    {{ $name }}
</a>
