<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\NpcType;
use App\Models\NpcSpell;
use App\Filters\NpcFilter;
use Illuminate\Http\Request;

class NpcController extends Controller
{
    public function index(Request $request)
    {
        $npcs = collect();
        $currentExpansion = config('everquest.current_expansion');

        if ($request->query->count() > 0) {
            $npcs = (new NpcFilter($request))
                ->apply(NpcType::query())
                ->select('id', 'name', 'level', 'race', 'class', 'hp', 'maxlevel', 'version')
                ->whereNotIn('race', [127, 240])
                ->whereHas('spawnEntries', function ($query) use ($currentExpansion) {
                    $query->whereHas('spawn2', function ($q) use ($currentExpansion) {
                        $q->whereColumn('spawn2.version', 'npc_types.version')
                        ->whereIn('zone', function ($sub) use ($currentExpansion) {
                            $sub->select('short_name')
                                ->from('zone')
                                ->where('expansion', '<=', $currentExpansion);
                        });
                    });
                })
                ->with('spawnEntries.spawn2')
                ->orderBy('name', 'asc')
                ->paginate(50)
                ->withQueryString();

            $zones = Zone::select('id', 'zoneidnumber', 'short_name', 'long_name', 'expansion', 'version')->get();

            foreach ($npcs as $npc) {
                foreach ($npc->spawnEntries as $entry) {
                    if (!isset($entry->spawn2)) continue;

                    $entry->matched_zone = $zones
                        ->where('short_name', $entry->spawn2->zone)
                        ->where('version', $entry->spawn2->version)
                        ->first();
                }
            }
        }

        return view('npcs.index', [
            'npcs' => $npcs,
            'metaTitle' => config('app.name') . ' - NPC Search',
        ]);
    }

    public function show(NpcType $npc)
    {
        $npc = NpcType::with('npcSpellset.attackProcSpell')
            ->with([
                'firstSpawnEntries.spawn2.zoneData',
                'npcFaction.primaryFaction',
                'npcFactionEntries.factionList',
                'lootTable.loottableEntries.lootdropEntries.item',
                'spawnEntries.spawn2',
            ])
            ->findOrFail($npc->id);

        if ($npc->npcSpellset) {
            $npc->attackProcSpell = $npc->npcSpellset->attackProcSpell;
            $npc->attackProcSpellProcChance = $npc->npcSpellset->proc_chance;
        }

        $npcSpellset = $npc->npcSpellset;
        if ($npcSpellset && $npcSpellset->parent_list > 0) {
            $npc->npcSpellset = NpcSpell::with('npcSpellEntries.spells', 'attackProcSpell')
                ->where('id', $npcSpellset->parent_list)
                ->first();
        }

        if ($npc->npcSpellset) {
            $npc->filteredSpellEntries = $npc->npcSpellset->npcSpellEntries()
                ->where('minlevel', '<=', $npc->level)
                ->where('maxlevel', '>=', $npc->level)
                ->orderBy('priority', 'desc')
                ->with('spells')
                ->get();
        } else {
            $npc->filteredSpellEntries = collect();
        }

        // separate and group faction
        $raisesFaction = [];
        $lowersFaction = [];

        foreach ($npc->npcFactionEntries as $entry) {
            $factionName = $entry->factionList->name ?? 'Unknown';
            $factionId   = $entry->faction_id;
            $value       = $entry->value;

            if ($value > 0) {
                $raisesFaction[] = [
                    'name' => $factionName,
                    'id' => $factionId,
                    'value' => $value,
                ];
            } elseif ($value < 0) {
                $lowersFaction[] = [
                    'name' => $factionName,
                    'id' => $factionId,
                    'value' => $value,
                ];
            }
        }

        $defaultTab = null;
        if ($npc->lootTable?->loottableEntries->isNotEmpty()) {
            $defaultTab = 'drops';
        } elseif ($npc->spawnEntries->isNotEmpty()) {
            $defaultTab = 'spawns';
        } elseif ($npc->npcFactionEntries->isNotEmpty()) {
            $defaultTab = 'faction';
        }

        $lvl = $npc->level ? ' - Level (' . $npc->level . ')' : '';

        return view('npcs.show', [
            'npc' => $npc,
            'defaultTab' => $defaultTab,
            'raisesFaction' => $raisesFaction,
            'lowersFaction' => $lowersFaction,
            'metaTitle' => config('app.name') . ' - NPC: ' . $npc->clean_name . $lvl,
        ]);
    }
}
