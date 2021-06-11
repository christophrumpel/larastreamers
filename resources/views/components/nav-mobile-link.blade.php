<a href="{{ $link }}" class="{{ request()->routeIs($routeName) ? 'bg-gray-100 border-gray-500 text-red-400' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
    {{ $name }}
</a>
