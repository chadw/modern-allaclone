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
<body class="flex items-center justify-center min-h-screen bg-base-100">
    <main class="relative max-w-2xl w-full p-10 bg-base-200 rounded-t-xl shadow-sm flex flex-col items-center">
        <div class="absolute top-0 left-1/2 -translate-y-1/2 -translate-x-1/2">
            <img src="{{ asset('img/eqemu.png') }}"
                title="{{ config('app.name') }}"
                alt="{{ config('app.name') }}" />
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6">
            <h1 class="text-3xl font-bold">@yield('title')</h1>
        </div>
        <div class="w-full mt-6">
            @yield('content')
        </div>
        <div class="w-full absolute -bottom-13 z-10 flex justify-center">
            <div
                class="bg-neutral text-sm text-gray-400 px-6 py-4 rounded-b-xl shadow-md w-full flex justify-between items-center">
                <div>
                    {{ config('app.name') }}
                </div>
                <div class="flex items-center gap-1">
                    <span>Loot the source on</span>
                    <a href="https://github.com/chadw/modern-allaclone" target="_blank"
                        class="flex items-center gap-1 link-accent link-hover">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="w-4 h-4">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5" />
                        </svg>
                        <span>GitHub</span>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
