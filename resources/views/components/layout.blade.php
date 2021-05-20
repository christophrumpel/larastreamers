<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Larastreamers</title>

    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📺</text></svg>">

    <link rel="alternate icon"
        href="/images/favicon-tv.png">

    <meta name="description"
        content="Larastreamers is an overview of who is streaming next in the Laravel world.">

    <meta name="author"
        content="Christoph Rumpel">

    <meta property="og:url"
        content="{{ url('') }}" />

    <meta property="og:type"
        content="website" />

    <meta property="og:title"
        content="Larastreamers" />

    <meta property="og:description"
        content="Larastreamers is an overview of who is live-coding next in the Laravel world." />

    <meta property="og:image"
        content="{{ asset('images/larastreamers_social.png') }}" />

    <link href="{{ mix('css/app.css') }}"
        rel="stylesheet" />

    <script src="{{ mix('js/app.js') }}"></script>

    @include('feed::links')

    @livewireStyles

    <!-- Fathom - beautiful, simple website analytics -->
    <script src="https://cdn.usefathom.com/script.js"
        data-site="POMKLANK"
        defer></script>
    <!-- / Fathom -->
</head>

<body class="flex flex-col min-h-screen font-sans antialiased text-gray-800 bg-gray-100">
    <section class="py-12 md:py-16">
        <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
            <header class="flex flex-col items-start justify-between gap-8 md:items-center md:flex-row">
                <aside class="max-w-xl space-y-4">
                    <h1 class="text-4xl font-bold tracking-tight md:text-5xl">
                        📺 Larastreamers
                    </h1>

                    <p class="text-gray-500 md:text-xl">
                        There is no better way to learn than by watching other developers
                        code live. Find out who is streaming next in the Laravel world.
                    </p>
                </aside>

                <x-add-streams-to-calendar />
            </header>
        </div>
    </section>

    <main class="flex-1 text-white bg-gray-700">
        {{ $slot ?? '' }}
    </main>

    <footer class="text-white bg-gray-700">
        <div class="w-full max-w-6xl px-4 mx-auto sm:px-6 md:px-8">
            <nav class="flex flex-col justify-between gap-4 py-8 border-t border-gray-600 md:items-center md:flex-row">
                <p class="text-sm text-gray-300">
                    <b class="text-white">Larastreamers</b> - A project by <a target="_blank"
                        href="https://christoph-rumpel.com">Christoph Rumpel</a>
                </p>

                <ul class="flex items-center space-x-6 text-sm">
                    <li>
                        <a class="hover:underline"
                            target="_blank"
                            href="https://twitter.com/larastreamers">Twitter</a>
                    </li>

                    <li>
                        <a class="hover:underline"
                            target="_blank"
                            href="https://github.com/christophrumpel/larastreamers">GitHub</a>
                    </li>
                </ul>
            </nav>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
