<div class="flex w-full flex-col">
    <div class="divider uppercase text-xl font-bold text-sky-400">
        Spells ({{ $groupedSpells->sum(fn($group) => count($group['spells'])) }} Found)
    </div>
</div>

@foreach ($groupedSpells as $spell)
    <span class="block bg-neutral/80 text-sky-400 mt-5 p-2 font-bold sticky top-15 z-10">Level:
        {{ $spell['level'] }}</span>
    <div class="border border-base-content/5 overflow-x-auto">
        <table class="table table-auto md:table-fixed w-full table-zebra">
            <thead class="text-xs uppercase bg-base-300">
                <tr>
                    <th scope="col" class="w-[20%]">Name</th>
                    <th scope="col" class="w-[10%]">Class</th>
                    <th scope="col" class="w-[40%]">Effect(s)</th>
                    <th scope="col" class="w-[10%] hidden lg:table-cell">Mana</th>
                    <th scope="col" class="w-[10%] hidden md:table-cell">Skill</th>
                    <th scope="col" class="w-[10%] hidden lg:table-cell">Target Type</th>
                </tr>
            </thead>
            @if ($spell['spells']->isNotEmpty())
                <tbody>
                    @foreach ($spell['spells'] as $sp)
                        <tr>
                            <td scope="row">
                                <x-spell-link
                                    :spell_id="$sp->id"
                                    :spell_name="$sp->name"
                                    :spell_icon="$sp->new_icon"
                                    spell_class="flex text-base"
                                />
                            </td>
                            <td>{{ config('everquest.classes_abbr.' . $selectedClass) }}/{{ $spell['level'] }}</td>
                            <td class="text-nowrap">
                                @for ($n = 1; $n <= 12; $n++)
                                <x-spell-effect
                                    :spell="$sp"
                                    :n="$n"
                                    :all-spells="$allSpells"
                                    :all-zones="$allZones"
                                />
                                @endfor
                            </td>
                            <td class="hidden lg:table-cell">{{ $sp->mana }}</td>
                            <td class="hidden md:table-cell">{{ config('everquest.db_skills.' . $sp->skill) ?? 'Unknown' }}</td>
                            <td class="hidden lg:table-cell">
                                @php
                                    $targetType = config('everquest.spell_targets')[$sp->targettype] ?? null;
                                @endphp
                                @if ($targetType)
                                    {{ $targetType }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
    </div>
@endforeach
