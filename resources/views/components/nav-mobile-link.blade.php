<a href="{{ $link }}" class="{{ request()->routeIs($routeName) ? 'text-red' : 'border-transparent text-gray-dark hover:text-gray-darkest' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
    {{ $name }}
</a>
