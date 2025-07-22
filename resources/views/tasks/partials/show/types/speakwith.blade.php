@if ($activity->goalcount > 0)
    <div class="flex flex-wrap items-center gap-2">
        Talk to
        @if ($activity->cached_npcs->isNotEmpty())
            <div class="flex flex-wrap items-center">
                @foreach ($activity->cached_npcs as $npc)
                    <a href="{{ route('npcs.show', $npc->id) }}" class="flex items-center link-info link-hover">
                        {{ $npc->clean_name }}
                    </a>
                    {{ $loop->last == true ? '' : ',' }}
                @endforeach
            </div>
        @endif
        in
        {!!
            $activity->cached_zones->map(function ($zone) {
                return '<a href="' . route('zones.show', $zone->id) . '" class="link-accent link-hover">' .
                    $zone->long_name . '</a>';
            })->implode(', ')
        !!}
    </div>
@endif
