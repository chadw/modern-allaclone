<div class="flex w-full flex-col">
    <div class="divider">This item is used in tradeskill recipes</div>
</div>

<div class="max-h-96 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-800 overflow-y-auto">
    <ul role="list" class="list bg-base-300 divide-y divide-base-200">
    @foreach ($used_in_ts as $ts)
        <li class="flex justify-between gap-x-6 px-3 py-1">
            <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                    <p class="text-sm/6 font-semibold text-neutral-content">
                        <a
                            href="{{ route('recipes.show', $ts->id) }}"
                            class="link-info link-hover">{{ ucRomanNumeral($ts->name) }}</a>
                    </p>
                </div>
            </div>
            <div class="shrink-0 sm:flex sm:flex-col sm:items-end">
                <p class="mt-1 text-xs/5 font-medium text-accent">
                    {{ config('everquest.db_skills.' . $ts->tradeskill) ?? '' }}
                </p>
            </div>
        </li>
    @endforeach
    </ul>
</div>
