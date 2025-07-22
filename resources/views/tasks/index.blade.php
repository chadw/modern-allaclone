@extends('layouts.default')
@section('title', 'Tasks')

@section('content')
    @include('tasks.partials.search')

    @if ($tasks->isNotEmpty())
        @include('tasks.partials.index.index-table')

        {{ $tasks->onEachSide(2)->links() }}
    @else
        <div class="alert alert-warning alert-soft">
            <span>No tasks found.</span>
        </div>
    @endif
@endsection
