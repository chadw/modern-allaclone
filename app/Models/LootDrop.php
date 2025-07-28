<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LootDrop extends Model
{
    protected $connection = 'eqemu';
    protected $table = 'lootdrop';

    public function loottableEntries(): HasMany
    {
        return $this->hasMany(LoottableEntry::class, 'lootdrop_id', 'id');
    }
}
