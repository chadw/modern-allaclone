<div class="flex w-full flex-col">
    <div class="divider">This item is found on creatures</div>
</div>

<div x-data="itemDrops({{ $item->id }})" x-init="load()"
    class="max-h-96 scrollbar-thin scrollbar-thumb-accent scrollbar-track-base-300 overflow-y-auto mb-7">
    <template x-if="loading">
        <div>
            <template x-for="n in 6">
                <div class="px-3 py-2 animate-pulse bg-base-300 border-b border-base-200 flex justify-between">
                    <div class="h-4 bg-base-100 rounded w-1/3"></div>
                    <div class="h-4 bg-base-100 rounded w-1/5"></div>
                </div>
            </template>
        </div>
    </template>
    <template x-if="!loading && drops.length > 0">
        <template x-for="drop in drops" :key="drop.zone">
            <div class="px-1">
                <span class="block bg-neutral/80 text-sky-400 mt-2 p-2 font-bold sticky top-0"
                    x-text="drop.zone_name"></span>
                <template x-if="drop.npcs.length > 0">
                    <ul role="list" class="list bg-base-300 divide-y divide-base-200">
                        <template x-for="npc in drop.npcs" :key="npc.id">
                            <li class="flex justify-between gap-x-6 px-3 py-1">
                                <div class="flex min-w-0 gap-x-4">
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm/6 font-semibold text-neutral-content">
                                            <a
                                                :href="@js(route('npcs.show', '__ID__')).replace('__ID__', npc.id)"
                                                x-text="npc.clean_name"
                                                class="link-info link-hover"></a>
                                        </p>
                                    </div>
                                </div>
                                <div class="shrink-0 sm:flex sm:flex-col sm:items-end">
                                    <p class="mt-1 text-xs/5 font-medium text-accent" x-text="`${npc.chance}%`"></p>
                                </div>
                            </li>
                        </template>
                    </ul>
                </template>
            </div>
        </template>
    </template>
    <template x-if="!loading && drops.length === 0">
        <div class="text-sm text-gray-400 px-2 py-4 italic">No NPCs found that drop this item.</div>
    </template>
</div>
