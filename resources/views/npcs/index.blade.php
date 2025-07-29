@extends('layouts.default')
@section('title', 'NPCs')

@section('content')
    @include('npcs.partials.index.search')

    @if ($npcs->isNotEmpty())
        @php
            $npc_class = config('everquest.npc_class');
            $npc_race = config('everquest.db_races');
            $expansions = config('everquest.expansions');
            $currentExpansion = config('everquest.current_expansion');
        @endphp
        <div class="flex w-full flex-col">
            <div class="divider uppercase text-xl font-bold text-sky-400">NPCs ({{ $npcs->total() }} Found)</div>
        </div>

        <div class="border border-base-content/5 overflow-x-auto">
            <table class="table table-auto md:table-fixed w-full table-zebra">
                <thead class="text-xs uppercase bg-base-300">
                    <tr>
                        <th scope="col" class="w-[30%]">Name</th>
                        <th scope="col" class="w-[30%]">Zone</th>
                        <th scope="col" class="w-[20%]">Lvl</th>
                        <th scope="col" class="w-[20%] hidden md:table-cell">HP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($npcs as $npc)
                        @php
                            $zone = $npc->firstSpawnEntries?->spawn2?->zoneData;
                        @endphp
                        @if (!$npc->clean_name)
                            @continue
                        @endif
                        <tr>
                            <td scope="row">
                                <div class="flex flex-col">
                                    <a href="{{ route('npcs.show', $npc->id) }}"
                                        class="text-base link-info link-hover">{{ $npc->clean_name }}</a>
                                    <span class="text-xs uppercase text-gray-500">
                                        {{ $npc_race[$npc->race] }} {{ $npc_class[$npc->class] }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    @if (is_object($zone) && $zone->expansion <= $currentExpansion)
                                        <a href="{{ route('zones.show', $zone->id) }}{{ $zone->version > 0 ? '?v=' . $zone->version : '' }}"
                                            class="text-base link-info link-hover">
                                            {{ $zone->long_name }}
                                        </a>
                                        <span class="text-xs uppercase text-gray-500">
                                            @if ($zone->expansion !== null)
                                                {{ $expansions[$zone->expansion] ?? 'Unknown Expansion' }}
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                {{ $npc->level }}{{ $npc->maxlevel && $npc->maxlevel > $npc->level ? '-' . $npc->maxlevel : '' }}
                            </td>
                            <td class="hidden md:table-cell text-nowrap">{{ number_format($npc->hp) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $npcs->onEachSide(2)->links() }}
    @endif
@endsection
