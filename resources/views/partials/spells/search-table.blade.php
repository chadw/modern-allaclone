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
                    <th scope="col" width="20%">Name</th>
                    <th scope="col" width="10%">Class</th>
                    <th scope="col" width="40%">Effect(s)</th>
                    <th scope="col" width="10%">Mana</th>
                    <th scope="col" width="10%">Skill</th>
                    <th scope="col" width="10%">Target Type</th>
                </tr>
            </thead>
            @if ($spell['spells']->isNotEmpty())
                <tbody>
                    @foreach ($spell['spells'] as $sp)
                        <tr>
                            <td scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ asset('img/items/' . $sp->new_icon . '.png') }}"
                                        alt="{{ $sp->name }} Icon" loading="lazy" class="w-6 h-6 rounded"
                                        width="24" height="24">
                                    <span class="font-medium">
                                        <a class="text-base link-info link-hover"
                                            href="{{ route('spells.show', $sp->id) }}">{{ $sp->name }}</a>
                                    </span>
                                </div>
                            </td>
                            <td>{{ config('everquest.classes_abbr')[$selectedClass] }}/{{ $spell['level'] }}</td>
                            <td class="text-nowrap">
                                @for ($n = 1; $n <= 12; $n++)
                                    {!! spell_desc($sp, $n) !!}
                                @endfor
                            </td>
                            <td>{{ $sp->mana }}</td>
                            <td>{{ config('everquest.db_skills')[$sp->skill] ?? 'Unknown' }}</td>
                            <td>
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
