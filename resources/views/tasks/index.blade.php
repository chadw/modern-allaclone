@extends('layouts.default')
@section('title', 'Tasks')

@section('content')
    @include('partials.tasks.search')

    @if ($tasks->isNotEmpty())
        @include('partials.tasks.index-table')

        {{ $tasks->onEachSide(2)->links() }}
    @endif
@endsection
