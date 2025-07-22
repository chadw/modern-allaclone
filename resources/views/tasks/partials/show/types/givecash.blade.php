<div class="flex flex-wrap items-center gap-1">
    Give <span class="badge badge-sm badge-soft badge-accent">{{ price($activity->goalcount) }}</span>
    @if ($activity->item_list !== '')
        {{ $activity->item_list }}
    @endif
    @if ($activity->cached_npcs->isNotEmpty())
    to
        <div class="flex flex-wrap items-center gap-1">
            @foreach ($activity->cached_npcs as $npc)
                <a href="{{ route('npcs.show', $npc->id) }}" class="link-info link-hover">
                    {{ $npc->clean_name }}
                </a>
                {{ $loop->last == true ? '' : ',' }}
            @endforeach
        </div>
    @endif
    @if ($activity->cached_zones->isNotEmpty())
    in
        {!!
            $activity->cached_zones->map(function ($zone) {
                return '<a href="' . route('zones.show', $zone->id) . '" class="link-accent link-hover">' .
                    $zone->long_name . '</a>';
            })->implode(', ')
        !!}
    @endif
</div>
