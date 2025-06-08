@extends('layouts.default')
@section('title', 'Factions')

@section('content')
    @if ($factions->isNotEmpty())
    <label class="select">
        <span class="label">Factions</span>
        <select id="select-faction">
            @foreach ($factions as $faction)
                <option value="{{ $faction->id }}">{{ $faction->name }}</option>
            @endforeach
        </select>
    </label>
    @endif
@endsection
