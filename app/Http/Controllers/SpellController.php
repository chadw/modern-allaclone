<?php

namespace App\Http\Controllers;

use App\Models\DbStr;
use App\Models\DiscoveredItem;
use App\Models\Spell;
use App\Models\Zone;
use App\Services\SpellSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SpellController extends Controller
{
    protected $allSpells;
    protected $allZones;

    public function __construct()
    {
        $this->allSpells = Cache::rememberForever('all_spells', function () {
            return Spell::pluck('name', 'id');
        });

        $this->allZones = Cache::rememberForever('all_zones', function () {
            return Zone::select('id', 'short_name', 'long_name', 'expansion')
                ->orderBy('id')
                ->get()
                ->unique('short_name')
                ->keyBy('short_name');
        });

        view()->share('allSpells', $this->allSpells);
        view()->share('allZones', $this->allZones);
    }

    public function index(Request $request)
    {
        if (
            empty($request->input('name')) &&
            empty($request->input('class')) &&
            $request->filled('level') &&
            $request->filled('opt')
        ) {
            return redirect()
                ->route('spells.index')
                ->withInput()
                ->withErrors(['class' => 'Please select a class when filtering by level.']);
        }

        $search = new SpellSearch($request);
        $spells = $search->search();
        $groupedSpells = $search->groupSpells($spells);

        $spellIds = $spells->pluck('id')->all();
        $extra = $search->extraSpells($spellIds);

        return view('spells.index', [
            'groupedSpells' => $groupedSpells,
            'selectedClass' => $request->input('class'),
            'searchName' => $request->input('name'),
            'extraSpells' => $extra['spells'],
            'extraSpellsCount' => $extra['count'],
            'metaTitle' => config('app.name') . ' - Spell Search',
        ]);
    }

    public function extra(Request $request)
    {
        $search = new SpellSearch($request);

        $excludeIds = explode(',', $request->input('exclude', ''));
        $excludeIds = array_filter(array_map('intval', $excludeIds));

        $query = $search->extraSpellsQuery($excludeIds);
        $paginated = $query->paginate(25);

        return view('spells.partials.other-spells', [
            'extraSpells' => $paginated,
            'extraSpellsCount' => $paginated->total(),
        ]);
    }

    public function show(Spell $spell)
    {
        $discoveryEnabled = config('everquest.discovered_items.enable');

        $description = null;
        if ($spell->descnum) {
            $desc = DbStr::where('id', $spell->descnum)->where('type', 6)->first();
            $description = $desc?->getSpellDescription($spell);
        }

        $spell->load([
            'scrolleffect',
            'clickeffect',
            'proceffect',
            'worneffect',
            'focuseffect',
        ]);

        // discovery
        $itemIds = collect();
        if ($discoveryEnabled) {
            $itemIds = $itemIds->merge($spell->scrolleffect->pluck('id'));
            $itemIds = $itemIds
                ->merge($spell->clickeffect->pluck('id'))
                ->merge($spell->proceffect->pluck('id'))
                ->merge($spell->focuseffect->pluck('id'))
                ->merge($spell->worneffect->pluck('id'));
        }

        $itemIds = $itemIds->filter()->unique()->values();

        $discoveredItems = $discoveryEnabled
            ? DiscoveredItem::whereIn('item_id', $itemIds)->pluck('item_id')->flip()
            : collect();

        return view('spells.show', [
            'spell' => $spell,
            'description' => $description,
            'discoveredItems' => $discoveredItems,
            'metaTitle' => config('app.name') . ' - Spell: ' . $spell->name,
        ]);
    }

    public function popup(Spell $spell)
    {
        $effectsOnly = request()->boolean('effects-only');

        $spell = Spell::where('id', $spell->id)->firstOrFail();

        return response()->json([
            'html' => view('spells.partials.popup', [
                'spell' => $spell,
                'effectsOnly' => $effectsOnly,
            ])->render()
        ]);
    }
}
