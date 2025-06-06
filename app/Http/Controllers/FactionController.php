<?php

namespace App\Http\Controllers;

use App\Models\FactionList;
use Illuminate\Http\Request;

class FactionController extends Controller
{
    public function index(Request $request)
    {
        return view('factions.index');
    }

    public function show(FactionList $faction)
    {
        return view('factions.show');
    }
}
