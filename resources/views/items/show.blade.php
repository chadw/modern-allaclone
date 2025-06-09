@extends('layouts.default')

@section('title')
    <img src="{{ asset('img/icons/' . $item->icon . '.png') }}" alt="{{ $item->Name }}" class="inline-block w-7 h-7 mr-2">
    {{ $item->Name ?? 'Unknown Item' }}
@endsection

@section('content')
<div class="flex flex-col lg:flex-row lg:items-start gap-4">
    <div class="sm:basis-1/3 md:basis-1/2 xl:basis-1/3 w-full">
        @include('partials.items.show.item', ['item' => $item])
    </div>

    <div class="sm:basis-2/3 md:basis-1/2 xl:basis-2/3 w-full">
        @include('partials.items.show.drops')

        @if ($recipes->isNotEmpty())
            @include('partials.items.show.recipes', ['recipes' => $recipes])
        @endif

        @if ($used_in_ts->isNotEmpty())
            @include('partials.items.show.used_in_tradeskills', ['used_in_ts' => $used_in_ts])
        @endif

        @if ($forage->isNotEmpty())
            @include('partials.items.show.forage', ['forage' => $forage])
        @endif

        @if ($ground_spawn->isNotEmpty())
            @include('partials.items.show.ground_spawn', ['ground_spawn' => $ground_spawn])
        @endif

        @if ($soldByZone->isNotEmpty())
            @include('partials.items.show.sold', ['sold' => $soldByZone])
        @endif
    </div>
</div>
@endsection
