@include('partials.spells.search-table', [
    'groupedSpells' => collect([[ 'level' => null, 'spells' => $extraSpells ]]),
    'title' => "Other Matching Spells ({$extraSpellsCount})",
])

@if ($extraSpells->hasPages())
<div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
    <div>
        <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
            Showing
            @if ($extraSpells->firstItem())
                <span class="font-medium">{{ $extraSpells->firstItem() }}</span>
                to
                <span class="font-medium">{{ $extraSpells->lastItem() }}</span>
            @else
                {{ $extraSpells->count() }}
            @endif
            of
            <span class="font-medium">{{ $extraSpells->total() }}</span>
            results
        </p>
    </div>
    <div class="join mt-4 flex flex-1 justify-end">
        @foreach ($extraSpells->appends(request()->query())->onEachSide(2)->links()->elements[0] as $page => $url)
            <a href="{{ $url }}"
               @click.prevent="$store.otherSpells.loadMore({{ $page }}).then(() => {
                    document.getElementById('extra')?.scrollIntoView({ behavior: 'smooth' });
               })"
               class="join-item btn {{ $extraSpells->currentPage() === $page ? 'btn-active' : '' }}">
                {{ $page }}
            </a>
        @endforeach
    </div>
</div>
@endif
