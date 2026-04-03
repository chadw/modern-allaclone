<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LootTable extends Model
{
    protected $connection = 'eqemu';
    protected $table = 'loottable';

    public function loottableEntries(): HasMany
    {
        return $this->hasMany(LoottableEntry::class, 'loottable_id', 'id');
    }
}
