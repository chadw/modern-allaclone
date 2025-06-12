@extends('layouts.default')
@section('title', 'Items')

@section('content')
    <div x-data x-init="$store.itemsearch.init()" class="relative">
        <div class="flex justify-end mb-2">
            <button @click="$store.itemsearch.toggle()"
                class="flex items-center btn btn-sm btn-soft transition" title="Toggle Item Search">
                <span x-text="$store.itemsearch.open ? 'Hide Search' : 'Show Search'"></span>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 transition-transform duration-200"
                    :class="{ 'rotate-180': $store.itemsearch.open }"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
        <div x-show="$store.itemsearch.open" x-cloak x-transition class="mt-4">
            @include('partials.items.index.search')
        </div>
    </div>
    @if ($items->isNotEmpty())
        <div class="flex w-full flex-col">
            <div class="divider uppercase text-xl font-bold text-sky-400">Items ({{ $items->total() }} Found)</div>
        </div>
        @include('partials.items.index.items-table')
    @endif
@endsection
