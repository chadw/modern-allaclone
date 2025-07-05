<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <base href="{{ url('/') }}/">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex flex-col">
    <div class="grow py-5 bg-base-300">
        <div class="container mx-auto px-4">
            @include('partials.navbar')
            <div class="flex flex-col min-w-0 break-words bg-base-200 w-full mb-6 rounded-lg min-h-lvh">
                <div class="p-10 h-full">
                    <x-h1>@yield('title')</x-h1>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/app.js'])
    <div x-data x-show="$store.tooltip.visible" x-html="$store.tooltip.content" x-ref="tooltip" x-transition x-cloak
        id="global-tooltip"
        class="fixed z-50 bg-base-200 border border-base-content rounded shadow-lg max-w-lg text-sm pointer-events-none"
        style="position: absolute; display: none; top: 0; left: 0">
    </div>
</body>
</html>
