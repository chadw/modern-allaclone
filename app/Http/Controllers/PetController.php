<?php

namespace App\Http\Controllers;

use App\Models\Spell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PetController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $petClassIds = array_keys((array) config('everquest.pet_classes'));
        $selClass = 'classes15';
        $selClassName = 'Beastlord';

        if ($id) {
            if (!in_array((int) $id, $petClassIds)) {
                abort(404);
            }

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
            'selClassName' => $selClassName,
            'metaTitle' => config('app.name') . ' - Pets: ' . $selClassName,
        ]);
    }
}
