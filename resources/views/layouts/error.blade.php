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
    <main class="relative max-w-2xl w-full p-10 bg-base-200 rounded-xl shadow-sm flex flex-col items-center">
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
    </main>
</body>
</html>
