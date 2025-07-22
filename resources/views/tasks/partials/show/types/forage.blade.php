<div class="flex flex-wrap items-center gap-1">
    Forage <span class="badge badge-sm badge-soft badge-accent">{{ $activity->goalcount }}x</span>
    @if ($activity->item_list !== '')
        {{ $activity->item_list }}
    @endif

    @if ($activity->cached_items->isNotEmpty())
        ({!! $activity->cached_items->map(function ($item) {
                return view('components.item-link', [
                    'itemId' => $item->id,
                    'itemName' => $item->Name,
                    'itemIcon' => $item->icon,
                    'itemClass' => 'task-loot-link',
                ])->render();
            })->implode(', ') !!})
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
