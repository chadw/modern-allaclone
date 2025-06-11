@extends('layouts.default')
@section('title', 'Tasks')

@section('content')
    @include('partials.tasks.search')

    @if ($tasks->isNotEmpty())
        @include('partials.tasks.index-table')
    @endif
@endsection
