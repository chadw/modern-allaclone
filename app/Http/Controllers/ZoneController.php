<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\AlternateCurrency;
use App\ViewModels\ZoneViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $currentExpansion = config('everquest.current_expansion');
        $expansions = config('everquest.expansions');

        $zones = Cache::remember('zones.index', now()->addMonth(), function () use ($currentExpansion) {
            return Zone::getExpansionZones($currentExpansion);
        });

        return view('zones.index', [
            'zones' => $zones,
            'expansions' => $expansions,
            'metaTitle' => config('app.name') . ' - Zones',
        ]);
    }

    public function show(Zone $zone, Request $request)
    {
        $version = (int) $request->query('v', 0);

        $zoneCache = Cache::remember("zones.show.{$zone->id}_v{$version}", now()->addMonth(), function () use ($zone, $version) {
            $zone = Zone::where('id', $zone->id)
                ->with('zonepoints', function ($q) use ($version) {
                    $q->when($version > 0, fn ($q) => $q->where('version', $version))
                        ->groupBy('target_zone_id')
                        ->with('targetZones:id,zoneidnumber,short_name,long_name');
                })
                ->when($version > 0, fn ($q) => $q->where('version', $version))
                ->firstOrFail();

            $vm = new ZoneViewModel($zone, $version);

            return [
                'zone' => $zone,
                'npcs' => $vm->npcs(),
                'drops' => $vm->drops(),
                'spawnGroups' => $vm->spawnGroups(),
                'foraged' => $vm->foraged(),
                'connectedZones' => $vm->connectedZones(),
                'tasks' => $vm->tasks(),
            ];
        });

        // get cached alt currency since tasks could use it
        $altCurrency = AlternateCurrency::allAltCurrency();

        // zone version for meta title
        $zone = $zoneCache['zone'];
        $zversion = $zone->version ? ' - version (' . $zone->version . ')' : '';

        return view('zones.show', [
            ...$zoneCache,
            'altCurrency' => $altCurrency,
            'metaTitle' => config('app.name') . ' - Zone: ' . $zone->long_name . $zversion,
        ]);
    }
}
