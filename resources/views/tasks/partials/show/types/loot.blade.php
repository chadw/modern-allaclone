@if ($activity->goalcount > 0)
    <div class="flex flex-wrap items-center gap-2">
        Loot <span class="badge badge-sm badge-soft badge-accent">{{ $activity->goalcount }}x</span>
        @if ($activity->cached_items->isNotEmpty())
            {!! $activity->cached_items->map(function ($item) {
                    return view('components.item-link', [
                        'itemId' => $item->id,
                        'itemName' => $item->Name,
                        'itemIcon' => $item->icon,
                        'itemClass' => 'task-loot-link mx-1',
                    ])->render();
                })->implode(', ') !!}
        @endif
        @if ($activity->cached_zones->isNotEmpty())
        in
        {!!
            $activity->cached_zones->map(function ($zone) {
                return '<a href="' . route('zones.show', $zone->id) . '" class="ml-1 link-accent link-hover">' .
                    $zone->long_name . '</a>';
            })->implode(', ')
        !!}
        @endif
    </div>
    @if ($activity->cached_npcs->isNotEmpty())
        <div class="flex items-center">
            @foreach ($activity->cached_npcs as $npc)
                <a href="{{ route('npcs.show', $npc->id) }}" class="ml-1 link-info link-hover">
                    {{ $npc->clean_name }}
                </a>
                {{ $loop->last == true ? '' : ',' }}
            @endforeach
        </div>
    @endif
@endif
