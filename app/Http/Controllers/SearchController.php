<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NpcType;
use App\Models\Item;
use App\Models\TradeskillRecipe;
use App\Models\Zone;
//use App\Models\Faction;
use App\Models\Spell;

class SearchController extends Controller
{
    public function suggest(Request $request)
    {
        //@todo add faction

        $q = $request->query('q');

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = collect();

        $results = $results
            ->merge(
                NpcType::where('name', 'like', "%{$q}%")->groupBy('name')->limit(5)->get()->map(function ($npc) {
                    return [
                        'type' => 'npc',
                        'name' => $npc->clean_name,
                        'url' => route('npcs.show', $npc->id),
                        'id' => 'npc-' . $npc->id
                    ];
                })
            )->merge(
                Item::where('name', 'like', "%{$q}%")->limit(10)->get()->map(function ($item) {
                    return [
                        'type' => 'item',
                        'name' => $item->Name,
                        'url' => route('items.show', $item->id),
                        'id' => 'item-' . $item->id
                    ];
                })
            )->merge(
                TradeskillRecipe::where('name', 'like', "%{$q}%")->limit(5)->get()->map(function ($r) {
                    return [
                        'type' => 'recipe',
                        'name' => $r->name,
                        'url' => route('recipes.show', $r->id),
                        'id' => 'recipe-' . $r->id
                    ];
                })
            )->merge(
                Zone::where('long_name', 'like', "%{$q}%")
                    ->orWhere('short_name', 'like', "%{$q}%")
                    ->groupBy('short_name', 'long_name')->limit(5)->get()->map(function ($z) {
                    return [
                        'type' => 'zone',
                        'name' => $z->long_name,
                        'url' => route('zones.show', $z->id),
                        'id' => 'zone-' . $z->id
                    ];
                })
                /*
            )->merge(
                Faction::where('name', 'like', "%{$q}%")->limit(3)->get()->map(function ($f) {
                    return [
                        'type' => 'faction',
                        'name' => $f->name,
                        'url' => route('factions.show', $f->id),
                        'id' => 'faction-' . $f->id
                    ];
                })
                */
            )->merge(
                Spell::where('name', 'like', "%{$q}%")->groupBy('name')->limit(5)->get()->map(function ($s) {
                    return [
                        'type' => 'spell',
                        'name' => $s->name,
                        'url' => route('spells.show', $s->id),
                        'id' => 'spell-' . $s->id
                    ];
                })
            );

        return response()->json($results->take(30)->values());
    }
}
