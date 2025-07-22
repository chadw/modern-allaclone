@extends('layouts.default')

@section('title')
    <img src="{{ asset('img/icons/' . $item->icon . '.png') }}" alt="{{ $item->Name }}" class="inline-block w-7 h-7 mr-2">
    {{ $item->Name ?? 'Unknown Item' }}
@endsection

@section('content')
<div class="flex flex-col lg:flex-row lg:items-start gap-4 min-h-screen">
    <div class="sm:basis-1/3 md:basis-1/2 xl:basis-1/3 w-full lg:min-h-screen">
        <div class="sticky top-[100px]">
            @include('items.partials.show.item', ['item' => $item])
        </div>
    </div>

    <div class="sm:basis-2/3 md:basis-1/2 xl:basis-2/3 w-full">
        @include('items.partials.show.drops')

        @if ($recipes->isNotEmpty())
            @include('items.partials.show.recipes', ['recipes' => $recipes])
        @endif

        @if ($used_in_ts->isNotEmpty())
            @include('items.partials.show.used_in_tradeskills', ['used_in_ts' => $used_in_ts])
        @endif

        @if ($forage->isNotEmpty())
            @include('items.partials.show.forage', ['forage' => $forage])
        @endif

        @if ($fishing->isNotEmpty())
            @include('items.partials.show.fishing', ['fishing' => $fishing])
        @endif

        @if ($ground_spawn->isNotEmpty())
            @include('items.partials.show.ground_spawn', ['ground_spawn' => $ground_spawn])
        @endif

        @if ($soldByZone->isNotEmpty())
            @include('items.partials.show.sold', ['sold' => $soldByZone])
        @endif
    </div>
</div>
@endsection
