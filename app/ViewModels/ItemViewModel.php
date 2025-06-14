<?php
namespace App\ViewModels;

use App\Models\Item;
use App\Models\Forage;
use App\Models\NpcType;
use App\Models\GroundSpawn;
use App\Models\LootdropEntry;
use App\Models\TradeskillRecipe;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    public function dropsByZone(): Collection
    {
        $itemId = $this->item->id;

        $lootdropIds = LootdropEntry::where('item_id', $itemId)->pluck('lootdrop_id');
        if ($lootdropIds->isEmpty()) return collect();//[];

        $drops = $this->getDropData($itemId, $lootdropIds, false);
        $drops2 = $this->getDropData($itemId, $lootdropIds, true);

        return $drops->keyBy('zone')->merge($drops2->keyBy('zone'))
            ->map(function ($zoneGroup, $zoneKey) use ($drops, $drops2) {
                $npcs = collect($drops->firstWhere('zone', $zoneKey)['npcs'] ?? [])
                    ->merge($drops2->firstWhere('zone', $zoneKey)['npcs'] ?? [])
                    ->unique('name')
                    ->values();

                return [
                    'zone' => $zoneKey,
                    'zone_name' => $zoneGroup['zone_name'],
                    'npcs' => $npcs,
                ];
        })->values();
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
                    'zone_id' => $zone->zoneidnumber ?? null,
                    'short_name' => $zone->short_name ?? null,
                    'long_name' => $zone->long_name ?? 'Unknown',
                    'expansion' => $expansionName,
                    'chance' => $forage->chance,
                    'level' => $forage->level,
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

    protected function getDropData(
        int $itemId,
        Collection $lootdropIds,
        bool $isFallback
    ): Collection {

        $ignoreZones = config('everquest.ignore_zones') ?? [];
        $excludeMerchants = config('everquest.merchants_dont_drop_stuff') ?? true;

        $query = NpcType::select([
            'npc_types.id',
            'npc_types.name',
            $isFallback
                ? DB::raw('CAST(SUBSTRING(npc_types.id, 1, LENGTH(npc_types.id) - 3) AS UNSIGNED) as zone_guess_id')
                : 'spawn2.zone',
            'zone.short_name as zone',
            'zone.long_name',
            'loottable_entries.multiplier',
            'loottable_entries.probability',
            'lootdrop_entries.chance'
        ]);

        if ($isFallback) {
            $query->leftJoin('spawnentry', 'npc_types.id', '=', 'spawnentry.npcID')
                ->whereNull('spawnentry.npcID')
                ->join('zone', DB::raw('CAST(SUBSTRING(npc_types.id, 1, LENGTH(npc_types.id) - 3) AS UNSIGNED)'), '=', 'zone.zoneidnumber');
        } else {
            $query->join('spawnentry', 'npc_types.id', '=', 'spawnentry.npcID')
                ->join('spawn2', 'spawnentry.spawngroupID', '=', 'spawn2.spawngroupID')
                ->join('zone', 'spawn2.zone', '=', 'zone.short_name');
        }

        $query->join('loottable_entries', function ($join) use ($lootdropIds) {
            $join->on('npc_types.loottable_id', '=', 'loottable_entries.loottable_id')
                ->whereIn('loottable_entries.lootdrop_id', $lootdropIds);
        })
        ->join('lootdrop_entries', function ($join) use ($itemId) {
            $join->on('loottable_entries.lootdrop_id', '=', 'lootdrop_entries.lootdrop_id')
                ->where('lootdrop_entries.item_id', '=', $itemId);
        });

        if ($excludeMerchants) {
            $query->where('npc_types.merchant_id', '=', 0);
        }

        if (!empty($ignoreZones)) {
            $query->whereNotIn('zone.short_name', $ignoreZones);
        }

        return $query->orderBy('zone.long_name')
            ->get()
            ->groupBy('zone')
            ->map(function ($items, $zone) {
                return [
                    'zone' => $zone,
                    'zone_name' => $items->first()->long_name ?? '',
                    'npcs' => $items->map(fn ($drop) => [
                        'id' => $drop->id,
                        'name' => $drop->name,
                        'clean_name' => $drop->clean_name,
                        'multiplier' => $drop->multiplier,
                        'probability' => $drop->probability,
                        'chance' => $drop->chance,
                    ])->values(),
                ];
        })->values();
    }
}
