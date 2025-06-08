@extends('layouts.default')
@php
    $title = $npc->clean_name;
    if ($npc->level) {
        $title .= ' - Lvl ' . $npc->level . ($npc->maxlevel > 0 ? '-' . $npc->maxlevel : '');
    }
@endphp
@section('title', $title)

@section('content')
    @if ($npc)
        @php
            $npc_class = config('everquest.npc_class');
            $npc_race = config('everquest.db_races');
            $npc_body = config('everquest.db_bodytypes');
            $zone = $npc->firstSpawnEntries->spawn2->zoneData;
        @endphp
        <div class="flex flex-row items-center gap-2 flex-wrap">
            <span>
                {{ $npc_race[$npc->race] }} {{ $npc_class[$npc->class] }}
                <small class="text-accent">{{ $npc_body[$npc->bodytype] }}</small>
            </span>
            @if ($zone->id)
                <a class="text-base link-info link-hover" href="{{ route('zones.show', $zone->id) }}">
                    {{ $zone->long_name }}
                </a>
            @endif
        </div>
        <div class="flex flex-row items-center gap-2 mb-4 flex-wrap">
            Primary Faction:
            @if ($npc->npcFaction?->primaryFaction)
                <a class="text-base link-secondary link-hover"
                    href="{{ route('factions.show', $npc->npcFaction->primaryFaction->id) }}">
                    {{ $npc->npcFaction->primaryFaction->name ?? 'None' }}
                </a>
            @else
                None
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-[3fr_1fr] gap-4">
            <div class="flex flex-col gap-2">
                <div class="stats stats-vertical lg:stats-horizontal shadow mb-3">
                    <div class="stat">
                        <div class="stat-figure">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
                                <path d="M12 3v18" />
                                <path d="M3.5 12h17" />
                            </svg>
                        </div>
                        <div class="stat-title">AC</div>
                        <div class="stat-value">{{ number_format($npc->AC) }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-figure">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M13 3a1 1 0 0 1 1 1v4.535l3.928 -2.267a1 1 0 0 1 1.366 .366l1 1.732a1 1 0 0 1 -.366 1.366l-3.927 2.268l3.927 2.269a1 1 0 0 1 .366 1.366l-1 1.732a1 1 0 0 1 -1.366 .366l-3.928 -2.269v4.536a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-4.536l-3.928 2.268a1 1 0 0 1 -1.366 -.366l-1 -1.732a1 1 0 0 1 .366 -1.366l3.927 -2.268l-3.927 -2.268a1 1 0 0 1 -.366 -1.366l1 -1.732a1 1 0 0 1 1.366 -.366l3.928 2.267v-4.535a1 1 0 0 1 1 -1h2z" />
                            </svg>
                        </div>
                        <div class="stat-title">HP</div>
                        <div class="stat-value">{{ number_format($npc->hp) }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-figure">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h2" />
                                <path
                                    d="M22 16c0 4 -2.5 6 -3.5 6s-3.5 -2 -3.5 -6c1 0 2.5 -.5 3.5 -1.5c1 1 2.5 1.5 3.5 1.5z" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            </svg>
                        </div>
                        <div class="stat-title">ATK</div>
                        <div class="stat-value">{{ $npc->ATK }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-figure">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M20 4v5l-9 7l-4 4l-3 -3l4 -4l7 -9z" />
                                <path d="M6.5 11.5l6 6" />
                            </svg>
                        </div>
                        <div class="stat-title">HIT</div>
                        <div class="stat-value">{{ $npc->mindmg }}-{{ $npc->maxdmg }}</div>
                    </div>
                </div>
                @if ($npc->special_abilities)
                    <div class="stats stats-horizontal shadow">
                        <div class="stat w-full">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M21 3v5l-11 9l-4 4l-3 -3l4 -4l9 -11z" />
                                        <path d="M5 13l6 6" />
                                        <path d="M14.32 17.32l3.68 3.68l3 -3l-3.365 -3.365" />
                                        <path d="M10 5.5l-2 -2.5h-5v5l3 2.5" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <div class="stat-title">Special Abilities</div>
                                    <div class="stat-value text-sm whitespace-normal">
                                        {{ implode(', ', $npc->parsed_special_abilities) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="bg-base-200">
                @if ($npc->attackProcSpell)
                    <h3 class="block mb-4 font-semibold">Proc ({{ $npc->attackProcSpellProcChance }}%)</h3>
                    <div class="flex items-center gap-2 mb-1 whitespace-nowrap">
                        <img src="{{ asset('img/items/' . $npc->attackProcSpell->new_icon . '.png') }}"
                            alt="{{ $npc->attackProcSpell->name }} Icon" loading="lazy"
                            class="border border-base-content/50 w-6 h-6 rounded" width="24" height="24">
                        <a class="text-base link-info link-hover"
                            href="{{ route('spells.show', $npc->attackProcSpell->id) }}">{{ $npc->attackProcSpell->name }}</a>
                    </div>
                    <div class="divider"></div>
                @endif
                <div class="grid grid-cols-2 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-2 gap-x-6 gap-y-4">
                    @if ($npc->filteredSpellEntries->isNotEmpty())
                        @foreach ($npc->filteredSpellEntries as $npcspell)
                            @if ($npcspell->spells)
                                <div class="flex items-center gap-2 whitespace-nowrap truncate">
                                    <img src="{{ asset('img/items/' . $npcspell->spells->new_icon . '.png') }}"
                                        alt="{{ $npcspell->spells->name }} Icon" loading="lazy"
                                        class="border border-base-content/50 w-6 h-6 rounded" width="24"
                                        height="24">
                                    <a class="text-base link-info link-hover"
                                        href="{{ route('spells.show', $npcspell->spells->id) }}">
                                        {{ $npcspell->spells->name }}
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p>No spells assigned.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="tabs tabs-lift mt-6">
            @if ($npc->lootTable?->loottableEntries)
                @include('partials.npcs.tab-drops')
            @endif
            @if ($npc->spawnEntries)
                @include('partials.npcs.tab-spawns')
            @endif
            @if ($raisesFaction || $lowersFaction)
                @include('partials.npcs.tab-faction')
            @endif
        </div>
    @endif
@endsection
