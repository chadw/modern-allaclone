<?php

namespace App\Http\Controllers;

use App\Models\AlternateCurrency;
use App\Models\Item;
use App\Models\Task;
use App\Models\Zone;
use App\Models\NpcType;
use App\Models\SpawnTwo;
use App\Models\SpawnGroup;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $currentExpansion = config('everquest.current_expansion');
        $expansions = config('everquest.expansions');

        $zones = Zone::getExpansionZones($currentExpansion);

        return view('zones.index', compact('zones', 'expansions'));
    }

    public function show(Zone $zone, Request $request)
    {
        $version = $request->query('v', 0);
        $server_level = config('everquest.server_max_level');

        $zone = Zone::where('id', $zone->id)
            ->with('zonepoints', function ($q) use ($version) {
                $q->when($version > 0, fn ($q) => $q->where('version', $version))->groupBy('target_zone_id')
                    ->with('targetZones', function ($q) {
                        $q->select('id', 'zoneidnumber', 'short_name', 'long_name');
                    });
            })
            ->when($version > 0, fn ($q) => $q->where('version', $version))
            ->firstOrFail();

        $connectedZones = $zone->zonepoints
            ->pluck('targetZones')
            ->filter()
            ->unique('id')
            ->sortBy('long_name')
            ->values();

        // npcs
        $npcs = NpcType::whereHas('spawnentries.spawn2', function ($query) use ($version, $zone) {
            $query->where('zone', $zone->short_name)->when($version > 0, fn ($q) => $q->where('version', $version));
        })
            ->whereNotIn('race', [127, 240])
            ->select(
                'id', 'class', 'hp', 'level', 'trackable', 'maxlevel', 'race', 'name', 'loottable_id',
                'raid_target', 'rare_spawn'
            )
            ->groupBy('name')
            ->get()
            ->sortBy(fn ($npc) => $npc->clean_name)
            ->values();

        // drops
        $loottableIds = $npcs->pluck('loottable_id')->unique()->filter()->all();
        $items = Item::select([
                'items.id', 'items.Name', 'items.icon', 'items.itemtype', 'items.bagslots',
                'loottable_entries.loottable_id',
            ])
            ->join('lootdrop_entries', 'items.id', '=', 'lootdrop_entries.item_id')
            ->join('loottable_entries', 'lootdrop_entries.lootdrop_id', '=', 'loottable_entries.lootdrop_id')
            ->whereIn('loottable_entries.loottable_id', $loottableIds)
            ->groupBy('items.id', 'loottable_entries.loottable_id') // group by loottable_id to track NPCs
            ->orderBy('items.Name')
            //if ($discovered_items_only) {
                //$items = $items->join('discovered_items', 'items.id', '=', 'discovered_items.item_id');
            //}
            ->get();

        $npcMap = [];
        foreach ($npcs as $npc) {
            if ($npc->loottable_id) {
                $npcMap[$npc->loottable_id][] = $npc;
            }
        }

        $drops = [];
        foreach ($items as $item) {
            if (!isset($drops[$item->id])) {
                $drops[$item->id] = [
                    'item' => $item,
                    'npcs' => [],
                ];
            }

            $drops[$item->id]['npcs'] = array_merge(
                $drops[$item->id]['npcs'],
                $npcMap[$item->loottable_id] ?? []
            );
        }

        uasort($drops, fn($a, $b) => strcasecmp($a['item']->Name, $b['item']->Name));

        // spawn groups
        $spawnGroups = SpawnTwo::with([
            'spawnGroup.spawnentries.npc' => function ($q) {
                $q->whereNotIn('race', [127, 240]);
            }
        ])
            ->where('zone', $zone->short_name)
            ->when($version > 0, fn ($q) => $q->where('version', $version))
            ->whereHas('spawnGroup.spawnentries.npc', function ($q) {
                $q->whereNotIn('race', [127, 240]);
            })
            ->get()
            ->map(function ($spawn2) {
                $group = $spawn2->spawnGroup;
                $group->x = $spawn2->x;
                $group->y = $spawn2->y;
                $group->z = $spawn2->z;
                $group->respawntime = $spawn2->respawntime;
                return $group;
            })
            ->sortBy('name')
            ->values();

        // foraged
        $foraged = Item::whereHas('foraged.zone', function ($query) use ($zone) {
                $query->where('zoneid', $zone->zoneidnumber);
            })
            ->select('Name', 'id', 'icon', 'itemtype', 'bagslots')
            ->orderBy('name', 'asc')
            ->get();

        // tasks
        $tasks = Task::whereHas('taskActivities', function ($query) use ($zone) {
            $query->where('zones', $zone->zoneidnumber)
                  ->where(function ($subQuery) use ($zone) {
                      $subQuery->where('zone_version', $zone->version)
                               ->orWhere('zone_version', -1);
                  });
        })->with(['taskActivities' => function ($query) use ($zone) {
            $query->where('zones', $zone->zoneidnumber)
                  ->where(function ($subQuery) use ($zone) {
                      $subQuery->where('zone_version', $zone->version)
                               ->orWhere('zone_version', -1);
                  });
        }])
        ->where('min_level', '<=', $server_level)
        ->where('enabled', 1)
        ->orderBy('min_level')->get();

        // get alt currency since tasks could use it
        $altCurrency = AlternateCurrency::with('item:id,Name,icon')->get();

        return view('zones.show', compact(
            'zone', 'npcs', 'drops', 'spawnGroups', 'foraged', 'connectedZones',
            'tasks', 'altCurrency',
        ));
    }
}
