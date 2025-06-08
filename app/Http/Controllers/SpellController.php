<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\DbStr;
use App\Models\Spell;
use App\Filters\SpellFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SpellController extends Controller
{
    public function index(Request $request)
    {
        $spells = collect();
        if ($request->filled('class') && $request->filled('level')) {
            $spells = (new SpellFilter($request))->apply(Spell::query())->get();
        }

        $level = 0;
        if ($request->filled('level')) {
            $level = $request->input('level');
        }

        $groupByLevel = collect();
        if ($request->filled('class') && $request->input('class') != 0 && $level > 0) {
            $class_col = "classes{$request->class}";

            $groupByLevel = $spells->groupBy(function ($spell) use ($class_col) {
                return $spell->$class_col;
            })
            ->filter(function ($spells, $level) {
                return is_numeric($level);
            })
            ->map(function ($spells, $level) {
                return [
                    'level' => (int) $level,
                    'spells' => $spells,
                ];
            })
            ->sortBy('level')
            ->values();
        }

        $allSpells = Cache::remember('all_spells', now()->addWeek(), function () {
            return Spell::pluck('name', 'id');
        });

        $allZones = Cache::remember('all_zones', now()->addMonth(), function () {
            return Zone::select('id', 'short_name', 'long_name')->get()->keyBy('short_name');
        });

        return view('spells.index', [
            'groupedSpells' => $groupByLevel,
            'selectedClass' => $request->input('class'),
            'searchName' => $request->input('name'),
            'allSpells' => $allSpells,
            'allZones' => $allZones,
        ]);
    }

    public function show(Spell $spell)
    {
        //@todo update
        $description = null;
        if ($spell->descnum) {
            $desc = DbStr::where('id', $spell->descnum)->first();
            $description = $desc?->value ?? null;
        }

        return view('spells.show', compact('spell', 'description'));
    }

    public function popup(Spell $spell)
    {
        $spell = Spell::where('id', $spell->id)->firstOrFail();

        $allSpells = Cache::remember('all_spells', now()->addWeek(), function () {
            return Spell::pluck('name', 'id');
        });

        $allZones = Cache::remember('all_zones', now()->addMonth(), function () {
            return Zone::select('id', 'short_name', 'long_name')->get()->keyBy('short_name');
        });

        return response()->json([
            'html' => view('partials.spells.popup', [
                'spell' => $spell,
                'allSpells' => $allSpells,
                'allZones' => $allZones,
            ])->render()
        ]);
    }
}
