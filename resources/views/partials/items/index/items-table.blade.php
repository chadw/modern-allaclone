<div class="border border-base-content/5 overflow-x-auto">
    <table class="table table-auto md:table-fixed w-full table-zebra">
        <thead class="text-xs uppercase bg-base-300">
            <tr>
                <th scope="col" width="40%">Name</th>
                <th scope="col" width="20%">Type</th>
                <th scope="col" width="10%">AC</th>
                <th scope="col" width="10%">HP</th>
                <th scope="col" width="10%">DMG</th>
                <th scope="col" width="10%">Delay</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td scope="row">
                        <div class="flex flex-col">
                            <x-item-link :item_id="$item->id" :item_name="$item->Name" :item_icon="$item->icon" />
                            <span class="text-xs uppercase text-gray-500 ml-8 truncate">
                                @if ($item->slots > 0)
                                    {{ get_slots_string($item->slots) }}
                                @endif
                                @if ($item->bagslots > 0)
                                    <strong>Slots:</strong> {{ $item->bagslots }}
                                    @if ($item->bagwr > 0)
                                        <strong>WR:</strong> {{ $item->bagwr }}%
                                    @endif
                                @endif
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            {{ config('everquest.item_types.' . $item->itemtype) }}
                            {{-- augment --}}
                            @if ($item->itemtype == 54)
                                @php
                                    if (($item->augtype ?? 0) > 0) {
                                        $augType = $item->augtype;
                                        $augSlots = [];
                                        for ($i = 1, $bit = 1; $i <= 24; $i++, $bit *= 2) {
                                            if ($bit <= $augType && ($augType & $bit)) {
                                                $augSlots[] = $i;
                                            }
                                        }
                                        $slotsText = implode(', ', $augSlots);
                                    }
                                @endphp
                                @if (count($augSlots))
                                    <span class="text-xs text-gray-500 truncate">
                                        Type: {{ $slotsText }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    </td>
                    <td>{{ $item->ac ?? '-' }}</td>
                    <td>{{ $item->hp }}</td>
                    <td>{{ $item->damage }}</td>
                    <td>{{ $item->delay }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $items->onEachSide(2)->links() }}
