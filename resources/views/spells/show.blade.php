@extends('layouts.default')
@section('title', $spell->name)

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-4">{{ $spell->name }}</h1>

    @if ($description)
        <div class="mb-6 p-4 bg-gray-100 rounded shadow-sm">
            <h2 class="font-semibold text-lg">Description</h2>
            <p class="mt-2">{{ $description }}</p>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4 text-sm">
        <div><strong>Level:</strong> {{ $spell->classes1 }}–{{ $spell->classes16 }}</div>
        <div><strong>Mana:</strong> {{ $spell->mana }}</div>
        <div><strong>Cast Time:</strong> {{ $spell->cast_time }}</div>
        <div><strong>Recast Time:</strong> {{ $spell->recast_time }}</div>
        <div><strong>Cast On You:</strong> {{ $spell->cast_on_you }}</div>
        <div><strong>Cast On Other:</strong> {{ $spell->cast_on_other }}</div>
        <div><strong>Range:</strong> {{ $spell->range }}</div>
        <div><strong>Duration:</strong> {{ $spell->duration_ticks }} ticks</div>
    </div>

    <div class="mt-6">
        <a href="{{ route('spells.index') }}" class="text-base link-info link-hover">← Back to Spell List</a>
    </div>
</div>
@endsection
