<input type="radio" name="zone_details" class="tab" aria-label="Drops ({{ count($drops) ?? 0 }})" />
<div class="tab-content bg-base-100 border-base-300">
    <div class="border border-base-content/5 overflow-x-auto">
        <table class="table table-auto md:table-fixed w-full table-zebra">
            <thead class="text-xs uppercase bg-base-300">
                <tr>
                    <th scope="col" width="40%">Item</th>
                    <th scope="col" width="20%">Type</th>
                    <th scope="col" width="40%">Dropped</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($drops as $drop)
                    <tr>
                        <td scope="row">
                            <div class="flex items-center space-x-3">
                                <x-item-link
                                    :item_id="$drop['item']['id']"
                                    :item_name="$drop['item']['Name']"
                                    :item_icon="$drop['item']['icon']"
                                />
                            </div>
                        </td>
                        <td>
                            @if ($drop['item']['bagslots'] > 0)
                                Bag
                            @else
                                {{ $item_type[$drop['item']['itemtype']] }}
                            @endif
                        </td>
                        <td class="text-nowrap">
                            @foreach ($drop['npcs'] as $index => $npc)
                                @if ($index < 2)
                                    {{ $npc->clean_name }} (Lv {{ $npc->level }})
                                @endif
                            @endforeach

                            @if (count($drop['npcs']) > 2)
                                <div class="collapse collapse-arrow bg-base-200 mt-2">
                                    <input type="checkbox" />
                                    <div class="collapse-title font-medium">
                                        Show {{ count($drop['npcs']) - 2 }} more NPCs
                                    </div>
                                    <div class="collapse-content">
                                        <ul class="list-disc list-inside">
                                            @foreach ($drop['npcs'] as $index => $npc)
                                                @if ($index >= 2)
                                                    <li>{{ $npc->clean_name }} (Lv {{ $npc->level }})</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
