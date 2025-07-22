@if ($activity->goalcount > 0)
    <div class="flex flex-wrap items-center gap-1">
        Kill <span class="badge badge-sm badge-soft badge-accent">{{ $activity->goalcount }}x</span> of the following
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
    @if ($activity->cached_npcs->isNotEmpty())
        <div class="flex flex-wrap items-center gap-1">
            @foreach ($activity->cached_npcs as $npc)
                <a href="{{ route('npcs.show', $npc->id) }}" class="link-info link-hover">
                    {{ $npc->clean_name }}
                </a>
                @if ($activity->cached_zones->isEmpty() && $npc['spawnentries']->first()?->spawn2->zoneData)
                    @php
                        $zone = $npc['spawnentries']->first()?->spawn2->zoneData;
                    @endphp
                in
                <a href="{{ route('zones.show', $zone->id) }}" class="link-accent link-hover">
                    {{ $zone->long_name }}
                </a>
                @endif
                {{ $loop->last == true ? '' : ',' }}
            @endforeach
        </div>
    @endif
@endif
