<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NpcController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\SpellController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FactionController;

Route::get('/', function () {
    return view('home');
});

Route::get('/search/suggest', [App\Http\Controllers\SearchController::class, 'suggest']);

Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
Route::get('/items/popup/{item}', [ItemController::class, 'popup'])->name('items.popup');
Route::get('/items/drops_by_zone/{item}', [ItemController::class, 'drops_by_zone'])->name('items.drops_by_zone');
Route::get('/zones', [ZoneController::class, 'index'])->name('zones.index');
Route::get('/zones/{zone}', [ZoneController::class, 'show'])->name('zones.show');
Route::get('/spells', [SpellController::class, 'index'])->name('spells.index');
Route::get('/spells/{spell}', [SpellController::class, 'show'])->name('spells.show');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/npcs', [NpcController::class, 'index'])->name('npcs.index');
Route::get('/npcs/{npc}', [NpcController::class, 'show'])->name('npcs.show');
Route::get('/factions', [FactionController::class, 'index'])->name('factions.index');
Route::get('/factions/{faction}', [FactionController::class, 'show'])->name('factions.show');
