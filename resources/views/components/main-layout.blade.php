<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>

    <link rel="icon"
          href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📺</text></svg>">

    <link rel="alternate icon"
          href="{{ asset('images/favicon-tv.png') }}">

    <meta name="description"
          content="{{ $description }}">

    <meta name="author"
          content="Christoph Rumpel">

    <meta property="og:url"
          content="{{ url('') }}"/>

    <meta property="og:type"
          content="website"/>

    <meta property="og:title"
          content="{{ $title }}"/>

    <meta property="og:description"
          content="{{ $description }}"/>

    <meta property="og:image"
          content="{{ asset('images/larastreamers_social.png') }}"/>

    <link href="{{ mix('css/app.css') }}"
          rel="stylesheet"/>

@include('feed::links')

@livewireStyles

<!-- Fathom - beautiful, simple website analytics -->
    <script src="https://cdn.usefathom.com/script.js"
            data-site="POMKLANK"
            defer></script>
    <!-- / Fathom -->
</head>

<body class="flex flex-col min-h-screen font-sans antialiased text-gray-800 bg-gray-100">
@include('pages.partials.header')

<main class="-mt-16 flex-1 text-white bg-gray-darkest">
    {{ $slot ?? '' }}
</main>

<footer class="text-white bg-black">
    <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
        <nav class="flex flex-col justify-between gap-4 py-10 md:items-center md:flex-row">
            <p class="text-sm text-gray-300">
                <b class="text-white">&copy; Larastreamers</b> - <span class="text-gray-light under">A project by <a target="_blank" class="underline hover:text-gray-lighter"
                                                                          href="https://christoph-rumpel.com">Christoph
                        Rumpel</a></span>
            </p>

            <ul class="flex items-center space-x-6 text-sm text-gray-light underline">
                <li>
                    <a class="hover:text-gray-lighter"
                       target="_blank"
                       href="https://twitter.com/larastreamers">Twitter</a>
                </li>

                <li>
                    <a class="hover:text-gray-lighter"
                       target="_blank"
                       href="https://github.com/christophrumpel/larastreamers">GitHub</a>
                </li>
            </ul>
        </nav>
    </div>
</footer>

@livewireScripts
<script src="{{ mix('js/app.js') }}"></script>
@stack('scripts')

</body>

</html>
