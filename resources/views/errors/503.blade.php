@extends('layouts.error')
@section('title', 'Maintenance Mode')

@section('content')
    <div class="">
        <h1 class="text-2xl font-bold mb-4">We're working on it...</h1>
        <div role="alert" class="alert alert-warning alert-soft">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-warning h-6 w-6 shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Performing some server maintenance. Be back soon!</span>
        </div>
    </div>
@endsection
