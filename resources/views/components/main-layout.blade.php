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

    @include('pages.partials.meta')

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('feed::links')

    @livewireStyles

    <!-- Fathom - beautiful, simple website analytics -->
    @production
        <script src="https://cdn.usefathom.com/script.js" data-site="POMKLANK" defer></script>
    @endproduction
    <!-- / Fathom -->

</head>

<body class="flex flex-col min-h-screen font-sans antialiased" x-data="{ showSubmissionModal: false, showMobileNav: false }">

{{ $slot ?? '' }}

@include('pages.partials.footer')

@include('pages.partials.submit-modal')

@livewireScripts
@stack('scripts')

</body>

</html>
