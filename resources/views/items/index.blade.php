@extends('layouts.default')
@section('title', 'Items')

@section('content')
    @include('partials.items.index.search')

    @if ($items->isNotEmpty())
        @include('partials.items.index.items-table')
    @endif
@endsection
