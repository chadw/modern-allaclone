@extends('layouts.default')
@section('title', 'Spells')

@section('content')
    @include('partials.spells.search')

    @if ($groupedSpells->isNotEmpty())
        @include('partials.spells.search-table', [
            'groupedSpells' => $groupedSpells,
            'title' => 'Spells',
        ])
    @endif

    @if ($extraSpells->isNotEmpty())
        <a id="extra"></a>
        <div
            x-init="$store.otherSpells.load('{{ $groupedSpells->flatMap(fn($g) => $g['spells'])->pluck('id')->join(',') }}')"
        >
            <template x-if="$store.otherSpells.spells === ''">
                <div class="text-center p-5 text-sm text-gray-500">Loading extra spells...</div>
            </template>

            <template x-if="$store.otherSpells.spells">
                <div x-html="$store.otherSpells.spells"></div>
            </template>
        </div>
    @endif

    @if ($groupedSpells->isEmpty() && $extraSpells->isEmpty() && count(request()->query()) > 0)
        <div role="alert" class="alert alert-error alert-soft">
            <span>No results found. Please try another search term.</span>
        </div>
    @endif
@endsection
