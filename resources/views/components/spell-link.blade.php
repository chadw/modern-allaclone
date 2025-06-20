<div x-data class="{{ $spellClass }}">
    <a href="{{ route('spells.show', $spellId) }}"
        @mouseenter="$store.tooltip.loadTooltip('{{ route('spells.popup', $spellId) }}', $el, $event)"
        @mouseleave="$store.tooltip.hideTooltip()" class="link-info link-hover flex items-center gap-1"
        title="{{ $spellName }}"
        data-effects-only="{{ $effectsOnly ? '1' : '0' }}"
        >
        @if ($spellIcon)
            <img src="{{ asset('img/icons/' . $spellIcon . '.png') }}" alt="{{ $spellName }}" width="20"
                height="20" class="mr-1">
        @endif
        {{ $spellName }}
        <template x-if="$store.tooltip.loadingUrl === '{{ route('spells.popup', $spellId) }}'">
            <svg class="animate-spin h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z"></path>
            </svg>
        </template>
    </a>
</div>
