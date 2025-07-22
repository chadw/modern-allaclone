<div class="flex flex-wrap items-center gap-1">
    @if ($activity->description_override !== '')
        {{ $activity->description_override }}
    @elseif ($activity->activitytype)
        {{ config('everquest.task_activity_types.' . $activity->activitytype) ?? null }}
        {{ $activity->target_name }}
    @endif
    in
    {!!
        $activity->cached_zones->map(function ($zone) {
            return '<a href="' . route('zones.show', $zone->id) . '" class="link-accent link-hover">' .
                $zone->long_name . '</a>';
        })->implode(', ')
    !!}
</div>
