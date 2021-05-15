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
    <script src="{{ asset('js/app.js') }}" />

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
        </div>

        <main class="bg-gray-800 py-16">
            <div class="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
                {{$slot ?? ''}}
            </div>
        </main>

        <footer class="bg-gray-900 flex justify-center px-4 text-gray-100">
            <div class="container py-6">
                <h1 class="text-center text-lg font-bold lg:text-2xl">
                    Want to know when a Laravel Stream start?<br> Join our Newsletter!
                </h1>

                <div class="flex justify-center mt-6">
                    <div class="bg-white rounded-lg">
                        <div class="flex flex-wrap justify-between md:flex-row">
                            <input type="email" class="m-1 p-2 appearance-none text-gray-700 text-sm focus:outline-none" placeholder="Enter your email">
                            <button class="w-full m-1 p-2 text-sm bg-gray-800 rounded-lg font-semibold uppercase lg:w-auto">subscribe</button>
                        </div>
                    </div>
                </div>

                <hr class="h-px mt-6 bg-gray-700 border-none">

                <div class="flex flex-col items-center justify-between mt-6 md:flex-row">
                    <div>
                        <a href="#" class="text-xl font-bold">Larastreamers</a>
                    </div>
                    <div class="flex mt-4 md:m-0">
                        <div class="-mx-4">
                            <a href="https://twitter.com/christophrumpel" class="px-4 text-sm">Twitter</a>
                            <a href="https://github.com/christophrumpel/larastreamers" class="px-4 text-sm">Github</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
@livewireScripts
</body>
</html>
