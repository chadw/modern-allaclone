<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'items';

    public function procEffectSpell(): BelongsTo
    {
        return $this->belongsTo(Spell::class, 'proceffect', 'id')->select('id', 'name', 'new_icon');
    }

    public function wornEffectSpell(): BelongsTo
    {
        return $this->belongsTo(Spell::class, 'worneffect', 'id')->select('id', 'name', 'new_icon');
    }

    public function focusEffectSpell(): BelongsTo
    {
        return $this->belongsTo(Spell::class, 'focuseffect', 'id')->select('id', 'name', 'new_icon');
    }

    public function clickEffectSpell(): BelongsTo
    {
        return $this->belongsTo(Spell::class, 'clickeffect', 'id')->select('id', 'name', 'new_icon');
    }

    public function scrollEffectSpell(): BelongsTo
    {
        return $this->belongsTo(Spell::class, 'scrolleffect', 'id')->select('id', 'name', 'new_icon');
    }

    public function merchants(): HasMany
    {
        return $this->hasMany(Merchantlist::class, 'item', 'id');
    }

    public function drops(): HasMany
    {
        return $this->hasMany(LootdropEntry::class, 'item_id', 'id');
    }

    public function foraged(): HasMany
    {
        return $this->hasMany(Forage::class, 'itemid', 'id');
    }
}
