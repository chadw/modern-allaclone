<div class="flex w-full flex-col">
    <div class="divider">This item is found on creatures</div>
</div>

<div class="max-h-96 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-800 overflow-y-auto mb-7">
    @foreach ($drops as $drop)
    <div class="px-1">
        <span class="block bg-neutral/80 text-sky-400 mt-2 p-2 font-bold sticky top-0">{{ $drop['zone_name'] }}</span>
    @if ($drop['npcs'])
    <ul role="list" class="list bg-base-300 divide-y divide-base-200">
        @foreach ($drop['npcs'] as $npc)
        <li class="flex justify-between gap-x-6 px-3 py-1">
            <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                    <p class="text-sm/6 font-semibold text-neutral-content">
                        <a href="/npc/{{ $npc['id'] }}">{{ $npc['name'] }}</a>
                    </p>
                </div>
            </div>
            <div class="shrink-0 sm:flex sm:flex-col sm:items-end">
                <p class="mt-1 text-xs/5 font-medium text-accent">{{ $npc['chance'] }}%</p>
            </div>
        </li>
        @endforeach
    </ul>
    @endif
    </div>
    @endforeach
</div>
