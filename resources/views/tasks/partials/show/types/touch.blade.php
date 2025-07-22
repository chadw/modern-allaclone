<div class="flex flex-wrap items-center gap-1">
    Go to
    @if ($activity->cached_zones->isNotEmpty())
        {!!
            $activity->cached_zones->map(function ($zone) {
                return '<a href="' . route('zones.show', $zone->id) . '" class="link-accent link-hover">' .
                    $zone->long_name . '</a>';
            })->implode(', ')
        !!}
    @endif
</div>
