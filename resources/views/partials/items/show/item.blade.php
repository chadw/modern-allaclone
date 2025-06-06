@switch($item->itemtype)
    @case(0)
    @case(2)

    @case(3)
    @case(42)

    @case(1)
    @case(4)

    @case(35)
        @php $itemTypePrefix = 'Skill'; @endphp
    @break

    @default
        @php $itemTypePrefix = 'Item Type'; @endphp
@endswitch

@php
    $tags = [];
    if ($item->itemtype == 54) {
        $tags[] = 'Augment';
    }
    if ($item->magic == 1) {
        $tags[] = 'Magic';
    }
    if ($item->loreflag == 1) {
        $tags[] = 'Lore';
    }
    if ($item->nodrop == 0) {
        $tags[] = 'No Trade';
    }
    if ($item->norent == 0) {
        $tags[] = 'No Rent';
    }
    if ($item->questitemflag == 1) {
        $tags[] = 'Quest';
    }
    if ($item->attuneable == 1) {
        $tags[] = 'Attuneable';
    }

    $itemTypes = config('everquest.item_types');
    $itemTypeName = $itemTypes[$item['itemtype']] ?? null;
    $itemValue = calculate_item_price($item->price);
@endphp

<div class="w-full p-4 bg-base-200 rounded-lg border-1 border-base-100 sticky top-120">
    <div class="flex justify-between items-start">
        <h1 class="text-2xl font-bold">{{ $item->Name }}</h1>
        <img src="{{ asset('img/items/' . $item->icon . '.png') }}" alt="{{ $item->Name }}" class="w-10 h-auto ml-4" />
    </div>

    <div class="mt-2 space-y-1 text-sm text-gray-300">
        <div>{{ implode(', ', $tags) }}</div>
        @if ($item->classes > 0)
            <div><strong>Class:</strong> {{ get_class_usable_string($item->classes) }}</div>
        @endif
        @if ($item->races > 0)
            <div><strong>Race:</strong> {{ get_race_usable_string($item->races) }}</div>
        @endif
        @if ($item->deity > 0)
            <div><strong>Deity:</strong> {{ get_deity_usable_string($item->deity) }}</div>
        @endif
        @if ($item->slots > 0)
            <div>{{ get_slots_string($item->slots) }}</div>
        @endif
        @if ($item->slots == 0)
            <div><strong>Slot:</strong> NONE</div>
        @endif
        @if ($item->bagslots > 0)
            <div><strong>Item Type:</strong> Container</div>
            <div><strong>Capacity:</strong> {{ $item->bagslots }}</div>
            @if ($item->bagtype == 13)
                <div><strong>Quest Container:</strong> {{ item_bagtypes($item->bagtype) }}</div>
            @elseif ($item->bagtype >= 9)
                <div><strong>Trade Skill Container:</strong> {{ item_bagtypes($item->bagtype) }}</div>
            @endif
            @if ($item->bagwr > 0)
                <div><strong>Weight Red:</strong> {{ $item->bagwr }}%</div>
            @endif
            <div>This can hold <span class="uppercase">{{ config('everquest.item_size')[$item->bagsize] ?? 'Unknown' }}</span> and smaller items.</div>
        @endif
    </div>
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-200">
        <div>
            <table class="w-full">
                <tr>
                    <td class="pr-2 font-bold">Size:</td>
                    <td class="text-right uppercase">{{ config('everquest.item_size')[$item->size] ?? 'Unknown' }}</td>
                </tr>
                <x-item-stat name="Weight" :stat="($item->weight / 10)" />

                @if ($itemTypeName && $item->bagslots == 0)
                    <tr>
                        <td class="pr-2 font-bold whitespace-nowrap align-text-top">{{ $itemTypePrefix }}:</td>
                        <td class="text-right">{{ $item->slots == 0 ? 'Inventory' : $itemTypeName }}</td>
                    </tr>
                @endif
                <x-item-stat name="Rec Level" :stat="$item->reclevel" />
                <x-item-stat name="Req Level" :stat="$item->reqlevel" />
            </table>
        </div>
        <div>
            <table class="w-full">
                <x-item-stat name="AC" :stat="$item->ac" />
                <x-item-stat name="HP" :stat="$item->hp" />
                <x-item-stat name="Mana" :stat="$item->mana" />
                <x-item-stat name="End" :stat="$item->endur" />
                @if ($item->haste > 0)
                    <x-item-stat name="Haste" :stat="$item->haste . '%'" />
                @endif
            </table>
        </div>
        <div>
            <table class="w-full">
                <x-item-stat name="Base Dmg" :stat="$item->damage" />
                @if ($item->elemdmgtype)
                    <x-item-stat :name="config('everquest.db_elements')[$item->elemdmgtype] . ' Dmg'" :stat="$item->elemdmgamt" />
                @endif

                <x-item-stat :name="config('everquest.db_bodytypes')[$item->banedmgbody]" :stat="$item->banedmgamt" />
                <x-item-stat name="Backstab Dmg" :stat="$item->backstabdmg" />
                <x-item-stat name="Delay" :stat="$item->delay" />

                @if ($item->damage > 0)
                    @switch($item->itemtype)
                        @case(0)
                        @case(2)

                        @case(4)
                        @case(42)
                            <tr>
                                <td class="pr-2 font-bold">Dmg Bon:</td>
                                <td class="text-right">13</td>
                            </tr>
                        @break

                        @case(1)
                        @case(4)

                        @case(35)
                            <tr>
                                <td class="pr-2 font-bold">Dmg Bon:</td>
                                <td class="text-right">{{ config('everquest.dmg2h')[$item->delay] }}</td>
                            </tr>
                        @break

                        @default
                    @endswitch
                @endif

                <x-item-stat name="Range" :stat="$item->range" />
            </table>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-100">
        <div>
            <table class="w-full">
                <tr class="sm:hidden table-row">
                    <td colspan="2" class="border-b border-base-content/5 text-base-content">Stats</td>
                </tr>
                <x-item-stat name="Strength" :stat="$item->astr" :stat2="$item->heroic_str" />
                <x-item-stat name="Stamina" :stat="$item->asta" :stat2="$item->heroic_sta" />
                <x-item-stat name="Intelligence" :stat="$item->aint" :stat2="$item->heroic_int" />
                <x-item-stat name="Wisdom" :stat="$item->awis" :stat2="$item->heroic_wis" />
                <x-item-stat name="Agility" :stat="$item->aagi" :stat2="$item->heroic_agi" />
                <x-item-stat name="Dexterity" :stat="$item->adex" :stat2="$item->heroic_dex" />
                <x-item-stat name="Charisma" :stat="$item->acha" :stat2="$item->heroic_cha" />
            </table>
        </div>
        <div>
            <table class="w-full">
                <tr class="sm:hidden table-row">
                    <td colspan="2" class="border-b border-base-content/5 text-base-content">Resists</td>
                </tr>
                <x-item-stat name="Magic" :stat="$item->mr" :stat2="$item->heroic_mr" />
                <x-item-stat name="Fire" :stat="$item->fr" :stat2="$item->heroic_fr" />
                <x-item-stat name="Cold" :stat="$item->cr" :stat2="$item->heroic_cr" />
                <x-item-stat name="Disease" :stat="$item->dr" :stat2="$item->heroic_dr" />
                <x-item-stat name="Poison" :stat="$item->pr" :stat2="$item->heroic_pr" />
            </table>
        </div>
        <div>
            <table class="w-full">
                <x-item-stat name="Attack" :stat="$item->attack" />
                <x-item-stat name="HP Regen" :stat="$item->regen" />
                <x-item-stat name="Mana Regen" :stat="$item->manaregen" />
                <x-item-stat name="End Regen" :stat="$item->enduranceregen" />
                <x-item-stat name="Spell Shield" :stat="$item->spellshield" />
                <x-item-stat name="Combat Eff" :stat="$item->combateffects" />
                <x-item-stat name="Shielding" :stat="$item->shielding" />
                <x-item-stat name="Dmg Shield" :stat="$item->damageshield" />
                <x-item-stat name="DoT Shield" :stat="$item->dotshielding" />
                <x-item-stat name="Dmg Shld Mit" :stat="$item->dsmitigation" />
                <x-item-stat name="Avoidance" :stat="$item->avoidance" />
                <x-item-stat name="Accuracy" :stat="$item->accuracy" />
                <x-item-stat name="Stun Resist" :stat="$item->stunresist" />
                <x-item-stat name="Strike Thr" :stat="$item->strikethrough" />
                <x-item-stat name="Spell Dmg" :stat="$item->spelldmg" />
            </table>
        </div>
    </div>

    <div class="mt-6 text-sm">
        @if ($item->banedmgrace > 0 && $item->banedmgraceamt != 0)
            <p>
                <strong>Bane Dmg: {{ config('everquest.db_races')[$item->banedmgrace] }}</strong>
                {{ sign($item->banedmgraceamt) }}
                {{-- if (($item["banedmgrace"] > 0) && ($item["banedmgamt"] != 0)) { --}}
            </p>
            <br>
        @endif
        @if ($item->extradmgamt > 0)
            <p>
                <strong>{{ config('everquest.db_skills')[$item->extradmgskill] }} Dmg</strong>
                {{ sign($item->extradmgamt) }}
            </p>
        @endif
        {{-- skill mods --}}
        @if ($item->skillmodtype > 0 && $item->skillmodvalue != 0)
            <p>
                <strong>Skill Mod: {{ config('everquest.db_skills')[$item->skillmodtype] }}:</strong>
                {{ sign($item->skillmodvalue) }}%
            </p>
        @endif
        {{-- augmentations --}}
        @for ($i = 1; $i <= 5; $i++)
            @php
                $type = $item->{'augslot' . $i . 'type'} ?? 0;
            @endphp
            @if ($type > 0)
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-warning"></div>
                    <strong class="mr-2">Slot {{ $i }}:</strong> Type {{ $type }}
                </div>
            @endif
        @endfor
        <br>
        {{-- item proc --}}
        @if ($item->proceffect > 0 && $item->proceffect < 65535)
            <p>
                <strong>Combat Effect:</strong> <a class="text-sky-600 no-underline hover:underline dark:text-sky-400"
                    href="/spells/{{ $item->proceffect }}">{{ $item->custom_proceffect }}</a>
                @if ($item->proclevel2 > 0)
                    <br>
                    <strong>Level for effect:</strong> {{ $item->proclevel2 }}
                @endif
                <br>
                <strong>Effect chance modifier:</strong> {{ 100 + $item->procrate }}%
            </p>
        @endif
        {{-- worn effect --}}
        @if ($item->worneffect > 0 && $item->worneffect < 65535)
            <p>
                <strong>Worn Effect:</strong> <a
                    href="/spells/{{ $item->worneffect }}">{{ $item->custom_worneffect }}</a>
                @if ($item->wornlevel > 0)
                    <br>
                    <strong>Level for effect:</strong> {{ $item->wornlevel }}
                @endif
            </p>
        @endif
        {{-- focus effect --}}
        @if ($item->focuseffect > 0 && $item->focuseffect < 65535)
            <p>
                <strong>Focus Effect:</strong> <a
                    href="/spells/{{ $item->focuseffect }}">{{ $item->custom_focuseffect }}</a>
                @if ($item->focuslevel > 0)
                    <br>
                    <strong>Level for effect:</strong> {{ $item->focuslevel }}
                @endif
            </p>
        @endif
        {{-- clicky effect --}}
        @if ($item->clickeffect > 0 && $item->clickeffect < 65535)
            <p>
                <strong>Click Effect:</strong> <a
                    href="/spells/{{ $item->clickeffect }}">{{ $item->custom_clickeffect }}</a>
                (
                @if ($item->clicktype == 4)
                    Must Equip.
                @endif
                @if ($item->casttime > 0)
                    <strong>Casting time:</strong> {{ $item->casttime / 1000 }} sec
                @else
                    <strong>Casting time:</strong> Instant
                @endif
                )
                @if ($item->clicklevel > 0)
                    <br><strong>Level for effect:</strong> {{ $item->clicklevel }}
                @endif
                @if ($item->maxcharges > 0)
                    <br><strong>Charges:</strong> {{ $item->maxcharges }}
                @elseif ($item->maxcharges < 0)
                    <br><strong>Charges:</strong> Unlimited
                @else
                    <br><strong>Charges:</strong> None
                @endif
            </p>
        @endif
        {{-- scroll --}}
        @if ($item->scrolleffect > 0 && $item->scrolleffect < 65535)
            <p>
                <strong>Spell Scroll Effect:</strong> <a
                    href="/spells/{{ $item->scrolleffect }}">{{ $item->custom_scrolleffect }}</a>
            </p>
        @endif
        {{-- bard item? --}}
        @if ($item->bardtype > 22 && $item->bardtype < 65535)
            <p>
                <strong>Bard skill:</strong> {{ config('everquest.db_bard_skills')[$item->bardtype] }}
                @if (config('everquest.db_bard_skills')[$item->bardtype] == '')
                    Unknown {{ $item->bardtype }}
                @endif

                @php
                    $bardVal = $item->bardvalue * 10 - 100;
                @endphp
                @if ($bardVal > 0)
                    ({{ sign($bardVal) }})
                @endif
            </p>
        @endif
    </div>

    {!! item_aug_data($item) !!}

    <div class="mt-4 text-sm">
        {{-- food/drink type --}}
        @if (($item->itemtype == 14 || $item->itemtype == 15) && $item->casttime_)
            <span class="block mb-2">{{ get_food_drink_desc($item->casttime_, $item->itemtype) }}</span>
        @endif
        {{-- stack size --}}
        @if ($item->stackable > 0 && $item->stacksize > 0)
            <strong>Stackable Count:</strong> {{ $item->stacksize }}<br>
        @endif
        {{-- item value --}}
        <strong>Value:</strong>
        <span class="inline-flex">
            <span>{{ $itemValue['platinum'] }} pp</span>
            <span class="ml-2">{{ $itemValue['gold'] }} gp</span>
            <span class="ml-2">{{ $itemValue['silver'] }} sp</span>
            <span class="ml-2">{{ $itemValue['copper'] }} cp</span>
        </span>
        {{-- tribute --}}
        @if ($item->favor > 0)
            <br><strong>Tribute Value:</strong> {{ $item->favor }}
        @endif
    </div>

    @if (!empty($item->lore))
        <div class="mt-6 border-t border-sky-950 dark:border-sky-950 pt-4 text-sm text-gray-400 italic">
            {{ $item->lore }}
        </div>
    @endif
</div>
