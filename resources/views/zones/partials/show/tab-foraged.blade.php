<input type="radio" name="zone_details" class="tab" aria-label="Foraged ({{ count($foraged) ?? 0 }})" />
<div class="tab-content bg-base-100 border-base-300 p-2">
    <ul class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
        @foreach ($foraged as $item)
            <li>
                <a href="{{ route('items.show', $item->id) }}"
                    title="{{ $item->Name }}"
                    class="block hover:bg-base-200 rounded p-2 transition">
                    <div class="flex items-center gap-2 text-base text-base-content">
                        <img src="{{ asset('img/icons/' . $item->icon . '.png') }}" alt="{{ $item->Name }} Icon"
                            class="w-6 h-6 rounded" width="24" height="24">
                        <span class="truncate">{{ $item->Name }}</span>
                    </div>
                    <div class="text-xs text-base-content/50 uppercase ml-8">
                        @if ($item->bagslots > 0)
                            Bag
                        @else
                            {{ $item_type[$item->itemtype] }}
                        @endif
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>
