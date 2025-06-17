<div class="flex w-full flex-col">
    <div class="divider">Sold by Merchants</div>
</div>

<div class="max-h-96 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-800 overflow-y-auto mb-5">
@foreach ($sold as $val)
<div class="px-1">
    <span class="block bg-neutral/80 text-sky-400 mt-2 p-2 font-bold sticky top-0">{{ $val['zone_name'] }}</span>
@if ($val['npcs'])
<ul role="list" class="list bg-base-300 divide-y divide-base-200">
    @foreach ($val['npcs'] as $npc)
    <li class="flex justify-between gap-x-6 px-3 py-1">
        <div class="flex min-w-0 gap-x-4">
            <div class="min-w-0 flex-auto">
                <p class="text-sm/6 font-semibold text-neutral-content">
                    <a
                        href="{{ route('npcs.show', $npc['id']) }}"
                        class="link-info link-hover">{{ $npc['clean_name'] }}</a>
                </p>
            </div>
        </div>
        <div class="shrink-0 sm:flex sm:flex-col sm:items-end">
            @if ($npc['class'] == 41)
                <p class="mt-1 text-xs/5 font-medium text-accent">{{ price($item->price) }}</p>
            @elseif($npc['class'] == 61)
                <p class="mt-1 text-xs/5 font-medium text-accent">{{ $item->ldopnprice }} points</p>
            @endif
        </div>
    </li>
    @endforeach
</ul>
@endif
</div>
@endforeach
</div>
