<div x-data class="{{ $itemClass }}">
    <a href="{{ route('items.show', $itemId) }}"
        @mouseenter="$store.tooltip.loadTooltip('{{ route('items.popup', $itemId) }}', $el, $event)"
        @mouseleave="$store.tooltip.hideTooltip()" class="text-base link-info link-hover flex items-center gap-1"
        title="{{ $itemName }}">

        @if ($itemIcon)
            <img src="{{ asset('img/icons/' . $itemIcon . '.png') }}" alt="{{ $itemName }}" width="20"
                height="20" class="mr-1">
        @endif

        {{ $itemName }}

        <template x-if="$store.tooltip.loadingUrl === '{{ route('items.popup', $itemId) }}'">
            <svg class="animate-spin h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z"></path>
            </svg>
        </template>
    </a>
</div>
