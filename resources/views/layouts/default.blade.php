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
            @include('layouts.partials.navbar')
            <div class="flex flex-col min-w-0 break-words bg-base-200 w-full mb-6 rounded-lg min-h-lvh">
                <div class="p-10 h-full">
                    <x-h1>@yield('title')</x-h1>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @vite(['resources/js/app.js'])
    <div
        x-data="{ show: false }"
        x-init="window.addEventListener('scroll', () => show = window.scrollY > 200)"
        x-show="show"
        x-transition
        class="fixed bottom-6 right-6 z-50"
    >
        <button
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="bg-base-200 text-base-content hover:bg-base-300 p-3 rounded-full shadow-lg"
            aria-label="Back to top"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>
    <div x-data x-show="$store.tooltip.visible" x-html="$store.tooltip.content" x-ref="tooltip" x-transition x-cloak
        id="global-tooltip"
        class="fixed z-50 bg-base-200 rounded shadow-[0px_0px_15px_0px_rgba(0,_0,_0,_0.7)] max-w-lg text-sm pointer-events-none"
        style="position: absolute; display: none; top: 0; left: 0">
    </div>
</body>
</html>
