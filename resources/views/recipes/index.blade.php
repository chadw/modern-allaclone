@extends('layouts.default')
@section('title', 'Recipes')

@section('content')
    @include('partials.recipes.search')

    @if ($recipes->isNotEmpty())
    <div class="flex w-full flex-col">
        <div class="divider uppercase text-xl font-bold text-sky-400">Recipes ({{ $recipes->total() }} Found)</div>
    </div>

    <div class="border border-base-content/5 overflow-x-auto">
        <table class="table table-auto table-zebra md:table-fixed w-full">
            <thead class="text-xs uppercase bg-base-300">
                <tr>
                    <th scope="col" class="w-[60%]">Name</th>
                    <th scope="col" class="w-[20%]">Tradeskill</th>
                    <th scope="col" class="w-[20%] text-right">Trivial</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recipes as $recipe)
                <tr>
                    <td scope="row">
                        <div class="flex items-center space-x-3">
                            <a class="text-base link-info link-hover"
                                href="{{ route('recipes.show', $recipe->id) }}">{{ ucRomanNumeral($recipe->name) }}
                            </a>
                        </div>
                    </td>
                    <td class="truncate">{{ config('everquest.skills.tradeskill')[$recipe->tradeskill] ?? 'Non-Tradeskill' }}</td>
                    <td class="text-right {{ $recipe->trivial >= 300 ? 'text-error font-semibold' : 'text-accent font-semibold' }}">
                        {{ $recipe->trivial }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $recipes->onEachSide(2)->links() }}
    @endif
@endsection
