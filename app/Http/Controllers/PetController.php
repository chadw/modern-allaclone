<?php

namespace App\Http\Controllers;

use App\Models\Spell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PetController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $request->validate([
            'class' => 'in:15,2,6,14,13,11,5,10,12',
        ]);

        $selClass = 'classes15';
        $selClassName = 'Beastlord';

        if ($id) {
            $selClass = 'classes' . $id;
            $selClassName = config('everquest.classes.' . $id);
        }

        $pets = Cache::remember("pet_{$selClass}", now()->addMonth(), function () use ($selClass) {
            return Spell::with('pets', 'npcs')
                ->where($selClass, '>', 0)
                ->where($selClass, '<=', config('everquest.server_max_level'))
                ->select('id', 'name', 'new_icon', 'teleport_zone', $selClass)
                ->groupBy('teleport_zone')
                ->orderBy($selClass)
                ->get();
        });

        return view('pets.index', [
            'pets' => $pets,
            'metaTitle' => config('app.name') . ' - Pets: ' . $selClassName,
        ]);
    }
}
