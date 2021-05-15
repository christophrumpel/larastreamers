<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Larastreamers</title>
    <meta name="description" content="Larastreamers is an overview of who is streaming next in the Laravel world.">
    <meta name="author" content="Christoph Rumpel">

    <meta property="og:url" content="{{ url('') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Larastreamers"/>
    <meta property="og:description"
          content="Larastreamers is an overview of who is live-coding next in the Laravel world."/>
    <meta property="og:image"
          content="{{ asset('images/larastreamers_social.png') }}"/>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    @include('feed::links')
    @livewireStyles

    <!-- Fathom - beautiful, simple website analytics -->
    <script src="https://cdn.usefathom.com/script.js" data-site="POMKLANK" defer></script>
    <!-- / Fathom -->
</head>

<body>

<div>
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 lg:flex lg:justify-between">
            <div class="max-w-xl">
                <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">📺
                    Larastreamers</h2>
                <p class="mt-5 text-xl text-gray-500">There is no better way to learn than by watching other developers
                    code
                    <b>live</b>. Find out who is streaming next in the Laravel world.</p>
            </div>
            <livewire:timezone-switcher :timezones="$timezones" :currentTimezone="$currentTimezone"/>
        </div>

        <main class="bg-gray-800 py-16">
            <div class="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
                {{$slot ?? ''}}
            </div>
        </main>
    </div>
</div>
@livewireScripts
</body>
</html>
