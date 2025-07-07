<input type="radio" name="npc_details" class="tab" aria-label="Spawns" {{ $defaultTab === 'spawns' ? 'checked' : '' }}/>
<div class="tab-content bg-base-100 border-base-300">
    <div class="border border-base-content/5 overflow-x-auto">
        <table class="table table-auto md:table-fixed w-full table-zebra">
            <thead class="text-xs uppercase bg-base-300">
                <tr>
                    <th scope="col" class="w-[60%]">Coords (x,y,z)</th>
                    <th scope="col" class="w-[20%]">Chance</th>
                    <th scope="col" class="w-[20%]">Respawn</th>
                </tr>
            </thead>
            <tbody>
                @if ($npc->spawnEntries)
                    @foreach ($npc->spawnEntries as $spawn)
                        <tr>
                            <td scope="row">
                                {{ floor($spawn->spawn2->x) }},
                                {{ floor($spawn->spawn2->y) }},
                                {{ floor($spawn->spawn2->z) }}
                            </td>
                            <td>{{ $spawn->chance }}%</td>
                            <td>
                                {{ seconds_to_human($spawn->spawn2->respawntime) }}
                                @if ($spawn->spawn2->variance > 0)
                                 <span class="text-accent">+/- {{ seconds_to_human($spawn->spawn2->variance) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
