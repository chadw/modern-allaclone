<?php

namespace App\Http\Controllers;

use App\Filters\ItemFilter;
use App\Models\Item;
use App\ViewModels\ItemViewModel;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'stat1comp' => 'in:1,2,5',
            'stat2comp' => 'in:1,2,5',
            'stat3comp' => 'in:1,2,5',
        ]);

        $items = (new ItemFilter($request))
            ->apply(Item::query())
            ->orderBy('id', 'asc')
            ->paginate(50)
            ->withQueryString();

        return view('items.index', [
            'items' => $items,
        ]);
    }

    public function show(Item $item)
    {
        $item = Item::where('id', $item->id)->firstOrFail();
        $vm = (new ItemViewModel($item))->withEffects();

        return view('items.show', [
            'item' => $item,
            'recipes' => $vm->recipes(),
            'used_in_ts' => $vm->usedInTradeskills(),
            'forage' => $vm->forageZones(),
            'soldByZone' => $vm->soldInZones(),
            'ground_spawn' => $vm->itemGroundSpawn(),
        ]);
    }

    public function popup(Item $item)
    {
        $item = Item::where('id', $item->id)->firstOrFail();

        return response()->json([
            'html' => view('partials.items.popup', ['item' => $item])->render()
        ]);
    }

    public function drops_by_zone(Item $item)
    {
        $drops = (new ItemViewModel($item))->dropsByZone();

        return response()->json($drops);
    }
}
