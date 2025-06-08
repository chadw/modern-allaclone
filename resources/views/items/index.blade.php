@extends('layouts.default')
@section('title', 'Items')

@section('content')
    @include('partials.items.index.search')

    @if ($items->isNotEmpty())
        <div class="flex w-full flex-col">
            <div class="divider uppercase text-xl font-bold text-sky-400">Items ({{ $items->total() }} Found)</div>
        </div>
        @include('partials.items.index.items-table')
    @endif
@endsection
