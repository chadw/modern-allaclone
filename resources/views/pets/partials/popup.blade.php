<div class="w-full p-4 bg-base-200 rounded-lg border-1 border-base-content/20">
    <div class="flex justify-between items-start">
        <h1 class="text-2xl font-bold">{{ $pet->type }}</h1>
    </div>

    <div class="mt-2 space-y-1">
        <dl class="divide-y divide-gray-800">
            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium">Race</dt>
                <dd class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">{{ config('everquest.db_races.' . $pet->npcs->race) ?? 'Unknown' }}</dd>
            </div>
            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium">Class</dt>
                <dd class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">{{ config('everquest.classes.' . $pet->npcs->class) }}</dd>
            </div>
            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium">Size</dt>
                <dd class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">{{ $pet->npcs->size }}</dd>
            </div>
            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium">AC</dt>
                <dd class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">{{ number_format($pet->npcs->AC) }}</dd>
            </div>
            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium">HP</dt>
                <dd class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">{{ number_format($pet->npcs->hp) }}</dd>
            </div>
            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium">ATK</dt>
                <dd class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">{{ $pet->npcs->ATK }}</dd>
            </div>
            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium">DMG</dt>
                <dd class="mt-2 text-sm sm:col-span-2 sm:mt-0">{{ $pet->npcs->mindmg }}-{{ $pet->npcs->maxdmg }}</dd>
            </div>
            @if ($pet->npcs->special_abilities)
            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium">Special Abilities</dt>
                <dd class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">{{ implode(', ', $pet->npcs->parsed_special_abilities) }}</dd>
            </div>
            @endif
            <div class="px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium">Regen</dt>
                <dd class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0">{{ $pet->npcs->hp_regen_rate }}/tick</dd>
            </div>
        </dl>
        <div class="divider"></div>
        <div class="grid sm:grid-cols-2 gap-8">
            <div>
                <table class="w-full table-zebra">
                    <tr class="sm:hidden table-row">
                        <td colspan="2" class="border-b border-base-content/5 text-base-content">Stats</td>
                    </tr>
                    <x-item-stat name="STR" :stat="$pet->npcs->STR" :stat2="null" />
                    <x-item-stat name="STA" :stat="$pet->npcs->STA" :stat2="null" />
                    <x-item-stat name="INT" :stat="$pet->npcs->_INT" :stat2="null" />
                    <x-item-stat name="WIS" :stat="$pet->npcs->WIS" :stat2="null" />
                    <x-item-stat name="AGI" :stat="$pet->npcs->AGI" :stat2="null" />
                    <x-item-stat name="DEX" :stat="$pet->npcs->DEX" :stat2="null" />
                    <x-item-stat name="CHA" :stat="$pet->npcs->CHA" :stat2="null" />
                </table>
            </div>
            <div>
                <table class="w-full table-zebra">
                    <tr class="sm:hidden table-row">
                        <td colspan="2" class="border-b border-base-content/5 text-base-content">Resists</td>
                    </tr>
                    <x-item-stat name="MR" :stat="$pet->npcs->MR" :stat2="null" />
                    <x-item-stat name="FR" :stat="$pet->npcs->FR" :stat2="null" />
                    <x-item-stat name="CR" :stat="$pet->npcs->CR" :stat2="null" />
                    <x-item-stat name="DR" :stat="$pet->npcs->DR" :stat2="null" />
                    <x-item-stat name="PR" :stat="$pet->npcs->PR" :stat2="null" />
                </table>
            </div>
        </div>
    </div>
</div>
