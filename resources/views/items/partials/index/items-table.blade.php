<div class="border border-base-content/5 overflow-x-auto">
    <table class="table table-auto md:table-fixed w-full table-zebra">
        <thead class="text-xs uppercase bg-base-300">
            <tr>
                <th scope="col">@sortablelink('Name', 'Name')</th>
                <th scope="col" class="w-[20%]">@sortablelink('itemtype', 'Type')</th>
                @php
                    $reserved = ['ac','hp','damage','delay','ratio'];
                    $activeStats = array_values(array_filter([
                        request('stat1'),
                        request('stat2'),
                        request('stat3'),
                    ]));
                    $activeStats = array_values(array_unique($activeStats));
                    $statLabels = config('custom_search_fields.item_stats_select', []);
                @endphp
                @foreach ($activeStats as $s)
                    <th scope="col" class="w-[5%]">@sortablelink($s, $statLabels[$s] ?? strtoupper($s))</th>
                @endforeach
                @if (!in_array('ac', $activeStats))
                    <th scope="col" class="w-[5%] hidden md:table-cell">@sortablelink('ac', 'AC')</th>
                @endif
                @if (!in_array('hp', $activeStats))
                    <th scope="col" class="w-[5%]">@sortablelink('hp', 'HP')</th>
                @endif
                @if (!in_array('damage', $activeStats))
                    <th scope="col" class="w-[5%] hidden md:table-cell">@sortablelink('damage', 'DMG')</th>
                @endif
                @if (!in_array('delay', $activeStats))
                    <th scope="col" class="w-[5%] hidden md:table-cell">Delay</th>
                @endif
                @if (!in_array('ratio', $activeStats))
                    <th scope="col" class="w-[5%] hidden md:table-cell">@sortablelink('ratio', 'Ratio')</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td scope="row">
                        <div class="flex flex-col">
                            <x-item-link
                                :item_id="$item->id"
                                :item_name="$item->Name"
                                :item_icon="$item->icon"
                                item_class="flex"
                            />
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
                                    $augSlots = [];

                                    if (($item->augtype ?? 0) > 0) {
                                        $augType = $item->augtype;

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
                    @foreach ($activeStats as $s)
                        @php
                            $val = null;
                            if ($s === 'ratio') {
                                $val = ($item->damage > 0 && $item->delay > 0) ? number_format($item->delay / $item->damage, 2) : '-';
                            } else {
                                $val = data_get($item, $s, null);
                            }
                        @endphp
                        <td class="text-sm">{{ $val === null || $val === '' ? '-' : $val }}</td>
                    @endforeach
                    @if (!in_array('ac', $activeStats))
                        <td class="hidden md:table-cell">{{ $item->ac ?? '-' }}</td>
                    @endif
                    @if (!in_array('hp', $activeStats))
                        <td>{{ $item->hp }}</td>
                    @endif
                    @if (!in_array('damage', $activeStats))
                        <td class="hidden md:table-cell">{{ $item->damage }}</td>
                    @endif
                    @if (!in_array('delay', $activeStats))
                        <td class="hidden md:table-cell">{{ $item->delay }}</td>
                    @endif
                    @if (!in_array('ratio', $activeStats))
                        <td class="hidden md:table-cell">
                            {{ $item->damage > 0 ? number_format($item->delay / $item->damage, 2) : '-' }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $items->onEachSide(2)->links() }}
