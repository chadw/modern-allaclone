<input type="radio" name="zone_details" class="tab" aria-label="NPCs ({{ count($npcs) ?? 0 }})" checked="checked" />
<div class="tab-content bg-base-100 border-base-300">
    <div class="border border-base-content/5 overflow-x-auto">
        <table class="table table-auto md:table-fixed w-full table-zebra">
            <thead class="text-xs uppercase bg-base-300">
                <tr>
                    <th scope="col" width="20%">Name</th>
                    <th scope="col" width="10%">Lvl</th>
                    <th scope="col" width="40%">HP</th>
                    <th scope="col" width="10%">Rare Spawn</th>
                    <th scope="col" width="10%">Raid Tgt</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($npcs as $npc)
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
                        <td>{{ $npc->level }}</td>
                        <td class="text-nowrap">{{ number_format($npc->hp) }}</td>
                        <td>
                            {!! $npc->rare_spawn
                                ? '<span class="badge badge-soft badge-accent">Yes</span>'
                                : '<span class="badge badge-soft badge-warning">No</span>' !!}
                        </td>
                        <td>
                            {!! $npc->raid_target
                                ? '<span class="badge badge-soft badge-accent">Yes</span>'
                                : '<span class="badge badge-soft badge-warning">No</span>' !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
