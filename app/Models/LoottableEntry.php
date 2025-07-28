<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoottableEntry extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'loottable_entries';

    public function loottable(): BelongsTo
    {
        return $this->belongsTo(Loottable::class, 'loottable_id', 'id');
    }

    public function npcs(): HasMany
    {
        return $this->hasMany(NpcType::class, 'loottable_id', 'loottable_id');
    }

    public function lootdropEntries(): HasMany
    {
        return $this->hasMany(LootdropEntry::class, 'lootdrop_id', 'lootdrop_id');
    }
}
