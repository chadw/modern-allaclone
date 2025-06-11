<div id="navbar-trigger" class="h-0"></div>
<nav class="navbar bg-neutral mb-3 sticky top-0 z-50">
    <div class="container mx-auto px-4 flex items-center justify-between w-full">

        <div class="flex items-center xl:w-1/3 w-auto">
            <a href="/" class="xl:hidden">
                <img src="{{ asset('img/eqemu.png') }}" class="min-w-[80px] min-h-[38px]">
            </a>

            <div class="dropdown xl:hidden ml-2">
                <label tabindex="0" class="btn btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </label>
                <ul tabindex="0" class="dropdown-content mt-3 z-[60] menu p-2 shadow bg-base-200 rounded-box w-52">
                    <li><a href="{{ route('zones.index') }}" class="uppercase">Zones</a></li>
                    <li><a href="{{ route('items.index') }}" class="uppercase">Items</a></li>
                    <li><a href="{{ route('spells.index') }}" class="uppercase">Spells</a></li>
                    <li><a href="{{ route('npcs.index') }}" class="uppercase">NPCs</a></li>
                    <li tabindex="0">
                        <details>
                            <summary class="uppercase flex items-center justify-between cursor-pointer">
                                More
                            </summary>
                            <ul class="pl-4">
                                <li><a href="{{ route('recipes.index') }}">Recipes</a></li>
                                <li><a href="{{ route('tasks.index') }}">Tasks</a></li>
                                <li><a href="{{ route('factions.index') }}">Faction</a></li>
                                <li><a href="{{ route('pets.index') }}">Pets</a></li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </div>
        </div>

        <div id="eqemu-desktop-logo" class="hidden xl:flex justify-center xl:w-1/3 relative">
            <a href="/" class="block absolute -top-9">
                <img src="{{ asset('img/eqemu.png') }}" class="w-[158px] h-[76px]">
            </a>
        </div>

        <div class="flex items-center justify-end xl:w-1/3 w-full">
            <form @submit.prevent class="flex items-center space-x-2 w-full justify-end">
                <div x-data="eqsearch()" @click.away="results = []" class="relative w-full max-w-xs">
                    <input type="text" placeholder="Search NPCs, Items, Recipes..." x-model="query"
                        @input.debounce.600ms="load" @focus="if (query.length > 0) load()" @keydown.enter.prevent
                        class="input input-bordered input-sm text-white w-full focus:outline-none" autocomplete="off" />
                    <div x-show="results.length > 0 || loading"
                        class="absolute right-0 mt-2 z-50
                               max-h-90 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-slate-800
                               sm:max-h-none sm:overflow-visible sm:scrollbar-none
                               sm:min-w-full sm:w-screen sm:max-w-md lg:max-w-lg xl:max-w-2xl">
                        <div class="bg-base-200 border border-sky-900/50 rounded shadow-lg p-2">
                            <template x-if="loading">
                                <div class="flex justify-center items-center p-2">
                                    <svg class="animate-spin h-5 w-5 text-sky-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                    </svg>
                                </div>
                            </template>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                <template x-for="result in results" :key="result.id">
                                    <li class="hover:bg-base-100 cursor-pointer rounded p-1 px-2 transition">
                                        <a :href="result.url" class="flex justify-between items-center space-x-2">
                                            <span class="block text-sm text-base-content truncate"
                                                x-text="result.name"></span>
                                            <span class="text-xs text-accent whitespace-nowrap"
                                                :class="{
                                                    'text-soft-accent': result.type === 'zone',
                                                    'text-primary': result.type === 'item',
                                                    'text-info': result.type === 'npc',
                                                    'text-warning': result.type === 'spell',
                                                    'text-success': result.type === 'recipe',
                                                }"
                                                x-text="result.type"></span>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="hidden xl:flex space-x-2 absolute left-5 top-1/2 -translate-y-1/2">
            <a href="{{ route('zones.index') }}" class="btn btn-ghost uppercase">Zones</a>
            <a href="{{ route('items.index') }}" class="btn btn-ghost uppercase">Items</a>
            <a href="{{ route('spells.index') }}" class="btn btn-ghost uppercase">Spells</a>
            <a href="{{ route('npcs.index') }}" class="btn btn-ghost uppercase">NPCs</a>
            <div class="dropdown dropdown-hover">
                <label tabindex="0" class="btn btn-ghost uppercase flex items-center gap-1">
                    More
                    <svg class="chevron h-4 w-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </label>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="{{ route('recipes.index') }}">Recipes</a></li>
                    <li><a href="{{ route('tasks.index') }}">Tasks</a></li>
                    <li><a href="{{ route('factions.index') }}">Faction</a></li>
                    <li><a href="{{ route('pets.index') }}">Pets</a></li>
                </ul>
            </div>
        </div>

    </div>
</nav>
