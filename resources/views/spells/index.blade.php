@extends('layouts.default')
@section('title', 'Spells')

@section('content')
    @include('partials.spells.search')

    @if ($groupedSpells->isNotEmpty())
        @include('partials.spells.search-table')
    @endif
@endsection
