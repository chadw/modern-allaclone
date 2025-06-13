<input type="radio" name="zone_details" class="tab" aria-label="Spawns Locs ({{ count($spawnGroups) ?? 0 }})" />
<div class="tab-content bg-base-100 border-base-300">
    <div class="border border-base-content/5 overflow-x-auto">
        <table class="table table-auto md:table-fixed w-full table-zebra">
            <thead class="text-xs uppercase bg-base-300">
                <tr>
                    <th scope="col" class="w-[40%] hidden md:table-cell">Spawn Grp (x,y,z)</th>
                    <th scope="col" class="w-[40%]">NPCs</th>
                    <th scope="col" class="w-[20%]">Respawn</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($spawnGroups as $grp)
                    <tr>
                        <td scope="row" class="hidden md:table-cell">
                            <div class="flex flex-col">
                                {{ $grp->name }}
                                <span class="text-xs uppercase text-gray-500">
                                    {{ floor($grp->x) }}, {{ floor($grp->y) }}, {{ floor($grp->z) }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <ul class="list">
                                @foreach ($grp->spawnentries as $spawn)
                                    @if ($spawn->npc)
                                        <li>
                                            <a class="text-base link-info link-hover"
                                                title="{{ $spawn->npc->clean_name }}"
                                                href="{{ route('npcs.show', $spawn->npc->id) }}">{{ $spawn->npc->clean_name }}</a>
                                            (lvl {{ $spawn->npc->level }}), {{ $spawn->chance }}% chance
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-nowrap">
                            {{ seconds_to_human($grp->respawntime) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
