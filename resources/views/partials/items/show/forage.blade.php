<div class="flex w-full flex-col">
    <div class="divider">This item is found by foraging</div>
</div>

<div class="max-h-96 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-800 overflow-y-auto">
<ul role="list" class="list bg-base-300 divide-y divide-base-200">
@foreach ($forage as $val)
    <li class="flex justify-between gap-x-6 px-3 py-1">
        <div class="flex min-w-0 gap-x-4">
            <div class="min-w-0 flex-auto">
                <p class="text-sm/6 font-semibold text-neutral-content">
                    <a href="{{ route('zones.show', $val['zone_id']) }}">{{ $val['long_name'] }}</a>
                    @if ($val['expansion'])
                        <span class="text-xs/5 text-gray-500">{{ $val['expansion'] }}</span>
                    @endif
                </p>
            </div>
        </div>
        @if ($val['level'] > 0)
        <div class="shrink-0 sm:flex sm:flex-col sm:items-end">
            <p class="mt-1 text-xs/5 font-medium text-accent">
                <span class="text-white">Req Level</span> {{ $val['level'] }}
            </p>
        </div>
        @endif
    </li>
@endforeach
</ul>
</div>
