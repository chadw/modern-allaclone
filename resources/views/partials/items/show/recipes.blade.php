<div class="flex w-full flex-col">
    <div class="divider">This item is the result of tradeskill recipes</div>
</div>

<div class="max-h-96 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-800 overflow-y-auto">
    <ul role="list" class="list bg-base-300 divide-y divide-base-200">
    @foreach ($recipes as $recipe)
        <li class="flex justify-between gap-x-6 px-3 py-1">
            <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                    <p class="text-sm/6 font-semibold text-neutral-content">
                        <a href="/recipes/{{ $recipe->id }}">{{ ucRomanNumeral($recipe->name) }}</a>
                    </p>
                    <p class="mt-1 truncate text-xs/5 text-gray-500">{{ config('everquest.db_skills')[$recipe->tradeskill] }}</p>
                </div>
            </div>
            <div class="shrink-0 sm:flex sm:flex-col sm:items-end">
                <p class="mt-1 text-xs/5 font-medium {{ $recipe->trivial > 300 ? 'text-red-500' : 'text-accent' }}">
                    {{ $recipe->trivial }} <span class="text-white">trivial</span>
                </p>
            </div>
        </li>
    @endforeach
    </ul>
</div>
