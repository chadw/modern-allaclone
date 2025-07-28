<?php
namespace App\ViewModels;

use App\Models\Item;
use App\Models\Zone;
use App\Models\Forage;
use App\Models\Fishing;
use App\Models\NpcType;
use App\Models\GroundSpawn;
use App\Models\TradeskillRecipe;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ItemViewModel
{
    public function __construct(public Item $item)
    {
        $relations = [];

        if ($item->proceffect > 0 && $item->proceffect < 65535) {
            $relations[] = 'procEffectSpell';
        }

        if ($item->worneffect > 0 && $item->worneffect < 65535) {
            $relations[] = 'wornEffectSpell';
        }

        if ($item->focuseffect > 0 && $item->focuseffect < 65535) {
            $relations[] = 'focusEffectSpell';
        }

        if ($item->clickeffect > 0 && $item->clickeffect < 65535) {
            $relations[] = 'clickEffectSpell';
        }

        if ($item->scrolleffect > 0 && $item->scrolleffect < 65535) {
            $relations[] = 'scrollEffectSpell';
        }

        if (!empty($relations)) {
            $this->item->load($relations);
        }
    }

    public function withEffects()
    {
        $i = $this->item;

        $i->custom_proceffect = $i->relationLoaded('procEffectSpell') && $i->procEffectSpell
            ? $i->procEffectSpell->name
            : null;

        $i->custom_worneffect = $i->relationLoaded('wornEffectSpell') && $i->wornEffectSpell
            ? $i->wornEffectSpell->name
            : null;

        $i->custom_focuseffect = $i->relationLoaded('focusEffectSpell') && $i->focusEffectSpell
            ? $i->focusEffectSpell->name
            : null;

        $i->custom_clickeffect = $i->relationLoaded('clickEffectSpell') && $i->clickEffectSpell
            ? $i->clickEffectSpell->name
            : null;

        $i->custom_scrolleffect = $i->relationLoaded('scrollEffectSpell') && $i->scrollEffectSpell
            ? $i->scrollEffectSpell->name
            : null;

        return $this;
    }

    public function dropsByZone(): array
    {
        $ignoreZones = config('everquest.ignore_zones') ?? [];
        $excludeMerchants = config('everquest.merchants_dont_drop_stuff') ?? true;
        $currentExpansion = config('everquest.current_expansion');

        $allZones = Cache::rememberForever('all_zones', function () {
            return Zone::select('id', 'short_name', 'long_name', 'expansion')
                ->orderBy('id')
                ->get()
                ->unique('short_name')
                ->keyBy('short_name');
        });

        $itemId = $this->item->id;

        $item = Item::with([
            'lootdropEntries.lootdrop.loottableEntries.npcs.spawnEntries.spawn2'
        ])
        ->where('id', $itemId)
        ->select('id')
        ->first();

        $results = [];
        foreach ($item->lootdropEntries as $lde) {
            $lootdrop = $lde->lootdrop;
            foreach ($lootdrop->loottableEntries as $lte) {
                foreach ($lte->npcs as $npc) {
                    foreach ($npc->spawnEntries as $se) {

                        $spawn2 = $se->spawn2;
                        $npcCleanName = $npc->clean_name;
                        if (empty(trim($npcCleanName ?? ''))) {
                            continue;
                        }

                        // ignore merchants
                        if ($excludeMerchants && $npc->merchant_id !== 0) {
                            continue;
                        }

                        if ($se->chance <= 0) {
                            continue;
                        }

                        // ignore zones
                        if (!$spawn2 || !$spawn2->zone || in_array($spawn2->zone, $ignoreZones)) {
                            continue;
                        }

                        $zone = strtolower($spawn2->zone);

                        if (($allZones[$zone]['expansion'] ?? 0) > $currentExpansion) {
                            continue;
                        }

                        $results[] = [
                            'zone'          => $zone,
                            'zone_name'     => $allZones[$zone]['long_name'] ?? $zone,
                            'npc_id'        => $npc->id,
                            'npc_name'      => $npc->name,
                            'clean_name'    => $npcCleanName,
                            'multiplier'    => $lte->multiplier,
                            'chance'        => $lde->chance,
                            'probability'   => $lte->probability,
                        ];
                    }
                }
            }
        }

        return collect($results)
            ->groupBy('zone')
            ->map(function ($zoneDrops, $zone) {
                return [
                    'zone'      => $zone,
                    'zone_name' => $zoneDrops->first()['zone_name'],
                    'npcs'      => $zoneDrops->groupBy('npc_name')->map(function ($group) {
                        $npc = $group->first();
                        return [
                            'id'          => $npc['npc_id'],
                            'name'        => $npc['npc_name'],
                            'clean_name'  => $npc['clean_name'],
                            'multiplier'  => $npc['multiplier'],
                            'probability' => $npc['probability'],
                            'chance'      => $npc['chance'],
                        ];
                    })
                    ->unique('id')
                    ->sortBy('clean_name', SORT_NATURAL | SORT_FLAG_CASE)
                    ->values(),
                ];
            })
            ->sortBy('zone_name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->toArray();
    }

    public function recipes(): Collection
    {
        return TradeskillRecipe::whereHas('entries', function ($q) {
            $q->where('item_id', $this->item->id)
                ->where('successcount', '>', 0);
        })
        ->select('id', 'name', 'tradeskill', 'trivial', 'nofail')
        ->groupBy('id', 'name', 'tradeskill')
        ->get();
    }

    public function usedInTradeskills(): Collection
    {
        return TradeskillRecipe::whereHas('entries', function ($q) {
            $q->where('item_id', $this->item->id)
                ->where('componentcount', '>', 0);
        })
        ->select('id', 'name', 'tradeskill')
        ->groupBy('id', 'name', 'tradeskill')
        ->get();
    }

    public function forageZones(): Collection
    {
        $expansions = config('everquest.expansions');

        return Forage::with('zone')
            ->where('itemid', $this->item->id)
            ->select('zoneid', 'chance', 'level')
            ->groupBy('zoneid', 'chance', 'level')
            ->get()
            ->map(function ($forage) use($expansions) {
                $zone = $forage->zone;
                $expansionName = $zone && isset($expansions[$zone->expansion])
                    ? " ({$expansions[$zone->expansion]})"
                    : '';
                return [
                    'zone_id' => $zone->id ?? null,
                    'short_name' => $zone->short_name ?? null,
                    'long_name' => $zone->long_name ?? 'Unknown',
                    'expansion' => $expansionName,
                    'chance' => $forage->chance,
                    'level' => $forage->level,
                ];
            })->sortBy('long_name')
            ->values();
    }

    public function fishingZones(): Collection
    {
        $expansions = config('everquest.expansions');

        return Fishing::with('zone')
            ->where('itemid', $this->item->id)
            ->where('zoneid', '>', 0)
            ->select('zoneid', 'chance', 'skill_level')
            ->groupBy('zoneid', 'chance', 'skill_level')
            ->get()
            ->map(function ($fishing) use($expansions) {
                $zone = $fishing->zone;
                $expansionName = $zone && isset($expansions[$zone->expansion])
                    ? " ({$expansions[$zone->expansion]})"
                    : '';
                return [
                    'zone_id' => $zone->id ?? null,
                    'short_name' => $zone->short_name ?? null,
                    'long_name' => $zone->long_name ?? 'Unknown',
                    'expansion' => $expansionName,
                    'chance' => $fishing->chance,
                    'level' => $fishing->skill_level,
                ];
            })->sortBy('long_name')
            ->values();
    }

    public function soldInZones(): Collection
    {
        return NpcType::whereHas('merchantlist', fn($q) => $q->where('item', $this->item->id))
            ->whereHas('spawnentries.spawn2.zoneData')
            ->with('spawnentries.spawn2.zoneData')
            ->get()
            ->map(function ($npc) {
                $spawn = $npc->spawnentries->pluck('spawn2')->filter()->first();
                $zone = $spawn?->zoneData;

                return [
                    'id' => $npc->id,
                    'name' => $npc->name,
                    'clean_name' => $npc->clean_name,
                    'zone' => $spawn?->zone,
                    'zone_long_name' => $zone?->long_name,
                    'class' => $npc->class,
                ];
            })->groupBy('zone')->map(function ($items, $zone) {
                return [
                    'zone' => $zone,
                    'zone_name' => $items->first()['zone_long_name'] ?? '',
                    'npcs' => $items->map(fn($npc) => [
                        'id' => $npc['id'],
                        'name' => $npc['name'],
                        'clean_name' => $npc['clean_name'],
                        'class' => $npc['class'],
                    ])->values(),
                ];
            })->sortBy('zone_name')
            ->values();
    }

    public function itemGroundSpawn(): Collection
    {
        return GroundSpawn::select(['zoneid', 'max_x', 'max_y', 'max_z'])
            ->with(['zone:id,zoneidnumber,long_name'])
            ->where('item', $this->item->id)
            ->get()
            ->map(function ($spawn) {
                return [
                    'zone_id' => $spawn->zone?->zoneidnumber,
                    'zone_name' => $spawn->zone?->long_name,
                    'x' => $spawn->max_x,
                    'y' => $spawn->max_y,
                    'z' => $spawn->max_z,
                ];
            })
            ->groupBy('zone_id')
            ->map(function ($items, $zoneId) {
                return [
                    'zone' => $zoneId,
                    'zone_name' => $items->first()['zone_name'] ?? '',
                    'spawns' => $items->map(fn($item) => [
                        'x' => $item['x'],
                        'y' => $item['y'],
                        'z' => $item['z'],
                    ])->values(),
                ];
            })->sortBy('zone_name')
            ->values();
    }
}
