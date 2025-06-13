<div id="navbar-trigger" class="h-0"></div>
<nav class="navbar bg-neutral mb-3 sticky top-0 z-50">
    <div class="container mx-auto px-4 flex items-center justify-between w-full">

        <div class="flex items-center xl:w-1/3 w-auto">
            <a href="/" class="xl:hidden" title="Modern EQEmu Allaclone">
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
                    <li><a href="{{ route('zones.index') }}" class="uppercase" title="Zones">Zones</a></li>
                    <li><a href="{{ route('items.index') }}" class="uppercase" title="Items">Items</a></li>
                    <li><a href="{{ route('spells.index') }}" class="uppercase" title="Spells">Spells</a></li>
                    <li><a href="{{ route('npcs.index') }}" class="uppercase" title="NPCs">NPCs</a></li>
                    <li tabindex="0">
                        <details>
                            <summary class="uppercase flex items-center justify-between cursor-pointer" title="More">
                                More
                            </summary>
                            <ul class="pl-4">
                                <li><a href="{{ route('recipes.index') }}" title="Recipes">Recipes</a></li>
                                <li><a href="{{ route('tasks.index') }}" title="Tasks">Tasks</a></li>
                                <li><a href="{{ route('factions.index') }}" title="Faction">Faction</a></li>
                                <li><a href="{{ route('pets.index') }}" title="Pets">Pets</a></li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </div>
        </div>

        <div id="eqemu-desktop-logo" class="hidden xl:flex justify-center xl:w-1/3 relative">
            <a href="/" class="block absolute -top-9" title="Modern EQEmu Allaclone">
                <img src="{{ asset('img/eqemu.png') }}" class="w-[158px] h-[76px]">
            </a>
        </div>

        <div class="flex items-center justify-end xl:w-1/3 w-full">
            @include('partials.suggest-search')
        </div>

        <div class="hidden xl:flex space-x-2 absolute left-5 top-1/2 -translate-y-1/2">
            <a href="{{ route('zones.index') }}" title="Zones" class="btn btn-ghost uppercase">Zones</a>
            <a href="{{ route('items.index') }}" title="Items" class="btn btn-ghost uppercase">Items</a>
            <a href="{{ route('spells.index') }}" title="Spells" class="btn btn-ghost uppercase">Spells</a>
            <a href="{{ route('npcs.index') }}" title="NPCs" class="btn btn-ghost uppercase">NPCs</a>
            <div class="dropdown dropdown-hover">
                <label tabindex="0" class="btn btn-ghost uppercase flex items-center gap-1" title="More">
                    More
                    <svg class="chevron h-4 w-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </label>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="{{ route('recipes.index') }}" title="Recipes">Recipes</a></li>
                    <li><a href="{{ route('tasks.index') }}" title="Tasks">Tasks</a></li>
                    <li><a href="{{ route('factions.index') }}" title="Faction">Faction</a></li>
                    <li><a href="{{ route('pets.index') }}" title="Pets">Pets</a></li>
                </ul>
            </div>
        </div>

    </div>
</nav>
