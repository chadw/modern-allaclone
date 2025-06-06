<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoottableEntry extends Model
{
    use HasFactory;

    protected $connection = 'eqemu';
    protected $table = 'loottable_entries';

    public function lootdropEntries(): HasMany
    {
        return $this->hasMany(LootdropEntry::class, 'lootdrop_id', 'lootdrop_id');
    }
}
